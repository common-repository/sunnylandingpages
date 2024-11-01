<?php

/**
 * Fired during plugin activation
 *
 * @link       https://funl.co
 * @since      1.0.0
 *
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/includes
 * @author     Vineet Kharwar <vineet.kharwar@gaboli.com>
 */
class Funl_Html_Landing_Pages_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$funlhtmllandingpages_pages_table_name = $wpdb->prefix . "funlhtmllandingpages_pages"; 

		$charset_collate = $wpdb->get_charset_collate();
		if( $wpdb->get_var("SHOW TABLES LIKE '$funlhtmllandingpages_pages_table_name'") != $funlhtmllandingpages_pages_table_name ) {
			$sql = "CREATE TABLE $funlhtmllandingpages_pages_table_name (
				post_id mediumint(9) NOT NULL,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				name tinytext NOT NULL,
				html mediumtext NOT NULL,
				url varchar(55) DEFAULT '' NOT NULL,
				post_author bigint(20) DEFAULT '0',
				UNIQUE KEY  (post_id),
				UNIQUE KEY  (post_author)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		$funl_forms_table_name = $wpdb->prefix.'funl_forms';

		if( $wpdb->get_var("SHOW TABLES LIKE '$funl_forms_table_name'") != $funl_forms_table_name ) {

			$sql1 = "CREATE TABLE $funl_forms_table_name (
				form_id bigint(20) NOT NULL AUTO_INCREMENT,
				ip_address int(4) unsigned NOT NULL,
				form_post_id bigint(20) NOT NULL,
				form_value longtext NOT NULL,
				form_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (form_id)
			) $charset_collate;";

			dbDelta( $sql1 );
		}
	}
}
