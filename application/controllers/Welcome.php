<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
		
	function __construct(){
		parent::__construct();
		$this->load->model("Model_common");

	}
	
	//http://localhost/bikroy_scrap/welcome/fetch_url/uttara/1  = fetch url by city 
	//http://localhost/bikroy_scrap/welcome/fetch_url/mirpur/1
	
	//http://localhost/bikroy_scrap/welcome/fetch_phone/dhaka
	

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data['location_subcity'] = $this->db->query("SELECT * FROM `location_subcity`")->result();
		
		pd($data['location_subcity']);
		
		//$this->load->view('welcome_message', $data);
	}
	
	// Fetch url by location //
	public function fetch_url(){
		
		$subcity 	= $this->uri->segment(3);
		$subcity_id 	= $this->uri->segment(4);
		//$lastlimit 	= $this->uri->segment(4);
		//$start 		= $this->uri->segment(5);
		
		//$lastlimit = $start + 10;
		
		//for($start  = 1 ; $start  < $lastlimit; $start++ ){
				// ghotona atogula page e loop hobe
				
			$page_number = $this->db->query("SELECT `page_number` FROM `post_links` ORDER BY `id` DESC LIMIT 1")->row()->page_number;
						
						if($page_number == "" || $page_number == NULL){
							$page = 1;
						}else{
							$page = $page_number + 1 ;
							
						}
				
			echo $url = "https://bikroy.com/en/ads/".$subcity."?page=".$page;
			echo "<br/>";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url); // Define target site
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Return page in string
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/5.0.342.3 Safari/533.2');
				curl_setopt($ch, CURLOPT_ENCODING , "gzip");     
				curl_setopt($ch, CURLOPT_TIMEOUT,5); 
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // Follow redirects

				$return = curl_exec($ch); 
				$info = curl_getinfo($ch); 
				curl_close($ch); 

				$d = new DOMDocument();
				@$d->loadHTML($return); // the variable $ads contains the HTML code above
				$xpath = new DOMXPath($d);
				
			//	print($xpath);
				
				$ls_ads = $xpath->query("//div[@class='serp-items']")->item(0);
				
		/* 		echo "<pre>";
				print_r($ls_ads->nodeValue);
				echo "</pre>"; */
				
				@$string = $ls_ads->nodeValue;

				preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);

			 	/* echo "<pre>";
				print_r($match[0]); 
				echo "</pre>";
				  */
				$content = $match[0];
				
				$count_content = count($content);
				
				 
			 	for($i = 0 ; $i < $count_content; $i++ ){
					
					$link = $content[$i];
		
					$is_correctlink = strpos($link,"en/ad/");
					
					if($is_correctlink){
						echo $link;
						echo "<br/>";
	

						$query = $this->db->query("SELECT `id` FROM `post_links` WHERE `link_url`='$link'");
						
						if($query->num_rows() > 0){
							echo "ALready Inserted";
						}else{
							
							$data = array(
								'subcity_id'=>$subcity_id,
								'link_url'=>$link,
								'city_word'=>$subcity,
								'page_number'=>$page,
								'flag'=>1
								);
		
							
							$this->db->insert("post_links",$data);
							
						}
						
						
					/* 	if($isuniq==false){
							echo "Alreate Insrted";
						}else{
							
							
						}
						 */
						
					} 
				}	
			// ghotona atogula page e loop hobe
		//}// end for
	
	} // function end
	
	
	
// Fetch Phone number //	
	public function fetch_phone(){
	
			$query = $this->db->query("SELECT `id`,`link_url`,`city_word`,`flag` FROM `post_links` WHERE `is_fetched`='0' ORDER BY `id` ASC LIMIT 10");
			
			$city = $this->uri->segment(3);
			
	
				if($query->num_rows() > 0){
					$urldata = $query->result();
					
					foreach($urldata as $urls){
						
						$id = $urls->id;
					echo	$url = $urls->link_url;
						$city_word = $urls->city_word;
						echo "<br/>";
						
						/* $url = "https://bikroy.com/en/ad/gree-12000btu-10-ton-air-conditioner-for-sale-dhaka-10"; */

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url); // Define target site
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Return page in string
						curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/5.0.342.3 Safari/533.2');
						curl_setopt($ch, CURLOPT_ENCODING , "gzip");     
						curl_setopt($ch, CURLOPT_TIMEOUT,5); 
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // Follow redirects

						$return = curl_exec($ch); 
						$info = curl_getinfo($ch); 
						curl_close($ch); 

						$d = new DOMDocument();
						@$d->loadHTML($return); // the variable $ads contains the HTML code above
						$xpath = new DOMXPath($d);
						
					//	print($xpath);
						
						$mobile = $xpath->query("//div[@class='item-contact-more is-showable']//ul//li")->item(0);
						
			
						
						$ls_ads = $xpath->query("//p[@class='item-intro']//a")->item(0);
						
				
	
						$mobileNumber = $mobile->textContent; 
						
						@$name = $ls_ads->textContent; 
						
						if($name=="" || $name==null){
							$namequery = $xpath->query("//p[@class='item-intro']//span[@class='poster']")->item(0);
							
							@$name = $namequery->textContent; 
							
							$name = substr($name,12);
							
							if($name=="" || $name==null){
								$name = "No Name";
							}
							
							
						}
						
						// post link flag 1 kore dao
						$this->db->query("UPDATE `post_links` SET `is_fetched`='1' WHERE `id`='$id'");
						
						if($mobile == NULL){
							return false;
						}	 
						
						// check number is uniq //
						$number_query = $this->db->query("SELECT `id` FROM `phone_data` WHERE `phone_one`='$mobileNumber'");
						$phoneexist = $number_query->num_rows();
						
						
						if($phoneexist){
							
							echo "Phone Exist <br/>";
							
						}else{
							
							$data = array(
										  'subcity_id' => 1 ,
										  'city_keyword' => $city_word ,
										  'post_link_id' => $id ,
										  'name_person' => $name ,
										  'location_subcity' => $city_word ,
										  'location_city' => $city ,
										  'phone_one' => $mobileNumber ,
										  'flag' => 1
									   );  
									   
							$this->db->insert('phone_data', $data);		   
							
						}
						
					}// insert loop end
					
					// Chunk insert er ekta array //
				
					
				}else{
					echo "No lead found <br/>";
							
				}
	
	
	
	}
	
	
}
