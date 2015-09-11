<?php
require_once('config.php');
require_once('shortner.class.php');

//if(isset($_POST) && isset($_POST['long_url'])){

	


	die();

	

	
	
	
//}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>URL Shortner service</title>

    <!-- Bootstrap -->
    <link href="assets/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
	<div class="container">
		<div id="urlshortner">
			<h1>URL Shortner service</h1>
			<form>
				<div class="form-group">
					<label for="long_url">URL</label>
					<input name="long_url" id="long_url" type="url" class="form-control" placeholder="Long url">
					<p class="help-block">Insert long url to make it short.</p>
				</div>
				<button id="getShortUrl" type="submit" class="btn btn-primary">GET Short URL</button>
			</form>
		</div>
	</div>
  

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
		jQuery("#getShortUrl").on("click",function(event){
			event.preventDefault();
			long_url = jQuery("#long_url").val();
			jQuery.ajax({
			  type: 'post',
			  data: {long_url:long_url},
			  success: function (data, status) {
			  },
			  error: function (xhr, desc, err) {
			  }
			});
		});
	</script>
	
  </body>
</html>