<?php // phpcs:ignore
/**
 * Uninstall file.
 *
 * @package snapshot
 */

use WPMUDEV\Snapshot4\Helper\Log;
use WPMUDEV\Snapshot4\Main;

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( class_exists( Main::class ) ) {
	return;
}

if ( ! empty( get_site_option( 'snapshot_remove_on_uninstall' ) ) ) {
	delete_site_option( 'snapshot_global_exclusions' );
	delete_site_option( 'snapshot_remove_on_uninstall' );
	delete_site_option( 'snapshot_email_settings' );
	delete_site_option( 'snapshot_exclude_large' );
	delete_site_option( 'snapshot_excluded_tables' );

	// Delete configs.
	delete_site_option( 'snapshot-presets_config' );

	update_site_option( 'snapshot_activate_schedule', 0 );
} else {
	update_site_option( 'snapshot_activate_schedule', 1 );
}

delete_site_option( 'snapshot-show-black-friday' );
delete_site_option( 'snapshot_started_seen' );
delete_site_option( 'snapshot_started_seen_persistent' );
delete_site_option( 'snapshot_latest_backup' );
delete_site_option( 'snapshot_running_backup' );
delete_site_option( 'snapshot_running_backup_status' );
delete_site_option( 'snapshot_whats_new_seen' );
delete_site_option( 'snapshot_manual_backup_trigger_time' );
delete_site_option( 'snapshot_tutorials_slider_seen' );

/**
 * Purge the transients.
 */
delete_transient( 'snapshot_current_stats' );
delete_transient( 'snapshot_listed_backups' );
delete_transient( 'snapshot_extra_security_step' );

require_once trailingslashit( __DIR__ ) . 'lib/snapshot/helper/class-log.php';
Log::remove_log_dir();