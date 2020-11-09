<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zlatexdev.me
 * @since      1.0.0
 *
 * @package    Zlatex_Plugin
 */

/**
 *
 * @package    Zlatex_Plugin
 * @author     Zlatex <maximzlatogorsky@gmail.com>
 */
class ajax {
	public function get_persons() {

		$persons = get_post_meta( $_POST['id'], 'boxes' )[0];

		wp_send_json( $persons );

		wp_die();
	}
	public function add_post_to_event() {

		$persons = get_post_meta( $_POST['id'], 'selected_posts' )[0];
		if ( ! isset( $persons ) ) {
			$persons = array(
				array(
					'post_id'    => $_POST['post_id'],
					'post_title' => $_POST['post_title'],
				),
			);
		}else {
            array_push($persons, array(
                'post_id'    => $_POST['post_id'],
                'post_title' => $_POST['post_title'],
            ));
        }
        update_post_meta($_POST['id'], 'selected_posts',$persons);
        wp_send_json_success($persons,200);
		// wp_send_json($persons);
		wp_die();
    }
    public function remove_post_from_event (){
        $persons = get_post_meta( $_POST['id'], 'selected_posts' )[0];
        $new_persons = array();
        foreach($persons as $person){
            if($person["post_id"] == $_POST['post_id']) continue;
            else{
                array_push($new_persons, array(
                    'post_id'    => $person['post_id'],
                    'post_title' => $person['post_title'],
                ));
            }
        }
        update_post_meta($_POST['id'], 'selected_posts',$new_persons);
        wp_send_json_success($new_persons,200);
		// wp_send_json($persons);
		wp_die();
    }
	public function getlikes() {
		$likesCount = get_post_meta( $_POST['postID'], 'likes' )[0] | 0;

		echo $likesCount;

		wp_die();
	}
	public function addlikes() {
		$likesCount = get_post_meta( $_POST['postID'], 'likes' )[0] | 0;

		update_post_meta( $_POST['postID'], 'likes', $likesCount + 1 );

		echo get_post_meta( $_POST['postID'], 'likes' )[0];

		wp_die();
	}
	public function remove_likes() {
		$likesCount = get_post_meta( $_POST['postID'], 'likes' )[0] | 0;

		update_post_meta( $_POST['postID'], 'likes', $likesCount - 1 );

		echo get_post_meta( $_POST['postID'], 'likes' )[0];

		wp_die();
	}
	public function get_events() {
		$posts  = get_posts(
			array(
				'numberposts'      => -1,
				'category'         => 0,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => array(),
				'exclude'          => array(),
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => 'old_events',
				'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
			)
		);
		$events = array();

		foreach ( $posts as $post ) {
			$event = array(
				'post'       => $post,
				'customDate' => date( 'd/m/Y', get_post_meta( $post->ID, 'EventDate' )[0] ),
				'url'        => get_post_permalink( $post->ID ),
			);
			array_push( $events, $event );
		}
		echo json_encode( $events );

		wp_die();
	}
}
