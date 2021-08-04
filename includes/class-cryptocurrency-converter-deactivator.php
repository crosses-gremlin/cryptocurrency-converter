<?php
/**
 * Description document
 *
 * @file
 * @package package
 */

/**
 * Class Cryptocurrency_Converter_Deactivator
 */
class Cryptocurrency_Converter_Deactivator {

	/**
	 * Description
	 */
	public static function deactivate() {

		wp_clear_scheduled_hook( 'get_currency_data_event' );

	}

}
