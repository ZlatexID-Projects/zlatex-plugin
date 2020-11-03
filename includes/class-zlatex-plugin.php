<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */
class Zlatex_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Zlatex_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'ZLATEX_PLUGIN_VERSION' ) ) {
			$this->version = ZLATEX_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'zlatex-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Zlatex_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Zlatex_Plugin_i18n. Defines internationalization functionality.
	 * - Zlatex_Plugin_Admin. Defines all hooks for the admin area.
	 * - Zlatex_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zlatex-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zlatex-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zlatex-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-zlatex-plugin-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/ajax.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/widget.php';

		$this->loader = new Zlatex_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Zlatex_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Zlatex_Plugin_i18n();

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

		$plugin_admin = new Zlatex_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );
		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'setup' );
		$this->loader->add_action('admin_menu',$plugin_admin, 'admin_menu');
		$this->loader->add_action( 'admin_init',$plugin_admin ,'register_mysettings' );
		$this->loader->add_filter( 'rest_route_for_post', 'my_plugin_rest_route_for_post', 10, 2 );
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'meta_boxes_function');
		$this->loader->add_action('init', $plugin_admin, 'register_taxonomies');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Zlatex_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'template_include',$plugin_public ,'load_custom_page_template' );
		$this->loader->add_filter('the_content', $plugin_public , 'filter_the_content_in_the_main_loop');
		$this->loader->add_filter( 'the_content',  $plugin_public, 't5_replace_content_with_excerpt', 100 );
		$this->loader->add_action('widgets_init',$plugin_public, 'zlatex_reg_widget');
		
	}

	private function ajax_hooks (){
		$plugin_ajax = new ajax();
		$this->loader->add_action( 'wp_ajax_get_likes', $plugin_ajax ,'getlikes');
		$this->loader->add_action( 'wp_ajax_add_likes', $plugin_ajax ,'addlikes' );
		$this->loader->add_action('wp_ajax_remove_likes', $plugin_ajax ,'remove_likes');
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
	 * @return    Zlatex_Plugin_Loader    Orchestrates the hooks of the plugin.
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

}
