<?php
/**
 * Validation diagnostics panel view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ads_txt_manager_ads_content = get_option( 'ads_txt_manager_ads_txt', '' );
$ads_txt_manager_app_ads_content = get_option( 'ads_txt_manager_app_ads_txt', '' );

$ads_txt_manager_ads_validation = Ads_Txt_Validator::validate_content( $ads_txt_manager_ads_content );
$ads_txt_manager_app_ads_validation = Ads_Txt_Validator::validate_content( $ads_txt_manager_app_ads_content );

$ads_txt_manager_ads_errors = isset( $ads_txt_manager_ads_validation['errors'] ) && is_array( $ads_txt_manager_ads_validation['errors'] ) ? $ads_txt_manager_ads_validation['errors'] : array();
$ads_txt_manager_ads_warnings = isset( $ads_txt_manager_ads_validation['warnings'] ) && is_array( $ads_txt_manager_ads_validation['warnings'] ) ? $ads_txt_manager_ads_validation['warnings'] : array();

$ads_txt_manager_app_errors = isset( $ads_txt_manager_app_ads_validation['errors'] ) && is_array( $ads_txt_manager_app_ads_validation['errors'] ) ? $ads_txt_manager_app_ads_validation['errors'] : array();
$ads_txt_manager_app_warnings = isset( $ads_txt_manager_app_ads_validation['warnings'] ) && is_array( $ads_txt_manager_app_ads_validation['warnings'] ) ? $ads_txt_manager_app_ads_validation['warnings'] : array();
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Detailed Validation Diagnostics', 'monetiscope-ads-txt-inserter' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Review line-by-line issues, critical syntax formatting errors, and duplicate warnings for your active configuration.', 'monetiscope-ads-txt-inserter' ); ?>
	</p>
</div>

<div class="atm-validation-grid">
	<!-- Web Ads.txt Validation -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Web Ads.txt Verification', 'monetiscope-ads-txt-inserter' ); ?></h3>
			<span class="atm-status-badge <?php echo $ads_txt_manager_ads_validation['valid'] && empty( $ads_txt_manager_ads_warnings ) ? 'status-active' : 'status-inactive'; ?>">
				<?php echo $ads_txt_manager_ads_validation['valid'] && empty( $ads_txt_manager_ads_warnings ) ? esc_html__( '100% Valid', 'monetiscope-ads-txt-inserter' ) : esc_html__( 'Action Needed', 'monetiscope-ads-txt-inserter' ); ?>
			</span>
		</div>
		<div class="atm-card-body">
			<ul class="atm-bullets">
				<li><strong><?php esc_html_e( 'Total Evaluated Lines:', 'monetiscope-ads-txt-inserter' ); ?></strong> <?php echo esc_html( $ads_txt_manager_ads_validation['total'] ); ?></li>
				<li><strong><?php esc_html_e( 'Syntax Errors:', 'monetiscope-ads-txt-inserter' ); ?></strong> <span class="<?php echo ! empty( $ads_txt_manager_ads_errors ) ? 'text-danger font-bold' : ''; ?>"><?php echo count( $ads_txt_manager_ads_errors ); ?></span></li>
				<li><strong><?php esc_html_e( 'Duplicate Lines:', 'monetiscope-ads-txt-inserter' ); ?></strong> <span class="<?php echo $ads_txt_manager_ads_validation['duplicates'] > 0 ? 'text-warning font-bold' : ''; ?>"><?php echo esc_html( $ads_txt_manager_ads_validation['duplicates'] ); ?></span></li>
			</ul>

			<?php if ( ! empty( $ads_txt_manager_ads_errors ) || ! empty( $ads_txt_manager_ads_warnings ) ) : ?>
				<div class="atm-diagnostic-log">
					<h4><?php esc_html_e( 'Diagnostic Log Messages:', 'monetiscope-ads-txt-inserter' ); ?></h4>
					<div class="atm-log-entries">
						<?php foreach ( $ads_txt_manager_ads_errors as $ads_txt_manager_error ) : ?>
							<div class="atm-log-item log-error">
								<span class="dashicons dashicons-dismiss"></span>
								<span><strong>Line <?php echo esc_html( $ads_txt_manager_error['line'] ); ?>:</strong> <?php echo esc_html( $ads_txt_manager_error['message'] ); ?></span>
							</div>
						<?php endforeach; ?>

						<?php foreach ( $ads_txt_manager_ads_warnings as $ads_txt_manager_warning ) : ?>
							<div class="atm-log-item log-warning">
								<span class="dashicons dashicons-warning"></span>
								<span><strong>Line <?php echo esc_html( $ads_txt_manager_warning['line'] ); ?>:</strong> <?php echo esc_html( $ads_txt_manager_warning['message'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="atm-alert alert-success">
					<span class="dashicons dashicons-yes-alt"></span>
					<p><?php esc_html_e( 'No issues found! Your ads.txt format conforms perfectly to IAB specifications.', 'monetiscope-ads-txt-inserter' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- App-ads.txt Validation -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'App-ads.txt Verification', 'monetiscope-ads-txt-inserter' ); ?></h3>
			<span class="atm-status-badge <?php echo $ads_txt_manager_app_ads_validation['valid'] && empty( $ads_txt_manager_app_warnings ) ? 'status-active' : 'status-inactive'; ?>">
				<?php echo $ads_txt_manager_app_ads_validation['valid'] && empty( $ads_txt_manager_app_warnings ) ? esc_html__( '100% Valid', 'monetiscope-ads-txt-inserter' ) : esc_html__( 'Action Needed', 'monetiscope-ads-txt-inserter' ); ?>
			</span>
		</div>
		<div class="atm-card-body">
			<ul class="atm-bullets">
				<li><strong><?php esc_html_e( 'Total Evaluated Lines:', 'monetiscope-ads-txt-inserter' ); ?></strong> <?php echo esc_html( $ads_txt_manager_app_ads_validation['total'] ); ?></li>
				<li><strong><?php esc_html_e( 'Syntax Errors:', 'monetiscope-ads-txt-inserter' ); ?></strong> <span class="<?php echo ! empty( $ads_txt_manager_app_errors ) ? 'text-danger font-bold' : ''; ?>"><?php echo count( $ads_txt_manager_app_errors ); ?></span></li>
				<li><strong><?php esc_html_e( 'Duplicate Lines:', 'monetiscope-ads-txt-inserter' ); ?></strong> <span class="<?php echo $ads_txt_manager_app_ads_validation['duplicates'] > 0 ? 'text-warning font-bold' : ''; ?>"><?php echo esc_html( $ads_txt_manager_app_ads_validation['duplicates'] ); ?></span></li>
			</ul>

			<?php if ( ! empty( $ads_txt_manager_app_errors ) || ! empty( $ads_txt_manager_app_warnings ) ) : ?>
				<div class="atm-diagnostic-log">
					<h4><?php esc_html_e( 'Diagnostic Log Messages:', 'monetiscope-ads-txt-inserter' ); ?></h4>
					<div class="atm-log-entries">
						<?php foreach ( $ads_txt_manager_app_errors as $ads_txt_manager_error ) : ?>
							<div class="atm-log-item log-error">
								<span class="dashicons dashicons-dismiss"></span>
								<span><strong>Line <?php echo esc_html( $ads_txt_manager_error['line'] ); ?>:</strong> <?php echo esc_html( $ads_txt_manager_error['message'] ); ?></span>
							</div>
						<?php endforeach; ?>

						<?php foreach ( $ads_txt_manager_app_warnings as $ads_txt_manager_warning ) : ?>
							<div class="atm-log-item log-warning">
								<span class="dashicons dashicons-warning"></span>
								<span><strong>Line <?php echo esc_html( $ads_txt_manager_warning['line'] ); ?>:</strong> <?php echo esc_html( $ads_txt_manager_warning['message'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="atm-alert alert-success">
					<span class="dashicons dashicons-yes-alt"></span>
					<p><?php esc_html_e( 'No issues found! Your app-ads.txt format conforms perfectly to IAB specifications.', 'monetiscope-ads-txt-inserter' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

