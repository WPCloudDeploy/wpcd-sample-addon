<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WPCD_WORDPRESS_TABS_APP_SAMPLE extends WPCD_WORDPRESS_TABS {

	/**
	 * WPCD_WORDPRESS_TABS_PHP constructor.
	 */
	public function __construct() {
		
		parent::__construct();

		add_filter( "wpcd_app_{$this->get_app_name()}_get_tabnames", array( $this, 'get_tab' ), 10, 1 );
		add_filter( "wpcd_app_{$this->get_app_name()}_get_tabs", array( $this, 'get_tab_fields_sample' ), 10, 2 );		
		add_filter( "wpcd_app_{$this->get_app_name()}_tab_action", array( $this, 'tab_action_sample' ), 10, 3 );  
		
		add_action( "wpcd_command_{$this->get_app_name()}_completed", array( $this, 'command_completed_sample' ), 10, 2 );	

	}

	 /**
	  * Called when a command completes.
	  *
	  * To see an example of how this would be used, see the 
	  * \includes\core\apps\wordpress-app\tabs\clone-site.php 
	  * file in the wpcd plugin.
	  * 
	  *
	  * Action Hook: wpcd_command_{$this->get_app_name()}_completed
	  *
	  * @param int		$id		The postID of the server cpt
	  * @param string	$name	The name of the command.
	  *
	  */
	function command_completed_sample( $id, $name ) {

		// remove the 'temporary' meta so that another attempt will run if necessary.
		delete_post_meta( $id, "wpcd_app_{$this->get_app_name()}_action_status" );
		delete_post_meta( $id, "wpcd_app_{$this->get_app_name()}_action" );
		delete_post_meta( $id, "wpcd_app_{$this->get_app_name()}_action_args" );

	}
	
	
	/**
	 * Populates the tab name.
	 *
	 * @param array		$tabs The default value.
	 *
	 * @return array	$tabs The default value.
	 */
	function get_tab( $tabs ) {
		$tabs['sample'] = array(
					'label' => __( 'Sample Add-on', 'wpcd' ),
		);
		return $tabs;
	}	


	/**
	 * Gets the fields to be shown in the Sample tab.
	 *
	 * Filter hook: wpcd_app_{$this->get_app_name()}_get_tabs
	 * 
	 * @return array Array of actions, complying with the structure necessary by metabox.io fields.
	 */	
	function get_tab_fields_sample( array $fields, $id ) {
		
		return $this->get_fields_for_tab( $fields, $id, 'sample' );
		
	}
	
	/**
	 * Called when an action needs to be performed on the tab.
	 *
	 * @param mixed		$result The default value of the result.
	 * @param string	$action The action to be performed.
	 * @param int		$id The post ID of the server.
	 *
	 * @return mixed	$result The default value of the result.
	 */
	function tab_action_sample( $result, $action, $id ) {
		
		switch ( $action ) {
			case 'sample-action-a':
				$result = $this->sample_action_a( $id, $action ) ;
				break;
			case 'sample-action-b':
				$result = $this->sample_action_b( $id, $action ) ;
				break;
		}
		
		return $result;
		
	}	

	/**
	 * Gets the actions to be shown in the Sample tab.
	 *
	 * @return array Array of actions with key as the action slug and value complying with the structure necessary by metabox.io fields.
	 */
	function get_actions( $id ) {
		
		return $this->get_server_fields_sample( $id );

	}
	
	/**
	 * Gets the fields for the services to be shown in the Sample tab in the server details screen.
	 *
	 * @params int $id the post id of the app cpt record
	 *
	 * @return array Array of actions with key as the action slug and value complying with the structure necessary by metabox.io fields.
	 */		
	private function get_server_fields_sample( $id ) {

		// Set up metabox items
		$actions = array();
			
		// Heading
		$sample_desc = __( 'Sample heading with some instructions and notes if you want.', 'wpcd' );
		$sample_desc .= '<br />';
		
		$actions['sample-add-on-heading'] = array(
			'label'	=> __( 'Sample Heading', 'wpcd' ),
			'type'	=> 'heading',
			'raw_attributes' => array(			
				'desc'	=> $sample_desc,
			),
		);
		
		$actions['sample-action-field-01'] = array(
			'label'	=> __( 'Sample Text Data', 'wpcd' ),
			'type'	=> 'text',
			'raw_attributes' => array(			
				'desc'	=> __( 'Enter some data here. It\'s actually not used in this example but is shown here so you can see how it\'s passed via the AJAX request ', 'wpcd' ),
				// the key of the field (the key goes in the request).
				'data-wpcd-name' => 'sample_data_01',						
			),
		);
		
		$actions['sample-action-a'] = array(
			'label'	=> __( 'Update Plugins', 'wpcd' ),
			'raw_attributes' => array(
				'std' 	=> __( 'Update All Plugins', 'wpcd' ),
				'desc'	=> __( 'Update all plugins on the site - this is real and will update all plugins on the site!', 'wpcd' ),
				// fields that contribute data for this action
				'data-wpcd-fields' => json_encode(array( '#wpcd_app_action_sample-action-field-01' )),											
				// make sure we give the user a confirmation prompt
				'confirmation_prompt' => __( 'Are you sure you would like to update all plugins?', 'wpcd' ),
			),
			'type'	=> 'button',
		);

		$actions['sample-action-b'] = array(
			'label'	=> __( 'Update Themes', 'wpcd' ),
			'raw_attributes' => array(
				'std' 	=> __( 'Update All Themes', 'wpcd' ),
				'desc'	=> __( 'Update all themes on the site - this is just another button but it does nothing.', 'wpcd' ),
				// fields that contribute data for this action
				'data-wpcd-fields' => json_encode(array( '#wpcd_app_action_sample-action-field-01' )),											
				// make sure we give the user a confirmation prompt
				'confirmation_prompt' => __( 'Are you sure you would like to update all themes?', 'wpcd' ),
			),
			'type'	=> 'button',
		);
		
		return $actions;				

	}
	
	 /**
	  * Sample Action "A": updates all plugins on the site.
	  *
	  * @param int		$id			The postID of the server cpt
	  * @param string	$action		The action to be performed (this matches the string required in the bash scripts if bash scripts are used )
	  *
	  * @return boolean	success/failure/other
	  */

	private function sample_action_a( $id, $action ) {
		
		// Get the instance details
		$instance = $this->get_app_instance_details( $id );
		 
		if ( is_wp_error( $instance ) ) {
			 return new \WP_Error( sprintf( __( 'Unable to execute this request because we cannot get the instance details for action %s', 'wpcd' ), $action ) );
		}
		
		// Get the domain we're working on
		$domain = $this->get_domain_name( $id ) ;

		// Construct a simple command.
		// This command is three bash commands chanined by "&&".
		// First it changes the folder to the wordpress folder (which is the same name as the domain).
		// Then it lists the folder name (this is unnecessary but included here just to show how bash chaining works if you're not familiar with it.
		// Finally it runs the wp-cli plugin update command.
		// The full command will look like this: 'cd /var/www/my.domain.com/html && pwd && sudo -u my.domain.com wp plugin update --all'.
		$command = 'cd /var/www/' . $domain  . '/html' . ' && pwd && sudo -u ' . $domain . ' wp plugin update --all';
			
		// Send the command and wait for a reply.
		// This command needs to complete within the limits of the PHP Execution timeout.
		$result = $this->execute_ssh( 'generic', $instance, array( 'commands' => $command  ) );		

		// Check output string to make sure we don't have an error...
		if ( !( strpos( $result, 'Success: Updated' ) ) && !( strpos( $result, 'Success: Plugin already updated.' ) ) ) {
			return new \WP_Error( __('An error was encounered during the updates. Please check the SSH logs for more information.' , 'wpcd' ) );
		}

		// If you got here, success!
		// @todo: you can do cool things by parsing the result string to check for number of plugins updated and reporting that back to the user.
		$success_msg = __( 'Command was a success - plugins updated!', 'wpcd');
		$result = array( 'msg' => $success_msg, 
						'refresh' => 'yes',
						);

		
		return $result ;
		
	}
	
}

new WPCD_WORDPRESS_TABS_APP_SAMPLE();