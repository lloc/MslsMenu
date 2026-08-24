=== MslsMenu ===

Contributors: realloc
Donate link: http://www.greenpeace.org/international/
Tags: menu, multilingual, multisite, msls, switcher
Requires at least: 6.1
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 3.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the output of the Multisite Language Switcher to one (or more) of your navigation menu(s)

== Description ==

The [Multisite Language Switcher](https://wordpress.org/plugins/multisite-language-switcher/) already offers plenty of ways to output its language links: a block, the shortcodes `[sc_msls]` and `[sc_msls_widget]`, the classic widget, a content filter, and the `msls_the_switcher()` API for your theme files.

What none of them covers is a navigation menu. A classic menu rendered by `wp_nav_menu()` accepts menu items and nothing else, so there is no slot for a block or a shortcode inside it - and writing a custom walker into the theme is more work than most sites are willing to spend on a language switcher.

**MslsMenu** closes that gap. Select the theme locations you want in the settings and the language links are appended to every menu rendered at those locations. No theme edit, no PHP, no custom walker.

== Installation ==

* Use the WordPress plugin installation and search for "MslsMenu".
* Alternatively, download the plugin, uncompress it with your preferred unzip programme and upload the folder `mslsmenu` to the `/wp-content/plugins/` directory.
* Activate the plugin
* You will find the configuration of the plugin once in each blog in Settings -> Multisite Language Switcher
* Set the menu specific options such as `<li class="msls-menu">` before the item output or the description. Please, check the Screenshots-section too!

== Frequently Asked Questions ==

= Does this work with the Navigation block of a block theme? =

No. MslsMenu hooks into `wp_nav_menu_items`, a filter of the classic `wp_nav_menu()` function, and the Navigation block does not run through it. In a block theme use the block of the Multisite Language Switcher instead and place it next to the Navigation block in your header template.

= Do I need the Multisite Language Switcher? =

Yes, version 3.0 or newer, installed and active. MslsMenu is an add-on and declares the dependency through its `Requires Plugins` header. That header cannot express a minimum version, so against an older Multisite Language Switcher MslsMenu stays inactive and explains itself with an admin notice.

= Where are the settings? =

In `Settings` -> `Multisite Language Switcher` of each site. MslsMenu adds its own "Menu Settings" section to that page.

== Screenshots ==

1. Edit menus
2. Manage locations
3. Plugin configuration
4. Output in the primary nav menu

== Changelog ==

= 3.0.2 =
* An admin notice explains why MslsMenu is doing nothing when Multisite Language Switcher is older than 3.0.
* The release is built and checked in CI, deployed from the same distribution Plugin Check verifies, and published with a build provenance attestation.
* The translation files were regenerated.

= 2.5.1 =
* plugin check integration added
* missing license problem addressed
* MslsOutput class deprecated init in favour of a function

= 2.4.1 =
* readme.txt tags updated
* Plugin check issues fixed
* "Requires Plugins" added

= 2.3.2 =
* Pest as new dev/tester dependency

= 2.2.6 =
* Unit testing completed
* phpstan config excluded
* Pest for Unit tests added
* PHP 7.1 as minimum declared

= 1.0 =
* marked as stable
* WordPress Coding Standards
* PHPDocs

== Upgrade Notice ==

= 3.0.2 =
Replaces the faulty 3.0.1, which shipped the code of 3.0.0. Requires MSLS 3.0 or newer and WordPress 6.1 or newer.
