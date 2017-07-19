<?php

class Penerimaan_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function data_penerimaan_custom($nomer_penerimaan)
	{

		$query 	=	$this->CI->db->query("
						SELECT 
							SUM(a.penerimaan_detail_total) as total, b.product_category_id
							,(SELECT category_rekening_persediaan FROM ".$this->CI->db->dbprefix."setting_produk_kategori WHERE category_kode = b.product_category_id) as rekening_persediaan
						FROM ".$this->CI->db->dbprefix."data_penerimaan_detail a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.penerimaan_detail_product_kode = b.product_kode

						WHERE a.penerimaan_detail_no_penerimaan = '".addslashes($nomer_penerimaan)."' 
						GROUP BY b.product_category_id						
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);	

		return $callback;

	}

	public function data_penerimaan_detail_single($mode,$identifier)
	{

		$query	=	$this->CI->db->query("
						SELECT a.*,b.product_nama, c.category_nama, d.merk_nama
							,IF(

								a.penerimaan_detail_product_nama IS NULL,
								CONCAT(
									b.product_nama,' (', CONCAT(
										c.category_nama, ' - ', CONCAT(
											d.merk_nama,'', ')'
										)
									)
								), a.penerimaan_detail_product_nama

							) as detail_nama_produk

						FROM ".$this->CI->db->dbprefix."data_penerimaan_detail a 
						
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.penerimaan_detail_product_kode = b.product_kode 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_kode

						
						WHERE a.penerimaan_detail_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;

	}

	public function data_penerimaan_single($mode,$identifier,$sess_user_cabang_id=false)
	{
		
		$append =	"";

		if($sess_user_cabang_id==true):
			$append .=	" && penerimaan_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;		

		$query 	=	$this->CI->db->query("
						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_penerimaan a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.penerimaan_supplier_id = b.supplier_code

						WHERE penerimaan_".$mode." = '".addslashes($identifier)."' $append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}		

	public function data_penerimaan($q=false,$perpage=20,$cabang=false,$supplier=false,$from=false,$to=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && (a.penerimaan_no_penerimaan LIKE '%".addslashes($q)."%' || a.penerimaan_no_po LIKE '%".addslashes($q)."%' || a.penerimaan_no_surat_jalan LIKE '%".addslashes($q)."%' )";			
			
		endif;

		if($cabang):
			$append .=	" && a.penerimaan_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($from && $to):
			$append	.=	" && a.penerimaan_tanggal BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;	

		if($supplier):
			$append .=	" && a.penerimaan_supplier_id = '".addslashes($supplier)."' ";
		endif;	

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_penerimaan a 
							
							LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.penerimaan_supplier_id = b.supplier_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.penerimaan_cabang_id = c.cabang_id

							WHERE a.penerimaan_id > '0' $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;


		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&cabang=' . @$_GET['cabang'] . '&supplier=' . @$_GET['supplier'] . '&from=' . @$_GET['from'] . '&to=' . @$_GET['to'],
						$total,$perpage
					);

		//if($start):
		//	$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		//else:
		//	$start 	=	'0';
		//endif;

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query	=	$this->CI->db->query("
						
						SELECT * 					

						FROM ".$this->CI->db->dbprefix."data_penerimaan a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.penerimaan_supplier_id = b.supplier_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.penerimaan_cabang_id = c.cabang_id
						
						WHERE a.penerimaan_id > '0' $append 
						ORDER BY a.penerimaan_id DESC

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
