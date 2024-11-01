<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://funl.co
 * @since             1.0.0
 * @package           Funl_Html_Landing_Pages
 *
 * @wordpress-plugin
 * Plugin Name:       Lead Gen Landing Pages
 * Plugin URI:        https://funl.co//
 * Description:       You can publish a Landing Page  on your wordpress site using our free plugin - Lead Gen Landing Pages.
 * Version:           4.0
 * Author:            Funl
 * Author URI:        https://funl.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       funl-html-landing-pages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FUNL_HTML_LANDING_PAGES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-funl-html-landing-pages-activator.php
 */
function activate_funl_html_landing_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-funl-html-landing-pages-activator.php';
	Funl_Html_Landing_Pages_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-funl-html-landing-pages-deactivator.php
 */
function deactivate_funl_html_landing_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-funl-html-landing-pages-deactivator.php';
	Funl_Html_Landing_Pages_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_funl_html_landing_pages' );
register_deactivation_hook( __FILE__, 'deactivate_funl_html_landing_pages' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-funl-html-landing-pages.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_funl_html_landing_pages() {
	$plugin = new Funl_Html_Landing_Pages();
	$plugin->run();

    global $pagenow;
   
    if (    $pagenow == 'edit.php' && isset( $_REQUEST[ 'post_type' ] ) && sanitize_key( $_REQUEST[ 'post_type' ] ) == 'funlhtmllandingpages' && !isset( $_REQUEST[ 'page' ] ) ) {
        add_action('init', 'run_upload');
    }

}

function run_upload(){
    $plugin = new Funl_Html_Landing_Pages();
    $plugin->uploadSection();
}

run_funl_html_landing_pages();
