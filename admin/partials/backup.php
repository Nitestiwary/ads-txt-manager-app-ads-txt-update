<?php
/**
 * Backups management view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ads_txt_manager_ads_backups = get_option( 'ads_txt_manager_ads_backups', array() );
if ( ! is_array( $ads_txt_manager_ads_backups ) ) {
	$ads_txt_manager_ads_backups = array();
}

$ads_txt_manager_app_backups = get_option( 'ads_txt_manager_app-ads_backups', array() );
if ( ! is_array( $ads_txt_manager_app_backups ) ) {
	$ads_txt_manager_app_backups = array();
}
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-backup"></span> <?php esc_html_e( 'Auto Backup System', 'ads-txt-manager-app-ads-txt-update' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Roll back to a previous state of your ads.txt or app-ads.txt files. A backup is captured automatically every time you click save.', 'ads-txt-manager-app-ads-txt-update' ); ?>
	</p>
</div>

<div class="atm-backups-grid">
	<!-- Ads.txt Backups -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Web Ads.txt Backup History', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<?php if ( empty( $ads_txt_manager_ads_backups ) ) : ?>
				<p class="description"><?php esc_html_e( 'No backups created yet. Saving changes will automatically generate one here.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			<?php else : ?>
				<table class="atm-stats-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Date & Time', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
							<th><?php esc_html_e( 'Size', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
							<th><?php esc_html_e( 'Action', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $ads_txt_manager_ads_backups as $ads_txt_manager_backup ) :
							if ( ! is_array( $ads_txt_manager_backup ) || ! isset( $ads_txt_manager_backup['timestamp'] ) ) {
								continue;
							}
						?>
							<tr>
								<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ads_txt_manager_backup['timestamp'] ) ); ?></td>
								<td><?php echo esc_html( strlen( $ads_txt_manager_backup['content'] ) ); ?> bytes</td>
								<td>
									<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;">
										<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
										<input type="hidden" name="action" value="ads_txt_action" />
										<input type="hidden" name="ads_txt_action" value="restore_backup" />
										<input type="hidden" name="backup_type" value="ads" />
										<input type="hidden" name="backup_timestamp" value="<?php echo esc_attr( $ads_txt_manager_backup['timestamp'] ); ?>" />
										<button type="submit" class="button button-small button-secondary atm-button-inline" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to restore this backup? Your current ads.txt content will be backed up and replaced.', 'ads-txt-manager-app-ads-txt-update' ); ?>')">
											<?php esc_html_e( 'Restore', 'ads-txt-manager-app-ads-txt-update' ); ?>
										</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>

	<!-- App-ads.txt Backups -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'App-ads.txt Backup History', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<?php if ( empty( $ads_txt_manager_app_backups ) ) : ?>
				<p class="description"><?php esc_html_e( 'No backups created yet. Saving changes will automatically generate one here.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			<?php else : ?>
				<table class="atm-stats-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Date & Time', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
							<th><?php esc_html_e( 'Size', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
							<th><?php esc_html_e( 'Action', 'ads-txt-manager-app-ads-txt-update' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $ads_txt_manager_app_backups as $ads_txt_manager_backup ) :
							if ( ! is_array( $ads_txt_manager_backup ) || ! isset( $ads_txt_manager_backup['timestamp'] ) ) {
								continue;
							}
						?>
							<tr>
								<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ads_txt_manager_backup['timestamp'] ) ); ?></td>
								<td><?php echo esc_html( strlen( $ads_txt_manager_backup['content'] ) ); ?> bytes</td>
								<td>
									<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;">
										<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
										<input type="hidden" name="action" value="ads_txt_action" />
										<input type="hidden" name="ads_txt_action" value="restore_backup" />
										<input type="hidden" name="backup_type" value="app-ads" />
										<input type="hidden" name="backup_timestamp" value="<?php echo esc_attr( $ads_txt_manager_backup['timestamp'] ); ?>" />
										<button type="submit" class="button button-small button-secondary atm-button-inline" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to restore this backup? Your current app-ads.txt content will be backed up and replaced.', 'ads-txt-manager-app-ads-txt-update' ); ?>')">
											<?php esc_html_e( 'Restore', 'ads-txt-manager-app-ads-txt-update' ); ?>
										</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

