jQuery(document).ready(function(jQuery) {
		jQuery('#yop-poll-edit-add-new-template-form-submit').click( function() {
				jQuery.ajax({
						type: 'POST', 
						url: yop_poll_add_new_template_config.ajax.url,
						data: 'action='+yop_poll_add_new_template_config.ajax.action+'&'+jQuery( "#yop-poll-edit-add-new-template-form" ).serialize(),
						cache: false,
						beforeSend: function() {
							jQuery('html, body').animate({scrollTop: '0px'}, 800);
							jQuery('#message').html('<p>' + yop_poll_add_new_template_config.ajax.beforeSendMessage + '</p>');
							jQuery("#message").removeClass();
							jQuery('#message').addClass('updated');
							jQuery('#message').show();  								
						},
						error: function() {
							jQuery('html, body').animate({scrollTop: '0px'}, 800);
							jQuery('#message').html('<p>' + yop_poll_add_new_template_config.ajax.errorMessage + '</p>');
							jQuery("#message").removeClass();
							jQuery('#message').addClass('error');
							jQuery('#message').show();
						}, 
						success: 
						function( data ){
							jQuery('html, body').animate({scrollTop: '0px'}, 800);
							jQuery('#message').html('<p>' + data + '</p>');
							jQuery("#message").removeClass();
							jQuery('#message').addClass('updated');
							jQuery('#message').show();
						}
				});
		});
		jQuery('#yop-poll-template-before-start-date-handler').click( function() {
			jQuery('#yop-poll-template-before-start-date-div').children('.inside').toggle('medium');
		});		
		jQuery('#yop-poll-template-after-end-date-handler').click( function() {
			jQuery('#yop-poll-template-after-end-date-div').children('.inside').toggle('medium');
		});
		jQuery('#yop-poll-template-css-handler').click( function() {
			jQuery('#yop-poll-template-css-div').children('.inside').toggle('medium');
		});
		jQuery('#yop-poll-template-js-handler').click( function() {
			jQuery('#yop-poll-template-js-div').children('.inside').toggle('medium');
		});
});		