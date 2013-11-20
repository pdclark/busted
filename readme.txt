=== Disable Browser Cache ===
Contributors: brainstormmedia, pdclark
Plugin URI: https://github.com/brainstormmedia/disable-browser-cache
Author URI: http://brainstormmedia.com
Tags: browser, cache, develop, debug, client
Requires at least: 3.4
Tested up to: 3.7.1
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Refresh browser cache for all stylesheets and scripts on every page load. Useful for sites under active development.

== Description ==

Refresh browser cache for all stylesheets and scripts on every page load.

Useful for sites under active development. For example, sites being shared with others for review.

**Requirements**

Requires <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_style">wp_enqueue_style</a> and <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script">wp_enqueue_script</a>.

Almost all scripts and styles meet these requirements. The only case it doesn't account for is when URLs are hard-coded. This shouldn't ever be done, since it can break sites for other reasons.

== Installation ==

1. Search for "Disable Browser Cache" under `WordPress Admin > Plugins > Add New`
1. Install the plugin.
1. Activate the plugin.

== Changelog ==

= 1.0 =
* Initial public release.

== Upgrade Notice ==

= 1.0 =
* Initial public release.