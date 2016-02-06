<?php
namespace HRE\Stats;

use HRE\Stats;
use HRE\Stats\Data\DataManager;

class ParticipantShortCode
{
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
		wp_enqueue_style( $this->plugin_name.'_participants', Stats::pluginURL('/public/css/hre-stats-public.css'), array(), $this->version, 'all' );
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
		wp_enqueue_script( $this->plugin_name.'_participants', Stats::pluginURL('/public/js/hre-stats-public.js'), array( 'jquery' ), $this->version, false );
	}
	
	public function register_shortcode()
	{
		add_shortcode( 'hre-participants', array( 'HRE\Stats\ParticipantShortCode', 'render_shortcode' ) );
	}
	
	public static function render_shortcode($atts, $content = null)
	{
		$dataManager = new DataManager();
		$data = $dataManager->participants();

		$html = '<table class="hre-participants-wrapper" border="0">';
		$grouping = null;
		foreach ($data as $participant)
		{
			if ($grouping != $participant['gender']) {
				$title = '';
				switch ($participant['gender']){
					case 'Man':
						$title = 'Mannen';
						break;
					case 'Vrouw':
						$title = 'Vrouwen';
						break;
				}
				$html .= "<tr><th colspan=\"2\" class=\"hre-participant-name\"><a name=\"{$participant['gender']}\">&nbsp;</a><h2>{$title}</h2></th></tr>";
				$grouping = $participant['gender'];
			}
			$html .= '<tr class="hre-participant">';
			$html .= "<td class=\"hre-participant-name\">{$participant['Name']}</td>";
			$html .= "<td class=\"hre-participant-city\">{$participant['city']}</td>";			
			$html .= '</tr>';
		}
		$html .= '</table>';
		
		return $html;
	}
	
}