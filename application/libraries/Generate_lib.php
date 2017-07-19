<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_lib extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;

	var $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library(array('tools'));
	}

	public function generate_buku_besar_sales($cabang_id)
	{
		$query =	$this->CI->db->query("
						SELECT generate_buku_besar_sales_kode(".$cabang_id.",'9') as kode_buku_besar
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;			
	}

	public function generate_kode_piutang($cabang_id)
	{
		$query =	$this->CI->db->query("
						SELECT generate_invoice_penjualan(".$cabang_id.",'11') as kode_piutang
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;			
	}

	public function generate_invoice_penjualan($cabang_id,$transaks_id)
	{
		$query =	$this->CI->db->query("
						SELECT generate_invoice_penjualan(".$cabang_id.",'".$transaks_id."') as nomer_invoice
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;			
	}
	
	public function generate_invoice_penjualan_tunda($cabang_id,$transaks_id)
	{
		$query =	$this->CI->db->query("
						SELECT generate_invoice_penjualan_tunda(".$cabang_id.",'".$transaks_id."') as nomer_invoice
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;			
	}

	public function generate_pembayaran_hutang_kode($cabang_id)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_number_pembayaran_hutang(".$cabang_id.",6) as nomer_kode
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;		
	}

	public function generate_kode_akun($id)
	{
		$query 	=	$this->CI->db->query("
						SELECT akun_nomer FROM ".$this->CI->db->dbprefix."setting_kode_akun 
						WHERE akun_id = '".$id."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function generate_jurnal_kode($cabang_id)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_number_jurnal(".$cabang_id.",15) as nomer_jurnal
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;		
	}

	public function generate_customer_code($cabang_id)
	{

		/*
		$query 	=	$this->CI->db->query("
					
					");

		*/

	}

	public function generate_produk_kode($param)
	{
			
		$query 	=	$this->CI->db->query("

						SELECT 

							IF(
								MAX(product_kode) < 1 OR MAX(product_kode) IS NULL, 
								CONCAT('".$param."','','001'), 
								MAX(product_kode) + 1
							) as kode_produk

						FROM ".$this->CI->db->dbprefix."setting_produk WHERE SUBSTRING(product_kode,1,4) = '".addslashes($param)."'

					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);		

		return $callback;		

	}

	//bikin kode rekening untuk database setting rekening
	//dari menu data rekening
	public function generate_rekening_kode($klasifikasi)
	{

		$query 	=	$this->CI->db->query("
						SELECT 

							IF(
								COUNT(rekening_kode) > 0
								,MAX(rekening_kode) + 1
								,CONCAT(
									'".$klasifikasi."','','001'
								)
							) as rekening_kode

						FROM ".$this->CI->db->dbprefix."data_rekening WHERE rekening_no_akun = '".$klasifikasi."'	
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);		

		return $callback;
	
	}


}