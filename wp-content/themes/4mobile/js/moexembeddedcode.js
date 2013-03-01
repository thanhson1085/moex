if (typeof jQuery != 'undefined') {
 
	load_moex_code();
 
}else{
	include('http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js', function() {
		$(document).ready(function() {
			load_moex_code();
		});
	});
}

function load_moex_code(){
	var current_href = jQuery(location).attr('href');
	var current_title = jQuery(document).attr('title');
	jQuery('#moex-code').html('<iframe frameBorder="0" height="500" width="100%" src="http://m.moex.vn/embedded-code/?ordername=' + current_title + '&orderurl=' + current_href +'"></iframe>');
}
function include(filename, onload) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.src = filename;
    script.type = 'text/javascript';
    script.onload = script.onreadystatechange = function() {
        if (script.readyState) {
            if (script.readyState === 'complete' || script.readyState === 'loaded') {
                script.onreadystatechange = null;                                                  
                onload();
            }
        } 
        else {
            onload();          
        }
    };
    head.appendChild(script);
}

