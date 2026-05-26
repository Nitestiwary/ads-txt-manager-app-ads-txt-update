=== Ads.txt Manager - App-ads.txt Update ===
Contributors: Monetiscope
Tags: ads.txt, app-ads.txt, admob, adsense, google ad exchange, monetize, publisher, seo, ads.txt manager, verification
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight, secure, and modern WordPress plugin to easily create, validate, manage, and update your ads.txt and app-ads.txt files directly from your dashboard without using FTP.

== Description ==

Maximize your ad revenue and prevent authorization issues with **Ads.txt Manager - App-ads.txt Update**, developed by [Monetiscope](https://monetiscope.com). This plugin provides a modern, secure, and highly visual dashboard to edit, validate, and manage authorization records for both websites (ads.txt) and mobile applications (app-ads.txt).

Whether you monetize with Google AdSense, AdMob, Google Ad Exchange, Unity Ads, AppLovin, or any other premium ad stack, this plugin ensures your seller entries comply perfectly with IAB specifications.

= Core Features =
* **Automatic Creation:** Creates ads.txt and app-ads.txt files in your server's root directory automatically.
* **Smart Fallback Routing:** If your server limits root write permissions, the plugin uses dynamic WordPress rewrites to seamlessly serve your files, guaranteeing a 100% success rate.
* **Real-time Live Validation:** Evaluates your lines as you type to highlight syntax errors, missing seller IDs, or malformed domain structures immediately.
* **Duplicate Detection:** Prevents bloated files by identifying and notifying you of identical entries.
* **Auto-Backups:** Keeps up to 10 previous revisions of your files, letting you roll back changes with a single click.
* **Import/Export Tooling:** Migrate your configurations easily by downloading and uploading structured JSON files.
* **Lightweight & Secure:** Minimal footprint, optimized operations, strict capability requirements, and zero performance impact.

== Installation ==

1. Upload the entire `ads-txt-manager` folder to your `/wp-content/plugins/` directory, or search and install it directly via your WordPress Admin Plugins installer.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the plugin settings from the new **Ads.txt Manager** menu on your left sidebar.

== Frequently Asked Questions ==

= Does this plugin work without FTP or File Manager access? =
Yes! The plugin attempts to write physical files to your WordPress root. If write permissions are restricted, it falls back to dynamically serving pages, ensuring search engine crawlers find files at `yourdomain.com/ads.txt`.

= How do I configure app-ads.txt for my mobile games/apps? =
Go to the **App-ads.txt** tab, insert your seller lines, and click Save. Make sure you link your developer site (e.g., `https://yourdomain.com`) in your Google Play Console or Apple App Store app listing. Crawlers will map them automatically.

= Where are backups saved? =
Backups are saved securely in your WordPress database options array. You can view, download, or restore backups from the **Backups** tab inside the dashboard.

== Changelog ==

= 1.0.0 =
* Initial public release.
* Support for ads.txt and app-ads.txt manager interfaces.
* Real-time live jQuery syntax validation checks.
* Smart root file-writing with rewrite fallback routines.
* Auto-backup revisions management panel.
* Dynamic promotional integration for Monetiscope monetization suites.
