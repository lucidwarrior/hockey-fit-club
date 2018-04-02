<?php

get_header();

?>

<div id="main-content">
	<div class="container">
	<div id="content-area" class="clearfix">
	<div id="left-area">
		<div class="prog-container">
		
		<!-- Main content of exercise program -->
		<?php while ( have_posts() ) : the_post(); ?>
		
		<!-- Set program page variables -->
		<?php $author_id = get_the_author_meta( 'ID' ); ?>
		<?php $prog_post_id = get_the_ID(); ?>
		<?php $prog_url = get_permalink($prog_post_id); ?>
		<?php $current_user = wp_get_current_user(); ?>
		<?php $current_user_id = $current_user->ID; ?>
		<?php $member_name = $current_user->first_name . " " . $current_user->last_name; ?>
		<?php $link = __('Add To My Programs','duplicate-post'); ?>
		<?php $before = ''; ?>
		<?php $after = ''; ?>

		<?php //if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

			<div class="progHeader">
			
				<!--- Set up header content --->
				<div class="progTitle"><h1><?php the_title(); ?></h1></div>
				
				<?php 
				$user = wp_get_current_user();
				if ( in_array( 'administrator', (array) $user->roles ) && is_user_logged_in() ) { ?>
					<div class="shortDescription"><?php the_field('b2p_program_short_description'); ?></div>
				<?php } elseif ( is_user_logged_in() ) { ?>
					<div class="shortDescription"><?php the_field('b2p_program_short_description'); ?></div>
				<?php }	else { ?>
					<div class="shortDescription"><?php the_field('b2p_program_short_description'); ?></div>
            		<div class="progDetails"><?php the_field('b2p_program_details'); ?></div>
            	<?php } ?>
            	
           		<!-- Set page permissions for MemberPress: Open Code -->
            	<?php if(current_user_can('mepr_auth')): ?>
            	
            	<!--- Include Program Status Info --->
				<?php
				if ( $author_id == $current_user_id ) { ?>
            	<div class="progStatus">Program Status: <?php the_field('bp2_program_status'); ?></div>
            	<?php } ?>
            	
            	<!--- Include Add Program Button --->
				<?php
				if ( ( $author_id <> $current_user_id ) && ( !in_array( 'administrator', (array) $user->roles ) ) ) { ?>
					<div class="progDuplicate"><?php duplicate_post_clone_post_link( $link, $before, $after, $prog_post_id ); ?></div>
				<?php } ?>

			</div>
            
			<!-- Exercise Menu -->
			<?php include "includes/prog_nav.php"; ?>

			<!-- check for rows (program week repeater) -->
			<?php
			if( have_rows('b2p_week') ): 
			?>

				<!-- Pass total weeks count to jQuery -->
				<?php
				$week_count = 0;
				while( have_rows('b2p_week') ): the_row();
					$week_count++;
				endwhile;
				?>
				<input type="hidden" value="<?php echo $week_count; ?>" id="week_count" />

				<!-- loop through rows (program week repeater) -->
				<?php
				while( have_rows('b2p_week') ): the_row();
				$week_number = get_row_index();

				?>

				<div class="b2p_program_week" id="week-<?php echo $week_number; ?>">

					<!-- check for rows (program day repeater) -->
					<?php
					if( have_rows('b2p_day') ): 
					?>

						<!-- loop through rows (program day repeater) -->
						<?php
						while( have_rows('b2p_day') ): the_row();
						$day_number = get_row_index();

						?>

						<div class="b2p_program_day" id="week-<?php echo $week_number . '-day-' . $day_number; ?>">
						<h4>Week <?php echo $week_number ?> - Day <?php echo $day_number ?></h4>

							<!-- Add "Print Page" button to exercise page -->
							<?php $user = wp_get_current_user();
							if ( in_array( 'administrator', (array) $user->roles ) && is_user_logged_in() ) { ?>
								
							<?php } ?>

							<!-- check for rows (program exercise repeater) -->
							<div class="eContainer">

							<?php 
							if( have_rows('b2p_program_exercises') ): ?>

								<!-- loop through rows (program exercise repeater) -->
								<?php
								while( have_rows('b2p_program_exercises') ): the_row();
								$exercise_number = get_row_index();

									// get exercise post object
									$post_object = get_sub_field('b2p_exercise_name');

									if( $post_object ): 

										// override $post
										$post = $post_object;
										setup_postdata( $post ); 

										// set link to exercise post
										$b2p_post_link = $post->guid;

										?>

										<div class="b2p_exercise" id="week-<?php echo $week_number . '-day-' . $day_number . '-exercise-' . get_row_index(); ?>">

											<!--- Include Exercise Form --->
											<?php
											if ( ($author_id == $current_user_id) && is_user_logged_in() ) {
												include "includes/exercise_form.php";
											} elseif 
													( ($author_id <> $current_user_id) && is_user_logged_in() && (in_array( 'administrator', (array) $user->roles )) ) {
												include "includes/exercise_form.php";
											} else {
												include "includes/exercise_form_nologin.php";
											}
											?>

										</div>

										<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>

									<?php endif; // post_object ?>

								<?php endwhile; // end looping 'b2p_program_exercise' ?>

							<?php endif; //if( get_sub_field('b2p_program_exercise') ): ?>

							<div style="clear:both"></div>
							</div> <!-- .eContainer -->

						</div> <!-- #b2p_program_day -->
						<?php endwhile; // while( has_sub_field('b2p_day') ): ?>

					<?php endif; //if( get_sub_field('b2p_day') ): ?>

				</div> <!-- #b2p_program_week -->
				<?php endwhile; // while( has_sub_field('b2p_week') ): ?>

			<?php endif; // if( get_field('b2p_week') ): ?>

			<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
            
        <!-- Set page permissions for MemberPress: Close Code -->
        <?php else: ?>
        <?php echo do_shortcode('[mepr-unauthorized-message]'); ?>
        <?php endif; ?> 
        
		</article> <!-- .et_pb_post -->

		<?php endwhile; // end of the post loop. ?>
			
	</div> <!-- .prog-container -->
	
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


