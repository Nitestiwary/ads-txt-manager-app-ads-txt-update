<?php
/**
 * Settings configuration view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ads_txt_manager_settings = get_option( 'ads_txt_manager_settings', array() );
$ads_txt_manager_enable_validation = isset( $ads_txt_manager_settings['enable_validation'] ) ? $ads_txt_manager_settings['enable_validation'] : '1';
$ads_txt_manager_enable_backup = isset( $ads_txt_manager_settings['enable_backup'] ) ? $ads_txt_manager_settings['enable_backup'] : '1';
$ads_txt_manager_auto_validation = isset( $ads_txt_manager_settings['auto_validation'] ) ? $ads_txt_manager_settings['auto_validation'] : '1';
$ads_txt_manager_duplicate_warning = isset( $ads_txt_manager_settings['duplicate_warning'] ) ? $ads_txt_manager_settings['duplicate_warning'] : '1';
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Plugin Settings & Tools', 'ads-txt-manager-app-ads-txt-update' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Configure validation rules, backup intervals, or perform import/export operations for migrating your ads.txt setups.', 'ads-txt-manager-app-ads-txt-update' ); ?>
	</p>
</div>

<div class="atm-settings-grid">
	<!-- Configurations Form -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Validation & Backup Toggles', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
				<input type="hidden" name="action" value="ads_txt_action" />
				<input type="hidden" name="ads_txt_action" value="save_settings" />

				<table class="form-table atm-settings-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Syntax Validation', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						<td>
							<label class="atm-switch-label">
								<input type="checkbox" name="enable_validation" value="1" <?php checked( $ads_txt_manager_enable_validation, '1' ); ?> />
								<span class="atm-switch-slider"></span>
							</label>
							<span class="description"><?php esc_html_e( 'Enable/disable detailed format verification.', 'ads-txt-manager-app-ads-txt-update' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Automatic Backups', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						<td>
							<label class="atm-switch-label">
								<input type="checkbox" name="enable_backup" value="1" <?php checked( $ads_txt_manager_enable_backup, '1' ); ?> />
								<span class="atm-switch-slider"></span>
							</label>
							<span class="description"><?php esc_html_e( 'Save a backup history entry automatically before saving changes.', 'ads-txt-manager-app-ads-txt-update' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Real-time Live Check', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						<td>
							<label class="atm-switch-label">
								<input type="checkbox" name="auto_validation" value="1" <?php checked( $ads_txt_manager_auto_validation, '1' ); ?> />
								<span class="atm-switch-slider"></span>
							</label>
							<span class="description"><?php esc_html_e( 'Run real-time checks in the editor text area while typing.', 'ads-txt-manager-app-ads-txt-update' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Duplicate Entry Warnings', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						<td>
							<label class="atm-switch-label">
								<input type="checkbox" name="duplicate_warning" value="1" <?php checked( $ads_txt_manager_duplicate_warning, '1' ); ?> />
								<span class="atm-switch-slider"></span>
							</label>
							<span class="description"><?php esc_html_e( 'Highlight warnings if identical entries exist on multiple lines.', 'ads-txt-manager-app-ads-txt-update' ); ?></span>
						</td>
					</tr>
				</table>

				<div class="atm-form-actions">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Settings', 'ads-txt-manager-app-ads-txt-update' ); ?></button>
				</div>
			</form>
		</div>
	</div>

	<!-- Migration / Import & Export -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Import / Export & Backup Tools', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<!-- Export -->
			<div class="atm-tool-block">
				<h4><?php esc_html_e( 'Export Configuration', 'ads-txt-manager-app-ads-txt-update' ); ?></h4>
				<p class="description"><?php esc_html_e( 'Download a JSON file containing all settings, ads.txt, and app-ads.txt content.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
					<input type="hidden" name="action" value="ads_txt_action" />
					<input type="hidden" name="ads_txt_action" value="export_settings" />
					<button type="submit" class="button button-secondary"><?php esc_html_e( 'Download Export JSON', 'ads-txt-manager-app-ads-txt-update' ); ?></button>
				</form>
			</div>

			<hr />

			<!-- Import -->
			<div class="atm-tool-block">
				<h4><?php esc_html_e( 'Import Configuration', 'ads-txt-manager-app-ads-txt-update' ); ?></h4>
				<p class="description"><?php esc_html_e( 'Upload a previously exported JSON backup file to overwrite current configurations.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
					<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
					<input type="hidden" name="action" value="ads_txt_action" />
					<input type="hidden" name="ads_txt_action" value="import_settings" />
					<input type="file" name="import_file" accept=".json" required class="margin-bottom-10" />
					<br />
					<button type="submit" class="button button-secondary" onclick="return confirm('<?php esc_attr_e( 'Are you absolutely sure? This will completely overwrite your existing ads.txt and app-ads.txt lines.', 'ads-txt-manager-app-ads-txt-update' ); ?>')">
						<?php esc_html_e( 'Upload and Import', 'ads-txt-manager-app-ads-txt-update' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Hard Reset Plugin -->
	<div class="atm-card atm-card-danger span-full">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Danger Zone - Hard Reset', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<p><?php esc_html_e( 'Warning: This action is permanent. Resetting will completely delete ads.txt/app-ads.txt physical root files, all database entries, validation statistics, and settings parameters, restoring the plugin state to initial activation.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
				<input type="hidden" name="action" value="ads_txt_action" />
				<input type="hidden" name="ads_txt_action" value="reset_settings" />
				<button type="submit" class="button button-link-delete button-hero" onclick="return confirm('<?php esc_attr_e( 'Are you absolutely 100% sure you want to perform a full factory reset? This cannot be undone and will delete files physically on your server.', 'ads-txt-manager-app-ads-txt-update' ); ?>')">
					<span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Perform Full Factory Reset', 'ads-txt-manager-app-ads-txt-update' ); ?>
				</button>
			</form>
		</div>
	</div>
</div>

