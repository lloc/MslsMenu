# Repository Guidelines

## Project Structure & Module Organization
- `MslsMenu.php` is the WordPress bootstrap; add hooks here or split helpers into new subdirectories under `mslsmenu/` as needed.
- `mslsmenu/` mirrors the shipping bundle; keep source and bundle in sync before release.
- `languages/` stores translation assets; regenerate via `wp i18n make-pot` when strings move.
- `tests/` houses Pest specs with Brain Monkey shims in `tests/Pest.php`; colocate fixtures with their specs.
- `bin/` contains release tooling (`bin/git-release.sh`); prefer extending scripts over one-off commands.

## Build, Test, and Development Commands
- Run `composer install` after cloning to pull dev dependencies.
- `composer qa` runs the full gate: `phpcs`, `phpstan`, then `pest`.
- `composer pest` executes the Pest suite with mocked WordPress APIs.
- `composer phpcs` checks the WordPress coding standards and PHP 7.4 compatibility via `.phpcs.xml.dist`; `composer phpcbf` fixes what can be fixed automatically.
- `composer analyze` runs PHPStan (level 5) against `MslsMenu.php`; keep it passing before pushing.
- `composer pest:coverage` enables Xdebug coverage for pull request evidence.
- `composer build` triggers `bin/git-release.sh`, refreshing `mslsmenu/` and `mslsmenu.zip` for distribution.

## Coding Style & Naming Conventions
- Follow WordPress PHP standards: tabs for indentation, braces on new lines, no trailing commas.
- Include `declare(strict_types=1);` in new entry points and document public APIs with PHPDoc.
- Use `snake_case` for hook callbacks, `PascalCase` for classes, and `camelCase` for internal helpers unless a hook signature dictates otherwise.

## Testing Guidelines
- Place new specs in `tests/YourFeatureTest.php`; keep case names descriptive (`it('renders menu item')`).
- Stub external WP functions with Brain Monkey setup blocks to keep tests deterministic.
- Run `composer pest:coverage` for behavioral changes and share the summary when coverage changes noticeably.
- `MslsMenu.php` guards against direct access with `ABSPATH`; `tests/bootstrap.php` defines that constant and then loads the plugin, so the suite must keep using it as its PHPUnit bootstrap.

## Commit & Pull Request Guidelines
- Write short imperative subjects (≈50 chars) such as `Clean menu walker output`; batch related edits together.
- Reference GitHub issues in the body (`Refs #123`) and list the commands you ran.
- PRs should explain the problem, outline the fix, attach UI evidence when relevant, and confirm `composer qa` passes.

## Release Packaging Tips
- Bump the plugin header version in `MslsMenu.php` and sync the same value into `readme.txt` before building.
- Run `composer build`, inspect the refreshed `mslsmenu/` contents, and spot-check the generated `mslsmenu.zip` before tagging.
