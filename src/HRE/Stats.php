<?php
namespace HRE;

use HRE\Stats\Loader;
use HRE\Stats\I18n;
use HRE\Stats\AdminWidget;
use HRE\Stats\ParticipantShortCode;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *
 * @package    HRE Stats
 * @subpackage hre_registation_export/includes
 */
class Stats {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'hre-stats';
		$this->version = '1.0.0';
		$this->loader = new Loader();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n();
		$plugin_i18n->set_domain($this->plugin_name);
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// check if wp admin pages showing
		if (!is_admin()) {
			return;
		}
		// create widget class
		$plugin_admin = new AdminWidget($this->get_plugin_name(),$this->get_version());
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'add_widget');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new ParticipantShortCode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcode');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static function relativePath($appendPath)
	{
		$path = dirname( dirname( dirname(plugin_basename( __FILE__ ) ) ) );
		if ($appendPath) {
			return $path.$appendPath;
		}
		return $path;
	}

	public static function absolutePath($appendPath)
	{
		$path = dirname( ( dirname(plugin_dir_path( __FILE__ ) ) ) );
		if ($appendPath) {
			return $path.$appendPath;
		}
		return $path;
	}

	public static function pluginURL($appendPath)
	{
		$path = dirname( ( dirname( plugin_dir_url( __FILE__ ) ) ) );
		if ($appendPath) {
			return $path.$appendPath;
		}
		return $path;
	}
}

// https://developer.wordpress.org/plugins/

// export + download from wordpress resources
// http://faisonz.com/blog/creating-a-wordpress-plugin-to-export-mysql-data-as-csv/
// https://wordpress.org/plugins/navayan-csv-export/

// http://wordpress.stackexchange.com/questions/3480/how-can-i-force-a-file-download-in-the-wordpress-backend
// http://www.codeitive.com/0SHeeWVPqe/i-want-to-download-csv-in-wordpress-admin-page…it-works-fine-at-local-server-but-live-it-give-header-already-sent-error.html
// https://www.wphub.com/wordpress-import-export-tricks-xls-csv-and-plugins/
// http://stackoverflow.com/questions/19240212/creating-a-wordpress-page-in-my-plugin-to-export-mysql-data-as-csv

// wordpress db reference
// http://codex.wordpress.org/Class_Reference/wpdb

// wordpress plugin boiler plate
// https://github.com/DevinVinson/WordPress-Plugin-Boilerplate

/**
 * The plugin bootstrap file
 *
 * @package           HRE_Exporter
 *
 * @wordpress-plugin
 * Plugin Name:       HRE Registration Exporter
 * Plugin URI:        http://hetrondjeeilanden.nl
 * Description:       Export all HRE registrations
 * Version:           1.0.0
 * Author:            Eelco Koelewijn
 * Author URI:        http://hetrondjeeilanden.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hre-registration-exporter
 * Domain Path:       /languages
 */
