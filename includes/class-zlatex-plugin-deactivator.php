<?php

/**
 * Fired during plugin deactivation
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Zlatex_Plugin
 * @subpackage Zlatex_Plugin/includes
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */
class Zlatex_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$page_id = get_option( 'archive-page' );
		wp_delete_post( $page_id );
	}

}
