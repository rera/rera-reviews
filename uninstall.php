<?php
/**
 * Fired when the plugin is uninstalled
 *
 * @package Rera_Reviews
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('rera_reviews_settings');
?>
