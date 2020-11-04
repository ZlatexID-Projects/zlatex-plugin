<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/public
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */

class Zlatex_Plugin_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->page_id     = get_option( 'archive-page' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	function load_custom_page_template( $page_template ) {

		if ( get_the_ID() == $this->page_id ) {
			$page_template = dirname( __FILE__ ) . '/partials/archive.php';
		} elseif ( is_singular( 'old_events' ) ) {
			$page_template = dirname( __FILE__ ) . '/partials/archive-post.php';
		}
		return $page_template;
	}
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zlatex_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zlatex_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zlatex-plugin-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'zlatex_default_styles', plugin_dir_url( __FILE__ ) . 'css/style.css' );
		wp_enqueue_style( 'zlatex-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', array( 'zlatex_default_styles' ) );
		global $post;
		$post_slug = $post->post_name;
		if(is_singular("old_events") || $post_slug == "archive"){
			wp_add_inline_style($this->plugin_name,get_option( 'custom-css' ));
		}

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
		 * defined in Zlatex_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zlatex_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( get_post_type() == 'old_events' ) {
			wp_enqueue_script( 'zlatex_like', plugin_dir_url( __FILE__ ) . 'js/like.js', array(), '1.0', true );

			wp_localize_script(
				'zlatex_like',
				'like',
				array(
					'url' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zlatex-plugin-public.js', $this->version, true );
		wp_localize_script(
			$this->plugin_name,
			'postsInfo',
			array(
				'showed' => get_option( 'how-much' ),
			)
		);
		wp_enqueue_script( 'zlatex_calendar', plugin_dir_url( __FILE__ ) . 'js/calendar.js', $this->version, true );
		wp_localize_script(
			'zlatex_calendar',
			'ajaxURL',
			admin_url( 'admin-ajax.php' )
		);
	}
	public function filter_the_content_in_the_main_loop( $content ) {
		if ( is_singular() && in_the_loop() && is_main_query() && get_post_type() == 'old_events' ) {
			return $content . $this->the_like() . $this->id_input();
		}

		return $content;
	}
	public function zlatex_reg_widget() {
		register_widget( 'zlatex_widget' );
	}
	private function the_like(){ ?>
		<div class="like"><a href="#">Like <span></span></a></div> 
		
		<?php
	}

	private function id_input() {
		?>
		 <input type="hidden" name="postID" id="postID" value="<?php echo get_the_ID(); ?>"> 
		 <p><?php echo date("Y-m-d", get_post_meta(get_the_ID(),"EventDate")[0]) ?></p>
		<?php
	}


	function t5_replace_content_with_excerpt( $content ) {
		if ( ! in_the_loop() ) {
			return get_the_excerpt();
		}
		return $content;
	}

}
