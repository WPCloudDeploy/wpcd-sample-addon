<?php
/**
 * Class for handling hooks and filters for the 4th article in our tutorial series.
 *
 * @package WPCD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for adding a new tab to the application details screen.
 */
class WPCD_WordPress_Tutorial_04 extends WPCD_APP {

	/**
	 * WPCD_WordPress_Tutorial_04 constructor.
	 */
	public function __construct() {

		parent::__construct();

		// Hook to add a field to the create WordPress site popup.
		add_action( 'wpcd_wordpress-app_install_app_popup_after_form_open', array( $this, 'install_app_popup_after_form_open' ), 10, 2 );

		// Hook to add a custom field into the custom fields array - this will link fields added to the screen using the hook above to a public array of fields.
		add_action( 'init', array( $this, 'add_custom_fields' ), 10 );

		// add_filter( 'wpcd_wordpress-app_install_wp_app_parms', array( $this, 'install_wp_app_parms' ), 10, 2 );
	}


	/**
	 * Add a select drop-down to the create WordPress site popup.
	 *
	 * Action Hook: wpcd_wordpress-app_install_app_popup_after_form_open
	 *
	 * @param int    $server_id The postID of the server cpt.
	 * @param string $user_id   The user id of the logged in admin performing the action.
	 */
	public function install_app_popup_after_form_open( $server_id, $user_id ) {
		?>
		<div class="wpcd-create-popup-label-wrap"><label class="wpcd-create-popup-label" for="wp_traffic"> <?php echo __( 'Estimated Level of Traffic For New Site', 'wpcd' ); ?>  </label></div>
		<div class="wpcd-create-popup-input-wrap wpcd-create-popup-input-wp-version-select2-wrap">
			<?php
				$traffic_options = array(
					'low'       => 'Low Traffic',
					'medium'    => 'Medium Traffic',
					'high'      => 'High Traffic',
					'superhigh' => 'Super High Traffic',
				);
				?>
			<select name="wp_traffic" id="wpcd-wp-traffic" style="width: 150px;">
				<?php
				foreach ( $traffic_options as $key => $traffic_option ) {
					?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $traffic_option ); ?></option>
						<?php
				}
				?>
			</select>
		</div>		
		<?php
	}

	/**
	 * Move data from the form into an array that will transfer the data
	 * to the BASH scripts.
	 *
	 * Filter Hook: wpcd_wordpress-app_install_wp_app_parms
	 *
	 * Hook Documentation: https://wpclouddeploy.com/documentation/wpcloud-deploy-dev-notes/filter-hook-wpcd_wordpress-app_install_app_popup/
	 *
	 * @param array $additional A key-value array that will be transfered to the BASH scripts.
	 * @param array $args       A text sanitized array of data that was entered on the create site popup form.
	 */
	public function install_wp_app_parms( $additional, $args ) {
		if ( isset( $args['wp_traffic'] ) ) {
			$additional['wp_traffic'] = $args['wp_traffic'];  // $args should already be sanitized.
		}

		return $additional;
	}

	/**
	 * Add a field to the custom fields array. It must have the same name as the element
	 * that was added to the app popup.
	 * See function install_app_popup_after_form_open() in this class as well
	 * as the wpcd_wordpress-app_install_app_popup_after_form_open filter.
	 */
	public function add_custom_fields() {

		$field = array();

		$field['name']         = 'wp_traffic';
		$field['location']     = 'wordpress-app-new-app-popup';
		$field['script_merge'] = true;

		WPCD_CUSTOM_FIELDS()->add_field( $field['name'], $field );

		/* This field here isn't used - it's just to show that you can add additional fields for different locations that can be used by other code outside of the new WordPress site popup. */
		$field2 = array();

		$field2['name']         = 'wp_server_custom_description';
		$field2['location']     = 'wordpress-app-wp_server';
		$field2['script_merge'] = false;

		WPCD_CUSTOM_FIELDS()->add_field( $field2['name'], $field2 );

	}

}

new WPCD_WordPress_Tutorial_04();
