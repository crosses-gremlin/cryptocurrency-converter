<?php


class Cryptocurrency_Converter_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
        $disable_select2 = get_option('cryptocurrency_converter_use_select2');

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cryptocurrency-converter-public.css', array(), $this->version, 'all' );
        if (!$disable_select2)
            wp_enqueue_style( 'select2', CCC_URL . 'vendor/select2/select2/dist/css/select2.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cryptocurrency-converter-public.js', array( 'jquery' ), $this->version, true );
        $params = array(
            'l18n' => array(

            ),
            'vars' => array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('converter-nonce')

            )
        );
        wp_localize_script($this->plugin_name, 'cryptocurrency_converter', $params);

        $disable_select2 = get_option('cryptocurrency_converter_use_select2');
        if (!$disable_select2)
            wp_enqueue_script( 'select2', CCC_URL . 'vendor/select2/select2/dist/js/select2.min.js', array( 'jquery' ), $this->version, true );

    }

    public function cryptocurrency_converter_shortcode_function() {
	    $converter_log_data = $this->get_converter_log_data();
        $converter_currency_data = $this->get_converter_currency_data();

        ob_start();
        include_once 'partials/'.$this->plugin_name.'-public-display.php';
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }

    public function get_converter_log_data() {
	    global $wpdb;

        $result = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix . CCC_TABLE_LOG ." ORDER BY id DESC LIMIT 10" );

        return $result;
    }

    public function get_converter_currency_data() {
        global $wpdb;

        $result = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix . CCC_TABLE_CURRENCY . " ORDER BY cmc_rank ASC" );

        return $result;
    }
}
