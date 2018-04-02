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
            <?php $link = __('Add To My Workouts','duplicate-post'); ?>
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
						<div class="shortDescription"><?php the_field('gf_workout_description'); ?></div>
					<?php } elseif ( is_user_logged_in() ) { ?>
						<div class="shortDescription"><?php the_field('gf_workout_description'); ?></div>
					<?php }	else { ?>
						<div class="shortDescription"><?php the_field('gf_workout_description'); ?></div>
						<div class="progDetails"><?php the_field('gf_workout_details'); ?></div>
					<?php } ?>

					<!-- Set page permissions for MemberPress: Open Code -->
					<?php if(current_user_can('mepr_auth')): ?>
            	
                    <!--- Include Add Program Button --->
                    <?php
                    if ( ( $author_id <> $current_user_id ) && ( in_array( 'administrator', (array) $user->roles ) ) ) { ?>
                        <div class="progDuplicate"><?php duplicate_post_clone_post_link( $link, $before, $after, $prog_post_id ); ?></div>
                    <?php } ?>
                    
           		</div>
            
                
                <!-- Add "Print Page" button to exercise page --> 
                <?php $user = wp_get_current_user();
                if ( ( $author_id = $user ) && is_user_logged_in() ) { ?>

                    <div id="printPage">
                        <script language="JavaScript">
                          if (window.print) {
                          document.write('<form> '
                          + '<input type=button class=et_pb_button name=print value="Print Page" '
                          + 'onClick="javascript:window.print()"></form>');
                          }
                        </script>
                    </div>

                <?php } ?>
                
                
                
                
				<!-- Workout Menu -->
				<?php include "includes/workout_nav.php"; ?>
		
				<!-- Check for rows (workout day repeater) -->
				<?php if( have_rows('gf_workout_program') ): ?>
					
					<!-- Pass total days and weeks count to jQuery -->
					<?php
					$day_count = 0;
					while( have_rows('gf_workout_program') ): the_row();
						$day_count++;
					endwhile;
				
					$weeks = $day_count / 7;
					$workout_weeks = (int)$weeks;
					$extra_days = fmod($day_count, 7);
					?>
					
					<input type="hidden" value="<?php echo $day_count; ?>" id="day_count" />
					<input type="hidden" value="<?php echo $workout_weeks; ?>" id="workout_weeks" />
					<input type="hidden" value="<?php echo $extra_days; ?>" id="extra_day_count" />
					
					<!-- loop through rows (workout day repeater) -->
					<?php
					while( have_rows('gf_workout_program') ): the_row();
					
						// set Day and Week variables
						$day_number = get_row_index();
                        switch ($day_number) {
			                case '1':
				                $day_name = 'Monday';
                                break;
                            case '2':
                                $day_name = 'Tuesday';
                                break;
                            case '3':
                                $day_name = 'Wednesday';
                                break;
                            case '4':
                                $day_name = 'Thursday';
                                break;
                            case '5':
                                $day_name = 'Friday';
                                break;
                            case '6':
                                $day_name = 'Saturday';
                                break;
                            case '7':
                                $day_name = 'Sunday';
                                break;
                            default:
                                break;
                        }
						$week_number = ceil($day_number / 7);
						?>

						<div class="gf_workout_day" id="gf_week-<?php echo $week_number . '-day-' . $day_number; ?>">
						    <h4><span class="workoutTabWeek">Week: <?php echo $week_number; ?></span><span class="workoutTabDay">Day: <?php echo $day_name; ?></span></h4>

                            <form id="exercise_results_form" name="results_form" class="results_update" method="post" action="wp-admin/admin-ajax.php?action=gf_exercise_results" method="post">

                                <!-- check for rows (exercise repeater) -->
                                <?php
                                $exercise_number = 0;
                                if( have_rows('gf_exercises') ): ?>

                                    <!-- loop through rows (workout exercises repeater) -->
                                    <?php while( have_rows('gf_exercises') ): the_row();
                                    $exercise_number = get_row_index(); ?>

                                        <!-- get exercise post object -->
                                        <?php
                                        $post_object = get_sub_field('gf_exercise_name');
                                        if( $post_object ):

                                            // override $post
                                            $post = $post_object;
                                            setup_postdata( $post ); 

                                            // set link to exercise post
                                            $b2p_post_link = $post->guid;
                                        ?>

                                        <!-- exercise list loop output block -->
                                        <div class="gf_exercise">
                                            <table class="rTable">
                                                <tr>
                                                    <td>
                                                        <?php the_sub_field('gf_order'); ?>: <a href="<?php echo $b2p_post_link; ?>" target="_blank"><?php the_title(); ?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Instructions:</strong> <?php the_sub_field('gf_exercise_instructions'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>Results: </strong><input class="exercise_results_input" type="text" name="exercise_results_<?php echo $exercise_number; ?>" value="<?php the_sub_field('gf_exercise_results'); ?>" size="35" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>

                                        <?php endif; // post_object ?>

                                    <?php endwhile; // while( has_sub_field('gf_exercises') ): ?>

                                <?php endif; // if( have_rows('gf_workout_program') ): ?>

                                <!-- Send the Day, Exercise, and Results values -->
                                <?php if($exercise_number > 0): ?>
                                    <input type="hidden" name="program_id" value="<?php echo $prog_post_id; ?>">
                                    <input type="hidden" name="gf_day_num" value="<?php echo $day_number; ?>">
                                    <div id="submit_container">
                                        <div style="float:left;">
                                            <input type="submit" class="et_pb_button" id="workout_form_submit" name="workout_form_submit" value="Update"></div>
                                        <div style="clear:both;"></div>
                                    </div>
                                <?php endif; ?>

                            </form>
                            
						</div> <!-- workout day repeater -->
					
					<?php endwhile; // while( has_sub_field('b2p_week') ): ?>

				<?php endif; // if( have_rows('gf_workout_program') ): ?>

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


