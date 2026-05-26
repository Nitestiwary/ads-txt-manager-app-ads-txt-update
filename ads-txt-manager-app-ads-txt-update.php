<?php
/**
 * Developer: Monetiscope (https://monetiscope.com)
 *
 * Plugin Name: Ads.txt Manager - App-ads.txt Update
 * Plugin URI:  https://github.com/Nitestiwary/ads.txt
 * Description: A lightweight, modern, beginner-friendly WordPress plugin to easily create, manage, validate, and update ads.txt and app-ads.txt files directly from the dashboard.
 * Version:     1.0.0
 * Author:      Monetiscope
 * Author URI:  https://monetiscope.com
 * License:     GPLv2 or later
 * Text Domain: ads-txt-manager-app-ads-txt-update
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants.
define( 'ADS_TXT_MANAGER_VERSION', '1.0.0' );
define( 'ADS_TXT_MANAGER_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADS_TXT_MANAGER_URL', plugin_dir_url( __FILE__ ) );
define( 'ADS_TXT_MANAGER_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoload classes or include them.
 */
require_once ADS_TXT_MANAGER_DIR . 'includes/class-ads-txt-core.php';
require_once ADS_TXT_MANAGER_DIR . 'includes/class-ads-txt-validator.php';

if ( is_admin() ) {
	require_once ADS_TXT_MANAGER_DIR . 'admin/class-ads-txt-admin.php';
}

/**
 * Activation Hook
 */
function ads_txt_manager_activate() {
	// Initialize default settings.
	if ( false === get_option( 'ads_txt_manager_settings' ) ) {
		$default_settings = array(
			'enable_validation' => '1',
			'enable_backup'     => '1',
			'auto_validation'   => '1',
			'duplicate_warning' => '1',
		);
		update_option( 'ads_txt_manager_settings', $default_settings );
	}

	// Create rewrite rules fallback.
	ads_txt_manager_add_rewrite_rules();
	flush_rewrite_rules();

	// Initialize the files if possible.
	$core = new Ads_Txt_Core();
	$core->write_physical_files();
}
register_activation_hook( __FILE__, 'ads_txt_manager_activate' );

/**
 * Deactivation Hook
 */
function ads_txt_manager_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ads_txt_manager_deactivate' );

/**
 * Add rewrite rules for fallback dynamic delivery.
 */
function ads_txt_manager_add_rewrite_rules() {
	add_rewrite_rule( '^ads\.txt$', 'index.php?ads_txt_trigger=1', 'top' );
	add_rewrite_rule( '^app-ads\.txt$', 'index.php?ads_txt_trigger=2', 'top' );
}
add_action( 'init', 'ads_txt_manager_add_rewrite_rules' );

/**
 * Register query vars for trigger.
 */
function ads_txt_manager_query_vars( $vars ) {
	$vars[] = 'ads_txt_trigger';
	return $vars;
}
add_filter( 'query_vars', 'ads_txt_manager_query_vars' );

/**
 * Catch the rewrite fallback trigger and output plain text.
 */
function ads_txt_manager_parse_request( $wp ) {
	if ( ! empty( $wp->matched_rule ) && ( strpos( $wp->matched_rule, '^ads\.txt$' ) !== false || strpos( $wp->matched_rule, '^app-ads\.txt$' ) !== false ) ) {
		$trigger = get_query_var( 'ads_txt_trigger' );
		if ( ! $trigger ) {
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			if ( strpos( $request_uri, 'app-ads.txt' ) !== false ) {
				$trigger = 2;
			} elseif ( strpos( $request_uri, 'ads.txt' ) !== false ) {
				$trigger = 1;
			}
		}

		if ( $trigger == 1 ) {
			header( 'Content-Type: text/plain; charset=utf-8' );
			header( 'X-Robots-Tag: index, follow' );
			$content = get_option( 'ads_txt_manager_ads_txt', '' );
			echo esc_html( $content );
			exit;
		} elseif ( $trigger == 2 ) {
			header( 'Content-Type: text/plain; charset=utf-8' );
			header( 'X-Robots-Tag: index, follow' );
			$content = get_option( 'ads_txt_manager_app_ads_txt', '' );
			echo esc_html( $content );
			exit;
		}
	}
}
add_action( 'parse_request', 'ads_txt_manager_parse_request' );

/**
 * Instantiate Main core processes.
 */
function ads_txt_manager_run() {
	$core = new Ads_Txt_Core();
	if ( is_admin() ) {
		$admin = new Ads_Txt_Admin( $core );
		$admin->init();
	}
}
add_action( 'plugins_loaded', 'ads_txt_manager_run' );

