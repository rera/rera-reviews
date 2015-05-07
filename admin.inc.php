<?php
/**
* Generates the settings page in the Admin
*
* @package Rera_Reviews
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
* Reviews options.
*/
function rera_reviews_options() {

	global $wpdb;
	$poststable = $wpdb->posts;

	$rera_reviews_settings = rera_reviews_read_options();

	if ( isset( $_POST['rera_reviews_save'] ) && check_admin_referer( 'rera-reviews-admin-options' ) ) {
		$rera_reviews_settings['test'] = $_POST['test'];

		update_option( 'rera_reviews_settings', $rera_reviews_settings );

		$str = '<div id="message" class="updated fade"><p>Options saved successfully.</p></div>';
		echo $str;
	}

	if ( isset( $_POST['rera_reviews_default'] ) && check_admin_referer( 'rera_reviews-admin-options' ) ) {
		delete_option( 'rera_reviews_settings' );
		$rera_reviews = rera_reviews_default_options();
		update_option( 'rera_reviews_settings', $rera_reviews_settings );

		$str = '<div id="message" class="updated fade"><p>Options set to Default.</p></div>';
		echo $str;
	}
	?>

	<div class="wrap">
		<h2>Reviews Settings</h2>
		<form method="post" id="rera_reviews_options" name="rera_reviews_options" onsubmit="return checkForm()">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<?php rera_reviews_admin_side(); ?>
					</div><!-- /side-sortables -->
				</div><!-- /postbox-container-1 -->
					<div id="post-body-content" class="postbox-container">
						<div class="meta-box-sortables">

							<div id="postbox-security" class="postbox">
								<div class="handlediv" title="Click to toggle"><br /></div>
								<h3 class='hndle'><span>Configuration</span></h3>
								<div class="inside">
									<table class="form-table">
										<tr><th scope="row"><label for="test">Test:</label></th>
											<td><input type="text" name="test" id="test" value="<?php echo stripslashes( $rera_reviews_settings['test'] ); ?>" /></td>
										</tr>
										<tr><td scope="row" colspan="2"></td>
										</tr>
									</table>
								</div>
							</div>

							<?php wp_nonce_field( 'rera-reviews-admin-options' ); ?>
						</div>

					</div><!-- /post-body-content -->
				</div><!-- /post-body -->
				<br class="clear" />
			</div><!-- /poststuff -->
		</form>
	</div><!-- /wrap -->

	<?php
}


/**
* Function to generate the right sidebar of the Settings page.
*/
function rera_reviews_admin_side() {
	?>

	<div id="postbox-security" class="postbox">
		<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class='hndle'><span>Actions</span></h3>
		<div class="inside">
			<p><input type="submit" name="rera_reviews_save" id="rera_reviews_save" value="Save Options" class="button wide_button button-primary" /></p>
			<p><input type="submit" name="rera_reviews_default" id="rera_reviews_default" value="Default Options" class="button wide_button button-secondary" onclick="if ( ! confirm( 'Do you want to set options to Default?' ) ) return false;" /></p>
		</div>
	</div>

	<style type="text/css">
		#rera_reviews_options .wide_button {
			width: 100%!important;
		}
		#rera_reviews_options input[type="text"] {
			width: 100%;
		}
	</style>
	<?php
}


/**
* Add menu item in WP-Admin.
*
*/
function rera_reviews_adminmenu() {
	// add_menu_page( 'Settings', 'Reviews', 'activate_plugins', 'rera-reviews', 'rera_reviews_options', 'dashicons-awards', '3.2' );
	// add_submenu_page('edit.php?post_type=review', 'Settings', 'Settings', 'manage_options', 'rera-reviews-settings', 'rera_reviews_options' );
}
add_action('admin_menu', 'rera_reviews_adminmenu');


/**
* Function scripts to Admin head.
*
* @access public
* @return void
*/
function rera_reviews_adminhead() {
	global $rera_reviews_url;

	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
	?>

	<style type="text/css">
	.postbox .handlediv:before {
		right:12px;
		font:400 20px/1 dashicons;
		speak:none;
		display:inline-block;
		top:0;
		position:relative;
		-webkit-font-smoothing:antialiased;
		-moz-osx-font-smoothing:grayscale;
		text-decoration:none!important;
		content:'\f142';
		padding:8px 10px;
	}
	.postbox.closed .handlediv:before {
		content: '\f140';
	}
	.wrap h2:before {
		content: "\f231";
		display: inline-block;
		-webkit-font-smoothing: antialiased;
		font: normal 29px/1 'dashicons';
		vertical-align: middle;
		margin-right: 0.3em;
	}
	</style>

	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		// close postboxes that should be closed
		$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		// postboxes setup
		postboxes.add_postbox_toggles('rera_reviews_options');
	});
	//]]>
	</script>

	<script type="text/javascript" language="JavaScript">
	//<![CDATA[
	function checkForm() {
		answer = true;
		if (siw && siw.selectingSomething)
		answer = false;
		return answer;
	}//
	//]]>
	</script>

	<?php
}

?>
