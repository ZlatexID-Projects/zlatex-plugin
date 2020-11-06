<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/admin
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */


class Zlatex_Plugin_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
		$this->addShortcode();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zlatex-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zlatex-plugin-admin.js', array( 'jquery' ), $this->version, true );

	}
	public function register_post_types() {
		register_post_type(
			'old_events',
			array(
				'labels'              => array(
					'name'               => 'Old events', // Основное название типа записи
					'singular_name'      => 'Old event', // отдельное название записи типа Book
					'add_new'            => 'Add event',
					'add_new_item'       => 'Add new event',
					'edit_item'          => 'Edit event',
					'new_item'           => 'New event',
					'view_item'          => 'Watch event',
					'search_items'       => 'Find event',
					'not_found'          => 'Events not found',
					'not_found_in_trash' => 'In trash events not found',
					'parent_item_colon'  => '',
					'menu_name'          => 'events',

				),
				'public'              => true,
				'show_in_rest'        => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'graphql_single_name' => 'event',
				'graphql_plural_name' => 'events',
				'show_in_graphql'     => true,
				'show_in_menu'        => true,
				'query_var'           => true,
				'rewrite'             => true,
				'capability_type'     => 'post',
				'has_archive'         => true,
				'hierarchical'        => false,
				'menu_position'       => null,
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			)
		);
		add_post_type_support( 'old_events', 'excerpt' );

	}
	public function register_taxonomies() {
		register_taxonomy(
			'importance',
			'old_events',
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'                       => 'importance',
					'singular_name'              => 'importance',
					'search_items'               => __( 'Поиск importance' ),
					'popular_items'              => __( 'Популярный importance' ),
					'all_items'                  => __( 'Все importance' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __( 'Редактировать importance' ),
					'update_item'                => __( 'Обновить importance' ),
					'add_new_item'               => __( 'Добавить новый importance' ),
					'new_item_name'              => __( 'Новое название importance' ),
					'separate_items_with_commas' => __( 'Separate importance with commas' ),
					'add_or_remove_items'        => __( 'Add or remove importance' ),
					'choose_from_most_used'      => __( 'Choose from the most used importance' ),
					'menu_name'                  => __( 'importance' ),
				),
				'capabilities'      => array(
					'assign_terms' => 'manage_options',
					'edit_terms'   => 'god',
					'manage_terms' => 'god',
				),
				'show_in_nav_menus' => false,
				'show_ui'           => true,
				'show_in_menu'      => false,

			)
		);
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( ! term_exists( strval( $i ), 'importance' ) ) {
				wp_insert_term( strval( $i ), 'importance' );
			}
		}
	}
	public function meta_boxes_function() {
	$screens = array( 'old_events' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'date',
            __( 'Date', 'zlatex-plugin' ),
            function(){
				?><input type="date" name="getDate" value="<?php 
				$post_meta = get_post_meta(get_the_ID(),"EventDate")[0];
				echo $post_meta ? date("Y-m-d", $post_meta) : date("Y-m-d") ?>"> <?php
			},
			$screen,
			'side'
        );
    }
	}
	public function post_saved($post_ID){
		if($_POST["getDate"]){
			$orgDate = $_POST["getDate"];
			update_post_meta($post_ID,"EventDate",strtotime($orgDate));
		}
	}
	public function setup() {
		add_theme_support( 'post-thumbnails' );
	}
	public function register_mysettings() {
		// whitelist options
		register_setting( 'old-events-settings-group', 'how-much' );
		register_setting( 'old-events-settings-group', 'lm-or-pg' );
		register_setting( 'old-events-settings-group', 'custom-css' );
	}
	public function admin_menu() {
		add_menu_page( 'Дополнительные настройки Events', 'Old Events', 'manage_options', 'old-events-options', 'add_my_setting', '', 4 );
		add_submenu_page( 'old-events-options', 'Generate short code', 'Short Code', 'manage_options', 'generate-short-code', 'generateShortcodePage', 1 );
		function add_my_setting() {
			?>
			<div class="wrap">
				<h2><?php echo get_admin_page_title(); ?></h2>
		
				<?php
				// settings_errors() не срабатывает автоматом на страницах отличных от опций
				if ( get_current_screen()->parent_base !== 'options-general' ) {
					settings_errors( 'old-events-options' );
				}
				?>
		
				<form action="options.php" method="POST">
				<h3><label for="how-much">skolko vivodit postov?</label></h3>
				<input type="number" name="how-much" value="<?php echo get_option( 'how-much' ) ? get_option( 'how-much' ) : 3; ?>" min="1" class="small-text" id="how-much" />
				<h3><label>Pagination or Load more?</label></h3>
		<p><input name="lm-or-pg" type="radio" value="Load More" id="lm" 
			<?php
			if ( get_option( 'lm-or-pg' ) == 'Load More' ) {
				?>
			 checked <?php } ?>><label for="lm">Load More</label> </p>
				<p><input name="lm-or-pg" type="radio" value="Pagination" id="pg" 
				<?php
				if ( get_option( 'lm-or-pg' ) == 'Pagination' ) {
					?>
					 checked <?php } ?>><label for="pg">Pagination</label></p>
					 <h3><label for="custom-css">Custom CSS</label></h3>
					 <textarea name="custom-css" id="custom-css" cols="90" rows="30"><?php echo get_option( 'custom-css' ) ?></textarea>
					<?php
						settings_fields( 'old-events-settings-group' );     // скрытые защитные поля
						do_settings_sections( 'old-events-settings-group' ); // секции с настройками (опциями).
						submit_button();
					?>
				</form>
			</div>
			<?php

		}
		function generateShortcodePage(){
			?>
			<div class="wrap">
				<h2><?php echo get_admin_page_title(); ?></h2>
		
				<?php
				// settings_errors() не срабатывает автоматом на страницах отличных от опций
				if ( get_current_screen()->parent_base !== 'options-general' ) {
					settings_errors( 'old-events-options' );
				}
				?>
		
				<form action="options.php" method="POST">
				<h3><label for="importance">Importance</label></h3>
				<input type="number" min="1" value="1" name="importance" id="importance">
				<h3><label for="posts-number">Сколько выводить постов?</label></h3>
				<input type="number" name="posts-number" id="posts-number">
				<h3><label for="from-date">FROM DATE</label></h3>
				<input type="date" name="from-date" id="from-date">
				<h3><label for="to-date">TO DATE</label></h3>
				<input type="date" name="to-date" id="to-date">
				<h3><label>FROM DATE</label></h3>
				<input style="width: 1000px;" type="text" id="shortcode" readonly value="123"> 
					<?php
						submit_button("Get Shortcode");
					?>
				</form>
			</div>
			<?php

		}
		}
	
	public function my_plugin_rest_route_for_post( $route, $post ) {
		if ( $post->post_type === 'old_events' ) {
			$route = '/wp/v2/old_events/' . $post->ID;
		}

		return $route;
	}
	public function addShortcode(){
		add_shortcode('events', function ($atts){
			$args = array(
				"post_type" => "old_events",
				"posts_per_page" => isset($atts["fromdate"]) && isset($atts["todate"]) ? -1 : $atts["last"] | 5
			);
			$q = new WP_Query($args);
			if ( $q->have_posts() && is_singular() && in_the_loop()) {
				
				while ($q->have_posts() ) {
					$q->the_post();
					$showed = false;
					if($atts["importance"]){
						foreach(get_the_terms(get_the_ID(),"importance") as $term){
							if($term->name >= $atts["importance"]){
								$showed = true;
							}
						}
					}
					$post_date = get_post_meta(get_the_ID(),"EventDate")[0];
					if(strtotime($atts["fromdate"]) <= $post_date && strtotime($atts["todate"]) >= $post_date && $showed){
						?> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php
					}else if( !$atts["fromdate"] && !$atts["todate"] && $atts["last"] && $showed){
						?> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php
					}
					

				}
			}

		});

	}

}
