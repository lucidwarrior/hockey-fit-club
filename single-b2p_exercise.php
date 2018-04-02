<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

				<?php while ( have_posts() ) : the_post(); ?>
					
				<div id="eHeader">
					<h1><?php the_title(); ?></h1>
					<div class="eBlockImage">
						<p><img src="<?php the_field('b2p_cover_image'); ?>"></p>
					</div>
					<div class="eBlockContents">
						<h3><?php the_field('b2p_exercise_short_description'); ?></h3>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_focus', 'Focus: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_movement', 'Movements: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_area', 'Areas: ', ' / ' ); ?></p>
						<p class="taxList"><?php the_terms( $post->ID, 'b2p_tax_usage', 'Usage: ', ' / ' ); ?></p>
						<div style="padding-top: 30px;">
							<?php $video_object = get_field('b2p_video_link');
								if( $video_object or have_rows('b2p_performance_steps')): ?>
								<h3 class="taxList">Instructions</h3>
								<p><?php the_field('b2p_performance_details'); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				<hr>
				<div>
					<?php the_field('b2p_video_link'); ?>
				</div>
				
				<div class="pSteps">
					<!-- loop through performance steps -->
					<?php if( have_rows('b2p_performance_steps') ): ?>

						<?php while( have_rows('b2p_performance_steps') ): the_row(); ?>
							<div id="eHeader">
								<div class="eBlockImage">
									<p><img src="<?php the_sub_field('performance_step_image'); ?>"></p>
								</div>
								<div class="eBlockContents">
									<h3><?php the_sub_field('performance_step_title'); ?></h3>
									<p><?php the_sub_field('performance_step_description'); ?></p>
								</div>
								<div style="clear:both"></div>
							</div>
							<hr>
						<?php endwhile; // while( have_rows('b2p_performance_steps') ): ?>

					<?php endif; //if( have_rows('b2p_performance_steps') ): ?>

				</div>

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