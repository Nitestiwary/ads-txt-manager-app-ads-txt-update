<?php
/**
 * Validation diagnostics panel view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$atm_ads_content = get_option( 'ads_txt_manager_ads_txt', '' );
$atm_app_ads_content = get_option( 'ads_txt_manager_app_ads_txt', '' );

$atm_ads_validation = Ads_Txt_Validator::validate_content( $atm_ads_content );
$atm_app_ads_validation = Ads_Txt_Validator::validate_content( $atm_app_ads_content );
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Detailed Validation Diagnostics', 'ads.txt-main' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Review line-by-line issues, critical syntax formatting errors, and duplicate warnings for your active configuration.', 'ads.txt-main' ); ?>
	</p>
</div>

<div class="atm-validation-grid">
	<!-- Web Ads.txt Validation -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Web Ads.txt Verification', 'ads.txt-main' ); ?></h3>
			<span class="atm-status-badge <?php echo $atm_ads_validation['valid'] && empty( $atm_ads_validation['warnings'] ) ? 'status-active' : 'status-inactive'; ?>">
				<?php echo $atm_ads_validation['valid'] && empty( $atm_ads_validation['warnings'] ) ? esc_html__( '100% Valid', 'ads.txt-main' ) : esc_html__( 'Action Needed', 'ads.txt-main' ); ?>
			</span>
		</div>
		<div class="atm-card-body">
			<ul class="atm-bullets">
				<li><strong><?php esc_html_e( 'Total Evaluated Lines:', 'ads.txt-main' ); ?></strong> <?php echo esc_html( $atm_ads_validation['total'] ); ?></li>
				<li><strong><?php esc_html_e( 'Syntax Errors:', 'ads.txt-main' ); ?></strong> <span class="<?php echo ! empty( $atm_ads_validation['errors'] ) ? 'text-danger font-bold' : ''; ?>"><?php echo count( $atm_ads_validation['errors'] ); ?></span></li>
				<li><strong><?php esc_html_e( 'Duplicate Lines:', 'ads.txt-main' ); ?></strong> <span class="<?php echo $atm_ads_validation['duplicates'] > 0 ? 'text-warning font-bold' : ''; ?>"><?php echo esc_html( $atm_ads_validation['duplicates'] ); ?></span></li>
			</ul>

			<?php if ( ! empty( $atm_ads_validation['errors'] ) || ! empty( $atm_ads_validation['warnings'] ) ) : ?>
				<div class="atm-diagnostic-log">
					<h4><?php esc_html_e( 'Diagnostic Log Messages:', 'ads.txt-main' ); ?></h4>
					<div class="atm-log-entries">
						<?php foreach ( $atm_ads_validation['errors'] as $error ) : ?>
							<div class="atm-log-item log-error">
								<span class="dashicons dashicons-dismiss"></span>
								<span><strong>Line <?php echo esc_html( $error['line'] ); ?>:</strong> <?php echo esc_html( $error['message'] ); ?></span>
							</div>
						<?php endforeach; ?>

						<?php foreach ( $atm_ads_validation['warnings'] as $warning ) : ?>
							<div class="atm-log-item log-warning">
								<span class="dashicons dashicons-warning"></span>
								<span><strong>Line <?php echo esc_html( $warning['line'] ); ?>:</strong> <?php echo esc_html( $warning['message'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="atm-alert alert-success">
					<span class="dashicons dashicons-yes-alt"></span>
					<p><?php esc_html_e( 'No issues found! Your ads.txt format conforms perfectly to IAB specifications.', 'ads.txt-main' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- App-ads.txt Validation -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'App-ads.txt Verification', 'ads.txt-main' ); ?></h3>
			<span class="atm-status-badge <?php echo $atm_app_ads_validation['valid'] && empty( $atm_app_ads_validation['warnings'] ) ? 'status-active' : 'status-inactive'; ?>">
				<?php echo $atm_app_ads_validation['valid'] && empty( $atm_app_ads_validation['warnings'] ) ? esc_html__( '100% Valid', 'ads.txt-main' ) : esc_html__( 'Action Needed', 'ads.txt-main' ); ?>
			</span>
		</div>
		<div class="atm-card-body">
			<ul class="atm-bullets">
				<li><strong><?php esc_html_e( 'Total Evaluated Lines:', 'ads.txt-main' ); ?></strong> <?php echo esc_html( $atm_app_ads_validation['total'] ); ?></li>
				<li><strong><?php esc_html_e( 'Syntax Errors:', 'ads.txt-main' ); ?></strong> <span class="<?php echo ! empty( $atm_app_ads_validation['errors'] ) ? 'text-danger font-bold' : ''; ?>"><?php echo count( $atm_app_ads_validation['errors'] ); ?></span></li>
				<li><strong><?php esc_html_e( 'Duplicate Lines:', 'ads.txt-main' ); ?></strong> <span class="<?php echo $atm_app_ads_validation['duplicates'] > 0 ? 'text-warning font-bold' : ''; ?>"><?php echo esc_html( $atm_app_ads_validation['duplicates'] ); ?></span></li>
			</ul>

			<?php if ( ! empty( $atm_app_ads_validation['errors'] ) || ! empty( $atm_app_ads_validation['warnings'] ) ) : ?>
				<div class="atm-diagnostic-log">
					<h4><?php esc_html_e( 'Diagnostic Log Messages:', 'ads.txt-main' ); ?></h4>
					<div class="atm-log-entries">
						<?php foreach ( $atm_app_ads_validation['errors'] as $error ) : ?>
							<div class="atm-log-item log-error">
								<span class="dashicons dashicons-dismiss"></span>
								<span><strong>Line <?php echo esc_html( $error['line'] ); ?>:</strong> <?php echo esc_html( $error['message'] ); ?></span>
							</div>
						<?php endforeach; ?>

						<?php foreach ( $atm_app_ads_validation['warnings'] as $warning ) : ?>
							<div class="atm-log-item log-warning">
								<span class="dashicons dashicons-warning"></span>
								<span><strong>Line <?php echo esc_html( $warning['line'] ); ?>:</strong> <?php echo esc_html( $warning['message'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="atm-alert alert-success">
					<span class="dashicons dashicons-yes-alt"></span>
					<p><?php esc_html_e( 'No issues found! Your app-ads.txt format conforms perfectly to IAB specifications.', 'ads.txt-main' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
