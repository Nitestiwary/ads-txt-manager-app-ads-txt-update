<?php
/**
 * App-ads.txt view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ads_txt_manager_app_ads_content = get_option( 'ads_txt_manager_app_ads_txt', '' );
$ads_txt_manager_settings = get_option( 'ads_txt_manager_settings', array() );
$ads_txt_manager_auto_validate = isset( $ads_txt_manager_settings['auto_validation'] ) ? $ads_txt_manager_settings['auto_validation'] : '1';
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-phone"></span> <?php esc_html_e( 'Manage app-ads.txt', 'ads-txt-manager-app-ads-txt-update' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Customize your mobile application app-ads.txt file. Make sure entries align with Google AdMob, AppLovin, Unity Ads, etc.', 'ads-txt-manager-app-ads-txt-update' ); ?>
	</p>
</div>

<div class="atm-editor-container">
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="atm-app-ads-txt-form">
		<?php wp_nonce_field( 'ads_txt_manager_action', 'ads_txt_nonce' ); ?>
		<input type="hidden" name="action" value="ads_txt_action" />
		<input type="hidden" name="ads_txt_action" value="save_app_ads" />

		<div class="atm-textarea-wrapper">
			<label for="app_ads_txt_content" class="atm-input-label">
				<?php esc_html_e( 'Edit app-ads.txt lines:', 'ads-txt-manager-app-ads-txt-update' ); ?>
				<span class="atm-preview-link-hint">
					(<?php esc_html_e( 'Live Preview URL:', 'ads-txt-manager-app-ads-txt-update' ); ?> <a href="<?php echo esc_url( $stats['app_ads']['url'] ); ?>" target="_blank"><?php echo esc_url( $stats['app_ads']['url'] ); ?></a>)
				</span>
			</label>
			<textarea 
				name="app_ads_txt_content" 
				id="app_ads_txt_content" 
				class="atm-code-editor" 
				placeholder="google.com, pub-1020304050607080, DIRECT, f08c47fec0942fa0"
				data-validate="<?php echo esc_attr( $ads_txt_manager_auto_validate ); ?>"
			><?php echo esc_textarea( $ads_txt_manager_app_ads_content ); ?></textarea>
		</div>

		<!-- Live Validation Container -->
		<div id="atm-live-validation-result" class="atm-live-validation-box hidden">
			<div class="atm-validation-header">
				<span class="dashicons dashicons-warning"></span>
				<h3><?php esc_html_e( 'Live Validation Analysis', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
			</div>
			<div class="atm-validation-body">
				<!-- Injected by JS -->
			</div>
		</div>

		<div class="atm-form-actions">
			<button type="submit" class="button button-primary button-hero atm-submit-btn">
				<?php esc_html_e( 'Save App-ads.txt Changes', 'ads-txt-manager-app-ads-txt-update' ); ?>
			</button>
		</div>
	</form>
</div>

<!-- Promo Banner for App Publishers -->
<div class="atm-promo-banner-container margin-bottom-20" style="margin-top: 24px;">
	<a href="https://monetiscope.com/contact/" target="_blank" rel="noopener noreferrer">
		<img src="<?php echo esc_url( $banner_app ); ?>" alt="Uplift Your Ad Revenue upto 30% - Monetiscope App Monetization" class="atm-promo-banner-img" />
	</a>
</div>

