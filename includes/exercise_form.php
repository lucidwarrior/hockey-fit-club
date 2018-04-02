<!--- Create exercise table --->
<form id="exercise_update_form" name="exercise_form" class="exercise_update" method="post" action="wp-admin/admin-ajax.php?action=b2p_update_exercise" method="post">
	<table class="eTable">
		<thead>
			<tr>
				<th colspan="5"><span class="exNumStyle"><?php echo the_sub_field('b2p_exercise_number'); ?></span> <a href="<?php echo $b2p_post_link; ?>" target="_blank"><?php the_title(); ?></a></th>
			</tr>
		</thead>
		<tbody>
			<tr><td colspan="5">Tempo: <?php the_sub_field('b2p_tempo'); ?></td></tr>
			<col width="10%">
			<col width="15%">
			<col width="45%">
			<col width="15%">
			<col width="15%">
			<tr><th>Set</th><th>Reps</th><th>INT</th><th>Rest</th><th>WT</th></tr>

			<?php
			$set_number = 0;
			if( have_rows('b2p_sets') ): ?>
      			
				<!-- loop through sets (exercise sets repeater) -->
				<?php
			
				while( have_rows('b2p_sets') ): the_row();

					// Set variable for posting weight
					$set_number = get_row_index();

				?>
				<tr>
					<td><?php echo $set_number; ?></td>
					<td><?php the_sub_field('b2p_reps'); ?></td>
					<td><?php the_sub_field('b2p_int'); ?></td>
					<td><?php the_sub_field('b2p_rest'); ?></td>
					<td><input class="set_weight_update" type="number" name="exercise_weight_<?php echo $set_number; ?>" value="<?php the_sub_field('b2p_weight'); ?>" /></td>
				</tr>
				<?php endwhile; // end looping 'b2p_sets' ?>
			<?php endif; // set list ?>
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