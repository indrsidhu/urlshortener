<?php
$notfound = false;
require_once('config.php');
if(isset($_GET) && (isset($_GET['r'])) && ($_GET['r']!="")){
	require_once('shortner.class.php');
	$shortner = new shortner;
	if($long_url = $shortner->findLongUrl($_GET['r'])){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$long_url."");
		exit;
	} else{
		$notfound = true;
	}
}

if(isset($_POST) && isset($_POST['long_url'])){
	require_once('shortner.class.php');
	$shortner = new shortner;
	$shortner->create($_POST);
}
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
		
			<?php if($notfound): ?>
			<div class="alert alert-danger">
			<h2>Not found</h2>
			Sorry your requested page not found, please check.
			</div>
			<?php endif; ?>
		
			<h1>URL Shortner service</h1>
			<form>
				<div class="form-group">
					<label for="long_url">URL</label>
					<input name="long_url" id="long_url" type="url" class="form-control" placeholder="Long url">
					<p class="help-block">Insert long and ugly url, w'll make it short & smart.</p>
				</div>
				<button id="getShortUrl" type="submit" data-loading-text="Processing..." class="btn btn-primary">GET Short URL</button>
			</form>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">URL Shortner service</h4>
		  </div>
		  <div class="modal-body">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
		  </div>
		</div>
	  </div>
	</div>	
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
		jQuery("#getShortUrl").on("click",function(event){
			event.preventDefault();
			
			var $btn = $(this);
			$btn.button('loading');
	
			long_url = jQuery("#long_url").val();
			jQuery.ajax({
				response:'JSON',	
				type: 'post',
				data: {long_url:long_url},
				success: function (response, status) {
					if(response.type=="success"){
						html = "";
						html += "<textarea class=\"form-control\"><?php echo SITE_DOMAIN; ?>/"+response.short_url+"</textarea>";
					} else{
						html = "";
						html += "<div class=\"alert alert-danger\">"+response.message+"</div>";
					}
					jQuery("#myModal .modal-body").html(html);
					jQuery('#myModal').modal('show');
					$btn.button('reset');
				},
			  error: function (xhr, desc, err) {
				$btn.button('reset');
				alert("Error: Unable to process this request, please try after some time");
			  }
			});
		});
	</script>
	
  </body>
</html>