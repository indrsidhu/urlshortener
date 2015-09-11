<?php
class shortner{
	private $db = "";
	private $shortCodeLimit = 6; //6 Chracter
	
	function __construct(){
		$this->db = $this->getDB();
	}
	
	function find($short_url){
		$response = new stdclass;
		$response->type 	= '';
		$response->message 	= '';
		
		if($url = $this->findLongUrl($short_url)){
			$response->type 	= 'success';
			$response->long_url=$url;
		} else{
			$response->type 	= 'error';
			$response->message	= 'Short version of this url not exist.';
		}
		$this->printResponse($response);
		exit;
	}
	
	public function findLongUrl($short_url){
		$db = $this->db;
		$sql = "SELECT t.long_url FROM urlshortner as t WHERE t.short_url=:x";
		$sql = $db->prepare($sql);
		$sql->bindParam("x", $short_url);
		$sql->execute();
		$resObj = $sql->fetch(PDO::FETCH_OBJ);
		
		if(!empty($resObj)){
			$long_url = trim($resObj->long_url);
			if($long_url!=""){
				return $long_url;
			} else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	private function findShortUrl($long_url){
		$long_url_hash = md5($long_url);
		$db = $this->db;
		$sql = "SELECT t.short_url FROM urlshortner as t WHERE t.long_url_hash=:x";
		$sql = $db->prepare($sql);
		$sql->bindParam("x", $long_url_hash);
		$sql->execute();
		$resObj = $sql->fetch(PDO::FETCH_OBJ);
		
		if(!empty($resObj)){
			$short_url = trim($resObj->short_url);
			return $short_url;
		} else{
			return false;
		}
	}	
	
	public function create($arg){
	
		$long_url = $arg['long_url'];
		
		$response = new stdclass;
		$response->type 	= '';
		$response->message 	= '';
		
		if(!$this->validateUrl($long_url)){
			$response->type 	= 'error';
			$response->message 	= 'Please enter valid URL';
			$this->printResponse($response);
			exit;
		}
		
		if($url = $this->findShortUrl($long_url)){
			$response->type 	= 'success';
			$response->short_url=$url;
			$response->long_url=$long_url;
			$this->printResponse($response);
			exit;
		}

		$db = $this->db;
		$short_url = $this->generateShortUrl();
		
		try{
			$long_url_hash = md5($long_url);
			$sql = $db->prepare("INSERT INTO urlshortner (short_url,long_url,long_url_hash,created_at) 
			VALUES (:short_url, :long_url,:long_url_hash,NOW())");
			$sql->bindParam(':short_url', $short_url);
			$sql->bindParam(':long_url', $long_url);
			$sql->bindParam(':long_url_hash',$long_url_hash);
			$sql->execute();
			$response->type="success";
			$response->message="";
			$response->short_url=$short_url;
			$response->long_url=$long_url;
		} catch(exception $e){
			$response->type="error";
			$response->message="Unable to create new short url, please try after some time";
		}
		$this->printResponse($response);
		exit;
	}
	
	private function generateShortUrl(){
		do {
		  $shortCode = $this->generate_random_letters($this->shortCodeLimit);
		} while ($this->findLongUrl($shortCode));
		//Validate shortcode with db entries 
		return $shortCode;
	}
	
	private function printResponse($response){
		header('Content-Type: application/json');
		echo json_encode($response);		
	}
	
	private function getDB() {
		if(!isset($this->getdb)){
			$dbhost = DB_HOST;
			$dbuser = DB_USER;
			$dbpass = DB_PASS;
			$dbname = DB_NAME;
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$getdb = $dbConnection;
		}
		return $getdb;
	}
	
	

	private function generate_random_letters($length) {
	  $random = '';
	  for ($i = 0; $i < $length; $i++) {
		$random .= chr(rand(ord('a'), ord('z')));
	  }
	  return $random;
	}
	
	private function validateUrl($url){
		if(in_array(parse_url($url, PHP_URL_SCHEME),array('http','https'))){
			if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}	
	}
	
}
?>