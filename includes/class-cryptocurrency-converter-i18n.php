<?php

class Cryptocurrency_Converter_i18n {

    public function load_plugin_textdomain($plugin_name) {

        load_plugin_textdomain(
            CCC_PLUGIN_SLUG,
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
