MslsMenu
========

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lloc/MslsMenu/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lloc/MslsMenu/?branch=master)
[![codecov](https://codecov.io/gh/lloc/MslsMenu/graph/badge.svg?token=902NM9H2BW)](https://codecov.io/gh/lloc/MslsMenu)

MslsMenu extends the Multisite Language Switcher by injecting a language picker into any registered WordPress navigation menu. Install it when you want users to toggle sites and languages without custom walkers.

## Install
- From WordPress: search for “MslsMenu” under Plugins → Add New and activate it.
- Manual: copy the `mslsmenu/` directory to `wp-content/plugins/` and activate the plugin.

## Usage
- Head to Appearance → Menus, add the “MslsMenu” item to your chosen menu, and arrange it like any other menu entry.
- Configure Multisite Language Switcher as usual; MslsMenu reuses its site mappings automatically.

## Develop
- `composer install` pulls dev tools (Pest, PHPStan, PHP_CodeSniffer, Brain Monkey).
- `composer qa` runs the full gate: coding standards, static analysis, then the Pest suite.
- `composer pest`, `composer phpcs` and `composer phpstan` run the individual steps; `composer phpcbf` auto-fixes coding-standard violations.

## Contribute
- Review `AGENTS.md` for project conventions and release steps.
- Latest release: https://wordpress.org/plugins/mslsmenu/
