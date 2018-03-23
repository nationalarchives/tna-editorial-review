<?php
/**
 * admin options
 */

function get_tna_admin_editor_users() {

	$args = array(
		'role__in'     => array( 'administrator', 'editor' )
	);
	$users = get_users( $args );

	return $users;
}

add_action( 'admin_menu', 'er_menu' );

function er_menu() {
	add_menu_page( 'TNA Editorial Review', 'erFlow', 'administrator', 'er-admin-page', 'er_admin_page', 'dashicons-admin-customizer', 21  );

	add_action( 'admin_init', 'er_admin_page_settings' );
}

function er_admin_page_settings() {
	register_setting( 'er-settings-group', 'er_editor_contact' );
}

function er_admin_page() {
	if (!current_user_can('administrator'))  {
		wp_die( __('You do not have sufficient pilchards to access this page.')    );
	}
	$editors = get_tna_admin_editor_users();
	?>
	<style>
		.er-admin input[type=text] {
			width: 100%;
			max-width: 320px;
		}
		.er-admin textarea {
			width: 100%;
			max-width: 320px;
			height: 12em;
		}
	</style>
	<div class="wrap er-admin">
		<h1>TNA Editorial Review settings</h1>
		<form method="post" action="options.php" novalidate="novalidate">
			<?php settings_fields( 'er-settings-group' ); ?>
			<?php do_settings_sections( 'er-settings-group' ); ?>

			<h2>Reviewer</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="er_editor_contact">Editor</label></th>
					<td><select id="er_editor_contact" name="er_editor_contact">
							<option value="">Please select</option>
							<?php foreach ( $editors as $editor ) {
								$value = esc_html( $editor->user_login );
								?>
								<option <?php if (get_option('er_editor_contact') == $value) { echo ' selected="selected"'; }; ?> value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php } ?>
						</select></td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
