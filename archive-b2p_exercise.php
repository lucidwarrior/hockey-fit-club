<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

	<div class="exercise-container">
	
	<h1>Exercise Library</h1>
	
	<?php include 'includes/eFocusMenu.php'; ?>
	
		<div class="eContainer">
			
			<?php while ( have_posts() ) : the_post();?>
				
				<div class="eBlocks">
					<h1><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
					<div class="eBlockImage">
						<p><a href="<?php echo get_permalink(); ?>"><img src="<?php the_field('b2p_cover_image'); ?>"></a></p>
					</div>
					<div class="eBlockContents">
						<h3><?php the_field('b2p_exercise_short_description'); ?></h3>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_focus', 'Focus: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_movement', 'Movements: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_area', 'Areas: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_usage', 'Usage: ', ' / ' ); ?></p>
					</div>
					<div style="clear:both"></div>
				</div> <!-- .b2p_exercise -->
		
			<?php endwhile; ?>
			
			<div style="clear:both"></div>
			
			<!-- Pagination Menu Bar -->
			<nav class="pagination">
				<?php pagination_bar(); ?>
			</nav>
			
		</div> <!-- .eContainer -->
		
	</div> <!-- .prog-container -->
	
	<div id="b2p_footer">
		<?php
		// Add global divi footer template to CPT pages
			 echo do_shortcode('[et_pb_section global_module="1139"] [/et_pb_section]');
		?>
	</div>
	
</div> <!-- #main-content -->

<?php get_footer(); ?>