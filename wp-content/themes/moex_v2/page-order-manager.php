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
var submit_click = false;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
$(document).ready(function(){
    $('#admin-area').ajaxSuccess(function(){
		var input = document.getElementById('input-from');
		var options = {
		  types: ['(cities)'],
		  componentRestrictions: {country: 'vn'}
		};
		autocomplete = new google.maps.places.Autocomplete(input, options);
        var myOptions = {
            zoom:7,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
        directionsDisplay.setMap(map);
        $("#search-from").html(request.origin);
        $("#search-to").html(request.destination)
        getRoute();
        $("#search-submit").click(function(){
		    submit_click = true;	
            request.origin = $('#input-from').attr('value');
            request.destination = $('#input-to').attr('value');
            $("#search-from").html(request.origin);
            $("#search-to").html(request.destination)
			$('#moex_corebundle_meorderstype_orderFrom').attr('value',request.origin);
			$('#moex_corebundle_meorderstype_orderTo').attr('value',request.destination);
            getRoute();
        });
    });
});
</script>
<?php
get_footer();
?>
