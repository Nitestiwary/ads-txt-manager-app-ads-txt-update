<?php
/**
 * Verification & Validation class.
 *
 * @package Ads_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ads_Txt_Validator {

	/**
	 * Validate entire file text and return feedback.
	 *
	 * @param string $content Text content.
	 * @return array Validation findings.
	 */
	public static function validate_content( $content ) {
		$results = array(
			'errors'   => array(),
			'warnings' => array(),
			'valid'    => true,
			'total'    => 0,
			'duplicates' => 0,
		);

		if ( empty( trim( $content ) ) ) {
			return $results;
		}

		$lines = explode( "\n", $content );
		$seen = array();

		foreach ( $lines as $index => $line_raw ) {
			$line_num = $index + 1;
			$line = trim( $line_raw );

			// Skip empty lines and comments
			if ( empty( $line ) || strpos( $line, '#' ) === 0 ) {
				continue;
			}

			$results['total']++;

			// Check duplicate line
			$normalized = strtolower( str_replace( ' ', '', $line ) );
			if ( isset( $seen[ $normalized ] ) ) {
				$results['duplicates']++;
				$results['warnings'][] = array(
					'line' => $line_num,
					'text' => $line,
					/* translators: %1$d: current line number, %2$d: matching duplicate line number */
					'message' => sprintf( __( 'Duplicate entry found on line %1$d (matches line %2$d).', 'ads-txt-manager-app-ads-txt-update' ), $line_num, $seen[ $normalized ] ),
				);
				continue;
			}
			$seen[ $normalized ] = $line_num;

			// Check syntax structure
			// Standard ads.txt contains: <Domain>, <Publisher ID>, <Relationship>, <Cert Authority ID (Optional)>
			$parts = explode( ',', $line );
			$count = count( $parts );

			if ( $count < 3 || $count > 4 ) {
				$results['errors'][] = array(
					'line' => $line_num,
					'text' => $line,
					/* translators: %d: line number */
					'message' => sprintf( __( 'Invalid formatting on line %d. Expected 3 or 4 comma-separated values (Domain, Publisher ID, Relationship, optional Cert Authority ID).', 'ads-txt-manager-app-ads-txt-update' ), $line_num ),
				);
				$results['valid'] = false;
				continue;
			}

			$domain = trim( $parts[0] );
			$pub_id = trim( $parts[1] );
			$relation = strtoupper( trim( $parts[2] ) );
			$cert_id = isset( $parts[3] ) ? trim( $parts[3] ) : '';

			// 1. Validate Domain
			if ( ! self::is_valid_domain( $domain ) ) {
				$results['errors'][] = array(
					'line' => $line_num,
					'text' => $line,
					/* translators: %1$s: domain name, %2$d: line number */
					'message' => sprintf( __( 'Invalid domain format "%1$s" on line %2$d.', 'ads-txt-manager-app-ads-txt-update' ), esc_html( $domain ), $line_num ),
				);
				$results['valid'] = false;
			}

			// 2. Validate Seller ID / Pub ID
			if ( empty( $pub_id ) ) {
				$results['errors'][] = array(
					'line' => $line_num,
					'text' => $line,
					/* translators: %d: line number */
					'message' => sprintf( __( 'Missing publisher/seller ID on line %d.', 'ads-txt-manager-app-ads-txt-update' ), $line_num ),
				);
				$results['valid'] = false;
			}

			// 3. Validate Relation
			if ( $relation !== 'DIRECT' && $relation !== 'RESELLER' ) {
				$results['errors'][] = array(
					'line' => $line_num,
					'text' => $line,
					/* translators: %1$s: relationship type, %2$d: line number */
					'message' => sprintf( __( 'Invalid relationship type "%1$s" on line %2$d. Must be DIRECT or RESELLER (case insensitive).', 'ads-txt-manager-app-ads-txt-update' ), esc_html( $relation ), $line_num ),
				);
				$results['valid'] = false;
			}
		}

		return $results;
	}

	/**
	 * Domain check.
	 */
	private static function is_valid_domain( $domain ) {
		if ( empty( $domain ) ) {
			return false;
		}
		// Match domain names like sub.domain.com, domain.co.uk, etc.
		return preg_match( '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i', $domain );
	}
}

