<?php
/**
 * Main dashboard layout.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap ads-txt-manager-wrap">
	<!-- Hidden h1 for WordPress notice positioning - WP JS moves notices after first h1 -->
	<h1 class="screen-reader-text"><?php esc_html_e( 'ads.txt Inserter', 'monetiscope-ads-txt-inserter' ); ?></h1>

	<!-- Plugin Header Card -->
	<div class="atm-header-card">
		<div class="atm-header-left">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="ads.txt Inserter" class="atm-plugin-logo" />
			<div class="atm-header-title-wrapper">
				<h2 class="atm-main-title" style="color:#ffffff !important;font-size:26px;font-weight:700;margin:0 0 4px 0;padding:0;line-height:1.2;"><?php esc_html_e( 'ads.txt Inserter', 'monetiscope-ads-txt-inserter' ); ?></h2>
				<p class="atm-subtitle"><?php esc_html_e( 'Manage and update ads.txt & app-ads.txt files with real-time validation by Monetiscope', 'monetiscope-ads-txt-inserter' ); ?></p>
			</div>
		</div>
		<div class="atm-header-right">
			<span class="atm-developer-badge">
				<?php esc_html_e( 'Developed by: ', 'monetiscope-ads-txt-inserter' ); ?>
				<a href="https://monetiscope.com" target="_blank" rel="noopener noreferrer">Monetiscope</a>
			</span>
		</div>
	</div>

	<!-- Alert Messages -->
	<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$ads_txt_manager_success_type = isset( $_GET['success'] ) ? sanitize_key( wp_unslash( $_GET['success'] ) ) : '';
	if ( ! empty( $ads_txt_manager_success_type ) ) :
	?>
		<div class="notice notice-success is-dismissible atm-notice">
			<p>
				<?php
				if ( 'restore' === $ads_txt_manager_success_type ) {
					esc_html_e( 'Backup restored successfully.', 'monetiscope-ads-txt-inserter' );
				} elseif ( 'import' === $ads_txt_manager_success_type ) {
					esc_html_e( 'Settings and files imported successfully.', 'monetiscope-ads-txt-inserter' );
				} elseif ( 'settings' === $ads_txt_manager_success_type ) {
					esc_html_e( 'Settings updated successfully.', 'monetiscope-ads-txt-inserter' );
				} elseif ( 'reset' === $ads_txt_manager_success_type ) {
					esc_html_e( 'Plugin reset successfully. All files and options cleared.', 'monetiscope-ads-txt-inserter' );
				} else {
					esc_html_e( 'Changes saved successfully.', 'monetiscope-ads-txt-inserter' );
				}
				?>
			</p>
		</div>
	<?php endif; ?>

	<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$ads_txt_manager_error_message = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( $_GET['error'] ) ) : '';
	if ( ! empty( $ads_txt_manager_error_message ) ) :
	?>
		<div class="notice notice-error is-dismissible atm-notice">
			<p><?php echo esc_html( urldecode( $ads_txt_manager_error_message ) ); ?></p>
		</div>
	<?php endif; ?>

	<!-- Navigation Tabs -->
	<nav class="nav-tab-wrapper atm-nav-tab-wrapper">
		<a href="?page=monetiscope-ads-txt-inserter&tab=dashboard" class="nav-tab <?php echo 'dashboard' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Dashboard', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=ads-txt" class="nav-tab <?php echo 'ads-txt' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-admin-site"></span> <?php esc_html_e( 'Ads.txt', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=app-ads-txt" class="nav-tab <?php echo 'app-ads-txt' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-phone"></span> <?php esc_html_e( 'App-ads.txt', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=validation" class="nav-tab <?php echo 'validation' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Validation', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=backup" class="nav-tab <?php echo 'backup' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-backup"></span> <?php esc_html_e( 'Backups', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=settings" class="nav-tab <?php echo 'settings' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Settings', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
		<a href="?page=monetiscope-ads-txt-inserter&tab=help" class="nav-tab <?php echo 'help' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-editor-help"></span> <?php esc_html_e( 'Help', 'monetiscope-ads-txt-inserter' ); ?>
		</a>
	</nav>

	<div class="atm-content-area">
		<?php
		switch ( $active_tab ) {
			case 'dashboard':
				// Render Overview Dashboard
				?>
				<div class="atm-dashboard-grid">
					<!-- Ads.txt Status -->
					<div class="atm-card">
						<div class="atm-card-header">
							<h2><?php esc_html_e( 'Web Ads.txt Overview', 'monetiscope-ads-txt-inserter' ); ?></h2>
							<span class="atm-status-badge <?php echo $stats['ads']['exists'] ? 'status-active' : 'status-inactive'; ?>">
								<?php echo $stats['ads']['exists'] ? esc_html__( 'File Active', 'monetiscope-ads-txt-inserter' ) : esc_html__( 'Not Created', 'monetiscope-ads-txt-inserter' ); ?>
							</span>
						</div>
						<div class="atm-card-body">
							<table class="atm-stats-table">
								<tr>
									<th><?php esc_html_e( 'Entries Count:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td><strong><?php echo esc_html( $stats['ads']['entries'] ); ?></strong></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Physical File:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<?php if ( $stats['ads']['exists'] ) : ?>
											<span class="dashicons dashicons-yes-alt text-success"></span> <?php esc_html_e( 'Created in Root', 'monetiscope-ads-txt-inserter' ); ?>
										<?php else : ?>
											<span class="dashicons dashicons-warning text-warning"></span> <?php esc_html_e( 'Using Dynamic Fallback Route', 'monetiscope-ads-txt-inserter' ); ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Write Permissions:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<?php if ( $stats['ads']['writable'] ) : ?>
											<span class="dashicons dashicons-yes-alt text-success"></span> <?php esc_html_e( 'Writable', 'monetiscope-ads-txt-inserter' ); ?>
										<?php else : ?>
											<span class="dashicons dashicons-lock text-danger"></span> <?php esc_html_e( 'Root Unwritable (Fallback enabled)', 'monetiscope-ads-txt-inserter' ); ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Last Updated:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td><?php echo esc_html( $stats['ads']['updated'] ); ?></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Public URL:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<a href="<?php echo esc_url( $stats['ads']['url'] ); ?>" target="_blank" class="atm-button button-secondary">
											<span class="dashicons dashicons-external"></span> <?php esc_html_e( 'View Public File', 'monetiscope-ads-txt-inserter' ); ?>
										</a>
									</td>
								</tr>
							</table>
							<div class="atm-card-actions">
								<a href="?page=monetiscope-ads-txt-inserter&tab=ads-txt" class="atm-button button-primary"><?php esc_html_e( 'Manage Ads.txt', 'monetiscope-ads-txt-inserter' ); ?></a>
							</div>
						</div>
					</div>

					<!-- App-ads.txt Status -->
					<div class="atm-card">
						<div class="atm-card-header">
							<h2><?php esc_html_e( 'App app-ads.txt Overview', 'monetiscope-ads-txt-inserter' ); ?></h2>
							<span class="atm-status-badge <?php echo $stats['app_ads']['exists'] ? 'status-active' : 'status-inactive'; ?>">
								<?php echo $stats['app_ads']['exists'] ? esc_html__( 'File Active', 'monetiscope-ads-txt-inserter' ) : esc_html__( 'Not Created', 'monetiscope-ads-txt-inserter' ); ?>
							</span>
						</div>
						<div class="atm-card-body">
							<table class="atm-stats-table">
								<tr>
									<th><?php esc_html_e( 'Entries Count:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td><strong><?php echo esc_html( $stats['app_ads']['entries'] ); ?></strong></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Physical File:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<?php if ( $stats['app_ads']['exists'] ) : ?>
											<span class="dashicons dashicons-yes-alt text-success"></span> <?php esc_html_e( 'Created in Root', 'monetiscope-ads-txt-inserter' ); ?>
										<?php else : ?>
											<span class="dashicons dashicons-warning text-warning"></span> <?php esc_html_e( 'Using Dynamic Fallback Route', 'monetiscope-ads-txt-inserter' ); ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Write Permissions:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<?php if ( $stats['app_ads']['writable'] ) : ?>
											<span class="dashicons dashicons-yes-alt text-success"></span> <?php esc_html_e( 'Writable', 'monetiscope-ads-txt-inserter' ); ?>
										<?php else : ?>
											<span class="dashicons dashicons-lock text-danger"></span> <?php esc_html_e( 'Root Unwritable (Fallback enabled)', 'monetiscope-ads-txt-inserter' ); ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Last Updated:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td><?php echo esc_html( $stats['app_ads']['updated'] ); ?></td>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Public URL:', 'monetiscope-ads-txt-inserter' ); ?></th>
									<td>
										<a href="<?php echo esc_url( $stats['app_ads']['url'] ); ?>" target="_blank" class="atm-button button-secondary">
											<span class="dashicons dashicons-external"></span> <?php esc_html_e( 'View Public File', 'monetiscope-ads-txt-inserter' ); ?>
										</a>
									</td>
								</tr>
							</table>
							<div class="atm-card-actions">
								<a href="?page=monetiscope-ads-txt-inserter&tab=app-ads-txt" class="atm-button button-primary"><?php esc_html_e( 'Manage App-ads.txt', 'monetiscope-ads-txt-inserter' ); ?></a>
							</div>
						</div>
					</div>
				</div>

				<!-- Monetiscope Web Ad Exchange Integration Promo Card -->
				<div class="atm-promo-section-card">
					<div class="atm-promo-banner-container">
						<a href="https://monetiscope.com/contact/" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( $banner_web ); ?>" alt="Upgrade to Google Ad Exchange" class="atm-promo-banner-img" />
						</a>
					</div>
				</div>
				<?php
				break;

			case 'ads-txt':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/ads-txt.php';
				break;

			case 'app-ads-txt':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/app-ads-txt.php';
				break;

			case 'validation':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/validation.php';
				break;

			case 'backup':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/backup.php';
				break;

			case 'settings':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/settings.php';
				break;

			case 'help':
				require_once ADS_TXT_MANAGER_DIR . 'admin/partials/help.php';
				break;
		}
		?>
	</div>
</div>

