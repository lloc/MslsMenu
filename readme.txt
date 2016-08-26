=== MslsMenu ===

Contributors: realloc
Donate link: http://www.greenpeace.org/international/
Tags: multilingual, multisite, language, switcher, international, localization, i18n, menu, nav_menu
Requires at least: 3.6.1
Tested up to: 4.6
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the output of the Multisite Language Switcher to one (or more) of your navigation menu(s)

== Description ==

Most people are likely to use some lines of PHP or the widget provided by the [Multisite Language Switcher](http://wordpress.org/plugins/multisite-language-switcher/) to integrate the links to the translations in their blogs.

But this can lead to fatal errors if you don't know much about PHP, or maybe the dynamic sidebars are not the best place in your opinion. If you want to integrate the *Multisite Language Switcher* in one (or more) of your Navigation Menu(s) then you should give **MslsMenu** a try. 

== Installation ==

* Download the plugin and uncompress it with your preferred unzip programme
* Copy the entire directory in your plugin directory of your WordPress blog (/wp-content/plugins)
* Activate the plugin
* You will find the configuration of the plugin once in each blog in Settings -> Multisite Language Switcher
* Set the the menu specific options such as `<li class="mslsl-menu">` before the item-ouitput or the description. Please, check the Screenshots-section too!

== Screenshots ==

1. Edit menus
2. Manage locations
3. Plugin configuration
4. Output in the primary nav menu

== Changelog ==

= 1.4 =
* plugin inits when plugins_loaded runs and adds callback to hooks in its factory now

= 1.3.1 =
* fix for [fatal eror on activation](https://wordpress.org/support/topic/fatal-error-warning-cannot-be-activated)
 
= 1.3 =
* defensive loading, factory added and chore

= 1.2 =
* empty menu-location is now an option

= 1.1 =
* selector for multiple menus added

= 1.0 =
* marked as stable
* WordPress Coding Standards
* PHPDocs

= 0.3.1 =
* check for 'the_msls' was too early

= 0.3 =
* Class MslsMenu

= 0.2 =
* configuration in the MSLS options added

= 0.1 =
* first version
