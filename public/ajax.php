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
    public function getlikes(){
        $likesCount = get_post_meta($_POST['postID'],'likes')[0] | 0;
        
        echo $likesCount;
    
    
        wp_die();
    }
    public function addlikes(){
        $likesCount = get_post_meta($_POST['postID'],'likes')[0] | 0;
    
        update_post_meta($_POST['postID'], 'likes', $likesCount + 1 );
    
        echo get_post_meta($_POST['postID'],'likes')[0] ;
    
        wp_die();
    }
    public function remove_likes(){
        $likesCount = get_post_meta($_POST['postID'],'likes')[0] | 0;
    
        update_post_meta($_POST['postID'], 'likes', $likesCount - 1 );
    
        echo get_post_meta($_POST['postID'],'likes')[0] ;
    
        wp_die();
    }
    public function get_events(){
        $posts = get_posts( array(
            'numberposts' => -1,
            'category'    => 0,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'include'     => array(),
            'exclude'     => array(),
            'meta_key'    => '',
            'meta_value'  =>'',
            'post_type'   => 'old_events',
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        ) );
        $events = array();
        
        foreach($posts as $post){
            $event = array(
                "post" => $post,
                "customDate" => date("d/m/Y",get_post_meta($post->ID,"EventDate")[0] ),
                "url" => get_post_permalink( $post->ID )
            );
            array_push($events,$event);
        }
        echo json_encode($events);

        wp_die();
    }
}
