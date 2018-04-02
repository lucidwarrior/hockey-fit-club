<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				
				<p>Category: <?php single_cat_title(); ?></p>
				
				<?php while ( have_posts() ) : the_post(); ?>
					
					<h1><?php the_title(); ?></h1>

					<h3><?php the_field('b2p_exercise_short_description'); ?></h3>
					<p><?php the_field('b2p_performance_details'); ?></p>
				
					<p><?php the_terms( $post->ID, 'category', 'Categories: ', ' / ' ); ?></p>
					<p><?php the_terms( $post->ID, 'b2p_tax_movement', 'Movements: ', ' / ' ); ?></p>
					<p><?php the_terms( $post->ID, 'b2p_tax_area', 'Areas: ', ' / ' ); ?></p>
					<p><?php the_terms( $post->ID, 'b2p_tax_usage', 'Usage: ', ' / ' ); ?></p>
					<hr>
				<?php endwhile; ?>
				
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
	
	<div id="b2p_footer">
		<?php
		// Add global divi footer template to CPT pages
			 echo do_shortcode('[et_pb_section global_module="1139"] [/et_pb_section]');
		?>
	</div>
	
</div> <!-- #main-content -->

<?php get_footer(); ?>