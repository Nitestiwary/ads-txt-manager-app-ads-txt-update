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
			__( 'Ads.txt Manager - App-ads.txt Update', 'ads-txt-manager' ),
			__( 'Ads.txt Manager', 'ads-txt-manager' ),
			'manage_options',
			'ads-txt-manager',
			array( $this, 'render_dashboard' ),
			'dashicons-media-text',
			85
		);
	}

	/**
	 * Enqueue stylesheet and JavaScript.
	 */
	public function enqueue_assets( $hook ) {
		if ( 'toplevel_page_ads-txt-manager' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'ads-txt-manager-admin-css',
			ADS_TXT_MANAGER_URL . 'assets/css/admin-style.css',
			array(),
			ADS_TXT_MANAGER_VERSION
		);

		wp_enqueue_script(
			'ads-txt-manager-admin-js',
			ADS_TXT_MANAGER_URL . 'assets/js/admin-script.js',
			array( 'jquery' ),
			ADS_TXT_MANAGER_VERSION,
			true
		);

		// Localize parameters for Javascript (dynamic settings & labels)
		wp_localize_script(
			'ads-txt-manager-admin-js',
			'adsTxtManager',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ads_txt_manager_nonce' ),
				'i18n'     => array(
					'saving'        => __( 'Saving...', 'ads-txt-manager' ),
					'saved'         => __( 'Saved Successfully!', 'ads-txt-manager' ),
					'save_failed'   => __( 'Failed to save settings.', 'ads-txt-manager' ),
					'valid'         => __( 'Syntax is perfectly valid!', 'ads-txt-manager' ),
					'duplicate'     => __( 'Duplicate entry found.', 'ads-txt-manager' ),
					'invalid_domain'=> __( 'Invalid domain detected.', 'ads-txt-manager' ),
				)
			)
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'ads_txt_manager_settings_group', 'ads_txt_manager_settings' );
	}

	/**
	 * Actions handler for POST requests.
	 */
	public function handle_actions() {
		if ( ! isset( $_POST['ads_txt_action'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'ads-txt-manager' ) );
		}

		// Verify nonce
		if ( ! isset( $_POST['ads_txt_nonce'] ) || ! wp_verify_nonce( $_POST['ads_txt_nonce'], 'ads_txt_manager_action' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'ads-txt-manager' ) );
		}

		$action = sanitize_text_field( $_POST['ads_txt_action'] );

		switch ( $action ) {
			case 'save_ads':
				$content = isset( $_POST['ads_txt_content'] ) ? $_POST['ads_txt_content'] : '';
				$res = $this->core->save_ads_txt( $content );
				if ( is_wp_error( $res ) ) {
					wp_redirect( admin_url( 'admin-page=ads-txt-manager&tab=ads-txt&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=ads-txt&success=1' ) );
				}
				exit;

			case 'save_app_ads':
				$content = isset( $_POST['app_ads_txt_content'] ) ? $_POST['app_ads_txt_content'] : '';
				$res = $this->core->save_app_ads_txt( $content );
				if ( is_wp_error( $res ) ) {
					wp_redirect( admin_url( 'admin-page=ads-txt-manager&tab=app-ads-txt&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=app-ads-txt&success=1' ) );
				}
				exit;

			case 'restore_backup':
				$type = sanitize_text_field( $_POST['backup_type'] );
				$timestamp = sanitize_text_field( $_POST['backup_timestamp'] );
				$res = $this->core->restore_backup( $type, $timestamp );
				if ( is_wp_error( $res ) ) {
					wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=backup&error=' . urlencode( $res->get_error_message() ) ) );
				} else {
					wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=backup&success=restore' ) );
				}
				exit;

			case 'import_settings':
				if ( ! empty( $_FILES['import_file']['tmp_name'] ) ) {
					$import_data = file_get_contents( $_FILES['import_file']['tmp_name'] );
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
						wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=settings&success=import' ) );
					} else {
						wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=settings&error=' . urlencode( __( 'Invalid file format.', 'ads-txt-manager' ) ) ) );
					}
				}
				exit;

			case 'export_settings':
				$export_data = array(
					'ads_txt'     => get_option( 'ads_txt_manager_ads_txt', '' ),
					'app_ads_txt' => get_option( 'ads_txt_manager_app_ads_txt', '' ),
					'settings'    => get_option( 'ads_txt_manager_settings', array() ),
				);
				header( 'Content-Disposition: attachment; filename="ads-txt-manager-backup-' . current_time( 'Y-m-d' ) . '.json"' );
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
				wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=settings&success=settings' ) );
				exit;

			case 'reset_settings':
				delete_option( 'ads_txt_manager_settings' );
				delete_option( 'ads_txt_manager_ads_txt' );
				delete_option( 'ads_txt_manager_app_ads_txt' );
				delete_option( 'ads_txt_manager_ads_backups' );
				delete_option( 'ads_txt_manager_app-ads_backups' );
				// Clean root files
				@unlink( ABSPATH . 'ads.txt' );
				@unlink( ABSPATH . 'app-ads.txt' );

				$default_settings = array(
					'enable_validation' => '1',
					'enable_backup'     => '1',
					'auto_validation'   => '1',
					'duplicate_warning' => '1',
				);
				update_option( 'ads_txt_manager_settings', $default_settings );

				wp_redirect( admin_url( 'admin.php?page=ads-txt-manager&tab=settings&success=reset' ) );
				exit;
		}
	}

	/**
	 * Main wrapper rendering the dashboard admin screen.
	 */
	public function render_dashboard() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard';
		$stats = $this->core->get_stats();

		// Branding Assets support compliant with WordPress guidelines with cache buster
		$logo_url = ADS_TXT_MANAGER_URL . 'assets/images/ads-txt-logo.png?v=' . ADS_TXT_MANAGER_VERSION;
		$banner_web = ADS_TXT_MANAGER_URL . 'assets/images/monetiscope-web-banner.png?v=' . ADS_TXT_MANAGER_VERSION;
		$banner_app = ADS_TXT_MANAGER_URL . 'assets/images/monetiscope-app-banner.png?v=' . ADS_TXT_MANAGER_VERSION;

		require_once ADS_TXT_MANAGER_DIR . 'admin/partials/dashboard.php';
	}
}
