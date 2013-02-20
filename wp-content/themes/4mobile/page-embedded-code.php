<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap 101 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo get_bloginfo("template_url")?>/bootstrap/css/style.css" rel="stylesheet" media="screen">
    <link href="<?php echo get_bloginfo("template_url")?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
	<div class="container">
		<fieldset class="fieldset">
		<legend>moEx.vn</legend>
		<form> 
			<div class="controls controls-row">
				<input class="span6" type="text" placeholder="From">
			</div>
			<div class="controls controls-row">
				<input class="span6" type="text" placeholder="To">
			</div>
			<div class="controls controls-row">
			  <div class="span2"><button class="btn btn-info">Go</button></div>
			  <div class="span4">Result</div>
			</div>
		  </div>
		</form>
		</fieldset>
	</div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="<?php echo get_bloginfo("template_url")?>/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
