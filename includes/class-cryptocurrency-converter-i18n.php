<?php
/**
 * Description document
 *
 * @file
 * @package package
 */

/**
 * Class Cryptocurrency_Converter_i18n
 */
class Cryptocurrency_Converter_i18n {

	/**
	 * Description
	 *
	 * @param string $plugin_name description.
	 */
	public function load_plugin_textdomain( $plugin_name ) {

		load_plugin_textdomain(
			CCC_PLUGIN_SLUG,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
