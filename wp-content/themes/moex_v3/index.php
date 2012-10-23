<?php get_header() ?>
        <div id="leftHomepage">
            <div class="fwb pb6">Điểm đi</div>
            <div class="pb10">
                <input id="input-from" type="text" class="textbox2" value="Số 2, Khâm Thiên, Đống Đa, Hà Nội"/>
            </div>
            <div class="fwb pb6">Điểm đến</div>
            <div class="pb17">
                <input id="input-to" type="text" class="textbox2" value="Số 1, Trần Duy Hưng, Cầu Giấy, Hà Nội"/>
            </div>
            <div class="fl fwb">Giá: <span id="search-result">45.000</span> VNĐ</div>            
            <div class="fr">
                <input id="search-submit" type="submit" value="Go" class="btGo" title="Click để tìm kiếm"/> 
            </div>
            <div class="cb pt17 pb10">
				<div id="map"></div>
            </div>
        </div>
        <div id="rightHomepage">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/adv1.jpg" class="anhQC"/></a>                
            </div>
            <div id="ListService">
                <div class="service bdt0">
                    <a class="svname cname0" href="<?php echo get_bloginfo("url");?>?page_id=36" title="An toàn">&nbsp;</a>
                    <a href="<?php echo get_bloginfo("url")?>?page_id=36">
                        <img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/service/p1.jpg" class="spic"/>
                    </a>
                    <div class="sdesc">
						<?php
						$antoan_id = 5;
						$post_intro = get_post($antoan_id);?>
						<?php echo $post_intro->post_content;?>
                    </div>
                    <div class="sbottomleft"><!----></div>
                    
                </div>
                <div class="service bdt1">
                    <a class="svname cname1" href="<?php echo get_bloginfo("url")?>?page_id=36" title="Tiện lợi">&nbsp;</a>
                    <a href="<?php echo get_bloginfo("url")?>?page_id=36">
                        <img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/service/p2.jpg" class="spic"/>
                    </a>
                    <div class="sdesc">
						<?php
						$tienloi_id = 7; 
						$post_intro = get_post($tienloi_id);?>
						<?php echo $post_intro->post_content;?>
                    </div>
                    <div class="sbottomleft"><!----></div>
                    
                </div>
                <div class="service bdt2">
                    <a class="svname cname2" href="<?php echo get_bloginfo("url")?>?page_id=36" title="Chu đáo">&nbsp;</a>
                    <a href="<?php echo get_bloginfo("url")?>?page_id=36">
                        <img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/service/p3.jpg" class="spic"/>
                    </a>
                    <div class="sdesc">
						<?php
						$chudao_id = 10;
						$post_intro = get_post($chudao_id);?>
						<?php echo $post_intro->post_content;?>
                    </div>
                    <div class="sbottomleft"><!----></div>
                    
                </div>
                <div class="cb h10"><!----></div>
            </div>

            <div id="footerAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/adv2.jpg" class="anhQC"/></a>                
            </div>
        </div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">
var province = ', Hà Nội, Việt Nam';
var request = {
    origin: '219 KHÂM THIÊN',
    destination: '99 PHỐ HUẾ',
    travelMode: google.maps.DirectionsTravelMode.WALKING
};
var distance = 0;
var submit_click = false;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
var oldDirections = [];
var currentDirections = null;
$(document).ready(function(){
    $('input[type="text"]').live('click', function(){
        $(this).select();
    });
    var inputFrom = document.getElementById('input-from');
    var inputTo = document.getElementById('input-to');
    var options = {
      types: ['geocode'],
      componentRestrictions: {country: 'vn'}
    };
    autocomplete = new google.maps.places.Autocomplete(inputFrom, options);
    autocomplete = new google.maps.places.Autocomplete(inputTo, options);
    var myOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay = new google.maps.DirectionsRenderer({
        'draggable': true
    });
              
   directionsDisplay.setMap(map);
    $("#search-from").html(request.origin);
    $("#search-to").html(request.destination)
    if ($('#input-from').val() != ""){
        request.origin = $('#input-from').val();
    }
    if ($('#input-to').val() != ""){
        request.destination = $('#input-to').val();
    }
    getRoute();
    $("#search-submit").click(function(){
        submit_click = true;
        request.origin = $('#input-from').attr('value');
        request.destination = $('#input-to').attr('value');
		$(location).attr("href",'<?php echo get_bloginfo("url");?>?page_id=191&from='+request.origin+'&to='+request.destination);
        getRoute();
    });

    $('#input-to').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                submit_click = true;
                request.origin = $('#input-from').attr('value');
                request.destination = $('#input-to').attr('value');
                getRoute();
            }
        }
    });
    $('#input-from').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                submit_click = true;
                request.origin = $('#input-from').attr('value');
                request.destination = $('#input-to').attr('value');
                getRoute();
            }
        }
    });

    google.maps.event.addListener(directionsDisplay, 'directions_changed',
    function() {
        if (currentDirections) {
            var rleg = directionsDisplay.directions.routes[0].legs[0];
            distance = rleg.distance.value;
            distance = rleg.distance.value;
            money_value = countMoney();
            $('#search-result').html(money_value);
            $('#input-from').attr('value',rleg.start_address);
            $('#input-to').attr('value',rleg.end_address);
        }
        currentDirections = directionsDisplay.getDirections();
    });
});
</script>

<?php
	get_footer();
?>