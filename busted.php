<?php
/*
Plugin Name: Busted!
Plugin URI: http://github.com/10up/busted
Description: Force browsers to load the most recent file if modified. Requires <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_style">wp_enqueue_style</a>, <a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script">wp_enqueue_script</a>, or <a href="http://codex.wordpress.org/Function_Reference/get_stylesheet_uri">get_stylesheet_uri</a> be used to load scripts and styles.
Version: 1.3
Author: Paul Clark, 10up
Author URI: http://pdclark.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class Storm_Busted {

	/**
	 * wp_print_scripts runs in header in footer and when called.
	 * Only run this modification once.
	 * 
	 * @var boolean
	 */
	static protected $filtered_wp_scripts = false;

	/**
	 * @var string Name for query arguements and version identifier.
	 */
	static protected $version_slug = 'b-modified';

	/**
	 * @var string Version string with current time to break caches.
	 */
	static protected $version_string;

	/**
	 * Setup hooks and vars.
	 * 
	 * @return void
	 */
	static public function init(){

		/**
		 * PHP_INT_MAX - 1 used as hook priority because many developers
		 * use wp_print_scripts for enqueues.
		 * 
		 * Extremely high priority assures we catch everything.
		 */
		add_action( 'wp_print_scripts', __CLASS__ . '::wp_print_scripts', PHP_INT_MAX - 1 );

		add_filter( 'stylesheet_uri', __CLASS__ . '::stylesheet_uri' );
		add_filter( 'locale_stylesheet_uri', __CLASS__ . '::stylesheet_uri' );
		
	}

	/**
	 * Update version in scripts and styles that use wp_enqueue_* functions.
	 * 
	 * @return void
	 */
	static public function wp_print_scripts() {

		global $wp_scripts;

		if ( !self::$filtered_wp_scripts ) {
			
			foreach( (array) $wp_scripts->registered as $handle => $script ) {

				$modification_time = self::modification_time( $script->src );

				if ( $modification_time ) {

					$version = $script->ver . '-' . self::$version_slug . '-' . $modification_time;

					$wp_scripts->registered[ $handle ]->ver = $version;

				}

			}

			self::$filtered_wp_scripts = true;

		}

	}

	/**
	 * Filter styles and scripts that use stylesheet_uri()
	 *
	 * @param  string  $uri  URI
	 * @return string  URI
	 */
	static public function stylesheet_uri( $uri ) {
		
		if ( in_array( pathinfo( $uri, PATHINFO_EXTENSION ), array( 'css', 'js' ) ) ) {

			$uri = add_query_arg( self::$version_slug, self::modification_time( $uri ), $uri );

		}

		return $uri;

	}

	/**
	 * @param  string $src Script relative path or URI
	 * @return int|bool File modification time or false.
	 */
	static public function modification_time( $src ) {

		$file = realpath( ABSPATH . str_replace( get_site_url(), '', $src ) );

		if ( file_exists( $file ) ) {
			return filemtime( $file );
		}

		return false;

	}

}

/**
 * Only load plugin on site front-end
 */
add_action( 'init', 'Storm_Busted::init' );
