<?php
/**
 * Plugin Name: UT Unfiltered HTML Exception
 * Description: Grants unfiltered_html capability to specific users on specific multisite subsites.
 * Version: 1.0.0
 * Author: Second Mile
 * Network: true
 *
 * Security Note: This plugin grants the ability to save unfiltered HTML (including scripts,
 * styles, iframes, etc.) to specified users. Only add trusted user IDs to the allowlist.
 *
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
|
| Update these values with your specific blog IDs and user IDs.
| Blog IDs: The multisite subsite IDs where this should apply.
| User IDs: The WordPress user IDs who should have unfiltered HTML access.
|
*/

// Target sites - add the blog IDs where this should apply.
if ( ! defined( 'UT_UNFILTERED_BLOG_IDS' ) ) {
	define( 'UT_UNFILTERED_BLOG_IDS', array( 2 ) );
}

// Allowed user IDs - add the WordPress user IDs who need this access.
if ( ! defined( 'UT_UNFILTERED_USER_IDS' ) ) {
	define( 'UT_UNFILTERED_USER_IDS', array( 1 ) );
}

// Debug logging - set to true to log when capability is granted.
if ( ! defined( 'UT_UNFILTERED_DEBUG' ) ) {
	define( 'UT_UNFILTERED_DEBUG', false );
}

/*
|--------------------------------------------------------------------------
| Capability Filter
|--------------------------------------------------------------------------
*/

add_filter( 'map_meta_cap', 'ut_unfiltered_html_map_meta_cap', 10, 4 );

/**
 * Grants unfiltered_html capability to allowed users on allowed sites.
 *
 * @param array  $caps    The required capabilities for the requested action.
 * @param string $cap     The capability being checked.
 * @param int    $user_id The user ID being checked.
 * @param array  $args    Additional arguments passed to the capability check.
 * @return array Modified capabilities array.
 */
function ut_unfiltered_html_map_meta_cap( $caps, $cap, $user_id, $args ) {
	// Only intercept unfiltered_html capability checks.
	if ( 'unfiltered_html' !== $cap ) {
		return $caps;
	}

	// Only applies on multisite installations.
	if ( ! is_multisite() ) {
		return $caps;
	}

	// Check if current blog is in the allowed list.
	$current_blog_id = get_current_blog_id();
	if ( ! in_array( $current_blog_id, UT_UNFILTERED_BLOG_IDS, true ) ) {
		return $caps;
	}

	// Check if user is in the allowed list.
	if ( ! in_array( $user_id, UT_UNFILTERED_USER_IDS, true ) ) {
		return $caps;
	}

	// Debug logging.
	if ( UT_UNFILTERED_DEBUG ) {
		error_log(
			sprintf(
				'[UT Unfiltered HTML] Granted unfiltered_html to user %d on blog %d',
				$user_id,
				$current_blog_id
			)
		);
	}

	// Grant the capability by returning an empty array.
	// This tells WordPress no additional capabilities are required.
	return array();
}

