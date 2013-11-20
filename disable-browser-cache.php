<?php
/*
Plugin Name: Disable Browser Cache
Plugin URI: http://github.com/brainstormmedia/no-cache
Description: Refresh browser cache for all stylesheets and scripts on every page load. Useful for sites under active development. Requires <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_style">wp_enqueue_style</a> and <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script">wp_enqueue_script</a>.
Version: 1.0
Author: Brainstorm Media
Author URI: http://brainstormmedia.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class Storm_Disable_Browser_Cache {

	/**
	 * wp_print_scripts runs in header in footer and when called.
	 * Only run this modification once.
	 * 
	 * @var boolean
	 */
	static protected $filtered_wp_scripts = false;

	/**
	 * @var string Version string with current time to break caches.
	 */
	static protected $version_string;

	/**
	 * @var string Name for query arguements and version identifier.
	 */
	static protected $version_slug = 'no-cache';

	static public function init(){
		self::$version_string = time();

		add_action( 'wp_print_scripts', __CLASS__ . '::wp_print_scripts', PHP_INT_MAX - 1 );

		add_filter( 'stylesheet_uri', __CLASS__ . '::stylesheet_uri' );
		add_filter( 'locale_stylesheet_uri', __CLASS__ . '::stylesheet_uri' );
	}

	/**
	 * Append self::$version_string to scripts and styles that use wp_enqueue_* functions.
	 * 
	 * @return void
	 */
	static public function wp_print_scripts() {
		global $wp_scripts;

		if ( !self::$filtered_wp_scripts ) {
			
			foreach( (array) $wp_scripts->registered as $handle => $script ) {

				$version = $script->ver . '-' . self::$version_slug . '-' . self::$version_string;

				$wp_scripts->registered[ $handle ]->ver = $version;

			}

			self::$filtered_wp_scripts = true;

		}

	}

	/**
	 * Filter styles and scripts that use stylesheet_uri()
	 */
	static public function stylesheet_uri( $uri ) {
		
		if ( in_array( pathinfo( $uri, PATHINFO_EXTENSION ), array( 'css', 'js' ) ) ) {

			$uri = add_query_arg( self::$version_slug, self::$version_string, $uri );

		}

		return $uri;
	}

}

if ( !is_admin() ) {
	add_action( 'plugins_loaded', 'Storm_Disable_Browser_Cache::init' );
}
