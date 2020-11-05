<?php
if(!$_GET["date"]){
$args       = array(
	'post_type' => 'old_events',
	'posts_per_page' => get_option('how-much'),
	'paged' => get_query_var('paged') && get_option('lm-or-pg') == "Pagination" ? get_query_var('paged') : 1
);
}else{
	$args       = array(
		'post_type' => 'old_events',
		'posts_per_page' => -1,

	);	
}

$old_events = new WP_Query($args);
get_header(); ?>
<div class="wrap">
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php else : ?>
	<header class="page-header">
		<h2 class="page-title"><?php _e( 'Posts', 'twentyseventeen' ); ?></h2>
	</header>
	<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			
			if ( $old_events->have_posts() ) :

				// Start the Loop.
				while ($old_events->have_posts() ) :
					
					$old_events->the_post();
					if($_GET["date"]){
						if(strtotime($_GET["date"]) == get_post_meta(get_the_ID(),"EventDate")[0]){
							zl_get_template_part( 'template-parts/post/content', get_post_format() );
						}
					}else{
						zl_get_template_part( 'template-parts/post/content', get_post_format() );
					}
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that
					 * will be used instead.
					 */
					

				endwhile;
				if(!$_GET["date"]){
				?>	
			
			<p class="pagination"><?php 
			if(get_option('lm-or-pg')== "Pagination"){
			$big = 999999999;
				echo paginate_links( array(
					'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $old_events->max_num_pages
				) );
				?></p><?php }else { ?>
					<a href="#" id="loadMore">Load More</a>
				<?php }
				}
			wp_reset_postdata();

			else :

				zl_get_template_part( 'template-parts/post/content', 'none' );

			endif;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->


<?php

get_footer();
// if($_GET["date"]){
	?>
	<script>
	document.body.classList.add("has-sidebar","blog")
	</script>
	<?php
// }
