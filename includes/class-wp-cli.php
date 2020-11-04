<?php
if ( defined( 'WP_CLI' ) && WP_CLI ) {

	$hello_command = function( $args, $assoc_args ) {
		list( $whatdo ) = $args;
		$date           = $assoc_args['date'];
		$importance     = $assoc_args['importance'];
		if ( $whatdo == 'delete' ) {
			WP_CLI::log( 'Deleting' );		
				WP_CLI::log( 'Deleting Posts' );
					$posts = get_posts(
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
							'tax_query'        => array(
								array(
									'taxonomy'         => 'importance',
									'field'            => 'slug',
									'terms'            => range( 0, $importance ), // Where term_id of Term 1 is "1".
									'include_children' => false,
								),
							),
						)
					);
				foreach ( $posts as $post ) {
                    if ( $date != 'mising' ) {
                        if ( get_post_meta( $post->ID, 'EventDate' )[0] <= $date ) {
                            wp_delete_post( $post->ID );
                            WP_CLI::log( 'Post with id ' . $post->ID . ' is deleted' );
                        }
                    }else{
                        wp_delete_post( $post->ID );
                        WP_CLI::log( "post deleted " . $post->ID );
                    }
				}
			
		} else {
			WP_CLI::error( 'You can only delete events' );
		}
	};

	WP_CLI::add_command(
		'old_events',
		$hello_command,
		array(
			'shortdesc' => 'Prints a greeting.',
			'synopsis'  => array(
				array(
					'type'        => 'positional',
					'name'        => 'whatdo',
					'description' => '',
					'optional'    => false,
					'repeating'   => false,
				),
				array(
					'type'        => 'assoc',
					'name'        => 'date',
					'description' => '',
					'optional'    => false,
					'default'     => 'mising',
					// 'options'     => array( 'success', 'error' ),
				),
				array(
					'type'        => 'assoc',
					'name'        => 'importance',
					'description' => 'Whether or not to greet the person with success or error.',
					'optional'    => false,
					'default'     => '0',
					'options'     => array( '1', '2', '3', '4', '5' ),
				),
			),
			'when'      => 'after_wp_load',
			'longdesc'  => '## EXAMPLES' . "\n\n" . 'wp example hello Newman',
		)
	);
}
