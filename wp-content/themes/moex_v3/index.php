<?php get_header() ?>
<?php
if ( function_exists( 'get_smooth_slider' ) ) { get_smooth_slider(); }
?>
        <div id="leftHomepage">
            <div class="fwb pb6"></div>
            <div class="pb10">
                <input id="input-from" type="text" class="textbox2" value="" placeholder="Điểm đi..."/>
            </div>
            <div class="fwb pb6"></div>
            <div class="pb10">
                <input id="input-to" type="text" class="textbox2" value="" placeholder="Điểm đến..." />
            </div>
            <div class="pb17">
                    <div id="khungddl">
                        <div class="cb h2"><!----></div>
                        <select id="ddlDichVu" name="ddlDichVu" onchange="FillBGColor('khungddl')">
                            <option style="background:#6b7b84" value="1">Moex Delivery</option>
                            <option style="background:#c8215d" value="2">Moex Go</option>
                            <option style="background:#8dc63f" value="3">Moex Food</option>
                            <option style="background:#f26522" value="4">Moex Shopping</option>
                            <option style="background:#20409a" value="5">Moex School</option>
                        </select>
                        <script type="text/javascript">
                            function FillBGColor(parrentId) {
                                document.getElementById(parrentId).style.backgroundColor = ddlDichVu.options[ddlDichVu.selectedIndex].style.backgroundColor;                                
                            }
                        </script>
                    </div>
			</div>
            <div class="fl fwb">Giá: <span id="search-result"></span></div>            
            <div class="fr">
                <input id="search-submit" type="submit" value="Go" class="btGo" title="Click để tìm kiếm"/> 
            </div>
            <div class="cb pt17 pb10">
				<div id="map"></div>
            </div>
        </div>
        <div id="rightHomepage">
            <div id="mainAdv">
				<div id="metaContainer">
					<!-- The sliderr works with virtually any HTML element (div, span etc) but for the sake of simplicity I have used images in this demo -->
					<div id="slideContainer">
						<div id="slideShim">
							<a href="<?php echo get_bloginfo("url");?>/xmas/"><img src="<?php echo get_bloginfo("template_url")?>/pic/home_xmas.jpg" alt="moEx Xmas" /></a>
							<a href="<?php echo get_bloginfo("url");?>/event/"><img src="<?php echo get_bloginfo("template_url")?>/pic/banner_ticket.jpg" alt="Slide Two" /></a>
							<a href="<?php echo get_bloginfo("url");?>/order-search"><img src="<?php echo get_bloginfo("template_url")?>/pic/banner_8000.jpg" alt="Slide One" /></a>
						</div>
					</div>
					<div id="pager" class="clear"></div>
				</div>

            </div>
<script type="text/javascript">
$(document).ready(function(){
    $('#slideShim').cycle({
            fx:     'fade',
            speed:  500,
            timeout: 5000,
            prev:   '#back',
            next:   '#forward',
            pause:  1,
            pager:  '#pager'
        });
});
</script>

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
                <a href="<?php echo get_bloginfo("url")?>?page_id=2"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/adv2.jpg" class="anhQC"/></a>                
            </div>
        </div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">
var province = ',hà nội, việt nam';
var request = {
    origin: '219 KHÂM THIÊN',
    destination: '99 PHỐ HUẾ',
    travelMode: google.maps.DirectionsTravelMode.WALKING
};
var distance = 0;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
var oldDirections = [];
var currentDirections = null;
$(document).ready(function(){
    $('input[type="text"]').live('click', function(){
        $(this).select();
    });
	$("#ddlDichVu option").each(function(){
		if($(this).val() == <?php echo (isset($_GET['service']))?$_GET['service']:1?>){
			$(this).attr('selected','selected');
			service_type = $(this).val();
			display_price();
		}
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
        request.origin = $('#input-from').val() + province;
    }
    if ($('#input-to').val() != ""){
        request.destination = $('#input-to').val() + province;
    }
    getRoute();
	/*
    $("#using-service").click(function(){
        request.origin = $('#input-from').attr('value');
        request.destination = $('#input-to').attr('value');
		$(location).attr("href",'<?php echo get_bloginfo("url");?>?page_id=191&service='+$('#ddlDichVu').val()+'&from='+request.origin+'&to='+request.destination);
        getRoute();
    });
	*/

	$("#ddlDichVu").change(function(){
		service_type = $(this).val();
		display_price();
	});

	$("#search-submit").bind('click',function(){
		request.origin = $('#input-from').attr('value');
		request.destination = $('#input-to').attr('value');
		getRoute();
	});

    $('#input-to').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                request.origin = $('#input-from').attr('value');
                request.destination = $('#input-to').attr('value');
                getRoute();
            }
        }
    });
    $('#input-from').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
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
            distance = rleg.distance.value/1000;
            money_value = countMoney();
			display_price();
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
