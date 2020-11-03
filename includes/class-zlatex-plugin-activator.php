<?php

/**
 * Fired during plugin activation
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */
class Zlatex_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$my_post = array(
			'post_title'    => wp_strip_all_tags( 'archive' ),
			'post_content'  => 'Welcome to archive page',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'page',
		);
	
		// Insert the post into the database
		$newvalue = wp_insert_post( $my_post );
		update_option( 'archive-page', $newvalue );
	}
}
