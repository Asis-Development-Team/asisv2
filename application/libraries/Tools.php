<?php

class Tools{

	var $CI;
	
	private $current_timestamp;
	private $current_timestamp_day;
	private $event_timestamp;
	private $event_timestamp_day;
	private $calc_time = false;   // Are we going to do times, or just dates?
	private $string = 'now';
	
	private $magic_5_mins = 300;
	private $magic_15_mins = 900;
	private $magic_30_mins = 1800;
	private $magic_1_hour = 3600;
	private $magic_1_day = 86400;
	private $magic_1_week = 604800;
	
	function __construct(){
		$this->CI =& get_instance();
		
		@date_default_timezone_set('UTC');
		
		//$this->current_timestamp = time();
		//$this->current_timestamp_day = mktime(0,  0 ,  0 , $month = date("n") , $day = date("j") , date("Y"));
		//$this->CI->load->helper(array('htmlpurifier'));
	}

	public function tanggal_indonesia($date)
	{

		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	 
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
	 
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
		
		return($result);

	}

	public function order_number()
	{
		
		$query	=	$this->CI->db->query("

							SELECT
								
							IF(DATE_FORMAT(now(), '%y') > SUBSTRING_INDEX(order_number, '.', '1')
								
								, CONCAT(DATE_FORMAT(now(), '%y'), '.', '001')
									
								,
								CONCAT(DATE_FORMAT(now(), '%y'), '.',
									IF(
									  LENGTH(
									   SUBSTRING_INDEX(order_number, '.', '-1')  + 1
									   ) < 2
									  ,CONCAT('00','',SUBSTRING_INDEX(order_number, '.', '-1')  + 1)
									  , IF(
										LENGTH(
										SUBSTRING_INDEX(order_number, '.', '-1')  + 1
										) < 3
										,CONCAT('0','',SUBSTRING_INDEX(order_number, '.', '-1')  + 1)
										,SUBSTRING_INDEX(order_number, '.', '-1')  + 1
									  )
									)
								)
								
							) as number_order
								
							FROM ".$this->CI->db->dbprefix."order a
							ORDER BY a.order_id DESC LIMIT 1
					
					
					");
		
		$fetch	=	$query->row_array();
		
		if( $query->num_rows() < 1 ){
			$order_number	=	date("y").".001";
		}else{
			$order_number	=	$fetch['number_order'];
		}
		
		return $order_number;
			
	}	
	

    public function getFirstPara($string){
        $string = substr($string,0, strpos($string, "</p>")+4);
        return $string;
    }	
	
	public function disguise_curl($url) 
	{ 
		$curl = curl_init(); 
		// setup headers - used the same headers from Firefox version 2.0.0.6
		// below was split up because php.net said the line was too long. :/
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Keep-Alive: 300"; 
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
		$header[] = "Accept-Language: en-us,en;q=0.5"; 
		$header[] = "Pragma: "; //browsers keep this blank. 
		
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com'); 
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); 
		curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); 
		
		$html = curl_exec($curl); //execute the curl command 
		
		if (!$html) 
		{
			echo "cURL error number:" .curl_errno($ch);
			echo "cURL error:" . curl_error($ch);
			exit;
		}
	  
