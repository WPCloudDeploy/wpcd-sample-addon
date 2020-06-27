<?php
/*
Plugin Name: WPCD Sample Add-on
Plugin URI: https://wpclouddeploy.com
Description: A sample add-on for the WPCloudPanel (formerly WPCloudDeploy) plugin 
Version: 1.0.0
Author: WPCloudDeploy
Author URI: https://wpclouddeploy.com
*/
require_once( ABSPATH.'wp-admin/includes/plugin.php' );

class WPCD_Sample_AddOn {

	public function __construct() {

		$plugin_data = get_plugin_data( __FILE__ );

		if ( ! defined( 'wpcdsample_url' ) ) {
			define( 'wpcdsample_url', plugin_dir_url( __FILE__ ) );
			define( 'wpcdsample_path', plugin_dir_path( __FILE__ ) );
			define( 'wpcdsample_plugin', plugin_basename( __FILE__ ) );
			define( 'wpcdsample_extension', $plugin_data['Name'] );
			define( 'wpcdsample_version', $plugin_data['Version'] );
			define( 'wpcdsample_textdomain', 'wpcd' );
			define( 'wpcdsample_requires', '2.0.3' );
		}
	
		/* Run things after WordPress is loaded */
		add_action( 'init', array( $this, 'required_files'), -20 );
		
		/* Insert wpapp tabs where they need to go */
		add_action( 'wpcd_wpapp_include_app_tabs', array( $this, 'required_wpapp_tab_files') );
		
	}

	/**
	 * Include additional files as needed 
	 *
	 * Action Hook: init
	 *
	 */
    function required_files() {
		require_once wpcd_path . 'includes/core/apps/wordpress-app/tabs/tabs.php';
	}

	/**
	 * Insert tabe on the app detail screen
	 *
	 * Action Hook: wpcd_wpapp_include_app_tabs
	 *
	 */	
	function required_wpapp_tab_files() {
		require_once wpcdsample_path . '/includes/wpapp-tabs-sample.php';
	}

	/**
	 * @TODO: You can hook into this function with a WP filter 
	 * if you need to do things when the plugin is activated.
	 * Right now nothing in this gets executed.
	 */
	function activation_hook() {
		//first install
		$version = get_option( 'wpcdsample_version' );
		if ( ! $version )
			update_option( 'wpcdsample_last_version_upgrade', wpcdsample_version );

		if ( $version != wpcdsample_version )
			update_option( 'wpcd_version', wpcdsample_version );

		// Some setup options here?
	}
}

/**
 * Bootstrap the class
 *
 */
if ( class_exists( 'WPCD_Init' ) ) { 
	$wpcdsample = new WPCD_Sample_AddOn();
}