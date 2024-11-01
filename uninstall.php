<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 *
 *
 * @link       https://funl.co
 * @since      1.0.0
 *
 * @package    Funl_Html_Landing_Pages
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if(get_option('funlhtmllandingpages_opt_remove_table_on_uninstall')) {
    // drop a database table
    global $wpdb;
    $funlhtmllandingpages_pages_table = $wpdb->prefix . 'funlhtmllandingpages_pages';
    $wpdb->query("DROP TABLE IF EXISTS ".$funlhtmllandingpages_pages_table);
	
	$funl_forms_table = $wpdb->prefix . 'funl_forms';
    $wpdb->query("DROP TABLE IF EXISTS ".$funl_forms_table);
}

// Access the database via SQL
global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'funlhtmllandingpages'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );

delete_option('funlhtmllandingpages_opt_allow_wp-admin');
delete_option('funlhtmllandingpages_opt_remove_table_on_uninstall');
