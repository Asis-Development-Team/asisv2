<?php

class Pembelian_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	public function po_supplier_single($mode,$identifier,$sess_user_cabang_id=false)
	{

		//validasi permintaan data pake cabang_id
		//untuk menghindari inject Customer ID dari address bar
		$append =	'';

		if($sess_user_cabang_id==true):
			$append .=	" && po_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * 
							
							,DATE_FORMAT(a.po_tgl_input, '%Y-%m-%d') as tanggal_po_input
							,DATE_FORMAT(a.po_tgl_pesan, '%Y-%m-%d') as tanggal_po

						FROM ".$this->CI->db->dbprefix."data_po a
						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier b ON a.po_supplier_id = b.supplier_code
						WHERE po_".$mode." = '".addslashes($identifier)."' $append
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function po_supplier_po_number($cabang_id)
	{
		
		$query	=	$this->CI->db->query("
						SELECT generate_number_po_supplier(".$cabang_id.",3) as nomer_po
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
		
	}

	public function po_supplier_detail_single($mode,$identifier,$temp=false)
	{
		
		$append =	"";

		if($temp):
			$append .=	" && po_detail_temporary = 'yes' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT 

							a.* ,b.product_nama, c.category_nama, d.merk_nama

							,IF(

								a.po_detail_product_nama IS NULL,
								CONCAT(
									b.product_nama,' (', CONCAT(
										c.category_nama, ' - ', CONCAT(
											d.merk_nama,'', ')'
										)
									)
								), a.po_detail_product_nama

							) as detail_nama_produk

						FROM ".$this->CI->db->dbprefix."data_po_detail a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.po_detail_product_kode = b.product_kode 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_kode
						WHERE a.po_detail_".$mode." = '".addslashes($identifier)."' $append
						GROUP BY b.product_kode

					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;
		

	}

	public function po_supplier($q=false,$perpage=false,$cabang=false,$proses=false,$from=false,$to=false)
	{
		
		//$this->CI->db->cache_on();

		$append 	=	'';

		if($q):
			$append .=	" && ( a.po_nomer_po LIKE '%".addslashes($q)."%' || a.po_no_penawaran LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.po_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($proses != NULL):
			$append .=	" && a.po_status = '".addslashes($proses)."' ";
		endif;

		if($from && $to):

			$append	.=	" && a.po_tgl_input BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";

		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_po a 
							WHERE a.po_id > '0' $append "
						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&proses='.@$_GET['proses'].'&cabang='.$cabang.'&show=' . $perpage .'&from=' . @$_GET['from'] . '&to=' . @$_GET['to'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	


		$query 	=	$this->CI->db->query("

						SELECT 
							a.*, b.cabang_nama, c.user_fullname as user, d.supplier_nama
							
							,IF(
								a.po_status = '1', '<span style=\"color:#3598dc\">Sudah di Proses</span>', '<span style=\"color:#e7505a\">Belum di Proses</span>' 
							) as status_pox

							,IF(
								a.po_status = '1', '<span style=\"color:#e7505a\">Belum di Proses</span>', 
								IF(
									a.po_status = '2', '<span style=\"color:#1BBC9B\">Sudah di Proses</span>', '<span style=\"color:#3598dc\">Diterima</span>'
								) 
							) as status_po

							,DATE_FORMAT(
								a.po_tgl_pesan, '%Y-%m-%d'
							) as tgl_po 

							,IF(
								a.po_tgl_pesan = '0000-00-00 00:00:00', DATE_FORMAT(a.po_tgl_input,'%Y-%m-%d'), DATE_FORMAT(a.po_tgl_pesan,'%Y-%m-%d')
							) as tgl_po_baru

							,DATE_FORMAT(
								a.po_tgl_input, '%d-%m-%Y'
							) as tgl_po_input

							,IF(
								a.po_cara_bayar = 'lunas', 
								(SELECT rekening_nama FROM ".$this->CI->db->dbprefix."data_rekening WHERE rekening_kode = a.po_akun_bayar),
								a.po_hari_jatuh_tempo
							) as akun_bayar

							,IF(
								a.po_status = '3', 
								(SELECT penerimaan_no_surat_jalan FROM ".$this->CI->db->dbprefix."data_penerimaan WHERE penerimaan_no_po = a.po_nomer_po ORDER BY penerimaan_id DESC LIMIT 1),
								''
							) as nomer_spj

							,IF(
								a.po_status = '3',
								(SELECT penerimaan_tanggal FROM ".$this->CI->db->dbprefix."data_penerimaan WHERE penerimaan_no_po = a.po_nomer_po ORDER BY penerimaan_id DESC LIMIT 1),
								''
							) as tanggal_terima

						FROM ".$this->CI->db->dbprefix."data_po a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang b ON a.po_cabang_id = b.cabang_id
						LEFT JOIN ".$this->CI->db->dbprefix."data_user c ON a.po_user_id = c.user_id 
						LEFT JOIN ".$this->CI->db->dbprefix."data_supplier d ON a.po_supplier_id = d.supplier_code

						WHERE a.po_id > '0' $append

						ORDER BY a.po_id DESC 

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

	public function po_outlet_single_detail($mode,$identifier,$sess_user_cabang_id=false)
	{	

		$append =	"";
		
		if($sess_user_cabang_id):
			$append .=	" && penawaran_detail_cabang_id = '".$sess_user_cabang_id."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT a.*, b.product_hpp as harga 
							
							,a.penawaran_detail_jumlah * a.penawaran_detail_harga as sub_total

						FROM ".$this->CI->db->dbprefix."data_penawaran_outlet_detail a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.penawaran_detail_product_kode = b.product_kode
						WHERE penawaran_detail_".$mode." = '".addslashes($identifier)."'
						GROUP BY b.product_kode
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;
	}	

	public function po_outlet_single($mode,$identifier,$sess_user_cabang_id=false)
	{

		//validasi permintaan data pake cabang_id
		//untuk menghindari inject Customer ID dari address bar
		$append =	'';

		if($sess_user_cabang_id==true):
			$append .=	" && penawaran_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;

		$single 	=	$this->CI->db->query("
							SELECT a.*, b.user_fullname as nama_user
								,DATE_FORMAT(penawaran_tanggal_pesan, '%Y-%m-%d') as tanggal_po
							FROM ".$this->CI->db->dbprefix."data_penawaran_outlet a
							LEFT JOIN ".$this->CI->db->dbprefix."data_user b ON a.penawaran_user_id = b.user_id
							WHERE penawaran_".$mode." = '".addslashes($identifier)."' $append
						");

		$total 		=	$single->num_rows();
		$single 	=	$single->row_array();

		$detail 	=	$this->CI->db->query("
							SELECT * FROM ".$this->CI->db->dbprefix."data_penawaran_outlet_detail WHERE 
							penawaran_detail_nomer = '".$single['penawaran_nomer']."'
						")->result_array();

		$callback	=	array(
							'total' => $total,
							'result' => $single,
							'detail' => $detail
						);

		return $callback;

	}

	public function po_penerimaan_number($cabang_id)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_number_penerimaan(".$cabang_id.",4) as nomer
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function po_outlet_po_number($cabang_id)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_number_po_outlet(".$cabang_id.",2) as nomer_po
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function po_outlet_temp($cabang,$nomer_order)
	{
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."temp_po_outlet WHERE 
						temp_cabang_id = '".addslashes($cabang)."' && temp_nomer_order = '".$nomer_order."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;
	}

	public function po_outlet($q=false,$perpage=false,$cabang=false,$proses=false,$from=false,$to=false)
	{
		
		//$this->CI->db->cache_on();

		$append 	=	'';

		if($q):
			$append .=	" && ( a.penawaran_nomer LIKE '%".addslashes($q)."%' ) ";
		endif;

		if($cabang):
			$append .=	" &&  a.penawaran_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($proses != NULL):
			$append .=	" && a.penawaran_status = '".addslashes($proses)."' ";
		endif;

		if($from && $to):
			$append	.=	" && a.penawaran_tanggal_pesan BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_penawaran_outlet a 
							WHERE a.penawaran_id > '0' $append "
						);



		$perpage	=	($perpage) ? $perpage : $total;


		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang='.$cabang.'&show=' . $perpage .'&from=' . @$_GET['from'] . '&to=' . @$_GET['to'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	


		$query 	=	$this->CI->db->query("

						SELECT 
							a.*, b.cabang_nama, c.user_fullname as user
							
							,IF(
								a.penawaran_status = '1', '<span style=\"color:#3598dc\">Sudah di Proses</span>', '<span style=\"color:#e7505a\">Belum di Proses</span>' 
							) as status_po

							,DATE_FORMAT(
								a.penawaran_tanggal_pesan, '%d-%m-%Y'
							) as tgl_po 

						FROM ".$this->CI->db->dbprefix."data_penawaran_outlet a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang b ON a.penawaran_cabang_id = b.cabang_id
						LEFT JOIN ".$this->CI->db->dbprefix."data_user c ON a.penawaran_user_id = c.user_id 

						WHERE a.penawaran_id > '0' $append

						ORDER BY a.penawaran_id DESC 

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
							'sisa_data' => $sisa_data,
						);	
		
		return $callback;			
	}	

}
?>
