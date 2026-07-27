<?php
/**
 * Help and FAQs panel view.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="atm-section-header">
	<h2><span class="dashicons dashicons-editor-help"></span> <?php esc_html_e( 'Documentation & Support Help Center', 'ads-txt-manager-app-ads-txt-update' ); ?></h2>
	<p class="atm-section-desc">
		<?php esc_html_e( 'Learn how to format ads.txt correctly, how app-ads.txt maps to app store developer listings, and how Monetiscope scales your ad revenue.', 'ads-txt-manager-app-ads-txt-update' ); ?>
	</p>
</div>

<div class="atm-help-grid">
	<!-- What is Ads.txt -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'How to Format ads.txt Entries', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<p><?php esc_html_e( 'An ads.txt line represents a authorized seller record. Each valid entry consists of 3 or 4 comma-separated values:', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			
			<table class="atm-stats-table">
				<tr>
					<th><code>google.com</code></th>
					<td><?php esc_html_e( 'Advertising Domain (e.g. ad exchange domain name)', 'ads-txt-manager-app-ads-txt-update' ); ?></td>
				</tr>
				<tr>
					<th><code>pub-10203040</code></th>
					<td><?php esc_html_e( 'Publisher ID / Seller ID (provided by ad network)', 'ads-txt-manager-app-ads-txt-update' ); ?></td>
				</tr>
				<tr>
					<th><code>DIRECT / RESELLER</code></th>
					<td><?php esc_html_e( 'Relationship (DIRECT if you own account, RESELLER if third-party)', 'ads-txt-manager-app-ads-txt-update' ); ?></td>
				</tr>
				<tr>
					<th><code>f08c47fec094fa</code></th>
					<td><?php esc_html_e( 'Optional Tag ID (Certification Authority TAG/ID)', 'ads-txt-manager-app-ads-txt-update' ); ?></td>
				</tr>
			</table>

			<div class="atm-alert alert-info">
				<p><strong><?php esc_html_e( 'Example Valid Record:', 'ads-txt-manager-app-ads-txt-update' ); ?></strong><br />
				<code>google.com, pub-1020304050607080, DIRECT, f08c47fec0942fa0</code></p>
			</div>
		</div>
	</div>

	<!-- App-ads.txt specific help -->
	<div class="atm-card">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Connecting App-ads.txt to Stores', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<p><?php esc_html_e( 'For mobile applications, search crawlers look for app-ads.txt to verify app developers. To link them correctly:', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			
			<ol class="atm-ordered-list">
				<li><?php esc_html_e( 'Configure your app-ads.txt lines inside the editor tab and save changes.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><?php esc_html_e( 'Copy your public app-ads.txt URL (e.g., https://example.com/app-ads.txt).', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><?php esc_html_e( 'Go to your Google Play Console or Apple App Store Connect developer account page.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><?php esc_html_e( 'Paste your main domain URL (e.g. https://example.com) into the Developer Website field of your app listing.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><?php esc_html_e( 'Ad networks will crawl and map listings automatically within 24 hours.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
			</ol>
		</div>
	</div>

	<!-- Monetiscope Integration -->
	<div class="atm-card span-full" style="border-left: 4px solid #ff7f0e;">
		<div class="atm-card-header">
			<h3><?php esc_html_e( 'Scale Ad Revenue with Monetiscope', 'ads-txt-manager-app-ads-txt-update' ); ?></h3>
		</div>
		<div class="atm-card-body">
			<p><?php esc_html_e( 'Monetiscope is a state-of-the-art monetization suite that assists publishers in connecting directly with Premium Google Ad Exchange, Open Bidding demands, and SDK mediation stacks.', 'ads-txt-manager-app-ads-txt-update' ); ?></p>
			<ul class="atm-bullets">
				<li><strong><?php esc_html_e( 'Higher Premium CPMs:', 'ads-txt-manager-app-ads-txt-update' ); ?></strong> <?php esc_html_e( 'Tap into hundreds of top-tier advertisers and open bidding stacks.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><strong><?php esc_html_e( 'Dedicated Support:', 'ads-txt-manager-app-ads-txt-update' ); ?></strong> <?php esc_html_e( 'Expert analysis on ads.txt mappings, layout placements, and yield optimization.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
				<li><strong><?php esc_html_e( 'Unified Analytics:', 'ads-txt-manager-app-ads-txt-update' ); ?></strong> <?php esc_html_e( 'Full dashboard breakdown of total daily active users, click-through rates, and revenue performance.', 'ads-txt-manager-app-ads-txt-update' ); ?></li>
			</ul>
			<p>
				<a href="https://monetiscope.com/contact/" target="_blank" class="button button-primary button-hero">
					<?php esc_html_e( 'Start Monetizing Your Site / App Now', 'ads-txt-manager-app-ads-txt-update' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>

