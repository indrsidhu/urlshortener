<?php
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