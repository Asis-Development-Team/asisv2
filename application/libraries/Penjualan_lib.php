<?php

class penjualan_lib{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		//$this->CI->load->library(array('tools'));
		//$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}

	//untuk detail piutang usaha popup
	public function data_piutang_kustomer_list($kustomer)
	{
		$query 	= 	$this->CI->db->query("
						SELECT 

							a.piutang_id,a.piutang_kustomer_kode,b.pelanggan_nama,a.piutang_no_invoice,a.piutang_tanggal_faktur 
							,IFNULL(c.piutang_saldo,'0') as periode_pertama
							,IFNULL(d.piutang_saldo,'0') as periode_kedua
							,IFNULL(e.piutang_saldo,'0') as periode_ketiga
							,IFNULL(f.piutang_saldo,'0') as periode_keempat
						
						FROM ".$this->CI->db->dbprefix."data_piutang_usaha a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.piutang_kustomer_kode = b.pelanggan_code

						LEFT JOIN (
							
							SELECT piutang_no_invoice,piutang_jumlah,piutang_saldo 
							FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE 
							DATE(piutang_tanggal_faktur) <= CURDATE() && 
							DATE(piutang_tanggal_faktur) >= CURDATE() - INTERVAL 30 day

							GROUP BY piutang_no_invoice

						) c ON a.piutang_no_invoice = c.piutang_no_invoice

						LEFT JOIN (
							
							SELECT piutang_no_invoice,piutang_jumlah,piutang_saldo 
							FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE 
							DATE(piutang_tanggal_faktur) <= CURDATE() - INTERVAL 31 day && 
							DATE(piutang_tanggal_faktur) >= CURDATE() - INTERVAL 60 day

							GROUP BY piutang_no_invoice

						) d ON a.piutang_no_invoice = d.piutang_no_invoice

						LEFT JOIN (
							
							SELECT piutang_no_invoice,piutang_jumlah,piutang_saldo 
							FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE 
							DATE(piutang_tanggal_faktur) <= CURDATE() - INTERVAL 61 day && 
							DATE(piutang_tanggal_faktur) >= CURDATE() - INTERVAL 90 day

							GROUP BY piutang_no_invoice

						) e ON a.piutang_no_invoice = e.piutang_no_invoice

						LEFT JOIN (
							
							SELECT piutang_no_invoice,piutang_jumlah,piutang_saldo 
							FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE 
							DATE(piutang_tanggal_faktur) <= CURDATE() - INTERVAL 91 day 

							GROUP BY piutang_no_invoice

						) f ON a.piutang_no_invoice = f.piutang_no_invoice


						WHERE a. piutang_kustomer_kode = '".addslashes($kustomer)."' 
						ORDER BY piutang_id DESC
					");


		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array()
						);

		return $callback;

	}


	public function data_penjualan_pembayaran_piutang_single($mode,$identifier,$cabang=false)
	{

		$append =	'';

		if($cabang):
			$append .=	" && a.pembayaran_cabang_id = '".$cabang."' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * 

							,DATE_FORMAT(a.pembayaran_tanggal_faktur,'%Y-%m-%d') as tgl 

						FROM ".$this->CI->db->dbprefix."data_pembayaran a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_piutang_usaha b ON a.pembayaran_no_invoice = b.piutang_no_invoice
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan c ON b.piutang_kustomer_kode = c.pelanggan_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang d ON a.pembayaran_cabang_id = d.cabang_id

						WHERE a.pembayaran_".$mode." = '".$identifier."' $append 
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array()
						);

