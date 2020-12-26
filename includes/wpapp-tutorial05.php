<?php
/**
 * Class for handling hooks and filters for the 5th article in our tutorial series.
 *
 * @package WPCD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for adding a new tab to the application details screen.
 */
class WPCD_WordPress_Tutorial_05 extends WPCD_APP {

	/**
	 * WPCD_WordPress_Tutorial_05 constructor.
	 */
	public function __construct() {

		parent::__construct();

		// Hook to add a field to the create new server popup.
		add_action( 'wpcd_wordpress-app_create_popup_before_install_button', array( $this, 'create_popup_before_install_button' ), 10, 2 );

		// Hook to add a custom field into the custom fields array - this will link fields added to the screen using the hook above to a public array of fields.
		add_action( 'init', array( $this, 'add_custom_fields' ), 10 );

		// Filter to handle script file tokens - but we're not going to replace tokens.  We're going to replace the
		// primary bash script name and path that installs the core server components.
		add_filter( 'wpcd_wpapp_replace_script_tokens', array( $this, 'wpcd_wpapp_replace_script' ), 10, 7 );
	}


	/**
	 * Add a select drop-down to the create WordPress site popup.
	 *
	 * Action Hook: wpcd_wordpress-app_install_app_popup_after_form_open
	 *
	 * @param int    $server_id The postID of the server cpt.
	 * @param string $user_id   The user id of the logged in admin performing the action.
	 */
	public function create_popup_before_install_button( $server_id, $user_id ) {
		?>
		<div class="wpcd-create-popup-label-wrap"><label class="wpcd-create-popup-label" for="port_25"> <?php echo __( 'Open Port 25?', 'wpcd' ); ?>  </label></div>
		<div class="wpcd-create-popup-input-wrap wpcd-create-popup-input-wp-version-select2-wrap">
			<?php
				$port_25_options = array(
					'1' => 'Yes',
					'0' => 'No',
				);
				?>
			<select name="port_25" id="wpcd-server-port-25" style="width: 150px;">
				<?php
				foreach ( $port_25_options as $key => $port_25_option ) {
					?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $port_25_option ); ?></option>
						<?php
				}
				?>
			</select>
		</div>		
		<?php
	}

	/**
	 * Add a field to the custom fields array. It must have the same name as the element
	 * that was added to the app popup.
	 * See function install_app_popup_after_form_open() in this class as well
	 * as the wpcd_wordpress-app_install_app_popup_after_form_open filter.
	 */
	public function add_custom_fields() {

		$field = array();

		$field['name']         = 'port_25';  // The name of the field - it should match the field id/name used above.
		$field['location']     = 'wordpress-app-new-server-popup';  // The field is shown on the popup when creating a new server in wp-admin as you can see in the function above.
		$field['script_merge'] = true;
		$field['script_name']  = 'after-server-create-run-commands.txt';  // This is the name of the script file that acts as bridge between the plugin and the main bash script that does all the heavy lifting.

		WPCD_CUSTOM_FIELDS()->add_field( $field['name'], $field );

	}

	/**
	 * Set the script to use to create a new WordPress installation..
	 *
	 * Filter Hook: wpcd_wpapp_replace_script_tokens
	 *
	 * @param array  $new_array          Existing array of placeholder data.
	 * @param array  $array              The original array of data passed into the core 'script_placeholders' function.
	 * @param string $script_name        The name of the script being processed.
	 * @param string $script_version     The version of script to be used.
	 * @param array  $instance           Various pieces of data about the server or app being used. It can use the following keys:
	 *      post_id: the ID of the post.
	 * @param string $command            The command being constructed.
	 * @param array  $additional         An array of any additional data we might need. It can use the following keys (non-exhaustive list):
	 *    command: The command to use (a script may have multiple commands)
	 *    domain: The domain of the site
	 *    user: The user to action.
	 *    email: The email to use.
	 *    public_key: The path to the public key
	 *    password: The password of the user.
	 */
	public function wpcd_wpapp_replace_script( $new_array, $array, $script_name, $script_version, $instance, $command, $additional ) {

		if ( 'after-server-create-run-commands.txt' === $script_name ) {
			$new_array['SCRIPT_URL'] = trailingslashit( WPCDSAMPLE_URL ) . 'includes/scripts/my-prepare-server.txt';
		}

		return $new_array;

	}

}

new WPCD_WordPress_Tutorial_05();
