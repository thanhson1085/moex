jQuery(document).ready(function(jQuery) {
		jQuery( "#yop-poll-allow-other-answers-yes" ).click( function () {
				jQuery( '#yop-poll-other-answers-label-div' ).show();
				jQuery( '#yop-poll-display-other-answers-values-div' ).show();
		});
		jQuery( "#yop-poll-allow-other-answers-no" ).click( function () {
				jQuery( '#yop-poll-other-answers-label-div' ).hide();
				jQuery( '#yop-poll-display-other-answers-values-div' ).hide();
		});		
		jQuery( "#yop-poll-display-answers-vertical" ).click( function () {
				jQuery( '#yop-poll-display-answers-tabulated-div' ).hide();
		}); 		
		jQuery( "#yop-poll-display-answers-orizontal" ).click( function () {
				jQuery( '#yop-poll-display-answers-tabulated-div' ).hide();
		});
		jQuery( "#yop-poll-display-answers-tabulated" ).click( function () {
				jQuery( '#yop-poll-display-answers-tabulated-div' ).show();
		});
		jQuery( "#yop-poll-display-results-vertical" ).click( function () {
				jQuery( '#yop-poll-display-results-tabulated-div' ).hide();
		});
		jQuery( "#yop-poll-display-results-orizontal" ).click( function () {
				jQuery( '#yop-poll-display-results-tabulated-div' ).hide();
		});
		jQuery( "#yop-poll-display-results-tabulated" ).click( function () {
				jQuery( '#yop-poll-display-results-tabulated-div' ).show();
		});
		jQuery( "#yop-poll-allow-multiple-answers-yes" ).click( function () {
				jQuery( '#yop-poll-allow-multiple-answers-div' ).show();
		});
		jQuery( "#yop-poll-allow-multiple-answers-no" ).click( function () {
				jQuery( '#yop-poll-allow-multiple-answers-div' ).hide();
		});
		jQuery( ".yop-poll-view-results-hide-custom" ).click( function () {
				jQuery( '#yop-poll-display-view-results-div' ).hide();
		});
		jQuery( ".yop-poll-view-results-show-custom" ).click( function () {
				jQuery( '#yop-poll-display-view-results-div' ).show();
		});
		jQuery( ".yop-poll-blocking-voters-hide-interval" ).click( function () {
				jQuery( '#yop-poll-blocking-voters-interval-div' ).hide();
		});
		jQuery( ".yop-poll-blocking-voters-show-interval" ).click( function () {
				jQuery( '#yop-poll-blocking-voters-interval-div' ).show(); 
		});
		jQuery( "#yop-poll-view-results-link-no" ).click( function () {
				jQuery( '#yop-poll-view-results-link-div' ).hide();
		});
		jQuery( "#yop-poll-view-results-link-yes" ).click( function () {
				jQuery( '#yop-poll-view-results-link-div' ).show();
		});
		
		jQuery( "#yop-poll-view-back-to-vote-link-no" ).click( function () {
				jQuery( '#yop-poll-view-back-to-vote-link-div' ).hide();
		});
		jQuery( "#yop-poll-view-back-to-vote-link-yes" ).click( function () {
				jQuery( '#yop-poll-view-back-to-vote-link-div' ).show();
		});
		
		jQuery( "#yop-poll-view-total-votes-no" ).click( function () {
				jQuery( '#yop-poll-view-total-votes-div' ).hide();
		});
		jQuery( "#yop-poll-view-total-votes-yes" ).click( function () {
				jQuery( '#yop-poll-view-total-votes-div' ).show();
		});
		
		jQuery( "#yop-poll-view-total-voters-no" ).click( function () {
				jQuery( '#yop-poll-view-total-voters-div' ).hide();
		});
		jQuery( "#yop-poll-view-total-voters-yes" ).click( function () {
				jQuery( '#yop-poll-view-total-voters-div' ).show();
		});
		
		jQuery( "#yop-poll-view-poll-archive-link-no" ).click( function () {
				jQuery( '#yop-poll-view-poll-archive-link-div' ).hide();
		});
		jQuery( "#yop-poll-view-poll-archive-link-yes" ).click( function () {
				jQuery( '#yop-poll-view-poll-archive-link-div' ).show();
		});
		
		jQuery( "#yop-poll-show-in-archive-no" ).click( function () {
				jQuery( '#yop-poll-show-in-archive-div' ).hide();
		});
		jQuery( "#yop-poll-show-in-archive-yes" ).click( function () {
				jQuery( '#yop-poll-show-in-archive-div' ).show();
		});
		
		jQuery( "#yop-poll-use-template-bar-no" ).click( function () {
				jQuery( '.yop-poll-custom-result-bar-table' ).show();
		});
		jQuery( "#yop-poll-use-template-bar-yes" ).click( function () {
				jQuery( '.yop-poll-custom-result-bar-table' ).hide();
		});
		
		jQuery( "#yop-poll-never-expire" ).click( function () {
			if ( true == jQuery( this ).is(':checked') ) {
				jQuery( "#yop-poll-end-date-input" ).attr("disabled", "disabled");
				jQuery( "#yop-poll-end-date-input" ).hide();
			}
			else {
				jQuery( "#yop-poll-end-date-input" ).removeAttr("disabled", "disabled");
				jQuery( "#yop-poll-end-date-input" ).show();
			}
		});
		jQuery ( "#message").hide();

		var yopPollStartDateTextBox = jQuery('#yop-poll-start-date-input');
		var yopPollEndDateTextBox = jQuery('#yop-poll-end-date-input');
		var yopPollViewResultStartDateTextBox = jQuery('#yop-poll-view-results-start-date').datetimepicker({
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			dateFormat: 'yy-mm-dd'}
		);

		yopPollStartDateTextBox.datetimepicker({ 			
				onClose: function(dateText, inst) {
					if (yopPollEndDateTextBox.val() != '') {
						var testStartDate = yopPollStartDateTextBox.datetimepicker('getDate');
						var testEndDate = yopPollEndDateTextBox.datetimepicker('getDate');
						if (testStartDate > testEndDate)
							yopPollEndDateTextBox.datetimepicker('setDate', testStartDate);
					}
					else {
						yopPollEndDateTextBox.val(dateText);
					}
				},
				onSelect: function (selectedDateTime){
					yopPollEndDateTextBox.datetimepicker('option', 'minDate', yopPollStartDateTextBox.datetimepicker('getDate') );
				}, 
				showSecond: true,
				timeFormat: 'hh:mm:ss',
				dateFormat: 'yy-mm-dd'
		});
		yopPollEndDateTextBox.datetimepicker({ 
				onClose: function(dateText, inst) {
					if (yopPollStartDateTextBox.val() != '') {
						var testStartDate = yopPollStartDateTextBox.datetimepicker('getDate');
						var testEndDate = yopPollEndDateTextBox.datetimepicker('getDate');
						if (testStartDate > testEndDate)
							yopPollStartDateTextBox.datetimepicker('setDate', testEndDate);
					}
					else {
						yopPollStartDateTextBox.val(dateText);
					}
				},
				onSelect: function (selectedDateTime){
					yopPollStartDateTextBox.datetimepicker('option', 'maxDate', yopPollEndDateTextBox.datetimepicker('getDate') );
				},
				showSecond: true,
				timeFormat: 'hh:mm:ss',
				dateFormat: 'yy-mm-dd'
		}); 
		
});

function yop_poll_update_bar_style( obj, property, value ) {
	if( 
		'background-color' == property || 
		'height' == property || 
		'border-color' == property || 
		'border-width' == property || 
		'border-style' == property ) {
		if( jQuery( obj ).length > 0 )
			{
			if( '' != value )
				jQuery( obj ).css( property , value );
		}	
	}
}