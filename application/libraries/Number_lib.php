<?php

class Number_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function number_po_supplier($cabang_id,$id_transaksi)
	{

		$query 	=	$this->CI->db->query("

						SET 
						@kode_outlet	=	(SELECT cabang_code FROM aston_setting_cabang WHERE cabang_id = '".$cabang_id."'),
						@kode_transaksi =	(SELECT kode_nomer FROM aston_setting_kode_transaksi WHERE kode_id = '".$id_transaksi."'),

						@mix 			=	CONCAT(@kode_transaksi,'',@kode_outlet),

						@current_number =	(SELECT po_nomer_po FROM aston_data_po WHERE po_cabang_id = '".$cabang_id."' ORDER BY po_id DESC LIMIT 1),

						@bln 			=	SUBSTRING(@current_number,5,2),
						@bln_sekarang 	=	DATE_FORMAT(NOW(), '%m'),

						@bln_thn_sekarang 	=	CONCAT(DATE_FORMAT(NOW(), '%m'),'',DATE_FORMAT(NOW(), '%y')),

						@nomer_baru 		=	CONCAT(@mix,@bln_thn_sekarang,'0001')

					");

		
		
		$query 	=	$this->CI->db->query("
						SELECT 
							
							IF(
								COUNT(po_nomer_po) > 0,
								
								IF(
									@bln <> @bln_sekarang, 

									CONCAT(
										@kode_transaksi,@kode_outlet,
										CONCAT(
											@bln_thn_sekarang,'','0001'
										)
									), 
									
									@current_number + 1
								),
								
								CONCAT(
									@kode_transaksi,@kode_outlet,
									CONCAT(
										@bln_thn_sekarang,'','0001'
									)
								)

							) as nomer_po

						FROM `aston_data_po` WHERE po_cabang_id = '".$cabang_id."' 						
					");
		
		

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function number_kode_akun_single($mode,$identifier)
	{
		$query =	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_kode_akun 
						WHERE akun_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function number_kode_akun($q=false,$perpage=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && ( a.akun_nama LIKE '%".addslashes($q)."%' || a.akun_nomer LIKE '%".addslashes($q)."%') ";
		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."setting_kode_akun a 
							WHERE a.akun_id > '0' $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT *
						FROM ".$this->CI->db->dbprefix."setting_kode_akun a
						
						WHERE a.akun_id > '0' $append

						ORDER BY a.akun_id DESC 

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
