<?php 
class Data_Lib{

	var $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library(array('tools'));
	}

	public function data_produk_rakitan_detail_single($mode,$identifier,$cabang)
	{	

		$append 	=	'';

		if($cabang):
			$append .=	" && a.rakitan_detail_cabang_id = '".addslashes($cabang)."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT a.*, b.product_nama, c.merk_nama, d.category_nama, CONCAT_WS(' - ',b.product_nama, c.merk_nama, d.category_nama) as nama_produk 
						FROM ".$this->CI->db->dbprefix."setting_produk_rakitan_detail a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.rakitan_detail_kode_produk = b.product_kode 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON b.product_merk_id = c.merk_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori d ON b.product_category_id = d.category_id
						WHERE a.rakitan_detail_".$mode." = '".addslashes($identifier)."' $append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;

	}

	public function data_produk_rakitan_number($cabang_id)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_rakitan_kode(".$cabang_id.",16) as nomer_rakitan
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function data_produk_rakitan($q=false,$perpage=false,$cabang=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.rakitan_detail_kode_produk LIKE '%".addslashes($q)."%' || b.product_nama LIKE '%".addslashes($q)."%' || d.merk_nama LIKE '%".addslashes($q)."%' || c.category_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.rakitan_detail_cabang_id = '".addslashes($cabang)."' ";
		endif;

		$query 	=	$this->CI->db->query("

						SELECT 


							a.rakitan_detail_id, a.rakitan_detail_cabang_id as cabang_id, a.rakitan_detail_kode_produk as produk_kode, b.product_nama, d.merk_nama, c.category_nama
							,(SELECT cabang_nama FROM aston_setting_cabang WHERE cabang_id = a.rakitan_detail_cabang_id) as nama_cabang

						FROM ".$this->CI->db->dbprefix."setting_produk_rakitan_detail a
						
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.rakitan_detail_kode_produk = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_id
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_rakitan e ON a.rakitan_detail_kode_produk = e.rakitan_product_kode

						WHERE a.rakitan_detail_id > '0' $append

						GROUP BY a.rakitan_detail_kode_produk, a.rakitan_detail_cabang_id
						ORDER BY a.rakitan_detail_id DESC 

					");

		$total 	=	$query->num_rows();

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT 


							a.rakitan_detail_id, a.rakitan_detail_cabang_id as cabang_id, a.rakitan_detail_kode_produk as produk_kode, b.product_nama, d.merk_nama, c.category_nama
							,(SELECT cabang_nama FROM aston_setting_cabang WHERE cabang_id = a.rakitan_detail_cabang_id) as nama_cabang

						FROM ".$this->CI->db->dbprefix."setting_produk_rakitan_detail a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.rakitan_detail_kode_produk = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_id
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_rakitan e ON a.rakitan_detail_kode_produk = e.rakitan_product_kode

						WHERE a.rakitan_detail_id > '0' $append

						GROUP BY a.rakitan_detail_kode_produk, a.rakitan_detail_cabang_id
						ORDER BY a.rakitan_detail_id DESC 

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

	public function data_bank_single($mode,$identifier)
	{
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_bank 
						WHERE bank_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}



	public function data_harga_jual($q=false,$perpage=false,$cabang=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.stok_produk_kode LIKE '%".addslashes($q)."%' || b.product_nama LIKE '%".addslashes($q)."%' || c.merk_nama LIKE '%".addslashes($q)."%' || d.category_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.stok_cabang_id = '".addslashes($cabang)."' ";
		endif;


		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_stock a 
							LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.stok_produk_kode = b.product_kode
							LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON b.product_merk_id = c.merk_id
							LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori d ON b.product_category_id = d.category_id

							WHERE a.stok_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT a.*, b.product_nama,b.product_hpp, c.merk_nama, d.category_nama, d.category_hpp_user, d.category_hpp_dealer 
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.stok_cabang_id) as nama_cabang
							,CONCAT_WS(' - ',d.category_nama, c.merk_nama, b.product_nama) as nama_produk

							,IF(
								b.product_harga_jual_user IS NULL, 
								b.product_hpp + (b.product_hpp * category_hpp_user / 100),
								b.product_harga_jual_user
							) as hpp_user_2

							,IF(
								b.product_harga_jual_dealer IS NULL, 
								b.product_hpp + (b.product_hpp * category_hpp_dealer / 100),
								b.product_harga_jual_dealer
							) as hpp_dealer_2

							,b.product_hpp + (b.product_hpp * category_hpp_user / 100) as hpp_user
							,b.product_hpp + (b.product_hpp * category_hpp_dealer / 100) as hpp_dealer

						FROM ".$this->CI->db->dbprefix."data_stock a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.stok_produk_kode = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk c ON b.product_merk_id = c.merk_id
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori d ON b.product_category_id = d.category_id

						WHERE a.stok_id > '0' $append

						ORDER BY a.stok_id DESC

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

	public function data_bank($q=false,$perpage=20)
	{

		$append 	=	'';

		if($q):
			$append .=	" && (a.bank_nama LIKE '%".addslashes($q)."%' || a.bank_no_rekening LIKE '%".addslashes($q)."%') ";
		endif;


		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_bank a

							WHERE a.bank_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT a.*

						FROM ".$this->CI->db->dbprefix."data_bank a 

						WHERE a.bank_id > '0' $append

						ORDER BY a.bank_id DESC

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

	public function data_stok_sn($cabang=false,$q=false,$perpage=20,$type=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.stock_detail_nomer_order LIKE '%".addslashes($q)."%' || a.stock_detail_produk_kode LIKE '%".addslashes($q)."%' || a.stock_detail_produk_nama LIKE '%".addslashes($q)."%' || a.stock_detail_barcode LIKE '%".addslashes($q)."%' || a.stock_detail_serial_number LIKE '%".addslashes($q)."%' ) ";
		endif;

		if($cabang):
			$append .=	" &&  a.stock_detail_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($type):
			$append .=	" && a.stock_detail_type = '".addslashes($type)."'";
		endif;

		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_stock_product_detail a WHERE stock_detail_id > '0' $append "
						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage . '&urut=' . @$_GET['urut'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_stock_product_detail a
						
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang b ON a.stock_detail_cabang_id = b.cabang_id

						WHERE a.stock_detail_id > '0' $append
						GROUP BY a.stock_detail_id DESC					

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

	public function data_stok($cabang=false,$q=false,$perpage=20,$urut=false,$positif=false)
	{

		$append 	=	'';

		if($q):
			$append .=	" && (a.stok_produk_kode LIKE '%".addslashes($q)."%' || b.product_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.stok_cabang_id = '".addslashes($cabang)."' ";
		endif;

		$order 	=	" ORDER BY a.stok_id DESC";
		
		if($urut == 'tinggi'):		
			$order =	" ORDER BY a.stok_jumlah DESC ";		
		elseif($urut == 'rendah'):		
			$order =	" ORDER BY a.stok_jumlah ASC ";
		endif;

		if($positif):
			$append .=	" && a.stok_jumlah > '0' ";
		endif;

		/*
		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_stock a 

						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.stok_produk_kode = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_kode

						WHERE a.stok_id > '0' $append
						GROUP BY b.product_kode
						"
						);
		*/

		$query 	=	$this->CI->db->query("

						SELECT a.*, b.product_nama, c.category_nama, d.merk_nama, b.product_hpp, c.category_id as produk_kategori_id
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.stok_cabang_id) as nama_cabang


						FROM ".$this->CI->db->dbprefix."data_stock a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.stok_produk_kode = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_kode

						WHERE a.stok_id > '0' $append
						GROUP BY b.product_kode

						$order

					");

		$total 	=	$query->num_rows();

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage . '&urut=' . @$_GET['urut'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT a.*, b.product_nama, c.category_nama, d.merk_nama, b.product_hpp, c.category_id as produk_kategori_id
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.stok_cabang_id) as nama_cabang


						FROM ".$this->CI->db->dbprefix."data_stock a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk b ON a.stok_produk_kode = b.product_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_produk_kategori c ON b.product_category_id = c.category_kode
						LEFT JOIN ".$this->CI->db->dbprefix."setting_merk_produk d ON b.product_merk_id = d.merk_kode

						WHERE a.stok_id > '0' $append
						GROUP BY b.product_kode

						$order

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

	public function data_pengguna_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						
						SELECT a.*, b.user_level_name 
		
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.user_cabang_id) as nama_cabang

						FROM ".$this->CI->db->dbprefix."data_user a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_user_level b ON a.user_level_id = b.user_level_id

						WHERE a.user_".$mode." = '".addslashes($identifier)."'

					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}	

	public function data_pengguna($cabang=false,$q=false,$perpage=false,$user_level=false)
	{

		$append 	=	'';

		if($q):
			$append .=	" && (a.user_fullname LIKE '%".addslashes($q)."%' || a.user_username LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.user_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($user_level):
			$append .=	" && a.user_level_id = '".addslashes($user_level)."' ";
		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_user a 

							WHERE a.user_id > '1' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT a.*, b.user_level_name 
		
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.user_cabang_id) as nama_cabang

						FROM ".$this->CI->db->dbprefix."data_user a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_user_level b ON a.user_level_id = b.user_level_id
						WHERE a.user_id > '1' $append

						ORDER BY a.user_id DESC

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

	public function data_rekening_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						
						SELECT a.*, b.setting_ka_nama as sub_klasifikasi_akun 
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.rekening_cabang_id) as nama_cabang
							,(SELECT setting_ka_nama FROM ".$this->CI->db->dbprefix."setting_klasifikasi_akun WHERE setting_ka_id = b.setting_ka_parent_id) as klasifikasi_akun

						FROM ".$this->CI->db->dbprefix."data_rekening a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_klasifikasi_akun b ON a.rekening_no_akun = b.setting_ka_sub_no_akun

						WHERE a.rekening_".$mode." = '".addslashes($identifier)."'

					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}


	public function data_rekening($q=false,$perpage=false,$start=false,$no_akun=false,$kas_bank=false,$conditional=false,$cabang=false,$opsi_keuangan=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.rekening_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($no_akun):
			$append	.=	" && rekening_no_akun = '".addslashes($no_akun)."' ";
		endif;

		if($kas_bank):
			$append .=	" && rekening_kas_bank = 'Yes' ";
		endif;			
		
		if($cabang):
			$append .=	" && a.rekening_cabang_id = '".addslashes($cabang)."'";
		endif;	

		if($conditional):
			$append .=	" || rekening_kode='21096' || rekening_kode='21092' || rekening_kode='21098' || rekening_kode='21099' ";
		endif;

		if($opsi_keuangan):

			$append .=	" && substr(rekening_kode,1,2)='61' or "
                        . "substr(rekening_kode,1,2)='11' or "
                        . "substr(rekening_kode,1,2)='71' or "
                        . "substr(rekening_kode,1,2)='91' or "
						. "substr(rekening_kode,1,2)='12' or "
                        . "rekening_kode='52020' or  "
                        . "rekening_kode='15002' or  "
                        . "rekening_kode='13010' or  "
                        . "rekening_kode='21050' or  "
                        . "rekening_kode='51014' or  "
                        . "rekening_kode='13016' or  "
                        . "rekening_kode='11010' or  "
                        . "rekening_kode='13007' or  "
                        . "rekening_kode='21070' or  "
                        . "rekening_kode='21071' or  "
                        . "rekening_kode='21072' or  "
                        . "rekening_kode='21091' or  "
                        . "rekening_kode='21040' or  "
                        . "rekening_kode='13018' or  "
                        . "rekening_kode='17005' or  "
                        . "rekening_kode='17006' or  "
                        . "rekening_kode='17007' or  "
                        . "rekening_kode='13019' or  "
                        . "rekening_kode='12025' or  "
                        . "rekening_kode='15001' or  "
                        . "rekening_kode='13005' or  "
                        . "rekening_kode='81015' or  "
                        . "rekening_kode='21080' or  "
                        . "rekening_kode='21092' or  "
                        . "rekening_kode='21094' or  "
                        . "rekening_kode='21096' or  "
                        . "rekening_kode='51013' 

						";

		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_rekening a 

							WHERE a.rekening_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage . '&cabang=' . @$_GET['cabang'],
							$total,$perpage
						);

		if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];	
		else:
			$start 	=	'0';
		endif;

		$query 	=	$this->CI->db->query("

						SELECT a.*, b.setting_ka_nama as sub_klasifikasi_akun 

							,c.cabang_nama
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.rekening_cabang_id) as nama_cabang
							,(SELECT setting_ka_nama FROM ".$this->CI->db->dbprefix."setting_klasifikasi_akun WHERE setting_ka_id = b.setting_ka_parent_id) as klasifikasi_akun

						FROM ".$this->CI->db->dbprefix."data_rekening a 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_klasifikasi_akun b ON a.rekening_no_akun = b.setting_ka_sub_no_akun
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.rekening_cabang_id = c.cabang_id

						WHERE a.rekening_id > '0' $append
						ORDER BY a.rekening_id ASC

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

	public function data_karyawan_single($mode,$identifier,$sess_user_cabang_id=false)
	{

		$append = "";

		//validasi permintaan data pake cabang_id
		//untuk menghindari inject Customer ID dari address bar
		if($sess_user_cabang_id==true):
			$append .=	" && karyawan_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;


		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_karyawan
						WHERE karyawan_".$mode." = '".addslashes($identifier)."'
						$append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function data_karyawan($q=false,$perpage=false,$cabang=false)
	{
		$append 	=	'';

		if($q):
			$append .=	" && (a.karyawan_nama LIKE '%".addslashes($q)."%' || a.karyawan_kode_identitas LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.karyawan_cabang_id = '".addslashes($cabang)."' ";
		endif;


		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_karyawan a 

							WHERE a.karyawan_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT * 
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.karyawan_cabang_id) as nama_cabang



						FROM ".$this->CI->db->dbprefix."data_karyawan a 

						WHERE a.karyawan_id > '0' $append

						ORDER BY a.karyawan_id DESC

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

	public function data_supplier_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_supplier 
						WHERE supplier_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function data_supplier($q=false,$perpage=false)
	{

		$append 	=	'';

		if($q):
			$append .=	" && (a.supplier_nama LIKE '%".addslashes($q)."%') ";
		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_supplier a 

							WHERE a.supplier_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT * 
							
						FROM ".$this->CI->db->dbprefix."data_supplier a 

						WHERE a.supplier_id > '0' $append

						ORDER BY a.supplier_id DESC

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

	public function data_pelanggan_single($mode,$identifier,$sess_user_cabang_id=false)
	{	

		$append = "";

		//validasi permintaan data pake cabang_id
		//untuk menghindari inject Customer ID dari address bar
		if($sess_user_cabang_id==true):
			$append .=	" && pelanggan_cabang_id = '".addslashes($sess_user_cabang_id)."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_pelanggan 
						WHERE pelanggan_".$mode." = '".addslashes($identifier)."' 
						$append
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;

	}

	public function data_pelanggan($cabang=false,$q=false,$perpage=10,$type=false)
	{

		$this->CI->db->cache_on();
		
		$append 	=	'';

		if($q):
			$append .=	" && (a.pelanggan_nama LIKE '%".addslashes($q)."%' || a.pelanggan_instansi_nama LIKE '%".addslashes($q)."%') ";
		endif;

		if($cabang):
			$append .=	" &&  a.pelanggan_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($type):

			if($type=='1'):
				$type =	'Perseorangan';
			elseif($type=='2'):
				$type =	'Instansi Pemerintah';
			elseif($type=='3'):
				$type =	'Perusahaan Swasta';
			elseif($type=='4'):
				$type =	'Lembaga Pendidikan';
			elseif($type=='5'):
				$type =	'Bank';
			endif;

			$append .=	" && a.pelanggan_type = '".addslashes($type)."' ";

		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_pelanggan a 

							WHERE a.pelanggan_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&cabang=' . $cabang . '&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	

		$query 	=	$this->CI->db->query("

						SELECT * 
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = a.pelanggan_cabang_id) as nama_cabang



						FROM ".$this->CI->db->dbprefix."data_pelanggan a 

						WHERE a.pelanggan_id > '0' $append

						ORDER BY a.pelanggan_id DESC

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

	public function data_rek_laba_rugi_sub_kasifikasi($mode,$fields=false,$identifier=false)
	{

		$where 	=	"";

		if($mode=='klasifikasi'):
			$where =	" WHERE setting_lrm_sub_klasifikasi = '".$identifier."' ";
		endif;

		$query	=	$this->CI->db->query("
						SELECT DISTINCT(".$fields.") as nama_field FROM ".$this->CI->db->dbprefix."data_rekening_laba_rugi 
						$where
						ORDER BY ".$fields." ASC
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array(),
						);

		return $callback;

	}

	public function data_rek_laba_rugi_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_rekening_laba_rugi 
						WHERE setting_lrm_".$mode." = '".addslashes($identifier)."'
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;
	}

	public function data_rek_laba_rugi($q=false,$perpage=10)
	{

		$append 	=	'';

		if($q):
			$append .=	" && (a.setting_lrm_sub_klasifikasi LIKE '%".addslashes($q)."%' || a.setting_lrm_klasifikasi LIKE '%".addslashes($q)."%' || b.rekening_nama LIKE '%".addslashes($q)."%' ) ";
		endif;

		$total 		=	$this->CI->db->count_all(

							$this->CI->db->dbprefix."data_rekening_laba_rugi a 
							LEFT JOIN ".$this->CI->db->dbprefix."data_rekening b ON a.setting_lrm_rekening_kode = b.rekening_kode 

							WHERE a.setting_lrm_id > '0' $append"

						);

		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'] .'&show=' . $perpage,
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];	


		$query 	=	$this->CI->db->query("

						SELECT * 
							
							,(SELECT cabang_nama FROM ".$this->CI->db->dbprefix."setting_cabang WHERE cabang_id = b.rekening_cabang_id) as nama_cabang

						FROM ".$this->CI->db->dbprefix."data_rekening_laba_rugi a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_rekening b ON a.setting_lrm_rekening_kode = b.rekening_kode 

						WHERE setting_lrm_id > '0' $append

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

	public function data_user_level($q=false,$perpage=10)
	{
		
		$append		=	'';
		
		if($q):
			$append .=	" && a.user_level_name LIKE '%".addslashes($key)."%' ";						
		endif;

		$total 		=	$this->CI->db->count_all($this->CI->db->dbprefix."data_user_level a WHERE a.user_level_id > '1' $append");
		$perpage	=	($perpage) ? $perpage : $total;
		
		$paging 	=	$this->CI->utility->pagination_setting(
							'?q=' . @$_GET['q'],
							$total,$perpage
						);

		$start 		=	(!@$_GET['page']) ? '0' : @$_GET['page'];		

		$query	=	$this->CI->db->query("
						SELECT a.* 
						FROM ".$this->CI->db->dbprefix."data_user_level a
						WHERE a.user_level_id > '1' $append 
						ORDER BY a.user_level_id ASC
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