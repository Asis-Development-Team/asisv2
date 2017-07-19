<?php

class Pembayaran_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function data_pembayaran_single_detail($mode,$identifier)
	{
		$query 	=	$this->CI->db->query("
						SELECT a.* 
							,b.hutang_tanggal_faktur as pembayaran_detail_tanggal_invoice
						FROM ".$this->CI->db->dbprefix."data_pembayaran_detail a
						LEFT JOIN ".$this->CI->db->dbprefix."data_hutang_usaha b ON a.pembayaran_detail_no_invoice = b.hutang_kode
						WHERE a.pembayaran_detail_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;
	}

	public function data_pembayaran_single($mode,$identifier,$sess_user_cabang_id=false)
	{

		//validasi permintaan data pake cabang_id
		//untuk menghindari inject ID dari address bar
		$append =	'';

		if($sess_user_cabang_id==true):
			$append .=	" && a.pembayaran_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * 
							
							,DATE_FORMAT(pembayaran_tanggal_faktur, '%Y-%m-%d') as pembayaran_tanggal_faktur_formated
							,(SELECT pembayaran_detail_no_invoice FROM ".$this->CI->db->dbprefix."data_pembayaran_detail WHERE pembayaran_detail_kode_pembayaran = a.pembayaran_kode LIMIT 1) as pembayaran_detail_no_invoice

						FROM ".$this->CI->db->dbprefix."data_pembayaran a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.pembayaran_dari_ke = b.supplier_code
						WHERE a.pembayaran_".$mode." = '".addslashes($identifier)."' $append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}
	
	public function data_pembayaran($type,$q=false,$perpage=20,$supplier=false,$cabang=false,$from=false,$to=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && (a.pembayaran_kode LIKE '%".addslashes(trim($q))."%' || a.pembayaran_rekening LIKE '%".addslashes(trim($q))."%' )";			
			
		endif;

		if($cabang):
			$append .=	" && a.pembayaran_cabang_id = '".addslashes($cabang)."' ";
		endif;		

		if($supplier):
			$append .=	" && a.pembayaran_dari_ke = '".addslashes($supplier)."' ";
		endif;

		if($from && $to):
			$append	.=	" && a.pembayaran_tanggal_faktur BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;		

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_pembayaran a 
							
							LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.pembayaran_dari_ke = b.supplier_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.pembayaran_cabang_id = c.cabang_id

							WHERE a.pembayaran_id > '0' && a.pembayaran_tipe = '".addslashes($type)."'  $append							
							"
						);

		$perpage	=	($perpage) ? $perpage : $total;


		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&supplier=' . @$_GET['supplier'] . '&cabang=' . @$_GET['cabang'] . '&from=' . @$_GET['from'] . '&to=' . @$_GET['to'],
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

							,(SELECT pembayaran_detail_no_invoice FROM ".$this->CI->db->dbprefix."data_pembayaran_detail WHERE pembayaran_detail_kode_pembayaran = a.pembayaran_kode LIMIT 1) as nomer_invoice
				
						FROM ".$this->CI->db->dbprefix."data_pembayaran a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.pembayaran_dari_ke = b.supplier_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.pembayaran_cabang_id = c.cabang_id			
						
						WHERE a.pembayaran_id > '0' && a.pembayaran_tipe = '".addslashes($type)."' $append 

						ORDER BY a.pembayaran_id DESC

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
