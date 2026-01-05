# UT Unfiltered HTML Exception

A WordPress must-use plugin that grants `unfiltered_html` capability to specific users on specific multisite subsites.

## Purpose

In WordPress Multisite, only Super Admins can save content containing `<script>`, `<style>`, inline SVG, and other HTML that WordPress KSES filtering normally strips. This plugin allows designated non-Super-Admin users to save unfiltered HTML on specific subsites without granting network-wide Super Admin privileges.

**Use cases:**
- Inline SVG in block content (e.g., Shape blocks)
- GeneratePress Elements with custom scripts/styles
- Any content requiring unfiltered HTML

## Installation

### As a Must-Use Plugin (recommended)
1. Upload `ut-unfiltered-html.php` to `wp-content/mu-plugins/`
2. The plugin activates automatically

## Configuration

Edit the constants at the top of the plugin file:

```php
// Blog IDs where this should apply
define( 'UT_UNFILTERED_BLOG_IDS', array( 2, 5 ) );

// User IDs who should have unfiltered HTML access
define( 'UT_UNFILTERED_USER_IDS', array( 1, 40, 52 ) );

// Enable debug logging (logs to PHP error log)
define( 'UT_UNFILTERED_DEBUG', false );
```

### Finding IDs
- **Blog ID:** Network Admin → Sites → hover over site name to see ID in URL
- **User ID:** Users → hover over username to see ID in URL, or check user profile URL

## Security Considerations

This plugin grants the ability to save **any HTML**, including:
- `<script>` tags
- `<style>` tags
- `<iframe>` elements
- Event handlers (`onclick`, etc.)

**Only add trusted user IDs to the allowlist.** This is equivalent to granting those users Super Admin-level content permissions on the specified sites.

## How It Works

The plugin uses WordPress's `map_meta_cap` filter to intercept `unfiltered_html` capability checks. When all conditions are met (multisite, correct blog ID, correct user ID), it grants the capability by returning an empty required-capabilities array.

## License

GPL v2 or later

