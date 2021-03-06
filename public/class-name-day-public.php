<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://prodev.lt
 * @since      1.0.0
 *
 * @package    Name_Day
 * @subpackage Name_Day/public
 */

use Goutte\Client;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Name_Day
 * @subpackage Name_Day/public
 * @author     Romualdas D. <hello@prodev.lt>
 */
class Name_Day_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Name_Day_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Name_Day_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/name-day-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Name_Day_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Name_Day_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/name-day-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * @return string
     */
	public function dayName(): string
    {
        ob_start();

        $names = $this->getCachedDayNames();

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/day-name-display.php';

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    /**
     * @return array
     */
    private function getDayNames(): array
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://day.lt/');
        $names = [];

        $crawler->filter('.vardadieniai > a')->each(function($node) use (&$names) {
            $names[] = $node->text();
        });

        return $names;
    }

    /**
     * @return array
     */
    private function getCachedDayNames(): array
    {
        $data = get_transient('day_names');

        if (empty($data)) {
            $data = $this->getDayNames();
            set_transient('day_names', $data, 3600 * 24);
        }

        return $data;
    }

}
