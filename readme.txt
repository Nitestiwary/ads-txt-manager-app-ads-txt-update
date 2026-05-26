=== Ads.txt Manager - App-ads.txt Update ===
Contributors: Monetiscope
Tags: ads.txt, app-ads.txt, admob, adsense, monetiscope
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lightweight, secure plugin to easily manage, validate, and update your ads.txt and app-ads.txt files directly from your WordPress dashboard.


== Description ==

Maximize your ad revenue, bypass crawlers index issues, and prevent unauthorized ad inventory selling with **Ads.txt Manager - App-ads.txt Update**, developed by [Monetiscope](https://monetiscope.com). This plugin provides a highly professional, modern, and beginner-friendly dashboard to edit, validate, and manage authorized seller records for both web inventories (ads.txt) and mobile application listings (app-ads.txt).

Whether you monetize using Google AdSense, AdMob, Google Ad Exchange (AdX), AppLovin, Unity Ads, IronSource, or any other premium programmatic advertising mediation stack, this plugin ensures your seller configuration complies perfectly with IAB Tech Lab specifications.

By automating file creation and offering smart rewrite fallback delivery rules, this plugin bypasses hosting write blocks entirely—guaranteeing search engine and network bots will crawl your verification files instantly without any manual setup.

= Web Ads.txt Key Features =
* **Automatic Creation:** Bypasses manual file building by instantly generating a valid ads.txt in your WordPress root directory.
* **Inline Direct Editing:** Edit your seller entries directly from the dashboard using our clean editor box.
* **Syntax Validation:** Scans domain formatting, publisher IDs, and relationship categories in real-time to avoid entry typos.
* **Duplicate Line Detection:** Alerts you to identical rows to maintain clean, optimized file sizes.
* **One-Click Live Preview:** View your public `yourdomain.com/ads.txt` file instantly using the live action link.
* **Safe Saves & Auto-Backups:** Automatically captures previous revisions before every update to allow rapid rollbacks.

= Mobile App-ads.txt Key Features =
* **Mobile-First Index Support:** Fully optimized to verify developer identities for Google Play Console and Apple App Store listings.
* **Automatic Integration:** Automatically maps listings dynamically when your main domain is listed in app stores.
* **App-Specific Validation:** Tailored validation format rules for programmatic app networks (AdMob, AppLovin, etc.).
* **Dynamic Fallback Routing:** Serves plain-text headers directly for `yourdomain.com/app-ads.txt` using the WordPress Rewrite API if file write permissions are locked.
* **Import & Export Migration:** Move hundreds of seller entries instantly by importing and exporting structured JSON files.

= Live Syntax & Verification System =
* **Real-time Editor Scan:** JavaScript-based character-check scans formats dynamically while you type.
* **Format Checks:** Checks for standard comma-separated sequences: `<Advertising Domain>, <Publisher ID>, <Relationship>, <Cert Authority ID (Optional)>`.
* **Relationship Match:** Normalizes relationship fields and flags warnings if not matched to DIRECT or RESELLER (case insensitive).
* **Domain Structure Validation:** Employs standard regex filters to find invalid subdomains, protocols, or trailing paths.
* **Duplicate Entry Warning:** Highlights matching rows, reducing bloated files and matching guidelines.

= Safety, Backups & Migration Tools =
* **Auto-Revision List:** Saves up to 10 historical copies of your files, easily accessible via a structured backup panel.
* **JSON Migration Pack:** Download your entire data parameters and configurations in a single JSON block to deploy across staging sites.
* **Secure Capability Checks:** Restricts all editing operations strictly to users with administrator privileges (`manage_options`).
* **Complete System Purge:** Fully wipes database options arrays, setting tables, and physical root files upon plugin deletion.

= Third-Party Integration & Transparency Disclosure =
To assist publishers in scaling their ad yield, this plugin features optional promotional banners linking directly to [Monetiscope](https://monetiscope.com), a premium monetization service. Clicking these banners redirects the user to their official contact page (https://monetiscope.com/contact/) where publishers can optionally request upgrades to premium Google Ad Exchange, SDK Ad Mediation stacks, or Open Bidding demand partners. Using these banners or contacting Monetiscope is completely optional and does not affect the core functionality of the plugin.

== Installation ==

1. Upload the entire `ads-txt-manager-app-ads-txt-update` folder to your `/wp-content/plugins/` directory, or search and install it directly via your WordPress Admin Plugins installer.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the plugin settings from the new **Ads.txt Manager** menu on your left sidebar.

== Frequently Asked Questions ==

= Does this plugin work without FTP or File Manager access? =
Yes! The plugin attempts to write physical files to your WordPress root. If write permissions are restricted, it falls back to dynamically serving pages, ensuring search engine crawlers find files at `yourdomain.com/ads.txt`.

= How do I configure app-ads.txt for my mobile games/apps? =
Go to the **App-ads.txt** tab, insert your seller lines, and click Save. Make sure you link your developer site (e.g., `https://yourdomain.com`) in your Google Play Console or Apple App Store app listing. Crawlers will map them automatically.

= Where are backups saved? =
Backups are saved securely in your WordPress database options array. You can view, download, or restore backups from the **Backups** tab inside the dashboard.

= Is this plugin compatible with caching plugins? =
Yes! The dynamic rewrite fallback bypasses common page caching systems by utilizing the native WordPress routing cycle and injecting clean `text/plain` headers that prevent browser caches from serving stale data.

== Screenshots ==

1. **Dashboard Overview** - Modern admin panel displaying real-time files active status, entries counts, and public preview links.
2. **Ads.txt Editor Panel** - Secure, syntax-highlighted editor box with live validation logging.
3. **App-ads.txt Editor Panel** - App-ads.txt custom entries editor with IAB verification highlights.
4. **Historical Backups Table** - Historical revisions management panel allowing rapid one-click rollback options.
5. **Validation Diagnostics** - In-depth validation log showing clear formatting warnings and duplicate highlights.

== Changelog ==

= 1.0.0 =
* Initial public release.
* Support for ads.txt and app-ads.txt manager interfaces.
* Real-time live jQuery syntax validation checks.
* Smart root file-writing with rewrite fallback routines.
* Auto-backup revisions management panel.
* Dynamic promotional integration for Monetiscope monetization suites.
