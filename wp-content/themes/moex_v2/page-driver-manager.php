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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript">
	var ajax_link = "<?php echo get_bloginfo("url");?>/core/web/app_dev.php/driver/"
	var ajax_filter_link = "<?php echo get_bloginfo("url");?>/core/web/app_dev.php/driver/filter"
</script>
<script type="text/javascript">
	var submit_click = false;
var province = ', Hà Nội, Việt Nam';
var submit_click = false;
geocoder = new google.maps.Geocoder();
var latlng = new google.maps.LatLng(21.0188564, 105.8397048);
$(document).ready(function(){
    $('#admin-area').ajaxSuccess(function(){
        var input = document.getElementById('input-from');
        var myOptions = {
            zoom:15,
			center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
		var marker = new google.maps.Marker({
			map: map,
			draggable: true
		});
		sAddress = $('#input-position').val() + province; 
		geocoder.geocode({ 'address': sAddress }, function( results, status){
			if (status == google.maps.GeocoderStatus.OK){
				map.setCenter(results[0].geometry.location);
				marker.setPosition(results[0].geometry.location);
			}
		});
		$('#search-submit').click(function(){
			sAddress = $('#input-position').val() + province; 
			geocoder.geocode({ 'address': sAddress }, function( results, status){
				if (status == google.maps.GeocoderStatus.OK){
					map.setCenter(results[0].geometry.location);
					marker.setPosition(results[0].geometry.location);
					$('#moex_corebundle_medriverstype_position').val(results[0].formatted_address);
					$('#moex_corebundle_medriverstype_lat').val(marker.getPosition().lat());
					$('#moex_corebundle_medriverstype_lng').val(marker.getPosition().lng());
				}
			});
		});
		$('#input-position').click(function(){
			$(this).select();
		});
		google.maps.event.addListener(marker, 'drag', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#moex_corebundle_medriverstype_position').val(results[0].formatted_address);
						$('#moex_corebundle_medriverstype_lat').val(marker.getPosition().lat());
						$('#moex_corebundle_medriverstype_lng').val(marker.getPosition().lng());
					}
				}
			});
		});
    });
});
</script>
<?php
get_footer();
?>
