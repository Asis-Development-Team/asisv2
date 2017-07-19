<?php

class Setting_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function setting_rekening_single($cabang,$mode,$identifier)
	{
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_rekening 
						WHERE rekening_cabang_id = '".$cabang."' && rekening_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array()
						);

		return $callback;
	}

	public function setting_rekening()
	{

	}

	public function setting_produk_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_produk a 
						WHERE a.product_".$mode." = '".$identifier."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function setting_produk($q=false,$perpage=20,$category=false,$merk=false,$type=false,$start=false,$rakitan=false,$cabang=false)
	{
		
		//$this->CI->db->cache_on();

		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && (a.product_nama LIKE '%".addslashes($q)."%' || a.product_kode LIKE '%".addslashes($q)."%' || b.category_nama LIKE '%".addslashes($q)."%' || c.merk_nama LIKE '%".addslashes($q)."%' )";			
			
		endif;

		if($category):
			$append	.=	" && a.product_category_id = '".addslashes($category)."' ";
		endif;

		if($merk):
			$append	.=	" && a.product_merk_id = '".addslashes($merk)."' ";
		endif;

		if($type):
			$append	.=	" && a.product_jasa_non_jasa = '".addslashes($type)."' ";
		endif;

		if($rakitan):
			$append .=	" && a.product_rakitan = '1' ";
		endif;

		if($cabang):
			$append	.=	" && a.product_cabang_id = '".addslashes($cabang)."'";
		endif;

		/*
		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."setting_produk a 

							LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori b ON a.product_category_id = b.category_id 
							LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON a.product_merk_id = c.merk_id

							WHERE a.product_id > '0' $append"
						);

		*/

		$query		=	$this->CI->db->query("
							SELECT a.* 

								,b.category_nama ,c.merk_nama, b.category_id as produk_kategori_id

							FROM ".$this->CI->db->dbprefix."setting_produk a
							LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori b ON a.product_category_id = b.category_id 
							LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON a.product_merk_id = c.merk_id

							WHERE a.product_id > '0' $append 
						"); 	

		$total 		=	$query->num_rows();

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&category=' . @$_GET['category'],
						$total,$perpage
					);

		if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		else:
			$start 	=	'0';
		endif;


		$query	=	$this->CI->db->query("
						SELECT a.* 

							,b.category_nama ,c.merk_nama, b.category_id as produk_kategori_id, d.cabang_nama

						FROM ".$this->CI->db->dbprefix."setting_produk a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori b ON a.product_category_id = b.category_id 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON a.product_merk_id = c.merk_id
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang d ON a.product_cabang_id = d.cabang_id

						WHERE a.product_id > '0' $append 
						ORDER BY a.product_id DESC

						LIMIT ".$start.", ".$perpage."
					");	

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'result' => $result,
							'paging' => $paging,
							'total' => $total,
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			

		
	}	

	public function setting_produk_kategori_single($mode,$identifier)
	{
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_produk_kategori 
						WHERE category_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function setting_produk_kategori($q=false,$perpage=20,$start=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && (a.category_nama LIKE '%".addslashes($q)."%' )";			
		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."setting_produk_kategori a 
							WHERE a.category_id > '0' $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$q,
						$total,$perpage
					);

		if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		else:
			$start 	=	'0';
		endif;

		$query	=	$this->CI->db->query("
						SELECT a.* 

						FROM ".$this->CI->db->dbprefix."setting_produk_kategori a

						WHERE a.category_id > '0' $append 
						ORDER BY a.category_id DESC

						LIMIT ".$start.", ".$perpage."
					");	

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'result' => $result,
							'paging' => $paging,
							'total' => $total,
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			

	}	

	public function setting_produk_merk($q=false,$perpage=20,$start=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && (a.merk_nama LIKE '%".addslashes($q)."%' )";			
		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."setting_merk_produk a 
							WHERE a.merk_id > '0' $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$q,
						$total,$perpage
					);

		if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		else:
			$start 	=	'0';
		endif;

		$query	=	$this->CI->db->query("
						SELECT a.* 

						FROM ".$this->CI->db->dbprefix."setting_merk_produk a

						WHERE a.merk_id > '0' $append 
						ORDER BY a.merk_nama ASC

						LIMIT ".$start.", ".$perpage."
					");	

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'result' => $result,
							'paging' => $paging,
							'total' => $total,
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			

	}		

	public function setting_klasifikasi_akun($q=false,$perpage=false,$parent=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.setting_ka_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($parent):

			if($parent == '-1'):
				$append	.=	" && setting_ka_parent_id > '0' ";
			elseif($parent == '-2'):
				$append	.=	" && setting_ka_parent_id = '0' ";
			else:
				$append .=	" && setting_ka_parent_id = '".addslashes($parent)."' ";
			endif;

		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."setting_klasifikasi_akun a 

							WHERE a.setting_ka_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	


		$query 	=	$this->CI->db->query("

						SELECT a.*

						FROM ".$this->CI->db->dbprefix."setting_klasifikasi_akun a 

						WHERE a.setting_ka_id > '0' $append
						ORDER BY a.setting_ka_id ASC

						LIMIT ".$start.", ".$perpage."

					");

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'result' => $result,
							'paging' => $paging,
							'total' => $total,
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			
	}

	public function setting_data_outlet_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_cabang 
						WHERE cabang_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function setting_data_outlet($q=false,$perpage=10,$start=false,$cabang=false)
	{

		$append		=	'';
		
		if($q):
			$append .=	" && a.cabang_nama LIKE '%".addslashes($q)."%' ";						
		endif;

		if($cabang):
			$append .=	" && a.cabang_id = '".$cabang."'";
		endif;

		$total 		=	$this->CI->db->count_all($this->CI->db->dbprefix."setting_cabang a WHERE a.cabang_id > '0' $append");
		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'],
						$total,$perpage
					);

		if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		else:
			$start 	=	'0';
		endif;

		$query	=	$this->CI->db->query("
						SELECT a.* 

						FROM ".$this->CI->db->dbprefix."setting_cabang a
						WHERE a.cabang_id > '0' $append 
						
						ORDER BY a.cabang_id ASC

						LIMIT ".$start.", ".$perpage."
					");	

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'total' => $total,
							'result' => $result,
							'paging' => $paging,							
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			

	}

	public function setting_page_privilleges($ulevel,$page,$view_page)
	{

		$query 	=	$this->CI->db->query("
						SELECT menu_icon, menu_access_level FROM ".$this->CI->db->dbprefix."setting_menu 
						WHERE menu_url = '".addslashes($page)."' && FIND_IN_SET($ulevel, menu_access_level)
					");

		return ($query->num_rows() < 1) ? '/view_no_access' : $view_page;

	}

	public function setting_main_menu_single($mode=false,$identifier=false)
	{

		$where 	=	" WHERE ";
		$where .=	" menu_".$mode." = '".addslashes($identifier)."' ";

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_menu 
						$where
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function setting_main_menu($q=false,$perpage=10,$parent=false,$user_level=false,$display=false,$limit=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));
			
			//$append	.=	' && a.order_invoice_number REGEXP ".*(.*('.addslashes($key).').*).*" ';
			$append .=	" && a.menu_name LIKE '%".addslashes($q)."%' ";			
			
		endif;

		if($parent!=null && $q == false):
			$append .=	" && a.parent_id = '".$parent."' ";
		endif;

		if($user_level):
			$append .=	" && FIND_IN_SET(".$user_level.", a.menu_access_level) ";
		endif;

		if($display):
			$append .=	" && a.menu_status = '".$display."' ";
		endif;

		$total 		=	$this->CI->db->count_all($this->CI->db->dbprefix."setting_menu a WHERE a.menu_id > '0' $append");
		$perpage	=	($perpage) ? $perpage : $total;


		//print $perpage; exit;


		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'],
						$total,$perpage
					);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];


		if($limit):
			$limit 	=	"LIMIT ".$start.", ".$perpage."";
		endif;

		$query	=	$this->CI->db->query("
						SELECT a.* 

							,IF(
								a.menu_status = 'show', 'Aktif', '<span style=\"color:#ff0000\">Tidak Aktif</span>'
							) as status_menu

						FROM ".$this->CI->db->dbprefix."setting_menu a
						WHERE a.menu_id > '0' $append 
						
						ORDER BY a.menu_ordering ASC

						$limit
					");	

		$result	=	$query->result_array(); 
		
		$total_result	=	$query->num_rows();
		
		$jumlah_tampil	=	$this->CI->pagination->cur_page * $this->CI->pagination->per_page;
		$sisa_data		=	$total - $jumlah_tampil;
		
		if($jumlah_tampil >= $total || $perpage >= $total):
			$sisa_data	=	'0';
		endif;
		
		$callback	=	array(
							'result' => $result,
							'paging' => $paging,
							'total' => $total,
							//'total_data' => $total_data,
							'total_result' => $total_result,
							'sisa_data' => $sisa_data
						);	
		
		return $callback;			

		
	}		

	public function main_menu($parent,$user_level,$display=false)
	{	

		$append =	"";

		$append .=	($display) ? " && a.menu_status = '".$display."' " : "";

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_menu a
						WHERE parent_id = '".$parent."' && FIND_IN_SET(".$user_level.", a.menu_access_level) 
						ORDER BY menu_ordering ASC
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;

	}
	
	public function list_menu($parent,$level,$user_level=false)
	{
		$append	=	'';

		if($user_level):
			$append 	=	" && FIND_IN_SET(".$user_level.", a.menu_access_level) ";
		endif;

	    $query 	=	$this->CI->db->query("
		    			
		    			SELECT a.parent_id, a.menu_id, a.menu_name, a.menu_url, Deriv1.Count 
		    			FROM ".$this->CI->db->dbprefix."setting_menu a  
		    			LEFT OUTER JOIN 
		    			(
		    				SELECT parent_id, COUNT(*) AS Count 
		    				FROM ".$this->CI->db->dbprefix."setting_menu GROUP BY parent_id
		    			) Deriv1 ON a.menu_id = Deriv1.parent_id 
		    			
		    			WHERE a.parent_id=" . $parent ." $append
	    			
	    			")->result_array();

	    
	    foreach($query as $result):

	        if ($result['Count'] > 0):

	        	if($result['parent_id'] < '1'):

	        		$toggle 	=	'nav-toggle';

	        	else:

	        		$toggle 	=	'';

	        		print '<ul class="sub-menu">';

	        	endif;
	        
	            print "<li class='nav-item'>";
	            print "<a class='nav-link ".$toggle."' href='" . $result['menu_url'] . "'>";
	            print $result['menu_name'] . ' --> ' . $result['parent_id'];
	            print "</a>";
	            
	            self::list_menu($result['menu_id'], $level + 1, $user_level);

	            print "</li>";

	        	if($result['parent_id'] < '0'):

	        	else:
	        		
	        		print '</ul>';

	        	endif;	            
	        
	        elseif ($result['Count']==0):
	        
	            print "<li><a href='" . $result['menu_url'] . "'>" . $result['menu_name'] . "</a></li>";
	        
	        endif;   	

	    endforeach;

	    
	}


	public function list_menu_master($parent,$level,$user_level=false)
	{
		$append	=	'';

		if($user_level):
			$append 	=	" && FIND_IN_SET(".$user_level.", a.menu_access_level) ";
		endif;

	    $query 	=	$this->CI->db->query("
		    			
		    			SELECT a.menu_id, a.menu_name, a.menu_url, Deriv1.Count 
		    			FROM ".$this->CI->db->dbprefix."setting_menu a  
		    			LEFT OUTER JOIN 
		    			(SELECT parent_id, COUNT(*) AS Count FROM ".$this->CI->db->dbprefix."setting_menu GROUP BY parent_id) Deriv1 ON a.menu_id = Deriv1.parent_id 
		    			WHERE a.parent_id=" . $parent ." $append
	    			
	    			")->result_array();

	    print "<ul>";

	    foreach($query as $result):

	        if ($result['Count'] > 0) {
	            print "<li><a href='" . $result['menu_url'] . "'>" . $result['menu_name'] . "</a>";
	            self::list_menu($result['menu_id'], $level + 1, $user_level);
	            print "</li>";
	        } elseif ($result['Count']==0) {
	            print "<li><a href='" . $result['menu_url'] . "'>" . $result['menu_name'] . "</a></li>";
	        } else;	    	

	    endforeach;

	    print "</ul>";

	}

}
?>
