jQuery.noConflict();
jQuery(document).ready(function() {
    
    jQuery('#rs_slices-slider').slider({
        value: jQuery( "#rs_slices" ).val( ),
        min: 10,
        max: 30,
        step: 1,
        slide: function( event, ui ) {
        jQuery( "#rs_slices" ).val( ui.value );
        }
    });
    
    jQuery('#rs_boxRows-slider').slider({
        value: jQuery( "#rs_boxRows" ).val( ),
        min: 1,
        max: 20,
        step: 1,
        slide: function( event, ui ) {
        jQuery( "#rs_boxRows" ).val( ui.value );
        }
    });
    
    jQuery('#rs_boxCols-slider').slider({
        value: jQuery( "#rs_boxCols" ).val( ),
        min: 1,
        max: 20,
        step: 1,
        slide: function( event, ui ) {
        jQuery( "#rs_boxCols" ).val( ui.value );
        }
    });
    
    function setAnimstyleOptions(style) {
		jQuery("#rs_slices_box").hide();
		jQuery("#rs_slices-slider").hide();
		jQuery("#rs_boxCols_box").hide();
		jQuery("#rs_boxCols-slider").hide();
		jQuery("#rs_boxRows_box").hide();
		jQuery("#rs_boxRows-slider").hide();
		if(style == "random" || style.match(/slice/i)) {
			jQuery("#rs_slices_box").show();
			jQuery("#rs_slices-slider").show();
		}
		if(style == "random" || style.match(/box/i)) {
			jQuery("#rs_boxCols_box").show();
			jQuery("#rs_boxCols-slider").show();
			jQuery("#rs_boxRows_box").show();
			jQuery("#rs_boxRows-slider").show();
		}
	}
    
	setAnimstyleOptions(jQuery("#rs_animstyle").val());
    
    jQuery("#rs_animstyle").change(function() {
		var style = jQuery(this).val();
		setAnimstyleOptions(style);
	});
    
    jQuery('#rs_speed-slider').slider({
        value: jQuery( "#rs_speed" ).val( ),
        min: 100,
        max: 5000,
        step: 100,
        slide: function( event, ui ) {
        jQuery( "#rs_speed" ).val( ui.value );
        }
    });
    
    jQuery('#rs_timeout-slider').slider({
        value: jQuery( "#rs_timeout" ).val( ),
        min: 1,
        max: 20,
        step: 1,
        slide: function( event, ui ) {
            jQuery( "#rs_timeout" ).val( ui.value );
        }
    });
    
    jQuery('#rs_addnewimg').click( function(e) {
        e.preventDefault();
        var rs_totalimgs = jQuery('#rs_totalimgs').attr('value');
        rs_totalimgs *= 1;
        if (rs_totalimgs)
        {
            jQuery('#rs_divinside').append( '<p style="background-color:#'+ (rs_totalimgs % 2 == 1?'E0E6ED;border: 1px dashed #888':'E6EDE0') +';padding:10px;">Image ' +(rs_totalimgs + 1) + ' path:' +
                '<input type="text" name="rs_img' + rs_totalimgs + '" style="width:100%;" />' + 
                'Image ' + (rs_totalimgs + 1) + ' links to:' +
                '<input type="text" name="rs_lnk' + rs_totalimgs + '" style="width:100%;" />' +
                'Image ' + (rs_totalimgs + 1) + ' caption:' +
                '<input type="text" name="rs_cap' + rs_totalimgs + '" style="width:100%;" />' +
                '<input type="checkbox" name="rs_bnk' + rs_totalimgs + '" id="rs_bnk' + rs_totalimgs + '" value="' + rs_totalimgs + '" />'+
                '<label for="rs_bnk' + rs_totalimgs + '"><em>Open link in New Tab/Window</em></label></p>');
            jQuery('#rs_totalimgs').attr('value',rs_totalimgs + 1);
        }
    });
    
    jQuery('#rs_showdir').click(function(){
        jQuery('#rs_showhover').attr('disabled', 'disabled');
        if(jQuery(this).attr('checked') == "checked") 
           jQuery('#rs_showhover').removeAttr('disabled');
    });

    jQuery('#rs_showhover').attr('disabled', 'disabled');
    if(jQuery('#rs_showdir').attr('checked') == "checked") 
        jQuery('#rs_showhover').removeAttr('disabled');

});
            
