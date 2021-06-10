<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'cryptocurrency_converter_general_settings' );
$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
    $wpdb->prefix . CCC_TABLE_LOG ) );

$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
    $wpdb->prefix . CCC_TABLE_CURRENCY ) );
