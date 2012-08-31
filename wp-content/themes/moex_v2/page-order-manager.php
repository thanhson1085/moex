<?php 
get_header();
?>
<div class="content-container">
    <div class="content">
		<div class="left-menu-container">
			<div class="left-menu">
			<?php
			get_sidebar('left');
			?>
			</div>
		</div>
		<div class="left-part-container">
			<div class="left-part page">
				<?php
				if ( have_posts() ) while ( have_posts() ) : the_post();
				?>
					<h1 class="page-title">
						<a href="<?php the_permalink();?>"><?php the_title();?></a>
						<p class="news-time"><?php echo date_i18n( __( 'd/m/Y g:i A' ), strtotime( $post->post_date ) );?></p>
					</h1>
					<div class="single-content">
					<?php
						the_content();
					?>
					<div id="admin-area"></div>
					</div>
				<?php
				endwhile;
				?>
			</div>
		</div>
		<div class="right-part-container">
			<div class="right-part">
				<div id="filter-area"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var ajax_link = "<?php echo get_bloginfo("url");?>/core/web/app_dev.php/order/"
	var ajax_filter_link = "<?php echo get_bloginfo("url");?>/core/web/app_dev.php/order/filter"
</script>
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
	$('.txt-main').live('click', function(){
		$(this).select();
	});
    $('#admin-area').ajaxSuccess(function(){
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
		if ($('#moex_corebundle_meorderstype_orderFrom').val() != ""){
			request.origin = $('#moex_corebundle_meorderstype_orderFrom').val();
		}
		if ($('#moex_corebundle_meorderstype_orderTo').val() != ""){
			request.destination = $('#moex_corebundle_meorderstype_orderTo').val();
		}
        getRoute();
        $("#search-submit").click(function(){
		    submit_click = true;	
            request.origin = $('#input-from').attr('value');
            request.destination = $('#input-to').attr('value');
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
				console.log(rleg);
				distance = rleg.distance.value;
				money_value = countMoney();
				$('#search-result').html(money_value);
				$('#moex_corebundle_meorderstype_price').attr('value',money_value);
				$('#moex_corebundle_meorderstype_orderFrom').attr('value',rleg.start_address);
				$('#moex_corebundle_meorderstype_orderTo').attr('value',rleg.end_address);
				$('#moex_corebundle_meorderstype_lat').val(rleg.start_location.lat());
				$('#moex_corebundle_meorderstype_lng').val(rleg.end_location.lng());
			}
			currentDirections = directionsDisplay.getDirections();
		});
	});
});
</script>
<?php
get_footer();
?>
