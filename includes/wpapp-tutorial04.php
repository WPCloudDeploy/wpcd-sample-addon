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

		// Filter to handle script file tokens - but we're not going to replace tokens.  We're going to replace the
		// primary bash script name and path that installs WordPress.
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
	 * Add a field to the custom fields array. It must have the same name as the element
	 * that was added to the app popup.
	 * See function install_app_popup_after_form_open() in this class as well
	 * as the wpcd_wordpress-app_install_app_popup_after_form_open filter.
	 */
	public function add_custom_fields() {

		$field = array();

		$field['name']         = 'wp_traffic';  // The name of the field - it should match the field id/name used above.
		$field['location']     = 'wordpress-app-new-app-popup';  // The field is shown on the popup when creating a new site in wp-admin as you can see in the function above.
		$field['script_merge'] = true;
		$field['script_name']  = 'install_wordpress_site.txt';  // This is the name of the script file that acts as bridge between the plugin and the main bash script that does all the heavy lifting.

		WPCD_CUSTOM_FIELDS()->add_field( $field['name'], $field );

		/* This field here isn't used - it's just to show that you can add additional fields for different locations that can be used by other code outside of the new WordPress site popup. */
		$field2 = array();

		$field2['name']         = 'wp_server_custom_description';
		$field2['location']     = 'wordpress-app-wp_server';
		$field2['script_merge'] = false;

		WPCD_CUSTOM_FIELDS()->add_field( $field2['name'], $field2 );

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

		if ( 'install_wordpress_site.txt' === $script_name ) {
			$new_array['SCRIPT_URL'] = trailingslashit( WPCDSAMPLE_URL ) . 'includes/scripts/' . 'my_custom_install_wp_site.txt';
		}

		return $new_array;

	}

}

new WPCD_WordPress_Tutorial_04();
