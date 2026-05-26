<?php
/**
 * Admin Panel Manager class.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ads_Txt_Admin {

	/**
	 * Core instance.
	 */
	private $core;

	/**
	 * Constructor.
	 */
	public function __construct( $core ) {
		$this->core = $core;
	}

	/**
	 * Init hooks.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
	}

	/**
	 * Add menu items.
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Ads.txt Manager - App-ads.txt Update', 'ads-txt-manager-app-ads-txt-update' ),
			__( 'Ads.txt Manager', 'ads-txt-manager-app-ads-txt-update' ),
			'manage_options',
			'ads-txt-manager-app-ads-txt-update',
			array( $this, 'render_dashboard' ),
			'dashicons-media-text',
			85
		);
	}

	/**
	 * Enqueue stylesheet and JavaScript.
	 */
	public function enqueue_assets( $hook ) {
		if ( 'toplevel_page_ads-txt-manager-app-ads-txt-update' !== $hook && 'toplevel_page_ads-txt-manager-app-ads-txt-update' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'ads-txt-manager-app-ads-txt-update-admin-css',
			ADS_TXT_MANAGER_URL . 'assets/css/admin-style.css',
			array(),
			ADS_TXT_MANAGER_VERSION
		);

		wp_enqueue_script(
			'ads-txt-manager-app-ads-txt-update-admin-js',
			ADS_TXT_MANAGER_URL . 'assets/js/admin-script.js',
			array( 'jquery' ),
			ADS_TXT_MANAGER_VERSION,
			true
		);

		// Localize parameters for Javascript (dynamic settings & labels)
		wp_localize_script(
			'ads-txt-manager-app-ads-txt-update-admin-js',
			'adsTxtManager',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ads_txt_manager_nonce' ),
				'i18n'     => array(
					'saving'        => __( 'Saving...', 'ads-txt-manager-app-ads-txt-update' ),
					'saved'         => __( 'Saved Successfully!', 'ads-txt-manager-app-ads-txt-update' ),
					'save_failed'   => __( 'Failed to save settings.', 'ads-txt-manager-app-ads-txt-update' ),
					'valid'         => __( 'Syntax is perfectly valid!', 'ads-txt-manager-app-ads-txt-update' ),
					'duplicate'     => __( 'Duplicate entry found.', 'ads-txt-manager-app-ads-txt-update' ),
					'invalid_domain'=> __( 'Invalid domain detected.', 'ads-txt-manager-app-ads-txt-update' ),
				)
			)
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting(
			'ads_txt_manager_settings_group',
			'ads_txt_manager_settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Sanitize options settings.
	 */
	public function sanitize_settings( $input ) {
		$output = array();
		if ( is_array( $input ) ) {
			$output['enable_validation'] = isset( $input['enable_validation'] ) ? '1' : '0';
			$output['enable_backup']     = isset( $input['enable_backup'] ) ? '1' : '0';
			$output['auto_validation']   = isset( $input['auto_validation'] ) ? '1' : '0';
			$output['duplicate_warning'] = isset( $input['duplicate_warning'] ) ? '1' : '0';
		}
		return $output;
	}

	/**
	 * Actions handler for POST requests.
	 */
	public function handle_actions() {
		if ( ! isset( $_POST['ads_txt_action'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'ads-txt-manager-app-ads-txt-update' ) );
		}

		// Verify nonce securely
		if ( ! isset( $_POST['ads_txt_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ads_txt_nonce'] ) ), 'ads_txt_manager_action' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'ads-txt-manager-app-ads-txt-update' ) );
		}

		$action = sanitize_key( wp_unslash( $_POST['ads_txt_action'] ) );

		switch ( $action ) {
			case 'save_ads':
				$content = isset( $_POST['ads_txt_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ads_txt_content'] ) ) : '';
				$res = $this->core->save_ads_txt( $content );
				if ( is_wp_error( $res ) ) {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=ads-txt&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=ads-txt&success=1' ) );
				}
				exit;

			case 'save_app_ads':
				$content = isset( $_POST['app_ads_txt_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['app_ads_txt_content'] ) ) : '';
				$res = $this->core->save_app_ads_txt( $content );
				if ( is_wp_error( $res ) ) {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=app-ads-txt&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=app-ads-txt&success=1' ) );
				}
				exit;

			case 'restore_backup':
				$type = isset( $_POST['backup_type'] ) ? sanitize_key( wp_unslash( $_POST['backup_type'] ) ) : '';
				$timestamp = isset( $_POST['backup_timestamp'] ) ? sanitize_text_field( wp_unslash( $_POST['backup_timestamp'] ) ) : '';
				$res = $this->core->restore_backup( $type, $timestamp );
				if ( is_wp_error( $res ) ) {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=backup&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=backup&success=restore' ) );
				}
				exit;

			case 'import_settings':
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$import_file = isset( $_FILES['import_file'] ) ? wp_unslash( $_FILES['import_file'] ) : array();
				if ( ! empty( $import_file['tmp_name'] ) ) {
					$tmp_path = sanitize_text_field( $import_file['tmp_name'] );
					global $wp_filesystem;
					if ( empty( $wp_filesystem ) || ! is_object( $wp_filesystem ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
						if ( ! WP_Filesystem() || ! is_object( $wp_filesystem ) ) {
							wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=settings&error=' . urlencode( __( 'WordPress failed to initialize filesystem.', 'ads-txt-manager-app-ads-txt-update' ) ) ) );
							exit;
						}
					}
					$import_data = $wp_filesystem->get_contents( $tmp_path );
					$decoded = json_decode( $import_data, true );
					if ( is_array( $decoded ) ) {
						if ( isset( $decoded['ads_txt'] ) ) {
							$this->core->save_ads_txt( $decoded['ads_txt'] );
						}
						if ( isset( $decoded['app_ads_txt'] ) ) {
							$this->core->save_app_ads_txt( $decoded['app_ads_txt'] );
						}
						if ( isset( $decoded['settings'] ) ) {
							update_option( 'ads_txt_manager_settings', $decoded['settings'] );
						}
						wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=settings&success=import' ) );
					} else {
						wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=settings&error=' . urlencode( __( 'Invalid file format.', 'ads-txt-manager-app-ads-txt-update' ) ) ) );
					}
				}
				exit;

			case 'export_settings':
				$export_data = array(
					'ads_txt'     => get_option( 'ads_txt_manager_ads_txt', '' ),
					'app_ads_txt' => get_option( 'ads_txt_manager_app_ads_txt', '' ),
					'settings'    => get_option( 'ads_txt_manager_settings', array() ),
				);
				header( 'Content-Disposition: attachment; filename="ads-txt-manager-app-ads-txt-update-backup-' . current_time( 'Y-m-d' ) . '.json"' );
				header( 'Content-Type: application/json; charset=utf-8' );
				echo wp_json_encode( $export_data );
				exit;

			case 'save_settings':
				$settings = array(
					'enable_validation' => isset( $_POST['enable_validation'] ) ? '1' : '0',
					'enable_backup'     => isset( $_POST['enable_backup'] ) ? '1' : '0',
					'auto_validation'   => isset( $_POST['auto_validation'] ) ? '1' : '0',
					'duplicate_warning' => isset( $_POST['duplicate_warning'] ) ? '1' : '0',
				);
				update_option( 'ads_txt_manager_settings', $settings );
				wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=settings&success=settings' ) );
				exit;

			case 'reset_settings':
				delete_option( 'ads_txt_manager_settings' );
				delete_option( 'ads_txt_manager_ads_txt' );
				delete_option( 'ads_txt_manager_app_ads_txt' );
				delete_option( 'ads_txt_manager_ads_backups' );
				delete_option( 'ads_txt_manager_app-ads_backups' );
				// Clean root files securely using WordPress APIs
				wp_delete_file( ABSPATH . 'ads.txt' );
				wp_delete_file( ABSPATH . 'app-ads.txt' );

				$default_settings = array(
					'enable_validation' => '1',
					'enable_backup'     => '1',
					'auto_validation'   => '1',
					'duplicate_warning' => '1',
				);
				update_option( 'ads_txt_manager_settings', $default_settings );

				wp_safe_redirect( admin_url( 'admin.php?page=ads-txt-manager-app-ads-txt-update&tab=settings&success=reset' ) );
				exit;
		}
	}

	/**
	 * Main wrapper rendering the dashboard admin screen.
	 */
	public function render_dashboard() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'dashboard';
		$stats = $this->core->get_stats();

		// Branding Assets support compliant with WordPress guidelines with real-time timestamp cache buster
		$logo_url = ADS_TXT_MANAGER_URL . 'assets/images/ads-txt-logo.png?v=' . current_time( 'timestamp' );
		$banner_web = ADS_TXT_MANAGER_URL . 'assets/images/monetiscope-web-banner.jpg?v=' . current_time( 'timestamp' );
		$banner_app = ADS_TXT_MANAGER_URL . 'assets/images/monetiscope-app-banner.jpg?v=' . current_time( 'timestamp' );

		require_once ADS_TXT_MANAGER_DIR . 'admin/partials/dashboard.php';
	}
}

