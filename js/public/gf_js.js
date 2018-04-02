jQuery(document).ready(function ($) {
	// set default variables
	var total_weeks = $("input#workout_weeks").val();
	var current_day = 1;
	var current_week = 1;
	var day_id = '#gf_week-' + current_week + '-day-' + current_day;	// sets day_id to value of current Day content div ID
	
	$('.gf_workout_day').addClass('hideDay');				// hides all Day content
	$(day_id).removeClass('hideDay');						// unhides current Day content
	$('#1').addClass('activeDay');      					// set activeDay class to day 1 menu li element
	
	// respond to Change Day menu clicks
	$('.gf_dayLink a').click(function () {
		var day_clicked = $(this).closest('.gf_dayLink').attr('id');
		var current_day = ((current_week * 7) - 6 + parseInt(day_clicked) - 1);
		var day_id = '#gf_week-' + current_week + '-day-' + current_day;
		var current_day_li = '#' + day_clicked;
		
		$('.gf_workout_day').addClass('hideDay');			// hides all Day content
		$('.gf_dayLink').removeClass('activeDay');    		// removes css for all day menu li elements
		$(day_id).removeClass('hideDay');					// unhides clicked Day content
		$(current_day_li).addClass('activeDay');      		// add activeDay class to active day menu li element
	});
	
	// respond to Change Week menu clicks
	$('.gf_weekLink a').click(function () {
		var week_direction = $(this).closest('.gf_weekLink').attr('id');
		
		switch (week_direction) {
			case 'gf_prev_week':
				if (current_week > 1) {
					current_week = current_week - 1;
				}
				current_day = (current_week * 7 - 6);		// calculates current day from current week
				day_id = '#gf_week-' + current_week + '-day-' + current_day;
				$('.gf_dayLink').removeClass('activeDay');    	// removes css for all day menu li elements
				$('#1').addClass('activeDay');      			// set activeDay class to day 1 menu li element
				break;
			case 'gf_next_week':
				if (current_week < total_weeks) {
					current_week = current_week + 1;
				}
				current_day = (current_week * 7 - 6);		// calculates current day from current week
				day_id = '#gf_week-' + current_week + '-day-' + current_day;
				$('.gf_dayLink').removeClass('activeDay');    	// removes css for all day menu li elements
				$('#1').addClass('activeDay');      			// set activeDay class to day 1 menu li element
				break;
			default:
				break;
		}
		
		$('.gf_workout_day').addClass('hideDay');
		$(day_id).removeClass('hideDay');
		
	});
    
	
	
	
	// setup our wp ajax URL
	var wpajax_url = document.location.protocol + '//' + document.location.host + '/wp-admin/admin-ajax.php';
	
	// workout data capture action url
	var workout_data_url = wpajax_url + '?action=gf_exercise_results';
	
	$('form#exercise_results_form').bind('submit',function(){
		
		// get the jquery form object
		var $form = $(this);
		
		// setup our form data for our ajax post
		var form_data = $form.serializeArray();
		
		// submit our form data with ajax
		$.ajax({
			'method':'post',
			'url':workout_data_url,
			'data':form_data,
			'dataType':'json',
			'cache':false,
			'success': function( data, textStatus, xhr ) {
				console.log(xhr.status);
				console.log(form_data);
				if( xhr.status == 200 ) {
					// success
					// reset the form
					$form[0].reset();
					// notify the user of success
					//alert(xhr.message);
					var msg = 'Update Request Successful: Please wait for page reload.';
					alert( msg );
					//alert(data.message);
					location.reload();
				} else {
					// error
					// begin building our error message text
					msg = xhr.message + xhr.message + '\r' + xhr.error + '\r';
					// loop over the errors
					$.each(xhr.errors,function(key,value){
						// append each error on a new line
						msg += '\r';
						msg += '- '+ value;
					});
					// notify the user of the error
					alert( msg );
				}
			},
			'error': function( jqXHR, textStatus, errorThrown ) {
				// ajax didn't work
			}
			
		});
		
		// stop the form from submitting normally
		return false;
		
	});
	
});
