<?php
namespace HRE\Stats;

//http://codex.wordpress.org/Dashboard_Widgets_API
//http://codex.wordpress.org/Example_Dashboard_Widget


use HRE\Stats;
use HRE\Stats\Data\DataManager;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class AdminWidget {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() 
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, Stats::pluginURL('/admin/css/hre-stats-admin.css'), array(), $this->version, 'all' );
	}
		
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() 
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, Stats::pluginURL('/admin/js/hre-stats-admin.js'), array( 'jquery' ), $this->version, false );
	}
	
	public function add_widget()
	{
		//register widget
		//Register the widget...
		wp_add_dashboard_widget(
				$this->plugin_name.'_'.$this->version, //A unique slug/ID
				__( 'Registration Stats', $this->plugin_name ),//Visible name for the widget
				array('HRE\Stats\AdminWidget','render_widget'),      //Callback for the main widget content
				null       //Optional callback for widget configuration content
		);		
	}
	
	public static function render_widget($post, $callback_args)
	{
		include_once(Stats::absolutePath('/admin/partials/hre-stats-widget.php') );		
	}
	
	public static function showStats()
	{
		$dataManager = new DataManager();
		$data = $dataManager->data();
		
		$stats = null;
		if (count($data) > 0) {
			foreach ($data as $item) {
				$stats[$item['order_item_name']] = $item['Total'];
			}
		}
		return $stats;
	}
}