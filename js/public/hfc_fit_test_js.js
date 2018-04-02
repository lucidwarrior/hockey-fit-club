jQuery(document).ready(function ($) {
	
	// setup our wp ajax URL
	var wpajax_url = document.location.protocol + '//' + document.location.host + '/wp-admin/admin-ajax.php';
	
	// workout data capture action url
	var fit_test_data_url = wpajax_url + '?action=hfc_update_fit_test';
	
	$('form#fit_test_results_form').bind('submit',function(){
		
		// get the jquery form object
		var $form = $(this);
		
		// setup our form data for our ajax post
		var form_data = $form.serializeArray();
		
		// submit our form data with ajax
		$.ajax({
			'method':'post',
			'url':fit_test_data_url,
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