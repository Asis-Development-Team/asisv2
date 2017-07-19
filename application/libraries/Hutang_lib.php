<?php

class Hutang_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}


	//datanya salah
	public function zdata_pembayaran_hutang($q=false,$perpage=20,$supplier=false,$cabang=false,$from=false,$to=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			//$append .=	" && (a.penerimaan_no_penerimaan LIKE '%".addslashes($q)."%' || a.penerimaan_no_po LIKE '%".addslashes($q)."%' || a.penerimaan_no_surat_jalan LIKE '%".addslashes($q)."%' )";			
			
		endif;

		if($cabang):
			//$append .=	" && a.hutang_cabang_id = '".addslashes($cabang)."' ";
		endif;		

		if($supplier):
			//$append .=	" && a.hutang_supplier_id = '".addslashes($supplier)."' ";
		endif;

		if($from && $to):
			$append	.=	" && a.pembayaran_tanggal_faktur BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;		

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_pembayaran_hutang a 
							
							LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.pembayaran_supplier_id = b.supplier_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.pembayaran_cabang_id = c.cabang_id

							WHERE a.pembayaran_id > '0' $append							
							"
						);

		$perpage	=	($perpage) ? $perpage : $total;


		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&supplier=' . @$_GET['supplier'],
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

				
						FROM ".$this->CI->db->dbprefix."data_pembayaran_hutang a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.pembayaran_supplier_id = b.supplier_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.pembayaran_cabang_id = c.cabang_id			
						
						WHERE a.pembayaran_id > '0' $append 

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


	public function data_hutang_usaha_detail_periode($supplier,$cabang)
	{
		$query 	=	$this->CI->db->query("
						SELECT  
						   b.supplier_nama, a.hutang_cabang_id, a.hutang_kode, a.hutang_tanggal_faktur, a.hutang_nomer_order, a.hutang_nomer_surat_jalan,
							 IFNULL(c.hutang_saldo,'0') as periode_pertama, IFNULL(d.hutang_saldo,'0') as periode_kedua, IFNULL(e.hutang_saldo,'0') as periode_ketiga,
							 IFNULL(f.hutang_saldo,'0') as periode_keempat

						FROM aston_data_hutang_usaha a 
						LEFT JOIN aston_data_supplier b ON a.hutang_supplier_id = b.supplier_code

						LEFT JOIN (
							SELECT hutang_nomer_order, hutang_jumlah, hutang_saldo FROM aston_data_hutang_usaha 
							WHERE DATE(hutang_tanggal_faktur) <= CURDATE() 
							AND DATE(hutang_tanggal_faktur) > CURDATE() - INTERVAL 30 DAY
							GROUP BY hutang_nomer_order
						) c ON a.hutang_nomer_order = c.hutang_nomer_order

						LEFT JOIN (
							SELECT hutang_nomer_order, hutang_jumlah, hutang_saldo FROM aston_data_hutang_usaha 
							WHERE DATE(hutang_tanggal_faktur) <= CURDATE() - INTERVAL 31 DAY
							AND DATE(hutang_tanggal_faktur) > CURDATE() - INTERVAL 60 DAY 
							GROUP BY hutang_nomer_order
						) d ON a.hutang_nomer_order = d.hutang_nomer_order

						LEFT JOIN (
							SELECT hutang_nomer_order, hutang_jumlah, hutang_saldo FROM aston_data_hutang_usaha 
							WHERE DATE(hutang_tanggal_faktur) <= CURDATE() - INTERVAL 61 DAY
							AND DATE(hutang_tanggal_faktur) > CURDATE() - INTERVAL 90 DAY
							GROUP BY hutang_nomer_order
						) e ON a.hutang_nomer_order = e.hutang_nomer_order

						LEFT JOIN (
							SELECT hutang_nomer_order, hutang_jumlah, hutang_saldo FROM aston_data_hutang_usaha 
							WHERE DATE(hutang_tanggal_faktur) <= CURDATE() - INTERVAL 91 DAY
							
							GROUP BY hutang_nomer_order
						) f ON a.hutang_nomer_order = f.hutang_nomer_order 

						WHERE a.hutang_supplier_id = '".addslashes($supplier)."' AND a.hutang_cabang_id = '".$cabang."'

					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;

	}		

	public function data_hutang($q=false,$perpage=20,$supplier=false,$cabang=false,$group=false,$lunas=false)
	{
		
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			//$append .=	" && (a.penerimaan_no_penerimaan LIKE '%".addslashes($q)."%' || a.penerimaan_no_po LIKE '%".addslashes($q)."%' || a.penerimaan_no_surat_jalan LIKE '%".addslashes($q)."%' )";			
			
		endif;

		if($cabang):
			$append .=	" && a.hutang_cabang_id = '".addslashes($cabang)."' ";
		endif;		

		if($supplier):
			$append .=	" && a.hutang_supplier_id = '".addslashes($supplier)."' ";
		endif;

		$grouping 	=	($group==true) ? " GROUP BY a.hutang_supplier_id, a.hutang_cabang_id " : "";

		
		if($lunas == 'belum'):
			$append .=	" && a.hutang_saldo > '0' ";
		elseif($lunas == 'lunas'):
			$append .=	" && a.hutang_saldo = '0' ";
		else:
			$append	.=	"";
		endif;
		

		/*
		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_hutang_usaha a 
							
							LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.hutang_supplier_id = b.supplier_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.hutang_cabang_id = c.cabang_id

							WHERE a.hutang_id > '0' $append
							GROUP BY a.hutang_supplier_id, a.hutang_cabang_id
							"
						);
		*/

		//,SUM(a.hutang_jumlah) as hutang_jumlah_total, SUM(a.hutang_terbayar) as hutang_terbayar_total, SUM(a.hutang_saldo) as hutang_saldo_total

		$query	=	$this->CI->db->query("
						
						SELECT * 					

							,(SELECT SUM(hutang_jumlah) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_jumlah_total
							,(SELECT SUM(hutang_terbayar) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_terbayar_total
							,(SELECT SUM(hutang_saldo) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_saldo_total
							

						FROM ".$this->CI->db->dbprefix."data_hutang_usaha a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.hutang_supplier_id = b.supplier_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.hutang_cabang_id = c.cabang_id			
						
						WHERE a.hutang_id > '0' $append 

						$grouping
					");	

		$total 		=	$query->num_rows();

		$perpage	=	($perpage) ? $perpage : $total;


		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&supplier=' . @$_GET['supplier'] . '&cabang=' . @$_GET['cabang'],
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

							,(SELECT SUM(hutang_jumlah) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_jumlah_total
							,(SELECT SUM(hutang_terbayar) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_terbayar_total
							,(SELECT SUM(hutang_saldo) FROM aston_data_hutang_usaha WHERE hutang_cabang_id = a.hutang_cabang_id && hutang_supplier_id = a.hutang_supplier_id) as hutang_saldo_total


						FROM ".$this->CI->db->dbprefix."data_hutang_usaha a

						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.hutang_supplier_id = b.supplier_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.hutang_cabang_id = c.cabang_id
		
						
						WHERE a.hutang_id > '0' $append 

						$grouping

						ORDER BY b.supplier_nama ASC

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
