<?php
/**
 * Core operations class.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ads_Txt_Core {

	/**
	 * Save ads.txt content.
	 *
	 * @param string $content File content.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function save_ads_txt( $content ) {
		$content = $this->sanitize_file_content( $content );
		
		// Create backup if enabled
		$this->maybe_backup( 'ads' );

		update_option( 'ads_txt_manager_ads_txt', $content );
		update_option( 'ads_txt_manager_ads_txt_updated', current_time( 'mysql' ) );

		$write_result = $this->write_file_to_root( 'ads.txt', $content );
		return $write_result;
	}

	/**
	 * Save app-ads.txt content.
	 *
	 * @param string $content File content.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public function save_app_ads_txt( $content ) {
		$content = $this->sanitize_file_content( $content );

		// Create backup if enabled
		$this->maybe_backup( 'app-ads' );

		update_option( 'ads_txt_manager_app_ads_txt', $content );
		update_option( 'ads_txt_manager_app_ads_txt_updated', current_time( 'mysql' ) );

		$write_result = $this->write_file_to_root( 'app-ads.txt', $content );
		return $write_result;
	}

	/**
	 * Sanitize text input.
	 */
	public function sanitize_file_content( $content ) {
		// Clean lines, sanitize strings line-by-line.
		$lines = explode( "\n", $content );
		$sanitized_lines = array();
		foreach ( $lines as $line ) {
			$sanitized_lines[] = sanitize_text_field( trim( $line ) );
		}
		return implode( "\n", $sanitized_lines );
	}

	/**
	 * Automatically write DB content to physical files.
	 */
	public function write_physical_files() {
		$ads_txt = get_option( 'ads_txt_manager_ads_txt', '' );
		if ( ! empty( $ads_txt ) ) {
			$this->write_file_to_root( 'ads.txt', $ads_txt );
		}
		$app_ads_txt = get_option( 'ads_txt_manager_app_ads_txt', '' );
		if ( ! empty( $app_ads_txt ) ) {
			$this->write_file_to_root( 'app-ads.txt', $app_ads_txt );
		}
	}

	/**
	 * Write file content to root using WP Filesystem.
	 */
	private function write_file_to_root( $filename, $content ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) || ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			if ( ! WP_Filesystem() || ! is_object( $wp_filesystem ) ) {
				return new WP_Error( 'filesystem_failed', __( 'WordPress failed to initialize filesystem. Using fallback routing instead.', 'ads-txt-manager-app-ads-txt-update' ) );
			}
		}

		$file_path = ABSPATH . $filename;

		// Check if file is writeable or parent directory is writeable using WP_Filesystem methods
		if ( $wp_filesystem->exists( $file_path ) && ! $wp_filesystem->is_writable( $file_path ) ) {
			return new WP_Error( 'file_not_writable', __( 'File root path is not writable. Using fallback routing instead.', 'ads-txt-manager-app-ads-txt-update' ) );
		}

		if ( ! $wp_filesystem->exists( $file_path ) && ! $wp_filesystem->is_writable( ABSPATH ) ) {
			return new WP_Error( 'dir_not_writable', __( 'WordPress root directory is not writable. Using fallback routing instead.', 'ads-txt-manager-app-ads-txt-update' ) );
		}

		if ( ! $wp_filesystem->put_contents( $file_path, $content, FS_CHMOD_FILE ) ) {
			return new WP_Error( 'write_failed', __( 'WordPress failed to write the file directly. Using fallback routing instead.', 'ads-txt-manager-app-ads-txt-update' ) );
		}

		return true;
	}

	/**
	 * Check if file has write issues.
	 */
	public function check_write_permission( $filename ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) || ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			if ( ! WP_Filesystem() || ! is_object( $wp_filesystem ) ) {
				return false;
			}
		}

		$file_path = ABSPATH . $filename;
		if ( $wp_filesystem->exists( $file_path ) ) {
			return $wp_filesystem->is_writable( $file_path );
		}
		return $wp_filesystem->is_writable( ABSPATH );
	}

	/**
	 * Create backups.
	 */
	private function maybe_backup( $type ) {
		$settings = get_option( 'ads_txt_manager_settings', array() );
		$enable_backup = isset( $settings['enable_backup'] ) ? $settings['enable_backup'] : '1';

		if ( $enable_backup !== '1' ) {
			return;
		}

		$current_val = get_option( "ads_txt_manager_{$type}_txt", '' );
		if ( empty( $current_val ) ) {
			return;
		}

		$backups = get_option( "ads_txt_manager_{$type}_backups", array() );
		$new_backup = array(
			'timestamp' => current_time( 'timestamp' ),
			'content'   => $current_val,
		);

		// Prepend new backup
		array_unshift( $backups, $new_backup );

		// Keep only last 10 backups
		if ( count( $backups ) > 10 ) {
			$backups = array_slice( $backups, 0, 10 );
		}

		update_option( "ads_txt_manager_{$type}_backups", $backups );
	}

	/**
	 * Restore backup.
	 */
	public function restore_backup( $type, $timestamp ) {
		$backups = get_option( "ads_txt_manager_{$type}_backups", array() );
		foreach ( $backups as $backup ) {
			if ( $backup['timestamp'] == $timestamp ) {
				if ( $type === 'ads' ) {
					return $this->save_ads_txt( $backup['content'] );
				} else {
					return $this->save_app_ads_txt( $backup['content'] );
				}
			}
		}
		return new WP_Error( 'backup_not_found', __( 'Specified backup not found.', 'ads-txt-manager-app-ads-txt-update' ) );
	}

	/**
	 * Safe check if root file exists using WP Filesystem to avoid direct path blocks (e.g. open_basedir).
	 */
	public function file_exists_safe( $filename ) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) || ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			if ( ! WP_Filesystem() || ! is_object( $wp_filesystem ) ) {
				return false;
			}
		}

		return $wp_filesystem->exists( ABSPATH . $filename );
	}

	/**
	 * Get detailed statistics.
	 */
	public function get_stats() {
		$ads_content = get_option( 'ads_txt_manager_ads_txt', '' );
		$app_ads_content = get_option( 'ads_txt_manager_app_ads_txt', '' );

		return array(
			'ads' => array(
				'exists' => $this->file_exists_safe( 'ads.txt' ),
				'writable' => $this->check_write_permission( 'ads.txt' ),
				'entries' => $this->count_entries( $ads_content ),
				'updated' => get_option( 'ads_txt_manager_ads_txt_updated', 'Never' ),
				'content' => $ads_content,
				'url' => home_url( '/ads.txt' ),
			),
			'app_ads' => array(
				'exists' => $this->file_exists_safe( 'app-ads.txt' ),
				'writable' => $this->check_write_permission( 'app-ads.txt' ),
				'entries' => $this->count_entries( $app_ads_content ),
				'updated' => get_option( 'ads_txt_manager_app_ads_txt_updated', 'Never' ),
				'content' => $app_ads_content,
				'url' => home_url( '/app-ads.txt' ),
			),
		);
	}

	private function count_entries( $content ) {
		if ( empty( trim( $content ) ) ) {
			return 0;
		}
		$lines = explode( "\n", $content );
		$count = 0;
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( ! empty( $line ) && strpos( $line, '#' ) !== 0 ) {
				$count++;
			}
		}
		return $count;
	}
}

