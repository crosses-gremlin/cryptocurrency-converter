<?php
/**
 * Description document
 *
 * @file
 * @package package
 */

/**
 * Class Cryptocurrency_Converter
 */
class Cryptocurrency_Converter {

	/**
	 * Description
	 *
	 * @var string $loader
	 */
	protected $loader;

	/**
	 * Description
	 *
	 * @var string $plugin_name
	 */
	protected $plugin_name;

	/**
	 * Description
	 *
	 * @var string $version
	 */
	protected $version;

	/**
	 * Cryptocurrency_Converter constructor.
	 */
	public function __construct() {
		if ( defined( 'CCC_VERSION' ) ) {
			$this->version = CCC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = CCC_PLUGIN_SLUG;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Description
	 */
	private function load_dependencies() {

		require_once CCC_PATH . 'includes/class-cryptocurrency-converter-loader.php';

		require_once CCC_PATH . 'includes/class-cryptocurrency-converter-i18n.php';

		require_once CCC_PATH . 'admin/class-cryptocurrency-converter-admin.php';

		require_once CCC_PATH . 'public/class-cryptocurrency-converter-public.php';

		$this->loader = new Cryptocurrency_Converter_Loader();

	}

	/**
	 * Description
	 */
	private function set_locale() {

		$plugin_i18n = new Cryptocurrency_Converter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Description
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cryptocurrency_Converter_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'cron_add_five_min' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'activation_cron_task' );
		$this->loader->add_action( 'get_currency_data_event', $plugin_admin, 'get_currency_data' );
		if ( wp_doing_ajax() ) {
			$this->loader->add_action( 'wp_ajax_converter_log', $plugin_admin, 'converter_log_callback' );
			$this->loader->add_action( 'wp_ajax_nopriv_converter_log', $plugin_admin, 'converter_log_callback' );
			$this->loader->add_action( 'wp_ajax_get_currency', $plugin_admin, 'get_currency_callback' );
			$this->loader->add_action( 'wp_ajax_nopriv_get_currency', $plugin_admin, 'get_currency_callback' );
		}
	}

	/**
	 * Description
	 */
	private function define_public_hooks() {

		$plugin_public = new Cryptocurrency_Converter_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( 'cryptocurrency_converter', $plugin_public, 'cryptocurrency_converter_shortcode_function' );

	}

	/**
	 * Description
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Description
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Description
	 *
	 * @return string
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Description
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

}
