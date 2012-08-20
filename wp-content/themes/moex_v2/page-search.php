<?php
get_header();
$from = (isset($_GET['from']))?$_GET['from']:'';
$to = (isset($_GET['to']))?$_GET['to']:'';
?>
<div class="content-container">
    <div class="content page">
        <?php get_template_part('loop','single'); ?>
		<div class="search-result-container">
			<div class="from-desc-container">
				<div class="from-desc">
					<p><span class="search-text">From:</span></p>
					<p class="search-address" id="search-from">dolor sit amet, consectetur, adipisicing elit</p>
				</div>
			</div>
			<div class="to-desc-container">
				<div class="to-desc">
					<p><span class="search-text">To:</span></p>
					<p class="search-address" id="search-to">dolor sit amet, consectetur, adipisicing elit</p>
				</div>
			</div>
			<div class="price-result-container">
				<div class="price-result" id ="search-result">
				</div>
			</div>
		</div>
		<div class="search-form-container">
			<div class="search-form">
				<input class="txt-main page" id="input-from" name="from" type="text" placeholder="From..."/>
				<input class="txt-main page" id="input-to" name="to" type="text" placeholder="To..."/>
				<div class="btn-search-container" id="search-submit"><span class="btn-main page">Submit</span></div>
			</div>
		</div>

		<div id="map"></div> 
	</div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript"> 
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();

var myOptions = {
	zoom:7,
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("map"), myOptions);
directionsDisplay.setMap(map);
var province = ', Hà Nội, Việt Nam';
var request = {
	origin: '<?php echo (isset($from) && $from)?$from:'219 Khâm Thiên'?>', 
	destination: '<?php echo (isset($to) && $to)?$to:'99 Phố Huế';?>',
	travelMode: google.maps.DirectionsTravelMode.WALKING
};
</script>
<script type="text/javascript">
$(document).ready(function(){
	$("#search-from").html(request.origin);
	$("#search-to").html(request.destination)
	getRoute();
	$("#search-submit").click(function(){
		request.origin = $('#input-from').attr('value');
		request.destination = $('#input-to').attr('value');
		$("#search-from").html(request.origin);
		$("#search-to").html(request.destination)
		getRoute();
		
	});
});
</script> 
<?php
get_footer();?>
