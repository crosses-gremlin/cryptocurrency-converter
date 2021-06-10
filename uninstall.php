<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
global $wpdb;
delete_option( 'cryptocurrency_converter_general_settings' );
$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
    $wpdb->prefix . "cc_log" ) );

$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
    $wpdb->prefix . "cc_currency" ) );
