<!--- Create exercise table --->
<button class="day_update_button">Complete</button>
<form id="exercise_update_form" name="exercise_form" class="exercise_update" method="post" action="wp-admin/admin-ajax.php?action=b2p_update_exercise" method="post">
	<?php $weeks = X; 
	if() then
	?>
	
	<table class="eTable" id="Week1">
		<tbody>
			<col width="10%">
			<col width="85%">
			<tr>
				<td><span class="exNumStyle"><?php echo the_sub_field('gf_order'); ?></span></td>
				<td><a href="<?php echo $b2p_post_link; ?>" target="_blank"><?php the_title(); ?></a></td>
			</tr>
			<tr>
				<td></td>
				<td><?php the_sub_field('gf_exercise_instructions'); ?></td>
			</tr>
			<tr>
				<td></td>
				<td><input class="results_update" type="text" name="exercise_results" value="<?php the_sub_field('gf_exercise_results'); ?>" /></td>
			</tr>
		</tbody>
	</table>
 	
 	
 	
 	
  	
   	<!-- Send the Week, Day, Exercise, and Post values -->
   	<?php if($set_number > 0): ?>
		<input type="hidden" name="program_id" value="<?php echo $prog_post_id; ?>">
		<input type="hidden" name="b2p_week_num" value="<?php echo $week_number; ?>">
		<input type="hidden" name="b2p_day_num" value="<?php echo $day_number; ?>">
		<input type="hidden" name="b2p_exercise_num" value="<?php echo $exercise_number; ?>">
		<div id="submit_container">
			<div style="float:left"><input type="submit" class="et_pb_button" id="exercise_form_submit" name="exercise_form_submit" value="Update"></div>
  			<div id="spinner" style="float:left"><img src="https://hockeyfitclub.com/wp-content/themes/divi-child-hockeyfitclub/includes/ajax-loader.gif" width="25" height="25"></div>
  			<div style="clear:both"></div>
   		</div>
    <?php endif; ?>
</form>