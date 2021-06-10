<?php
class Cryptocurrency_Converter_Deactivator {

    public static function deactivate() {

        wp_clear_scheduled_hook( 'get_currency_data_event' );

    }

}
