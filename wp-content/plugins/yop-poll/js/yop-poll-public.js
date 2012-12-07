function yop_poll_do_vote( poll_id ) {
	jQuery.ajax({
			type: 'POST', 
			url: yop_poll_public_config.ajax.url, 
			data: 'action='+yop_poll_public_config.ajax.vote_action+'&poll_id=' + poll_id + '&' + jQuery('#yop-poll-form-'+ poll_id).serialize(), 
			cache: false,
			error: function() {
				alert('An error has occured!');
			}, 
			success: 
			function( data ){
				data		= yop_poll_extractResponse( data ); 
				response	= JSON.parse(data);
				if ( '' != response.error )
					jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
				else {
					if ( '' != response.message ) {
						jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
						jQuery('#yop-poll-container-error-'+ poll_id).html('');
						eval(
							"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
							"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
							"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
						);
					}
					else
						jQuery('#yop-poll-container-error-'+ poll_id).html('An Error Has Occured!');
				}					
			}
	});	
}

function yop_poll_view_results( poll_id ) {
	jQuery.ajax({
			type: 'POST', 
			url: yop_poll_public_config.ajax.url, 
			data: 'action='+yop_poll_public_config.ajax.view_results_action+'&poll_id=' + poll_id, 
			cache: false,
			error: function() {
				alert('An error has occured!');
			}, 
			success: 
			function( data ){
				data		= yop_poll_extractResponse( data );
				response	= JSON.parse(data);
				if ( '' != response.error )
					jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
				else { 
					if ( '' != response.message ) {
						jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
						jQuery('#yop-poll-container-error-'+ poll_id).html('');
						eval(
							"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
							"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
							"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
						);
					}
					else
						jQuery('#yop-poll-container-error-'+ poll_id).replaceWith('An Error Has Occured!');
				}					
			}
	});	
}

function yop_poll_back_to_vote( poll_id ) {
	jQuery.ajax({
			type: 'POST', 
			url: yop_poll_public_config.ajax.url, 
			data: 'action='+yop_poll_public_config.ajax.back_to_vote_action+'&poll_id=' + poll_id, 
			cache: false,
			error: function() {
				alert('An error has occured!');
			}, 
			success: 
			function( data ){
				data		= yop_poll_extractResponse( data );
				response	= JSON.parse(data);
				if ( '' != response.error )
					jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
				else {
					if ( '' != response.message ) {
						jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
						jQuery('#yop-poll-container-error-'+ poll_id).html('');
						eval(
							"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
							"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
							"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
						);
					}
					else
						jQuery('#yop-poll-container-error-'+ poll_id).html('An Error Has Occured!');
				}					
			}
	});	
}

function yop_poll_extractResponse( str ) {
	var patt	= /\[ajax-response\](.*)\[\/ajax-response\]/m;
	resp 		= str.match( patt )
	return resp[1];
}
