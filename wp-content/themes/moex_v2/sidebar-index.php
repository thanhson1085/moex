<div class="main-intro">
	<?php 
		$from = '219 Khâm Thiên';
		$to = '99 Phố Huế';
		$slogan_id = 20;
		$slogan = get_post($slogan_id);?>
	<p class="main-header"><?php echo $slogan->post_title;?>
		<?php if (current_user_can('edit_post')):?>
		<a href="<?php echo admin_url()?>/post.php?post=<?php echo $slogan->ID?>&action=edit" class="title-edit-link" target="_blank">(edit)</a>
		<?php endif;?>
	</p>
	<p class="main-intro-content">
		<?php echo $slogan->post_content;?>
	</p>
</div>
<div class="main-form">
	<input class="txt-main" id="search-from" name="from" type="text" placeholder="<?php echo $from?>"/>
	<input class="txt-main" id="search-to" name="to" type="text" placeholder="<?php echo $to;?>"/>
	<p><span class="btn-main" id="search-submit">Submit</span></p>

</div>
<div id="map-home"></div> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript"> 
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();

var myOptions = {
	zoom:7,
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("map-home"), myOptions);
directionsDisplay.setMap(map);
var province = ', Hà Nội, Việt Nam';
var request = {
	origin: '<?php echo (isset($from) && $from)?$from:'219 Khâm Thiên'?>', 
	destination: '<?php echo (isset($to) && $to)?$to:'99 Phố Huế';?>',
	travelMode: google.maps.DirectionsTravelMode.WALKING
};
var distance = 0;
function getRoute(){
	distance = 0;
	request.origin += province;
	request.destination += province;
	directionsService.route(request, function(response, status) {
	if (status == google.maps.DirectionsStatus.OK) {
		distance = response.routes[0].legs[0].distance.value;
		directionsDisplay.setDirections(response);
	}
	});
}
getRoute();

</script>
