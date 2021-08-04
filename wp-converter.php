<?php
/**
 * Cryptocurrency converter
 *
 * Plugin Name: Cryptocurrency converter
 * Plugin URI:
 * Description: Convert from one cryptocurrency to another
 * Version:     1.0.0
 * Author:      Evgen
 * Author URI:
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: cryptocurrency-converter
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 7.0
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCC_NAME', 'Cryptocurrency converter' );
define( 'CCC_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCC_URL', plugin_dir_url( __FILE__ ) );
define( 'CCC_PLUGIN_SLUG', 'cryptocurrency-converter' );
define( 'CCC_VERSION', '1.0.0' );
define( 'CCC_TABLE_LOG', 'cc_log' );
define( 'CCC_TABLE_CURRENCY', 'cc_currency' );

function activate_cryptocurrency_converter() {
	require_once CCC_PATH . 'includes/class-cryptocurrency-converter-activator.php';
	Cryptocurrency_Converter_Activator::activate();
}

function deactivate_cryptocurrency_converter() {
	require_once CCC_PATH . 'includes/class-cryptocurrency-converter-deactivator.php';
	Cryptocurrency_Converter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cryptocurrency_converter' );
register_deactivation_hook( __FILE__, 'deactivate_cryptocurrency_converter' );

$autoload_file = CCC_PATH . 'vendor/autoload.php';

if ( is_readable( $autoload_file ) ) {
	require $autoload_file;
}

require CCC_PATH . 'includes/class-cryptocurrency-converter.php';

function execute_ccc_plugin() {

	$plugin = new Cryptocurrency_Converter();
	$plugin->run();

}

execute_ccc_plugin();
