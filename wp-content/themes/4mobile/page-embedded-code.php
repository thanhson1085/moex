<!DOCTYPE html>
<html>
  <head>
    <title>moEx 1900565636</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo get_bloginfo("template_url")?>/bootstrap/css/style.css" rel="stylesheet" media="screen">
    <link href="<?php echo get_bloginfo("template_url")?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
	<div class="container-fluid" style="max-width: 600px;">
		<fieldset class="fieldset">
		<legend>Ship hàng với moEx.vn</legend>
		<form name="formmoex" action="#" method="POST" id="form-moex"> 
			<div class="control-group">
				<div class="row-fluid">
					<div id="moex-alert"></div>
					<input name="orderfrom" class="span12" type="text" placeholder="From" id="input-from">
				</div>
				<div class="row-fluid">
					<input name="orderto" class="span12" type="text" placeholder="To" id="input-to">
				</div>
				<div class="row-fluid">
				  <div class="span2"><button class="btn btn-info" id="search-submit">Go</button></div>
				  <div class="span3 pull-right"><strong><span id="search-result"></span></strong></div>
				</div>
			</div>
			<div class="control-group">
				<div class="row-fluid">
					<input name="orderemail" class="span12" type="email" required placeholder="Email">
				</div>
				<div class="row-fluid">
					<input name="orderphone" class="span12" type="text" placeholder="Mobile" required>
					<input name="orderprice" id="order-price" class="span12" type="hidden" value="">
					<input name="orderurl" id="order-url" class="span12" type="hidden" value="<?php echo $_GET['orderurl'];?>">
					<input name="ordername" id="order-name" class="span12" type="hidden" value="<?php echo $_GET['ordername'];?>">
				</div>
				<div class="row-fluid">
				  <div class="span12"><button type="submit" class="btn btn-info">Gửi yêu cầu</button></div>
				</div>
			</div>
			<div class="control-group">
				<div class="row-fluid" style="background: transparent url(<?php echo get_bloginfo("template_url")?>/images/phone.jpg) no-repeat;width: 100%; height: 30px;">
				</div>
				<div class="row-fluid">
				  <div id="map" width="100" height="100"></div>
				</div>
			</div>
		  </div>
		</form>
		</fieldset>
	</div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="<?php echo get_bloginfo("template_url")?>/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
    <script src="<?php echo get_bloginfo("template_url")?>/js/mescript.js"></script>

	<script type="text/javascript">
	var directionsService = new google.maps.DirectionsService();
	var directionsDisplay = new google.maps.DirectionsRenderer();
	var oldDirections = [];
	var currentDirections = null;
	$(document).ready(function(){
		$('input[type="text"]').bind('click', function(){
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
			request.origin = $('#input-from').val() + province;
		}
		if ($('#input-to').val() != ""){
			request.destination = $('#input-to').val() + province;
		}
		getRoute();

		$("#ddlDichVu").change(function(){
			service_type = $(this).val();
			display_price();
		});

		$("#search-submit").bind('click',function(e){
			e.preventDefault();
			request.origin = $('#input-from').val();
			request.destination = $('#input-to').val();
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
		$("#form-moex").submit(function(e){
			e.preventDefault();
			//console.log(JSON.stringify($('#form-moex').serializeObject()));
			var submit_flag = validateFormOnSubmit(document.getElementById('form-moex'));
			if (!submit_flag) return false;
			$.ajax({
				type: 'POST', 
				async: true,
				url: "http://m.moex.vn/erp/app.php/api/embedded/create",
				data: $('#form-moex').serialize(),
				success: function(data){
					$('#moex-alert').html('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Chúc mừng!</strong> Yêu cầu của bạn đã gửi thành công. moEx sẽ liên hệ với bạn ngay</div>' );
				},
				error: function(){
					$('#moex-alert').html('<div class="alert alert-warning"> <button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Xin lỗi!</strong> Hệ thống đang bận, bạn vui lòng thử lại<div>' );
		
				},
			})
			return false;
		});
	});
	$.fn.serializeObject = function()
	{
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};

	function validateFormOnSubmit(theForm) {
		var reason = "";

		reason += validateEmail(theForm.orderemail);
		reason += validatePhone(theForm.orderphone);

		if (reason != "") {
			$('#moex-alert').html('<div class="alert alert-warning"> <button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Xin lỗi!</strong> '+ reason +'<div>' );
			return false;
		}

		return true;
	}

	function validatePhone(fld) {
		var error = "";
		var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');

	   if (fld.value == "") {
			error = "Số điện thoại không hợp lệ.<br />";
			fld.style.background = 'Yellow';
		} else if (isNaN(parseInt(stripped))) {
			error = "Số điện thoại không hợp lệ.<br />";
			fld.style.background = 'Yellow';
		} else if (!(stripped.length >= 10)) {
			error = "Số điện thoại không hợp lệ.<br />";
			fld.style.background = 'Yellow';
    	}
		return error;
	}

	function validateEmail(fld) {
		var error="";
		var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
		var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
		var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;

		if (fld.value == "") {
			fld.style.background = 'Yellow';
			error = "Email không hợp lệ.<br />";
		} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
			fld.style.background = 'Yellow';
			error = "Email không hợp lệ.<br />";
		} else if (fld.value.match(illegalChars)) {
			fld.style.background = 'Yellow';
			error = "Email không hợp lệ.<br />";
		} else {
			fld.style.background = 'White';
		}
		return error;
	}
	function trim(s)
	{
	  	return s.replace(/^\s+|\s+$/, '');
	}

	</script>
  </body>
</html>
