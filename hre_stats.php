<?php

/**
 * Autoload all classes needed for plugin
 */
require_once plugin_dir_path( __FILE__ ) .'/autoloader.php';

use HRE\Stats;
use HRE\Stats\PSR4Autoloader;
use HRE\Stats\Activator;
use HRE\Stats\Deactivator;

/*
 Plugin Name: HRE Stats
 Plugin URI:  http://hetrondjeeilanden.nl
 Description: Stats widget and shortcodes for HRE
 Version:     0.1
 Author:      Eelco Koelewijn
 Author URI:  http://eelcokoelewijn.nl
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hre-exporter-activator.php
 */
function activate_hre_stats() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hre-exporter-deactivator.php
 */
function deactivate_hre_stats() {
	Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_hre_stats' );
register_deactivation_hook( __FILE__, 'deactivate_hre_stats' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_hre_stats() {
	$plugin = new Stats();
	$plugin->run();
}

run_hre_stats();
