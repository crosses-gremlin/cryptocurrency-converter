<?php
// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
wp_clear_scheduled_hook( 'get_currency_data_event' );

global $wpdb;
delete_option( 'cryptocurrency_converter_use_select2' );
delete_option( 'cryptocurrency_converter_coinmarketcap_key' );
$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
	$wpdb->prefix . "cc_log" ) );

$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
	$wpdb->prefix . "cc_currency" ) );