		return $callback;

	}

	public function data_penjualan_pembayaran_piutang($q=false,$perpage=20,$cabang=false,$from=false,$to=false,$pembayaran=false)
	{
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && a.pembayaran_kode LIKE '%".addslashes($q)."%' || a.pembayaran_no_invoice LIKE '%".addslashes($q)."%' || c.pelanggan_nama LIKE '%".addslashes($q)."%' ";			
		endif;

		if($from && $to):
			$append	.=	" && a.pembayaran_tanggal_faktur BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;

		if($cabang):
			$append .=	" && a.pembayaran_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($pembayaran):
			//$append .=	" && a.invoice_status_pembayaran = '".addslashes($pembayaran)."' ";
		endif;
		
		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_pembayaran a 

							LEFT JOIN ".$this->CI->db->dbprefix."data_piutang_usaha b ON a.pembayaran_no_invoice = b.piutang_no_invoice
							LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan c ON b.piutang_kustomer_kode = c.pelanggan_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang d ON a.pembayaran_cabang_id = d.cabang_id

							WHERE a.pembayaran_tipe = 'piutang' $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&cabang=' . @$_GET['cabang'] . '&from=' . @$_GET['from'] . '&to=' . @$_GET['to'] . '&show=' . @$_GET['show'] .'&pembayaran=' . @$_GET['pembayaran'],
						$total,$perpage
					);

		//if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		//else:
		//	$start 	=	'0';
		//endif;

		$query	=	$this->CI->db->query("
						SELECT 

							a.*, c.pelanggan_nama, d.cabang_nama

						FROM ".$this->CI->db->dbprefix."data_pembayaran a

						LEFT JOIN ".$this->CI->db->dbprefix."data_piutang_usaha b ON a.pembayaran_no_invoice = b.piutang_no_invoice
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan c ON b.piutang_kustomer_kode = c.pelanggan_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang d ON a.pembayaran_cabang_id = d.cabang_id

						WHERE a.pembayaran_tipe = 'piutang'   $append

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

	public function data_piutang_kode_pembayaran($cabang)
	{
		$query	=	$this->CI->db->query("
						SELECT generate_invoice_kode_pembayaran(".$cabang.",12) as nomer_po
					");

		$callback	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array(),
						);

		return $callback;		
	}

	public function data_piutang_invoice_konsumen($mode,$indentifier,$status=false)
	{

		$append =	'';

		if($status == 0):
			$append .=	" && piutang_status = '0' ";
		elseif($status == 1):
			$append	.=	" && piutang_status = '1' ";
		elseif($status == 2):
			$append .=	" && piutang_status = '2' ";
		elseif($status == 9):
			$append .=	" && piutang_status < '2' ";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_piutang_usaha a WHERE 
						piutang_".$mode." = '".addslashes($indentifier)."'  $append 
						ORDER BY piutang_id DESC
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array()
						);

		return $callback;

	}

	public function data_piutang_kustomer($cabang)
	{

		$query 	=	$this->CI->db->query("
						SELECT 
							
							a.piutang_kustomer_kode, b.pelanggan_nama, b.pelanggan_id 

						FROM ".$this->CI->db->dbprefix."data_piutang_usaha a 
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.piutang_kustomer_kode = b.pelanggan_code
						WHERE a.piutang_cabang_id = '".addslashes($cabang)."' && a.piutang_saldo > 0 
						GROUP BY a.piutang_kustomer_kode 
						ORDER BY b.pelanggan_nama ASC 
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->result_array()
						);

		return $callback;

	}

	//data piutang usaha
	public function data_penjualan_piutang_usaha_single($mode,$identifier,$cabang=false)
	{

		$append =	'';

		if($cabang):
			$append .=	" && piutang_cabang_id = '".addslashes($cabang)."'";
		endif;

		$query 	=	$this->CI->db->query("
						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE
						piutang_".$mode." = '".addslashes($identifier)."' 
					");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $query->row_array()
						);

		return $callback;
	}

	public function data_penjualan_piutang_usaha($q=false,$perpage=20,$cabang=false,$from=false,$to=false,$pembayaran=false,$saldo=false)
	{
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && b.pelanggan_nama = '".addslashes($q)."' ";			
		endif;


		if($cabang):
			$append .=	" && a.piutang_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($pembayaran):
			$append .=	" && a.invoice_status_pembayaran = '".addslashes($pembayaran)."' ";
		endif;

		if($saldo):
			$append .=	" && a.piutang_saldo > '0' ";
		endif;
		
		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_piutang_usaha a 

							LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.piutang_kustomer_kode = b.pelanggan_code
							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.piutang_cabang_id = c.cabang_id

							WHERE a.piutang_id > '0' && a.piutang_kustomer_kode = b.pelanggan_code $append"
						);

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&cabang=' . @$_GET['cabang'] . '&from=' . @$_GET['from'] . '&to=' . @$_GET['to'] . '&show=' . @$_GET['show'] .'&pembayaran=' . @$_GET['pembayaran'],
						$total,$perpage
					);

		//if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		//else:
		//	$start 	=	'0';
		//endif;

		$query	=	$this->CI->db->query("
						SELECT * 

						FROM ".$this->CI->db->dbprefix."data_piutang_usaha a
						
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.piutang_kustomer_kode = b.pelanggan_code
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.piutang_cabang_id = c.cabang_id 

						WHERE a.piutang_id > '0' && a.piutang_kustomer_kode = b.pelanggan_code $append

						ORDER BY a.piutang_id DESC

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


	public function data_penjualan_inovice_single($mode,$identifier)
	{

		$query 	=	$this->CI->db->query("
						SELECT * 
							
							,IF(
								a.invoice_status_pembayaran = 'Tunai',
								(SELECT rekening_nama FROM ".$this->CI->db->dbprefix."data_rekening WHERE rekening_kode = a.invoice_kode_akun_lunas && rekening_cabang_id = a.invoice_cabang_id),
								a.invoice_hari_jatuh_tempo
							) as lunas_tempo

							,(SELECT karyawan_nama FROM ".$this->CI->db->dbprefix."data_karyawan WHERE karyawan_kode_identitas = a.invoice_sales_id && karyawan_cabang_id = a.invoice_cabang_id) as nama_sales

						FROM ".$this->CI->db->dbprefix."data_invoice a
						LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.invoice_customer_kode = b.pelanggan_code
						LEFT JOIN ".$this->CI->db->dbprefix."data_jurnal_umum c ON a.invoice_no_order = c.jurnal_nomer_bukti
							
						WHERE invoice_".$mode." = '".addslashes($identifier)."' && b.pelanggan_cabang_id = a.invoice_cabang_id
					");

		$fetch 	=	$query->row_array();

		$query_detail 	=	$this->CI->db->query("
								SELECT * 

									,(SELECT stok_jumlah FROM ".$this->CI->db->dbprefix."data_stock WHERE stok_produk_kode = a.invoice_detail_kode_produk && stok_cabang_id = a.invoice_detail_cabang_id) as stok_riil
									,(SELECT stok_id FROM ".$this->CI->db->dbprefix."data_stock WHERE stok_cabang_id = a.invoice_detail_cabang_id && stok_produk_kode = a.invoice_detail_kode_produk ) as stok_id

								FROM ".$this->CI->db->dbprefix."data_invoice_detail a 			
								LEFT JOIN ".$this->CI->db->dbprefix."data_stock_product b ON a.invoice_detail_no_order = b.stock_nomer_order				

								WHERE a.invoice_detail_".$mode." = '".addslashes($identifier)."'
							");

		$callback 	=	array(
							'total' => $query->num_rows(),
							'result' => $fetch,
							'detail' => $query_detail->result_array()
						);		

		return $callback;
	}

	//data invoice
	public function data_penjualan_inovice($q=false,$perpage=20,$cabang=false,$from=false,$to=false,$pembayaran=false)
	{
		$append		=	'';
		
		if($q):
			$key	=	str_replace(' ','|',trim($q));

			$append .=	" && a.invoice_no_order = '".addslashes($q)."' ";			
		endif;

		if($from && $to):
			$append	.=	" && a.invoice_tanggal_faktur BETWEEN '".addslashes($from)."' AND '".addslashes($to)."' ";
		endif;

		if($cabang):
			$append .=	" && a.invoice_cabang_id = '".addslashes($cabang)."' ";
		endif;

		if($pembayaran):
			$append .=	" && a.invoice_status_pembayaran = '".addslashes($pembayaran)."' ";
		endif;
		
		$total 		=	$this->CI->db->count_all(
							$this->CI->db->dbprefix."data_invoice a 

							LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang b ON a.invoice_cabang_id = b.cabang_id

							WHERE a.invoice_id > '0' $append"
						);


		/*
		$query		=	$this->CI->db->query("
							SELECT *

							FROM ".$this->CI->db->dbprefix."data_invoice a
							LEFT JOIN ".$this->CI->db->dbprefix."data_pelanggan b ON a.invoice_customer_kode = b.pelanggan_code 

							WHERE a.invoice_id > '0' $append 
						"); 	

		$total 		=	$query->num_rows();
		*/

		$perpage	=	($perpage) ? $perpage : $total;

		$paging =	$this->CI->utility->pagination_setting(
						'?q=' . @$_GET['q'] . '&cabang=' . @$_GET['cabang'] . '&from=' . @$_GET['from'] . '&to=' . @$_GET['to'] . '&show=' . @$_GET['show'] .'&pembayaran=' . @$_GET['pembayaran'],
						$total,$perpage
					);

		//if($start):
			$start 	=	(!@$_GET['page']) ? '0' : @$_GET['page'];
		//else:
		//	$start 	=	'0';
		//endif;

		$query	=	$this->CI->db->query("
						SELECT * 
							
							,(SELECT pelanggan_nama FROM ".$this->CI->db->dbprefix."data_pelanggan WHERE pelanggan_code = a.invoice_customer_kode && pelanggan_cabang_id = a.invoice_cabang_id) as pelanggan_nama
							,(SELECT karyawan_nama FROM ".$this->CI->db->dbprefix."data_karyawan WHERE karyawan_kode_identitas = a.invoice_sales_id && karyawan_cabang_id = a.invoice_cabang_id) as nama_sales

							,IF(
								a.invoice_status_pembayaran = 'Tunai',
								(SELECT rekening_nama FROM ".$this->CI->db->dbprefix."data_rekening WHERE rekening_kode = a.invoice_kode_akun_lunas && rekening_cabang_id = a.invoice_cabang_id),
								a.invoice_hari_jatuh_tempo
							) as lunas_tempo

						FROM ".$this->CI->db->dbprefix."data_invoice a
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang b ON a.invoice_cabang_id = b.cabang_id

						WHERE a.invoice_id > '0' $append 
						ORDER BY a.invoice_id DESC

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


	public function data_stok_produkx($q=false,$perpage=20,$cabang=false)
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

								,b.category_nama ,c.merk_nama

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
