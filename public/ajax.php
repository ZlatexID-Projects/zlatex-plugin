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
}
