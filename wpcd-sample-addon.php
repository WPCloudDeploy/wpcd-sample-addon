<?php
/**
 * Plugin Name: WPCD Sample Add-on
 * Plugin URI: https://wpclouddeploy.com
 * Description: A sample add-on for the WPCloudPanel (formerly WPCloudDeploy) plugin
 * Version: 1.1.0
 * Author: WPCloudDeploy
 * Author URI: https://wpclouddeploy.com
 */

require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Bootstrap class for this sample plugin.
 */
class WPCD_Sample_AddOn {

	/**
	 *  Constructor function of course.
	 */
	public function __construct() {
		$plugin_data = get_plugin_data( __FILE__ );

		if ( ! defined( 'WPCDSAMPLE_URL' ) ) {
			define( 'WPCDSAMPLE_URL', plugin_dir_url( __FILE__ ) );
			define( 'WPCDSAMPLE_PATH', plugin_dir_path( __FILE__ ) );
			define( 'WPCDSAMPLE_PLUGIN', plugin_basename( __FILE__ ) );
			define( 'WPCDSAMPLE_EXTENSION', $plugin_data['Name'] );
			define( 'WPCDSAMPLE_VERSION', $plugin_data['Version'] );
			define( 'WPCDSAMPLE_TEXTDOMAIN', 'wpcd' );
			define( 'WPCDAMPLE_REQUIRES', '2.0.3' );
		}

		/* Run things after WordPress is loaded */
		add_action( 'init', array( $this, 'required_files' ), -20 );

		/* Insert wpapp tabs where they need to go */
		add_action( 'wpcd_wpapp_include_app_tabs', array( $this, 'required_wpapp_tab_files' ) );

	}

	/**
	 * Include additional files as needed
	 *
	 * Action Hook: init
	 */
	public function required_files() {
		include_once wpcd_path . 'includes/core/apps/wordpress-app/tabs/tabs.php';
		include_once WPCDSAMPLE_PATH . '/includes/wpapp-tutorial04.php';
		include_once WPCDSAMPLE_PATH . '/includes/wpapp-tutorial05.php';
	}

	/**
	 * Insert tabs on the app detail screen
	 *
	 * Action Hook: wpcd_wpapp_include_app_tabs
	 */
	public function required_wpapp_tab_files() {
		include_once WPCDSAMPLE_PATH . '/includes/wpapp-tabs-sample.php';
	}

	/**
	 * Placeholder activation function.
	 *
	 * @TODO: You can hook into this function with a WP filter
	 * if you need to do things when the plugin is activated.
	 * Right now nothing in this gets executed.
	 */
	public function activation_hook() {
		// first install.
		$version = get_option( 'wpcdsample_version' );
		if ( ! $version ) {
			update_option( 'wpcdsample_last_version_upgrade', WPCDSAMPLE_VERSION );
		}

		if ( WPCDSAMPLE_VERSION !== $version ) {
			update_option( 'wpcd_version', WPCDSAMPLE_VERSION );
		}

		// Some setup options here?
	}
}

/**
 * Bootstrap the class
 */
if ( class_exists( 'WPCD_Init' ) ) {
	$wpcdsample = new WPCD_Sample_AddOn();
}
