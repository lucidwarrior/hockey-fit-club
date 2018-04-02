<!--- Create exercise table --->
<table class="eTable">
	<thead>
		<tr>
			<th colspan="5"><span class="exNumStyle"><?php echo the_sub_field('b2p_exercise_number'); ?></span> <a href="<?php echo $b2p_post_link; ?>" target="_blank"><?php the_title(); ?></a></th>
		</tr>
	</thead>
	<tbody>
		<tr><td colspan="4">Tempo: <?php the_sub_field('b2p_tempo'); ?></td></tr>
		<col width="25%">
		<col width="25%">
		<col width="25%">
		<col width="25%">
		<tr><th>Set</th><th>Reps</th><th>INT</th><th>Rest</th></tr>

		<?php
		if( have_rows('b2p_sets') ): ?>

			<!-- loop through sets (exercise sets repeater) -->
			<?php
			while( have_rows('b2p_sets') ): the_row();
			?>
				<tr>
					<td><?php the_sub_field('b2p_set_number'); ?></td>
					<td><?php the_sub_field('b2p_reps'); ?></td>
					<td><?php the_sub_field('b2p_int'); ?></td>
					<td><?php the_sub_field('b2p_rest'); ?></td>
				</tr>
			<?php endwhile; // end looping 'b2p_sets' ?>

		<?php endif; // set list ?>
	</tbody>
</table>

