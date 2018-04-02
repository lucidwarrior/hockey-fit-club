<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

	<div class="prog-container">
	
	<h1>Workout Library</h1>
	
	<?php include 'includes/ProgramMenu.php'; ?>
	
		<div class="eContainer material">
			
			<?php while ( have_posts() ) : the_post();?>
				
				<div class="eBlocks">
					<div id="program_title">
						<h1><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
					</div>
					<div id="program_overlay">
						<div class="progContainer">
							<?php the_post_thumbnail(); ?>
							<div class="progOverlay">
								<div class="progText">
									<h1><?php the_title(); ?></h1>
								</div>
							</div>
						</div>
					</div>
					<div id="program_button">
						<p><a class="et_pb_button" href="#">Button Text</a></p>
					</div>
					<div id="program_text">
						<h3><?php the_field('b2p_program_short_description'); ?></h3>
					</div>
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