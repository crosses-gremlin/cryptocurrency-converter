<?php

class Cryptocurrency_Converter_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        wp_unschedule_hook( 'get_currency_data_event' );

        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . CCC_TABLE_LOG . " (
			  id int(9) NOT NULL AUTO_INCREMENT,
			  convert_from tinytext NOT NULL,
			  convert_to tinytext NOT NULL,
			  convert_value VARCHAR(30) NOT NULL,
			  time_action DATETIME NOT NULL default CURRENT_TIMESTAMP,
			  UNIQUE KEY id (id)
			);";

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . CCC_TABLE_CURRENCY . " (
			  id int(9) NOT NULL AUTO_INCREMENT,
			  cur_id int(9) NOT NULL,
			  cur_symbol tinytext NOT NULL,
              cur_name VARCHAR(20) NOT NULL,
              cmc_rank int(9) NOT NULL,
			  usd VARCHAR(30) NOT NULL,
			  time_action DATETIME NOT NULL default CURRENT_TIMESTAMP,
			  UNIQUE KEY id (id)
			);";

        dbDelta($sql);

    }

}
