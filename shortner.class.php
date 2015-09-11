<?php
class shortner{
	$db = "";
	
	function __construct(){
		$this->db = getDB();
	}
	
	function handeler(){
	}
	
	private function find($short_url){
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
	
	private function create($long_url){
		$db = $this->db;
		$short_url = $this->generateShortUrl($long_url);
		try{
			$long_url_hash = md5($long_url);
			$sql = $db->prepare("INSERT INTO urlshortner (short_url,long_url,long_url_hash,created_at) 
			VALUES (:short_url, :long_url,:long_url_hash,NOW())");
			$sql->bindParam(':short_url', $short_url);
			$sql->bindParam(':long_url', $long_url);
			$sql->bindParam(':long_url_hash',$long_url_hash);
			$sql->execute();
		} catch(exception $e){
			$response->type="error";
			$response->message="Unable to create new short url, please try after some time";
		}	
		
	}
	
	private function generateShortUrl($long_url){
	}
	
}
?>