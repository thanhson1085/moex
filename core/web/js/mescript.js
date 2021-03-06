
(function($) {

  jQuery.fn.extend({
    slimScroll: function(options) {

      var defaults = {
        wheelStep : 20,
        width : 'auto',
        height : '250px',
        size : '7px',
        color: '#000',
        position : 'right',
        distance : '1px',
        start : 'top',
        opacity : .4,
        alwaysVisible : false,
        railVisible : false,
        railColor : '#333',
        railOpacity : '0.2',
        railClass : 'slimScrollRail',
        barClass : 'slimScrollBar',
        wrapperClass : 'slimScrollDiv',
        allowPageScroll: false,
        scroll: 0
      };

      var o = ops = $.extend( defaults , options );

      // do it for every element that matches selector
      this.each(function(){

      var isOverPanel, isOverBar, isDragg, queueHide, barHeight, percentScroll,
        divS = '<div></div>',
        minBarHeight = 30,
        releaseScroll = false,
        wheelStep = parseInt(o.wheelStep),
        cwidth = o.width,
        cheight = o.height,
        size = o.size,
        color = o.color,
        position = o.position,
        distance = o.distance,
        start = o.start,
        opacity = o.opacity,
        alwaysVisible = o.alwaysVisible,
        railVisible = o.railVisible,
        railColor = o.railColor,
        railOpacity = o.railOpacity,
        allowPageScroll = o.allowPageScroll,
        scroll = o.scroll;
      
        // used in event handlers and for better minification
        var me = $(this);

        //ensure we are not binding it again
        if (me.parent().hasClass('slimScrollDiv'))
        {
            //check if we should scroll existing instance
            if (scroll)
            {
                //find bar and rail
                bar = me.parent().find('.slimScrollBar');
                rail = me.parent().find('.slimScrollRail');

                //scroll by given amount of pixels
                scrollContent( me.scrollTop() + parseInt(scroll), false, true);
            }

            return;
        }

        // wrap content
        var wrapper = $(divS)
          .addClass( o.wrapperClass )
          .css({
            position: 'relative',
            overflow: 'hidden',
            width: cwidth,
            height: cheight
          });

        // update style for the div
        me.css({
          overflow: 'hidden',
          width: cwidth,
          height: cheight
        });

        // create scrollbar rail
        var rail  = $(divS)
          .addClass( o.railClass )
          .css({
            width: size,
            height: '100%',
            position: 'absolute',
            top: 0,
            display: (alwaysVisible && railVisible) ? 'block' : 'none',
            'border-radius': 0,
            background: railColor,
            opacity: railOpacity,
            zIndex: 90
          });

        // create scrollbar
        var bar = $(divS)
          .addClass( o.barClass )
          .css({
            background: color,
            width: size,
            position: 'absolute',
            top: 0,
            opacity: opacity,
            display: alwaysVisible ? 'block' : 'none',
            'border-radius' : 0,
            BorderRadius: 0,
            MozBorderRadius: 0,
            WebkitBorderRadius: 0,
            zIndex: 99
          });

        // set position
        var posCss = (position == 'right') ? { right: distance } : { left: distance };
        rail.css(posCss);
        bar.css(posCss);

        // wrap it
        me.wrap(wrapper);

        // append to parent div
        me.parent().append(bar);
        me.parent().append(rail);

        // make it draggable
        bar.draggable({ 
          axis: 'y', 
          containment: 'parent',
          start: function() { isDragg = true; },
          stop: function() { isDragg = false; hideBar(); },
          drag: function(e) 
          { 
            // scroll content
            scrollContent(0, $(this).position().top, false);
          }
        });

        // on rail over
        rail.hover(function(){
          showBar();
        }, function(){
          hideBar();
        });

        // on bar over
        bar.hover(function(){
          isOverBar = true;
        }, function(){
          isOverBar = false;
        });

        // show on parent mouseover
        me.hover(function(){
          isOverPanel = true;
          showBar();
          hideBar();
        }, function(){
          isOverPanel = false;
          hideBar();
        });

        var _onWheel = function(e)
        {
          // use mouse wheel only when mouse is over
          if (!isOverPanel) { return; }

          var e = e || window.event;

          var delta = 0;
          if (e.wheelDelta) { delta = -e.wheelDelta/120; }
          if (e.detail) { delta = e.detail / 3; }

          // scroll content
          scrollContent(delta, true);

          // stop window scroll
          if (e.preventDefault && !releaseScroll) { e.preventDefault(); }
          if (!releaseScroll) { e.returnValue = false; }
        }

        function scrollContent(y, isWheel, isJump)
        {
          var delta = y;

          if (isWheel)
          {
            // move bar with mouse wheel
            delta = parseInt(bar.css('top')) + y * wheelStep / 100 * bar.outerHeight();

            // move bar, make sure it doesn't go out
            var maxTop = me.outerHeight() - bar.outerHeight();
            delta = Math.min(Math.max(delta, 0), maxTop);

            // scroll the scrollbar
            bar.css({ top: delta + 'px' });
          }

          // calculate actual scroll amount
          percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
          delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

          if (isJump)
          {
            delta = y;
            var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
            bar.css({ top: offsetTop + 'px' });
          }

          // scroll content
          me.scrollTop(delta);

          // ensure bar is visible
          showBar();

          // trigger hide when scroll is stopped
          hideBar();
        }

        var attachWheel = function()
        {
          if (window.addEventListener)
          {
            this.addEventListener('DOMMouseScroll', _onWheel, false );
            this.addEventListener('mousewheel', _onWheel, false );
          } 
          else
          {
            document.attachEvent("onmousewheel", _onWheel)
          }
        }

        // attach scroll events
        attachWheel();

        function getBarHeight()
        {
          // calculate scrollbar height and make sure it is not too small
          barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
          bar.css({ height: barHeight + 'px' });
        }

        // set up initial height
        getBarHeight();

        function showBar()
        {
          // recalculate bar height
          getBarHeight();
          clearTimeout(queueHide);

          // release wheel when bar reached top or bottom
          releaseScroll = allowPageScroll && percentScroll == ~~ percentScroll;

          // show only when required
          if(barHeight >= me.outerHeight()) {
            //allow window scroll
            releaseScroll = true;
            return;
          }
          bar.stop(true,true).fadeIn('fast');
          if (railVisible) { rail.stop(true,true).fadeIn('fast'); }
        }

        function hideBar()
        {
          // only hide when options allow it
          if (!alwaysVisible)
          {
            queueHide = setTimeout(function(){
              if (!isOverBar && !isDragg) 
              { 
                bar.fadeOut('slow');
                rail.fadeOut('slow');
              }
            }, 1000);
          }
        }

        // check start position
        if (start == 'bottom') 
        {
          // scroll content to bottom
          bar.css({ top: me.outerHeight() - bar.outerHeight() });
          scrollContent(0, true);
        }
        else if (typeof start == 'object')
        {
          // scroll content
          scrollContent($(start).position().top, null, true);

          // make sure bar stays hidden
          if (!alwaysVisible) { bar.hide(); }
        }
      });
      
      // maintain chainability
      return this;
    }
  });

  jQuery.fn.extend({
    slimscroll: jQuery.fn.slimScroll
  });

})(jQuery);
var distance = 0;
var moex_distance = 0;
var price_level = 8000;
var service_type = 1;
var province = ',hà nội, việt nam';
var money_value = 0; 
var search_result = "";
var mainColor = "#0066b3";
function countMoney(){
    distance = (Math.ceil(Math.ceil(distance*10)/5)*5)/10;
    moex_distance = distance; 
	$('#moex_corebundle_meorderstype_distance').attr('value',moex_distance);
    ret = distance*price_level;
    return ret;
}

