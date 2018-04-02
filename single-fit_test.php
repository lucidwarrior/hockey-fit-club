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
                <?php $current_user = wp_get_current_user();?>
                <?php $current_user_id = $current_user->ID; ?>
                <?php $member_name = $current_user->first_name . " " . $current_user->last_name; ?>
                <?php $link = __('Add To My Active Programs','duplicate-post'); ?>
                <?php $before = ''; ?>
                <?php $after = ''; ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

                    <div class="progHeader">

                        <!--- Set up page header content --->
                        <div class="progTitle"><h1><?php the_title(); ?></h1></div>

                    </div>
                    
                    <!--- Include Add Program Button --->
                    <?php
                    if ( ( $author_id <> $current_user_id ) ) { ?>
                        <div class="progDuplicate"><?php duplicate_post_clone_post_link( $link, $before, $after, $prog_post_id ); ?></div>
                    <?php } ?>
                   
                    <div class="fit_test_form">

                        <form id="fit_test_results_form" name="fit_test_results_form" class="fit_test_results_update" method="post">

                            <?php
                            // setup variables:
                            $row_index = 0;
                            $has_tests = false;

                            // get raw date
                            $start_date = get_field('test_start_date', false, false);
                            $end_date = get_field('test_end_date', false, false);

                            // make date object
                            $start_date = new DateTime($start_date);
                            $end_date = new DateTime($end_date);
                            ?>

                            <!--- Set up form header content --->
                            <div id="fit_test_header">
                                <div id="fit_test_info">
                                    <strong>Performance Test Instructions:</strong><br>
                                    <?php the_field('performance_test_instructions'); ?>
                                </div>
                                <div id="date_container">
                                    <div id="fit_test_start_date">
                                        <strong>Start Date: </strong>
                                        <input class="fit_test_date_input" type="date" name="test_start_date" value="<?php echo $start_date->format('Y-m-d'); ?>" />
                                    </div>
                                    <div id="fit_test_end_date">
                                        <strong>End Date: </strong>
                                        <input class="fit_test_date_input" type="date" name="test_end_date" value="<?php echo $end_date->format('Y-m-d'); ?>" />
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>

                            <!--- Movement Performance Form Content --->
                            <div class="movement_perf_content">

                                <div class="fit_test_section_title"><h2>Movement Exercises</h2></div>

                                <!-- check for rows (fit-test movement performance rows) -->
                                <?php
                                if( have_rows('movement_performance_tests') ):
                                    $has_tests = true;
                                ?>

                                    <!-- loop through rows (fit-test movement performance rows) -->
                                    <?php while( have_rows('movement_performance_tests') ): the_row(); ?>

                                        <?php  // vars

                                            $video_link = get_sub_field('movement_video_id');
                                            $checked = '';
                                            $can_you_perform = get_sub_field('can_you_perform', false);
                                            if ( $can_you_perform == 'yes' )
                                            {
                                                /*

                                                    You'll need to set the attribute 'checked' in order to reflect
                                                    the fact that this field has a value set.

                                                */
                                                $checked = ' checked';
                                            }

                                        ?>

                                        <!--- Movement test loop --->
                                        <div class="movement_perf_details">
                                            <strong>Test #<?php echo $row_index+1; ?>: </strong>
                                            <?php the_sub_field('movement_exercise_name'); ?>
                                            <span class="video_link"><?php echo do_shortcode("[video_lightbox_youtube video_id='{$video_link}' width='640' height='480' anchor='See Demo']"); ?></span>
                                            <strong>Can You Perform Movement?</strong>
                                            <input type="checkbox" name="can_you_perform[]" value="<?php echo $row_index; ?>" <?php echo $checked; ?>/>
                                        </div>

                                        <?php
                                        $row_index++;
                                        ?>

                                    <?php endwhile; ?><!-- end loop through rows (fit-test movement performance rows) -->
                                    
                                <?php endif; ?><!-- endif for rows (fit-test movement performance rows) -->

                            </div>


                            <!--- Maximum Performance Form Content --->
                            <div class="max_perf_content">

                                <div class="fit_test_section_title"><h2>Maximum Performance Exercises</h2></div>

                                <!-- check for rows (fit-test maximum performance rows) -->
                                <?php if( have_rows('maximum_performance_tests') ): ?>

                                    <!-- loop through rows (fit-test maximum performance rows) -->
                                    <?php $max_test_number = 0; ?>
                                    <?php while( have_rows('maximum_performance_tests') ): the_row(); ?>

                                        <?php  // vars
                                        $max_test_number++;
                                        $max_video_link = get_sub_field('maximum_performance_video_id');
                                        ?>

                                        <div class="max_perf_details">
                                            <strong>Test #<?php echo $max_test_number; ?>: </strong>
                                            <?php the_sub_field('max_performance_exercise_name'); ?>
                                            <span class="video_link"><?php echo do_shortcode("[video_lightbox_youtube video_id='{$max_video_link}' width='640' height='480' anchor='See Demo']"); ?></span><br>
                                            <strong>Instructions</strong>
                                            <?php the_sub_field('maximum_performance_test_instructions'); ?>
                                            
                                            <div id="fit_test_value_container">
                                                <div id="fit_test_start_value">
                                                    <strong>Starting Value: </strong><input class="fit_test_results_input" type="text" name="starting_value[]" value="<?php the_sub_field('starting_value'); ?>" size="20" />
                                                </div>

                                                <div id="fit_test_end_value">
                                                    <strong>Ending Value: </strong><input class="fit_test_results_input" type="text" name="ending_value[]" value="<?php the_sub_field('ending_value'); ?>" size="20" />
                                                </div>
                                            </div>
                                            
                                        </div>

                                    <?php endwhile; ?><!-- end loop through rows (fit-test maximum performance rows) -->

                                <?php endif; ?><!-- endif check for rows (fit-test maximum performance rows) -->

                            </div>
                            
                            <div id="coach-notes">
                                <strong>Coach Performance Notes</strong><br>
<!--                                <input class="fit_test_coach_notes" type="textarea" rows="4" cols="50" name="coach_notes" value="<?php the_field('coach_notes'); ?>" />-->
                                <textarea form ="fit_test_results_form" name="coach_notes" id="coach_notes" cols="40" wrap="soft"><?php the_field('coach_notes'); ?></textarea>
                            </div>

                            <!-- Send the Day, Exercise, and Results values -->
                            <?php if($has_tests): ?>
                                <input type="hidden" name="program_id" value="<?php the_ID(); ?>">
                                <div id="submit_container">
                                    <div style="float:left;">
                                        <input type="submit" class="et_pb_button" id="fit_test_form_submit" name="fit_test_form_submit" value="Update"></div>
                                    <div style="clear:both;"></div>
                                </div>
                            <?php endif; ?>

                        </form>

                     </div>
                        
                        <?php //the_content(); ?>

                        <?php //acf_form(); ?>

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
