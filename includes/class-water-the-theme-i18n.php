<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://freesoftwarepc.com
 * @since      1.0.0
 *
 * @package    Water_The_Theme
 * @subpackage Water_The_Theme/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Water_The_Theme
 * @subpackage Water_The_Theme/includes
 * @author     Jonathan Rosenbaum <gnuser@gmail.com>
 */
class Water_The_Theme_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'water-the-theme',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