		curl_close($curl); //close the connection 
		return $html; //and finally, return $html 
	}

	
	public function save_image($inPath,$outPath)
	{ //Download images from remote server
		$in=    fopen($inPath, "rb");
		$out=   fopen($outPath, "wb");
		while ($chunk = fread($in,8192))
		{
			fwrite($out, $chunk, 8192);
		}
		fclose($in);
		fclose($out);
		
		//save_image('http://www.someimagesite.com/img.jpg','image.jpg');
	}	

	public function clean($string) 
	{
	   //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
	
	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
	
	public function get_file_size($file_path)
	{
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL, 'http://www.ursdoc.com/pdf/azzeramento-service-ford-mondeo.pdf');
		curl_setopt($ch, CURLOPT_URL, $file_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);	
		
		return $size;
	}

	/* this function is used to create table and write that table into excel */
    public function writeDataintoCSV() {
        //place where the excel file is created
        $myFile = "./upload/testexcel.xls";
        //$this->load->library('parser');
 
        //load required data from database
        //$userDetails = $this->model_details->getUserDetails();
        
		$query	=	$this->CI->db->query("
						SELECT * FROM ".tableprefix."registration
					");
		
		$userDetails	=	$query->result();
		
		$data['user_details'] = $userDetails;
 
        //pass retrieved data into template and return as a string
        $stringData = $this->CI->parser->parse('user_details_csv', $data, true);
 
        //open excel and write string into excel
        $fh = fopen($myFile, 'w') or die("can't open file");
        fwrite($fh, $stringData);
 
        fclose($fh);
        //download excel file
        $this->downloadExcel();
    }
 
	/* download created excel file */
    function downloadExcel() {
        $myFile = "./upload/testexcel.xls";
        header("Content-Length: " . filesize($myFile));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=testexcel.xls');
 
        readfile($myFile);
    }	
	
	
	public function time_zone()
	{
		date_default_timezone_set('Asia/Jakarta');
		$datetime = new DateTime();
		return $datetime;
		//calling $datetime->format('d m Y');
	}	



	function generate_string_text( $length=8,$level=3 ){
		
	   list($usec, $sec) = explode(' ', microtime());
	   srand((float) $sec + ((float) $usec * 100000));
	
	   $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
	   $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	   $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";
	
	   $password  = "";
	   $counter   = 0;
	
	   while ($counter < $length) 
	   {
		 $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);
	
		 // All character must be different
		 if (!strstr($password, $actChar)) {
			$password .= $actChar;
			$counter++;
		 }
	   }
	
	   return $password;		
	}	

	function isDateBetween($dt_start, $dt_check, $dt_end)
	{
	  if(strtotime($dt_check) > strtotime($dt_start) && strtotime($dt_check) < strtotime($dt_end)) 
	  {
		return true;
	  }
	  return false;
	}

	/*
	 convert number
	 */
	function format_angka($num,$val){
		return	number_format($num,$val,",",".");
	}
	
	public function format_angka2($num,$val){
		return	number_format($num,$val,",","");
	}

	/*
	 convert number into romanian
	 */
	function romanNumerals($num){
		$n = intval($num);
		$res = '';

		/*** roman_numerals array  ***/
		$roman_numerals = array(
					'M'  => 1000,
					'CM' => 900,
					'D'  => 500,
					'CD' => 400,
					'C'  => 100,
					'XC' => 90,
					'L'  => 50,
					'XL' => 40,
					'X'  => 10,
					'IX' => 9,
					'V'  => 5,
					'IV' => 4,
					'I'  => 1);

		foreach ($roman_numerals as $roman => $number){
			/*** divide to get  matches ***/
			$matches = intval($n / $number);

			/*** assign the roman char * $matches ***/
			$res .= str_repeat($roman, $matches);

			/*** substract from the number ***/
			$n = $n % $number;
		}

		/*** return the res ***/
		return $res;
	}
	

	function cleanString($string,$numberOnly=false)
	{
		$clean	=	strip_tags($string);
		$clean	=	preg_replace("/[^a-zA-Z0-9_-\s]/", "", $clean);
		
		//untuk ngambil nomer aja
		if($numberOnly == true):
			$clean	=	preg_replace('(\D+)', '', $clean);
		endif;
		
		return $clean;
	}
	

	
	function send_email($to, $data='',$subject,$mail_template){

		$this->CI->email->from('no-reply@server.com' , 'server.com');
		$this->CI->email->to($to);
		$this->CI->email->subject($subject);
		$msg	=	$this->CI->load->view('templates/'.$this->CI->template->data['template'].'/email/'.$mail_template, $data, true);
		$this->CI->email->message($msg);
		return $this->CI->email->send();			
		//return true;
		
	}
	
    function tanggal($name='',$value='')
    {
        $days='';
        while ( $days <= '31'){
            $day[]=$days;$days++;
        }
    }


	function tahun($range=0)
    {        
        $years = range(date('Y'), date("Y") + $range);
        foreach($years as $year)
        {
            $year_list[$year] = $year;
        }    
        
        return $year_list;
    } 	
	
    
	public function hari($date)
	{
		/*
		$hari	=	array(
						'Sunday' => 'Minggu',
						'Monday' => 'Senin',
						'Tuesday' => 'Selasa',
						'Wednesday' => 'Rabu',
						'Thursday' => 'Kamis',
						'Friday' => 'Jumat',
						'Saturday' => 'Sabtu'	
					);
		*/			
		$array	=	array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
		$hari	=	$array[$date];
		
		return $hari;
	}
	
	public function bulan($m)
	{
		$array_bulan = array(1=>"Januari","Februari","Maret", "April","Mei","Juni","Juli","Agustus","September","Oktober", "November","Desember");
		
		$bulan = $array_bulan[$m];
		
		return $bulan;			
	}	
	
	public function date_interval($now)
	{
		$query	=	$this->CI->db->query("
						SELECT DATE_ADD('".$now."',INTERVAL 1 YEAR) as expire
					");
		
		return $query->row_array();
	}


	function time_elapsed_string($datetime, $full = false) {
			$today = time();    
					 $createdday= strtotime($datetime); 
					 $datediff = abs($today - $createdday);  
					 $difftext="";  
					 $years = floor($datediff / (365*60*60*24));  
					 $months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));  
					 $days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));  
					 $hours= floor($datediff/3600);  
					 $minutes= floor($datediff/60);  
					 $seconds= floor($datediff);  
					 //year checker  
					 if($difftext=="")  
					 {  
					   if($years>1)  
						//$difftext=$years." years ago";  
						$difftext=$years." Tahun";  
					   elseif($years==1)  
						//$difftext=$years." year ago"; 
						$difftext=$years." Tahun";  
					 }  
					 //month checker  
					 if($difftext=="")  
					 {  
						if($months>1)  
						//$difftext=$months." months ago";  
						$difftext=$months." Bulan";  
						elseif($months==1)  
						//$difftext=$months." month ago";  
						$difftext=$months." Bulan"; 
					 }  
					 //month checker  
					 if($difftext=="")  
					 {  
						if($days>1)  
						//$difftext=$days." days ago"; 
						$difftext=$days." Hari";  
						elseif($days==1)  
						//$difftext=$days." day ago"; 
						$difftext=$days." Hari"; 
					 }  
					 //hour checker  
					 if($difftext=="")  
					 {  
						if($hours>1)  
						//$difftext=$hours." hours ago";  
						$difftext=$hours." Jam"; 
						elseif($hours==1)  
						//$difftext=$hours." hour ago"; 
						$difftext=$hours." Jam";   
					 }  
					 //minutes checker  
					 if($difftext=="")  
					 {  
						if($minutes>1)  
						//$difftext=$minutes." minutes ago";  
						$difftext=$minutes." Menit";  
						elseif($minutes==1)  
						//$difftext=$minutes." minute ago";  
						$difftext=$minutes." Menit";  
					 }  
					 //seconds checker  
					 if($difftext=="")  
					 {  
						if($seconds>1)  
						//$difftext=$seconds." seconds ago";
						$difftext=$seconds." Detik";  
						elseif($seconds==1)  
						//$difftext=$seconds." second ago";  
						$difftext=$seconds." Detik";  
					 }  
					 return $difftext;  
		}
		
	
}
?>
