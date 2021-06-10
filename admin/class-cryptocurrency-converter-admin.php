<?php

class Cryptocurrency_Converter_Admin {

	private $plugin_name;

	private $version;

	private $key;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
		add_action('admin_init', array( $this, 'registerAndBuildFields' ));
        $this->key = get_option('cryptocurrency_converter_coinmarketcap_key');
	}

	private function insert_currency_data($data) {
        global $wpdb;
        $sql = "TRUNCATE TABLE " . $wpdb->prefix . CCC_TABLE_CURRENCY;
        $wpdb->get_results($sql);

        foreach ($data as $cur) {
            $wpdb->insert($wpdb->prefix . CCC_TABLE_CURRENCY, array(
                    'cur_id'   => $cur['id'],
                    'cur_symbol'  => $cur['symbol'],
                    'cmc_rank' => $cur['cmc_rank'],
                    'cur_name'=> $cur['name'],
                    'usd'   => $cur['quote']['USD']['price'] ?? '',
                    'time_action'   => current_time('mysql'),
                )
            );
        }
    }

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cryptocurrency-converter-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cryptocurrency-converter-admin.js', array( 'jquery' ), $this->version, true );

	}

    public function cron_add_five_min( $schedules ) {

	    $schedules['five_min'] = array(
            'interval' => 60 * 5,
            'display' => __('Once in 5 min', CCC_PLUGIN_SLUG),
        );

        return $schedules;
    }

    public function activation_cron_task() {
        if( ! wp_next_scheduled( 'get_currency_data' ) && $this->key) {
            wp_schedule_event( time(), 'five_min', 'get_currency_data_event');
        }
    }

    public function get_currency_data() {

        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';

        $parameters = [
            'start' => '1',
            'limit' => '500',
            'convert' => 'USD'
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: '. $this->key
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $result = json_decode($response, true); // print json decoded response
        curl_close($curl); // Close request

        if (isset($result['data'])) {
           $this->insert_currency_data($result['data']);
        } else {
            $error_message = $result['status']['error_message'];
            file_put_contents(CCC_PATH . 'error.log', $error_message . PHP_EOL, FILE_APPEND);
        }
    }

	public function addPluginAdminMenu() {

		add_menu_page(  $this->plugin_name, 'Cryptocurrency Converter', 'administrator', $this->plugin_name, array( $this, 'displayPluginAdminDashboard' ), 'dashicons-chart-area', 26 );

	}

	public function displayPluginAdminDashboard() {

        require_once 'partials/'.$this->plugin_name.'-admin-settings-display.php';

	}

	public function registerAndBuildFields() {

		add_settings_section(
			'cryptocurrency_converter_general_section',
			'',
				array( $this, 'cryptocurrency_converter_display_general_account' ),
			'cryptocurrency_converter_general_settings'
		);
		unset($args);
		$args = array (
							'type'      => 'input',
							'subtype'   => 'text',
							'id'    => 'cryptocurrency_converter_coinmarketcap_key',
							'name'      => 'cryptocurrency_converter_coinmarketcap_key',
							'required' => 'required',
							'value_type'=>'normal'

					);
		add_settings_field(
			'cryptocurrency_converter_coinmarketcap_key',
            __('API KEY', CCC_PLUGIN_SLUG),
			array( $this, 'cryptocurrency_converter_render_settings_field' ),
			'cryptocurrency_converter_general_settings',
			'cryptocurrency_converter_general_section',
			$args
		);

		register_setting(
						'cryptocurrency_converter_general_settings',
						'cryptocurrency_converter_coinmarketcap_key'
						);

        $args = array (
            'type'      => 'input',
            'subtype'   => 'checkbox',
            'id'    => 'cryptocurrency_converter_use_select2',
            'name'      => 'cryptocurrency_converter_use_select2',
            'required' => '',
            'value_type'=>'normal'
        );

        add_settings_field(
            'cryptocurrency_converter_use_select2',
            __('Disable Select2 script from plugin', CCC_PLUGIN_SLUG),
            array( $this, 'cryptocurrency_converter_render_settings_field' ),
            'cryptocurrency_converter_general_settings',
            'cryptocurrency_converter_general_section',
            $args
        );

        register_setting(
            'cryptocurrency_converter_general_settings',
            'cryptocurrency_converter_use_select2'
        );

	}

	public function cryptocurrency_converter_display_general_account() {

		echo '<p>Lorem ipsum.</p>';

	}

	public function cryptocurrency_converter_render_settings_field($args) {

        $wp_data_value = get_option($args['name']);

		switch ($args['type']) {

			case 'input':
					$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
					if($args['subtype'] != 'checkbox'){
							$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
							$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
							$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
							$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
							$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
							if(isset($args['disabled'])){
									echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
							} else {
									echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$args['required'].' '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
							}

					} else {
							$checked = ($value) ? 'checked' : '';
							echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$args['required'].' name="'.$args['name'].'" size="40" value="1" '.$checked.' />';
					}
					break;
			default:
					break;
		}
	}

    public function converter_log_callback() {
	    global $wpdb;
        check_ajax_referer( 'converter-nonce', 'nonce_code' );

        $convert_from  = trim($_POST['convert_from']);
        $convert_to    = trim($_POST['convert_to']);
        $convert_value = trim($_POST['convert_value']);
        $time_action   = date('Y-m-d H:i:s');
        $wpdb->query($wpdb->prepare("INSERT INTO $wpdb->prefix".CCC_TABLE_LOG." (convert_from, convert_to, convert_value, time_action) VALUES (%s, %s, %s, %s)", $convert_from, $convert_to, $convert_value, $time_action ));

        echo 'log saved';
        wp_die();

    }

    public function get_currency_callback() {
        global $wpdb;
        check_ajax_referer( 'converter-nonce', 'nonce_code' );
        $convert_from  = trim($_POST['convert_from']);
        $convert_to    = trim($_POST['convert_to']);
        $result = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM ". $wpdb->prefix . CCC_TABLE_CURRENCY ."
		            WHERE cur_symbol IN (%s, %s) ",
            $convert_from, $convert_to
        ) );
        foreach ( $result as $res) {
            if ($res->cur_symbol === $convert_from) {
                $convert_from_usd = $res->usd;
            }
            if ($res->cur_symbol === $convert_to) {
                $convert_to_usd = $res->usd;
            }
        }
        if ($convert_to_usd !== 0) {
            $currency = $convert_from_usd / $convert_to_usd;
        } else  {
            $currency = 0;
        }
        echo round($currency, 7);
        wp_die();
    }
}

