<?php
declare( strict_types=1 );

namespace WP_Defender\Model\Setting;

use Calotes\Model\Setting;

class Main_Setting extends Setting {
	/**
	 * Option name
	 * @var string
	 */
	public $table = 'wd_main_settings';

	/**
	 * @var string
	 * @defender_property
	 */
	public $translate;

	/**
	 * @var bool
	 * @defender_property
	 */
	public $usage_tracking = false;

	/**
	 * @var string
	 * @sanitize_text_field
	 * @defender_property
	 */
	public $uninstall_data = 'keep';

	/**
	 * @var string
	 * @sanitize_text_field
	 * @defender_property
	 */
	public $uninstall_settings = 'preserve';

	/**
	 * @var bool
	 * @defender_property
	 */
	public $high_contrast_mode = false;

	protected function after_load(): void {
		$site_locale = is_multisite() ? get_site_option( 'WPLANG' ) : get_locale();

		if ( empty( $site_locale ) || 'en_US' === $site_locale ) { // @see wp_dropdown_languages() by default empty string for English.
			$site_language = 'English';
		} else {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
			$translations = wp_get_available_translations();
			$site_language = isset( $translations[ $site_locale ] )
				? $translations[ $site_locale ]['native_name']
				: __( 'Error detecting language', 'defender-security' );
		}

		$this->translate = $site_language;
	}

	/**
	 * Define settings labels.
	 *
	 * @return array
	 */
	public function labels(): array {
		return [
			'translate' => __( 'Translations', 'defender-security' ),
			'usage_tracking' => __( 'Usage Tracking', 'defender-security' ),
			'uninstall_data' => __( 'Uninstall Data', 'defender-security' ),
			'uninstall_settings' => __( 'Uninstall Settings', 'defender-security' ),
			'high_contrast_mode' => __( 'High Contrast Mode', 'defender-security' ),
		];
	}
}
