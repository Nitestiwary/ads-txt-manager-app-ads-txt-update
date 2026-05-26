<?php
/**
 * Purge database and physical configurations upon deletion.
 *
 * @package Ads_Txt_Manager
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clear database records
delete_option( 'ads_txt_manager_settings' );
delete_option( 'ads_txt_manager_ads_txt' );
delete_option( 'ads_txt_manager_app_ads_txt' );
delete_option( 'ads_txt_manager_ads_txt_updated' );
delete_option( 'ads_txt_manager_app_ads_txt_updated' );
delete_option( 'ads_txt_manager_ads_backups' );
delete_option( 'ads_txt_manager_app-ads_backups' );

// Clean physical files if present
wp_delete_file( ABSPATH . 'ads.txt' );
wp_delete_file( ABSPATH . 'app-ads.txt' );

