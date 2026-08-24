MslsMenu
========

[![codecov](https://codecov.io/gh/lloc/MslsMenu/graph/badge.svg?token=902NM9H2BW)](https://codecov.io/gh/lloc/MslsMenu)

MslsMenu extends the Multisite Language Switcher by appending its language links to the navigation menus of your choice. MSLS itself ships a block, the shortcodes `[sc_msls]` and `[sc_msls_widget]`, a widget, a content filter and the `msls_the_switcher()` API — but none of them fit into a nav menu, because a classic menu accepts menu items and nothing else. Use MslsMenu when you want the switcher inside a menu without editing your theme or writing a custom walker.

Multisite Language Switcher **3.0 or newer** has to be installed and active first — MslsMenu is an add-on and declares that through its `Requires Plugins` header. That header carries no version constraint, so against an older MSLS the plugin stays inactive rather than raising an error.

## Install
- From WordPress: search for “MslsMenu” under Plugins → Add New and activate it.
- With Composer: `composer require wpackagist-plugin/mslsmenu`, with the [wpackagist](https://wpackagist.org) repository configured in your `composer.json`.
- Manual: unpack the release archive and copy the resulting `mslsmenu/` folder to `wp-content/plugins/`, then activate the plugin.

## Usage
MslsMenu contributes no menu item, so there is nothing to place under Appearance → Menus. It hooks into `wp_nav_menu_items` and appends the switcher to whichever menus you point it at.

That filter belongs to the classic `wp_nav_menu()`, so MslsMenu covers classic menus only — the Navigation block of a block theme never calls it. In a block theme, place the MSLS block next to the Navigation block in your header template instead.

Open Settings → Multisite Language Switcher in the site you want to configure; MslsMenu adds its own “Menu Settings” section to that page.

- **Theme Location** — pick one or more registered theme locations. Every menu rendered at one of them receives the language links.
- **Display** — the link style: flag, description, or both.
- **Text/HTML before/after the list** and **before/after each item** — markup wrapped around the output, for example `<li class="msls-menu">` … `</li>` per item.

Everything else follows your Multisite Language Switcher configuration, including its “only with translation” setting.

## Develop
- `composer install` pulls dev tools (Pest, PHPStan, PHP_CodeSniffer, Brain Monkey).
- `composer qa` runs the full gate: coding standards, static analysis, then the Pest suite.
- `composer pest`, `composer phpcs` and `composer phpstan` run the individual steps; `composer phpcbf` auto-fixes coding-standard violations.

## Contribute
- Review `AGENTS.md` for project conventions and release steps.
- Latest release: https://wordpress.org/plugins/mslsmenu/