function getRoute(){
    distance = 0;
    request.origin += province;
    request.destination += province;
	var driving_distance = 0;
    request.travelMode = google.maps.DirectionsTravelMode.WALKING;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
		
        //distance = response.routes[0].legs[0].distance.value/1000;
        var routes = response.routes[0].legs;
		for (var i = 0; i < routes.length; i++) {
			distance = distance + routes[i].distance.value/1000; 
		}
		money_value = countMoney();
        $('#search-result').html(money_value);
		$('#moex_corebundle_meorderstype_roadPrice').attr('value',money_value);
        directionsDisplay.setDirections(response);
    }
    });
    request.travelMode = google.maps.DirectionsTravelMode.DRIVING;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        //driving_distance = response.routes[0].legs[0].distance.value/1000;
        var routes = response.routes[0].legs;

		for (var i = 0; i < routes.length; i++) {
			driving_distance = driving_distance + routes[i].distance.value/1000; 
		}
		if (driving_distance < distance){
			money_value = countMoney();
			$('#search-result').html(money_value);
			$('#moex_corebundle_meorderstype_roadPrice').attr('value',money_value);
			directionsDisplay.setDirections(response);
		}
    }
    });
    request.travelMode = google.maps.DirectionsTravelMode.WALKING;
}

$(document).ready(function(){
    $('.filter #btn-clear').live('click', function(){
        $('.filter form input[type="text"]').each(function(){
            $(this).attr('value','');
        });
    });
	$(".dropdown dt a").click(function() {
		var toggleId = "#" + this.id.replace(/^link/,"ul");
		$(".dropdown dd ul").not(toggleId).hide();
		$(toggleId).toggle();
		if($(toggleId).css("display") == "none"){
			$(this).removeClass("selected");
		}else{
			$(this).addClass("selected");
		}

	});

	$(".dropdown dd ul li a").click(function() {
		var text = $(this).html();
		$(".dropdown dt a span").html(text);
		$(".dropdown dd ul").hide();
	});

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass("dropdown")){
			$(".dropdown dd ul").hide();
			$(".dropdown dt a").removeClass("selected");
		}
	});
	
	$(".icon-del").click(function(){
		return confirm_delete();
	});

	$("#fade").click(function(){
		$("#fade").css("display","none");
		$("#mybox").css("display","none");
	});

	$("a.quickview").live("click",function(e){
		e.preventDefault();
		$(".quickview-list li").each(function(){
			$(this).attr("class","");
		});	
		$(this).parent().attr("class", "current");
		jQuery.post($(this).attr("href"),
		function(data){
			$('#order-info').slimScroll({
					wheelStep : 10,
					opacity : .6,
					color: mainColor,
					width: '100%',
					height: $('#mybox').height(),
					railVisible: true,
					alwaysVisible : true,
					railColor : mainColor,
		 
			}); 
			jQuery("#order-info").html(data);
		});
	});

	$("#extra-info-container > img").click(function(){
		$('#extra-info-container').fadeOut(300);
	});

	$("a.icon-quickview").click(function(e){
		e.preventDefault();
    	$.post($(this).attr("href"),
    	function(data){
        	jQuery("#mybox").html(data);
			$("#fade").fadeIn(100);
			$("#mybox").fadeIn(300);
			jQuery.post($("a.#current-quickview").attr("href"),
			function(data){
				$('#order-info').slimScroll({
						wheelStep : 10,
						opacity : .6,
						color: mainColor,
						width: '100%',
						height: $('#mybox').height(),
						railVisible: true,
						alwaysVisible : true,
						railColor : mainColor,
			 
				}); 
				jQuery("#order-info").html(data);
			});
		});
	});
    $('#loading')
    .hide()  // hide it initially
    .ajaxStart(function() {
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 
});
function confirm_delete()
{
	var agree = confirm("Are you sure you wish to continue?");
	if (agree)
		return true ;
	else
		return false ;
}
$(function() {
        $('.txt-time').datetimepicker({
            timeFormat: "hh:mm:ss",
            dateFormat: "yy-mm-dd",
        });  
    });

