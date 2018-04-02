jQuery(document).ready(function ($) {
	// set default variables
	var total_weeks = $("input#week_count").val();
	var current_day = 1;
	var current_week = 1;
	var week_id = '#week-' + current_week;
	var day_id = '#week-' + current_week + '-day-' + current_day;
	
	// set default visible page to week-1 and day-1
	$('.b2p_program_week').addClass('hideWeek');
	$(week_id).removeClass('hideWeek');
	
	$('.b2p_program_day').addClass('hideDay');
	$(day_id).removeClass('hideDay');
	
	$('#1').addClass('activeDay');      // set activeDay class to day-1 menu li element
	
	// respond to Change Day menu clicks
	$('.dayLink a').click(function () {
		var current_day = $(this).closest('.dayLink').attr('id');
		var day_id = '#week-' + current_week + '-day-' + current_day;
		var current_day_li = '#' + current_day;
		
		$('.b2p_program_day').addClass('hideDay');  	// add 'hideDay' class to all "Day" content
		$('.dayLink').removeClass('activeDay');     	// removes 'activeDay' class for all "Day" menu li elements
		$(day_id).removeClass('hideDay');				// remove 'hideDay' class from clicked "Day" content
		$(current_day_li).addClass('activeDay');    	// add 'activeDay' class to clicked "Day" menu li element
	});
	
	// respond to Change Week menu clicks
	$('.weekLink a').click(function () {
		var week_direction = $(this).closest('.weekLink').attr('id');
		var current_day = 1;
		
		switch (week_direction) {
			case 'prev_week':
				if (current_week > 1) {
					current_week = current_week - 1;
				}
				var week_id = '#week-' + current_week;
				var day_id = '#week-' + current_week + '-day-' + current_day;
				$('.dayLink').removeClass('activeDay');   // removes 'activeDay' class for all "Day" menu li elements
				$('#1').addClass('activeDay');      	  // add 'activeDay' class to day-1 menu li element
				break;
			case 'next_week':
				if (current_week < total_weeks) {
					current_week = current_week + 1;
				}
				week_id = '#week-' + current_week;
				day_id = '#week-' + current_week + '-day-' + current_day;
				$('.dayLink').removeClass('activeDay');   // removes 'activeDay' class for all "Day" menu li elements
				$('#1').addClass('activeDay');      	  // add 'activeDay' class to day-1 menu li element
				break;
			default:
				break;
		}
		
		$('.b2p_program_week').addClass('hideWeek');
		$(week_id).removeClass('hideWeek');
		
		$('.b2p_program_day').addClass('hideDay');
		$(day_id).removeClass('hideDay');
		
	});
    
	

    // setup our wp ajax URL
	var wpajax_url = document.location.protocol + '//' + document.location.host + '/wp-admin/admin-ajax.php';
	
	// exercise data capture action url
	var exercise_data_url = wpajax_url + '?action=b2p_update_exercise';
	
	$('form#exercise_update_form').bind('submit',function(){
		
		// get the jquery form object
		var $form = $(this);
		
		// setup our form data for our ajax post
		var form_data = $form.serializeArray();
		
		// submit our form data with ajax
		$.ajax({
			'method':'post',
			'url':exercise_data_url,
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
