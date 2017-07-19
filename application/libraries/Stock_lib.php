<?php

class Stock_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function stock_data_stock_single($mode,$identifier,$cabang_id=false)
	{

		$append	=	'';

		if($cabang_id):
			$append .=	" && stok_cabang_id = '".addslashes($cabang_id)."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_stock 
						WHERE stok_".$mode." = '".$identifier."' $append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array()
						);

		return $callback;

	}

	public function stock_data_stock()
	{

	}

	public function stock_product_single($mode,$identifier,$cabang_id,$latest=false,$latest_id=false)
	{
		$append =	'';
		$append .=	($latest) ? " ORDER BY stock_id DESC LIMIT 1" : "";

		if($latest_id):
			$append .=	" && stock_id < '".$latest_id."' ORDER BY stock_id DESC LIMIT 1 ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_stock_product 
						WHERE stock_".$mode." = '".addslashes($identifier)."' && stock_cabang_id = '".addslashes($cabang_id)."'
						$append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array()
						);

		return $callback;
	}

	public function stock_product($q=false,$perpage=20,$category=false,$merk=false,$type=false,$start=false,$rakitan=false)
	{
		
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

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."setting_produk a 

							LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori b ON a.product_category_id = b.category_id 
							LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON a.product_merk_id = c.merk_id

							WHERE a.product_id > '0' $append"
						);

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

							,b.category_nama ,c.merk_nama

						FROM ".$this->CI->db->dbprefix."setting_produk a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori b ON a.product_category_id = b.category_id 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON a.product_merk_id = c.merk_id

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




}
?>
