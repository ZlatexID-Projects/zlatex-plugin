
<?php

add_action( 'rest_api_init', function(){

    register_rest_route( 'nice-plugin/v1', 'oldEvents-posts', [
        array(
            'methods'  => 'GET',
            'callback' => 'get_events',
        ),
        array(
            'methods'  => 'POST',
            'callback' => 'events_search',
            'permission_callback' => function () {
                return true;
            },
            'args'     => array(
                'name' => array(
                    'type'     => 'string', 
                    'required' => true,
                ),
                'from_date' => array(
                    'type'     => 'integer', 
                    'default' => 0,
                ),
                'to_date' => array(
                    'type'    => 'integer', 
                    'default' => time(),       
                ),
                'importance' => array(
                    'type'    => 'integer', 
                    'required' => true,       
                ),
            )
        ) 
    ]);
});

function get_events(){
    $old_events = new WP_Query(array(
        "post_type" => "old_events"
    ));

    $old_events_result = array();

    while($old_events->have_posts()){
        $old_events->the_post();
        $title = get_the_title();
        $persons = get_post_meta( get_the_ID(), 'boxes' )[0];
        $posts = get_post_meta( get_the_ID(), 'selected_posts' )[0];
        array_push($old_events_result,array(
            "title" => $title,
            "persons" => $persons,
            "posts" => $posts,
            "link" => get_the_permalink(),
            "date" => get_post_meta( get_the_ID(), 'EventDate' )[0]
        ));
    }

    return $old_events_result;
}

function events_search ( $request ){
    $old_events = new WP_Query(array(
        "post_type" => "old_events",
        "s" => $request["name"]
    ));

    $old_events_result = array();

    while($old_events->have_posts()){
        $old_events->the_post();
        $title = get_the_title();
        $persons = get_post_meta( get_the_ID(), 'boxes' )[0];
        $posts = get_post_meta( get_the_ID(), 'selected_posts' )[0];
        $date = get_post_meta( get_the_ID(), 'EventDate' )[0];
        $terms = wp_get_post_terms(get_the_ID(),"importance");
        foreach( $terms as $term){
            if($term->slug == $request["importance"]){
                if($request["from_date"] < $date && $request["to_date"] > $date){
                    array_push($old_events_result,array(
                        "title" => $title,
                        "persons" => $persons,
                        "posts" => $posts,
                        "link" => get_the_permalink(),
                        "date" => $date,
                    ));
                }
                
            }
        }
        
    }

    return $old_events_result;
}