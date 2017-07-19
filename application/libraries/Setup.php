<?php

class Setup{

	var $CI;
	
	private $local_time;
	private $dbname;

	function __construct(){
		$this->CI =& get_instance();
		
		$this->CI->load->library(array('tools'));
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	


		$this->dbname 	=	($this->CI->input->post('dbname')) ? $this->CI->input->post('dbname') : 'astonpri_asisall';


		$config['hostname'] = 'localhost';
		$config['username'] = 'root';
		$config['password'] = '';
		$config['database'] = $this->dbname;
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';


        //$this->db_old =   $this->CI->load->database($config,TRUE,TRUE);    

	}

	//sudah ada
	public function dump_pembayaran_hutang($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			$cabang_id =	$query['cabang_id'];

			exit;

			//hapus data pembayaran hutang
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pembayaran_hutang 
				WHERE pembayaran_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pembayaran_hutang_detail 
				WHERE pembayaran_detail_cabang_id = '".$cabang_id."'
			");


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_hutang_usaha (
					pembayaran_cabang_id, pembayaran_kode, pembayaran_tanggal_faktur, pembayaran_supplier_id, 
					pembayaran_rekening, pembayaran_administrasi_bank, pembayaran_departemen, pembayaran_total,
					pembayaran_denda, pembayaran_keterangan
				)

				SELECT 

					'".$cabang_id."', kode_pembayaran, tanggal_faktur, penerima, 
					rekening, adm_bank, departement, total,
					denda, keterangan

				FROM ".$dbname.".trx_head_pembayaran_hutang

			");	


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_pembayaran_hutang_detail (
					pembayaran_detail_cabang_id, pembayaran_detail_pembayaran_id, pembayaran_detail_kode,
					pembayaran_detail_no_surat_jalan, pembayaran_detail_saldo, pembayaran_detail_diskon,
					pembayaran_detail_bayar
				)


				SELECT 

				FROM ".$dbname.".trx_detail_pembayaran_hutang


			");

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;

		return $callback;	

	}

	
	public function dump_perusahaan($dbname)
	{
		if($dbname):
			
			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			$cabang_id =	$query['cabang_id'];

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."setting_kode_transaksi WHERE kode_cabang_id = '".$cabang_id."'
			");

			$query 	=	$this->CI->db->query("
							SHOW COLUMNS FROM ".$dbname.".perusahaan
						")->result_array();

			for($i=8;$i<=count($query)-2;$i++):

				$field 	=	$query[$i]['Field'];

				$nama 	=	ucwords(str_replace('_',' ', str_replace('kode_','',$field)));

				$fetch	=	$this->CI->db->query("
								SELECT ".$field." FROM ".$dbname.".perusahaan
							")->row_array();				

				$data 	=	array(
								'kode_cabang_id' => $cabang_id,
								'kode_nama' => $nama,
								'kode_nomer' => $fetch[$field]
 							);		

				$this->CI->db->insert($this->CI->db->dbprefix."setting_kode_transaksi", $data);

			endfor;

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;				
	}

	public function dump_penjualan($dbname)
	{	

		if($dbname):

			//data jurnal umum sudah didump waktu impor data PO
			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			$cabang_id =	$query['cabang_id'];

			//hapus data invoice dari cabang terkait
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_invoice 
				WHERE invoice_cabang_id = '".$cabang_id."' && invoice_type = 'Penjualan'
			");	

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_invoice_detail 
				WHERE invoice_detail_cabang_id = '".$cabang_id."'
			");	


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_invoice 
				WHERE invoice_cabang_id = '".$cabang_id."' && invoice_type = 'Penjualan Non Stock'
			");			

			//insert data_invoice dari trx_head_invoice
			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_invoice 
				(
					`invoice_cabang_id`, `invoice_type`,`invoice_no_order`,`invoice_no_so`,`invoice_sales_id`,
					`invoice_customer_kode`,`invoice_tanggal_faktur`,`invoice_keterangan`,`invoice_tanggal_pengantaran`,
					`invoice_hari_jatuh_tempo`,`invoice_kredit`,`invoice_biaya_lain`,`invoice_total_setelah_pajak`,
					`invoice_uang_muka`,`invoice_piutang`,`invoice_kode_akun_lunas`,
					`invoice_status_pembayaran`,
					`invoice_status_penjualan_non_stock`,`invoice_user_id`
				)

				SELECT 
					
					'".$cabang_id."', 'Penjualan', `no_order`,`so_no`,`sales`,
					`customer`,`tgl_faktur`,`keterangan`,`tgl_pengantaran`,
					`hr_jth_tempo`,`kredit`,`biaya_lain`,`total_setelah_pajak`,
					`uang_muka`,`piutang`,`akunLunas`,
					IF(status_pembayaran = '1', 'Tempo', 'Tunai'),
					'0',`actor`

				FROM ".$dbname.".trx_head_invoice

			");

			//insert data_invoice_detail
			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_invoice_detail 
				(
					`invoice_detail_cabang_id`,`invoice_detail_no_order`,`invoice_detail_no_so`,`invoice_detail_tanggal_faktur`,
					`invoice_detail_kode_produk`,`invoice_detail_nama_produk`,`invoice_detail_jumlah_produk`,`invoice_detail_jumlah_diterima`,
					`invoice_detail_satuan`,`invoice_detail_harga`,`invoice_detail_diskon`,`invoice_detail_pajak`,`invoice_detail_total`
				)

				SELECT 

					'".$cabang_id."', `no_order`,`so_no`,`tgl_faktur`,
					`kode_produk`,(SELECT nama_produk FROM ".$dbname.".produk WHERE kode_produk = a.kode_produk),`jumlah`,`jumlah_diterima`,
					`satuan`,`harga`,`diskon`,`pajak`,`total`

				FROM ".$dbname.".trx_detail_invoice a

			");		


			//insert data penjualan non stock

			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_invoice 
				(
					`invoice_cabang_id`, `invoice_type`,`invoice_no_order`,`invoice_no_so`,`invoice_sales_id`,
					`invoice_customer_kode`,`invoice_tanggal_faktur`,`invoice_keterangan`,`invoice_tanggal_input`,
					`invoice_hari_jatuh_tempo`,`invoice_kredit`,`invoice_biaya_lain`,`invoice_total_setelah_pajak`,
					`invoice_uang_muka`,`invoice_piutang`,`invoice_kode_akun_lunas`,
					`invoice_status_pembayaran`,
					`invoice_user_id`, `invoice_status_penjualan_non_stock`
				)

				SELECT

					'".$cabang_id."','Penjualan Non Stock', '', `so_no`, `sales`,
					`customer`,`tgl_faktur`,`keterangan`,`tgl_input`,
					`hr_jth_tempo`,'',`biaya_lain`,`total_setelah_pajak`,
					`uang_muka`,`piutang`,`akunLunas`,
					IF(status_pembayaran = '1', 'Tempo', 'Tunai'),
					`actor`, IF(status_penjualan = '0', 'pending' ,'posting')

				FROM ".$dbname.".trx_head_po_jual

			");	

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_invoice_detail 
				(
					`invoice_detail_cabang_id`,`invoice_detail_no_order`,`invoice_detail_no_so`,`invoice_detail_tanggal_faktur`,
					`invoice_detail_kode_produk`,`invoice_detail_nama_produk`,`invoice_detail_jumlah_produk`,`invoice_detail_jumlah_diterima`,
					`invoice_detail_satuan`,`invoice_detail_harga`,`invoice_detail_diskon`,`invoice_detail_pajak`,`invoice_detail_total`
				)

				SELECT 

					'".$cabang_id."', '',`so_no`,'',
					`kode_produk`,`nama_produk`,`jumlah`,`jumlah_diterima`,
					`satuan`,`harga`,`diskon`,`pajak`,`total`

				FROM ".$dbname.".trx_detail_po_jual a				

			");

			//dump data piutang_usaha
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_piutang_usaha WHERE piutang_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_piutang_usaha
				(
					`piutang_cabang_id`,`piutang_kode`,`piutang_kustomer_kode`,`piutang_tanggal_faktur`,
					`piutang_no_invoice`,`piutang_no_order`,`piutang_mata_uang`,`piutang_jumlah`,
					`piutang_terbayar`,`piutang_saldo`,`piutang_akun`,`piutang_status`
				)

				SELECT 

					'".$cabang_id."', `kode_piutang`,`customer`,`tgl_faktur`,
					`no_invoice`,`no_order`,`mata_uang`,`jumlah`,
					`terbayar`,`saldo`,`akun`,`status`

				FROM ".$dbname.".piutang_usaha

			");

			//dump data pembayaran hutang dan piutang

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pembayaran 
				WHERE pembayaran_cabang_id = '".$cabang_id."' && pembayaran_tipe = 'piutang'
			");


			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."data_pembayaran
				(
					`pembayaran_cabang_id`,`pembayaran_tipe`,`pembayaran_kode`,`pembayaran_tanggal_faktur`,
					`pembayaran_dari_ke`,`pembayaran_rekening`,`pembayaran_total`,`pembayaran_denda`,
					`pembayaran_keterangan`,`pembayaran_proyek`,`pembayaran_departemen`,`pembayaran_adm_bank`
				)

				SELECT 

					'".$cabang_id."', 'piutang',`kode_pembayaran`,`tanggal_faktur`,
					`dari`,`rekening`,`total`,`denda`,
					`keterangan`,'','',''

				FROM ".$dbname.".trx_head_pembayaran_piutang
			");


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pembayaran 
				WHERE pembayaran_cabang_id = '".$cabang_id."' && pembayaran_tipe = 'hutang'
			");


			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."data_pembayaran
				(
					`pembayaran_cabang_id`,`pembayaran_tipe`,`pembayaran_kode`,`pembayaran_tanggal_faktur`,
					`pembayaran_dari_ke`,`pembayaran_rekening`,`pembayaran_total`,`pembayaran_denda`,
					`pembayaran_keterangan`,`pembayaran_proyek`,`pembayaran_departemen`,`pembayaran_adm_bank`
				)

				SELECT 

					'".$cabang_id."', 'hutang',`kode_pembayaran`,`tanggal_faktur`,
					`penerima`,`rekening`,`total`,`denda`,
					`keterangan`,'','',`adm_bank`

				FROM ".$dbname.".trx_head_pembayaran_hutang
			");


			//dump detail pembayaran
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pembayaran_detail 
				WHERE pembayaran_detail_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_pembayaran_detail
				(
					`pembayaran_detail_cabang_id`,`pembayaran_detail_kode_pembayaran`,
					`pembayaran_detail_no_invoice`,`pembayaran_detail_saldo`,`pembayaran_detail_diskon`,`pembayaran_detail_bayar`,
					`pembayaran_detail_tipe`
				)

				SELECT 

					'".$cabang_id."', `kode_pembayaran`,
					`no_invoice`,`saldo`,`diskon`,`bayar`,'piutang'

				FROM ".$dbname.".trx_detail_pembayaran_piutang

			");

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_pembayaran_detail
				(
					`pembayaran_detail_cabang_id`,`pembayaran_detail_kode_pembayaran`,
					`pembayaran_detail_no_invoice`,`pembayaran_detail_saldo`,`pembayaran_detail_diskon`,`pembayaran_detail_bayar`,
					`pembayaran_detail_tipe`
				)

				SELECT 

					'".$cabang_id."', `kode_pembayaran`,
					`no_invoice`,`saldo`,`diskon`,`bayar`,'hutang'

				FROM ".$dbname.".trx_detail_pembayaran_hutang

			");

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;				

	}

	public function dump_stock_product($dbname)
	{

		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			$cabang_id =	$query['cabang_id'];

			//hapus stock dari cabang terpilih

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_stock_product WHERE stock_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_stock WHERE stok_cabang_id = '".$cabang_id."'
			");

			//dump data
			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."data_stock_product
				(
					`stock_cabang_id`,`stock_produk_kode`,`stock_nomer_order`,`stock_type_transaksi`,
					`stock_jenis`,`stock_harga_beli`,`stock_jumlah`,`stock_stok_awal`,`stock_stok_sisa`,
					`stock_stok_akhir`,`stock_nilai_barang`,`stock_hpp`,`stock_tanggal_transaksi`,`stock_ambil_dari`
				)

				SELECT
					
					'".$cabang_id."', `kode_produk`,`no_order`,`tipe_transaksi`,
					`jenis_stok`,`harga_beli`,`jumlah`,`stok_awal`,`sisa`,
					`stok_akhir`,`nilai_barang`,`hpp`,`tgl_transaksi`,`ambil_dari`

				FROM ".$dbname.".stok_produk
			");

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_stock 
				(
					`stok_produk_kode`,`stok_cabang_id`
				)

				SELECT 

					DISTINCT(`stock_produk_kode`), '".$cabang_id."'

				FROM ".$this->CI->db->dbprefix."data_stock_product
			");

			//masukkan jumlah stok akhir
			/*
			$query =	$this->CI->db->query("

							SELECT a.stok_id 
								,(SELECT stock_stok_akhir FROM ".$this->CI->db->dbprefix."data_stock_product WHERE stock_type_transaksi = 'beli' && stock_produk_kode = a.stok_produk_kode ORDER BY stock_id DESC LIMIT 1) as jumlah_stok_akhir
							FROM ".$this->CI->db->dbprefix."data_stock a
							

						")->result_array();
			*/

			$query 	=	$this->CI->db->query("

							SELECT * 
							FROM ".$this->CI->db->dbprefix."data_stock_product a 

							INNER JOIN (
								SELECT stock_cabang_id, stock_produk_kode, MIN(stock_stok_akhir) stok_akhir
								FROM ".$this->CI->db->dbprefix."data_stock_product GROUP BY stock_cabang_id, stock_produk_kode
							) b ON a.stock_cabang_id = b.stock_cabang_id AND 
							a.stock_produk_kode = b.stock_produk_kode AND a.stock_stok_akhir = b.stok_akhir

						")->result_array();

			foreach($query as $result):

				//perbaiki stock_nilai_barang
				$stock_nilai_barang = $result['stock_stok_akhir'] * $result['stock_hpp'];
				
				$this->CI->db->query("
					UPDATE ".$this->CI->db->dbprefix."data_stock_product SET 
						`stock_nilai_barang` = '".$stock_nilai_barang."'
					WHERE stock_id = '".$result['stock_id']."'
				");

				$this->CI->db->query("
					UPDATE ".$this->CI->db->dbprefix."data_stock SET 
						`stok_jumlah` = '".$result['stok_akhir']."'
					WHERE stok_cabang_id = '".$result['stock_cabang_id']."' && stok_produk_kode = '".$result['stock_produk_kode']."'
				");

			endforeach;


			//input data stock detail
			//bersihkan dulu data lama
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_stock_product_detail WHERE stock_detail_cabang_id = '".$cabang_id."'
			");

			$query	=	$this->CI->db->query("

							SELECT *
								,(SELECT product_nama FROM ".$this->CI->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk
							FROM ".$this->CI->db->dbprefix."data_stock_product a 
							WHERE stock_type_transaksi = 'beli'

						")->result_array();

			foreach($query as $result):

				for($i=1;$i<=$result['stock_jumlah'];$i++):

					$data 	=	array(
									'stock_detail_cabang_id' => $result['stock_cabang_id'],
									'stock_detail_stock_id' => $result['stock_id'],
									'stock_detail_type' => 'masuk',
									'stock_detail_nomer_order' => $result['stock_nomer_order'],
									'stock_detail_produk_kode' => $result['stock_produk_kode'],
									'stock_detail_produk_nama' => $result['nama_produk'],
									'stock_detail_serial_number' => '',
									'stock_detail_tanggal_masuk' => $result['stock_tanggal_transaksi']
								);

					$this->CI->db->insert($this->CI->db->dbprefix."data_stock_product_detail", $data);

				endfor;

			endforeach;

			$query	=	$this->CI->db->query("

							SELECT *
								,(SELECT product_nama FROM ".$this->CI->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk 
							FROM ".$this->CI->db->dbprefix."data_stock_product a 
							WHERE stock_type_transaksi = 'jual'

						")->result_array();



			foreach($query as $result):

				for($i=1;$i<=$result['stock_jumlah'];$i++):

					$data 	=	array(
									'stock_detail_cabang_id' => $result['stock_cabang_id'],
									'stock_detail_stock_id' => $result['stock_id'],
									'stock_detail_type' => 'keluar',
									'stock_detail_nomer_order' => $result['stock_nomer_order'],
									'stock_detail_produk_kode' => $result['stock_produk_kode'],
									'stock_detail_produk_nama' => $result['nama_produk'],
									'stock_detail_serial_number' => '',
									'stock_detail_tanggal_keluar' => $result['stock_tanggal_transaksi'],
									'stock_detail_harga_jual' => $result['stock_nilai_barang']
								);

					$this->CI->db->insert($this->CI->db->dbprefix."data_stock_product_detail", $data);

				endfor;

			endforeach;


			//dump data produk_rakitan
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."setting_produk_rakitan 
				WHERE rakitan_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."setting_produk_rakitan_detail 
				WHERE rakitan_detail_cabang_id = '".$cabang_id."'
			");


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."setting_produk_rakitan 
				(
					`rakitan_cabang_id`,`rakitan_kode`,`rakitan_product_kode`,`rakitan_tanggal`,
					`rakitan_jumlah`,`rakitan_hpp`,`rakitan_user_id`,`rakitan_status`
				)

				SELECT 

					'".$cabang_id."', kode_rakitan,kode_produk,tgl_rakit,
					jumlah,hpp,actor,status

				FROM ".$dbname.".rakitan a 

			");	

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."setting_produk_rakitan_detail 
				(
					`rakitan_detail_cabang_id`,`rakitan_detail_kode_produk`,`rakitan_detail_rakitan_kode`,`rakitan_detail_jumlah`
				)

				SELECT 

					'".$cabang_id."', kode_produk, detail_rakitan, jumlah

				FROM ".$dbname.".rakitan_detail a 

			");	


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;					

	}

	public function dump_po_outlet($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			//$fetch 	=	$query->row_array();

			$cabang_id =	$query['cabang_id'];

			//trx penawaran
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_penawaran_outlet
				WHERE penawaran_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_penawaran_outlet 
				(
					`penawaran_cabang_id`,`penawaran_tanggal_pesan`,`penawaran_nomer`,`penawaran_keterangan`,
					`penawaran_status`,`penawaran_user_id`, `penawaran_tanggal_input`
				)

				SELECT 

					'".$cabang_id."', a.tgl_pesan, a.no_penawaran, a.keterangan, 
					a.status_po, a.actor, a.tgl_pesan

				FROM ".$dbname.".trx_head_pb_penawaran a 
				 
			"); 

			$this->CI->db->query("
				UPDATE ".$this->CI->db->dbprefix."data_penawaran_outlet SET 
					`penawaran_status` = '0'
				WHERE `penawaran_status` != '1' && `penawaran_cabang_id` = '".$cabang_id."' 
			");


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_penawaran_outlet_detail
				WHERE `penawaran_detail_cabang_id` = '".$cabang_id."'
			");

			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."data_penawaran_outlet_detail 
				(
					`penawaran_detail_cabang_id`,`penawaran_detail_nomer`,`penawaran_detail_product_kode`,`penawaran_detail_jumlah`,`penawaran_detail_status`,
					`penawaran_detail_product_nama`
				)

				SELECT 

					'".$cabang_id."', `no_penawaran`,`kode_produk`, `jumlah`,`status`,
					(SELECT nama_produk FROM ".$dbname.".produk WHERE kode_produk = a.kode_produk)

				FROM ".$dbname.".trx_detail_pb_penawaran a

			");

			//$this->CI->db->query("
			//	UPDATE ".$this->CI->db->dbprefix."data_penawaran_outlet_detail SET 
			//		`penawaran_status` = '0'
			//	WHERE `penawaran_status` != '1' && `penawaran_detail_cabang_id` = '".$cabang_id."'
			//");

			//eof trx_penawaran


			//trx head po ke data_po

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_po
				WHERE po_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_po 
				(
					`po_cabang_id`,`po_nomer_po`,`po_no_penawaran`,`po_no_invoice`,`po_supplier_id`,`po_tgl_pesan`,
					`po_keterangan`,`po_tgl_input`,`po_user_id`,`po_hari_jatuh_tempo`,`po_biaya_lain`,
					`po_total_setelah_pajak`,`po_uang_muka`,`po_hutang`,`po_cara_bayar`,`po_akun_bayar`,`po_status_pembayaran`,`po_status`
				)

				SELECT 

					'".$cabang_id."',`no_po`,`no_penawaran`,`no_invoice_sup`,`vendor`,`tgl_pesan`,
					`keterangan`,`tgl_input`,`actor`,`hr_jth_tempo`,`biaya_lain`,
					`total_setelah_pajak`,`uang_muka`,`hutang`,`caraBayar`,`akunBayar`,`status_pembayaran`,`status`

				FROM ".$dbname.".trx_head_po

			");

			$this->CI->db->query("
				UPDATE ".$this->CI->db->dbprefix."data_po SET 
					`po_status` = '0'
				WHERE `po_status` = '' && `po_cabang_id` = '".$cabang_id."'
			");


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_po_detail 
				WHERE `po_detail_cabang_id` = '".$cabang_id."'
			");

			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."data_po_detail 
				(
					`po_detail_cabang_id`,`po_detail_nomer_po`,`po_detail_nomer_penawaran`,`po_detail_product_kode`,`po_detail_product_nama`,
					`po_detail_jumlah_permintaan`,`po_detail_jumlah_acc`,`po_detail_jumlah_beli`,`po_detail_satuan`,`po_detail_harga`,
					`po_detail_diskon`,`po_detail_total`
				)

				SELECT 
					
					'".$cabang_id."', `no_po`,`no_penawaran`,`kode_produk`,`nama_produk`,
					`jumlah`,`jumlah_acc`,`jumlah_beli`,`satuan`,`harga`,
					`diskon`,`total`

				FROM ".$dbname.".trx_detail_po
			");


			//data penerimaan
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_penerimaan
				WHERE penerimaan_cabang_id = '".$cabang_id."'
			");	

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_penerimaan_detail
				WHERE penerimaan_detail_cabang_id = '".$cabang_id."'
			");			


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_penerimaan
				(
					`penerimaan_cabang_id`,`penerimaan_tanggal`,`penerimaan_user_id`,`penerimaan_no_penerimaan`,
					`penerimaan_no_po`,`penerimaan_no_surat_jalan`,`penerimaan_supplier_id`,`penerimaan_tanggal_faktur`,`penerimaan_keterangan`,
					`penerimaan_tanggal_pengiriman`,`penerimaan_hari_jatuh_tempo`,`penerimaan_kredit`,`penerimaan_biaya_lain`,`penerimaan_total_setelah_pajak`,
					`penerimaan_uang_muka`,`penerimaan_hutang`,`penerimaan_status_pembayaran`
				)

				SELECT 

					'".$cabang_id."', `tgl_faktur`,`actor`,`no_order`,
					`no_po`,`no_invoice_sup`,`vendor`,`tgl_faktur`,`keterangan`,
					`tgl_pengiriman`,`hr_jth_tempo`,`kredit`,`biaya_lain`,`total_setelah_pajak`,
					`uang_muka`,`hutang`,`status_pembayaran`

				FROM ".$dbname.".trx_head_penerimaan_produk

			");


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_penerimaan_detail
				(
					`penerimaan_detail_cabang_id`,`penerimaan_detail_no_penerimaan`,`penerimaan_detail_no_po`,
					`penerimaan_detail_tanggal_faktur`,`penerimaan_detail_product_kode`,`penerimaan_detail_product_nama`,
					`penerimaan_detail_jumlah`,`penerimaan_detail_jumlah_terima`,`penerimaan_detail_satuan`,
					`penerimaan_detail_harga`,`penerimaan_detail_diskon`,`penerimaan_detail_total`
				)

				SELECT 

					'".$cabang_id."', `no_order`,`no_po`,
					`tgl_faktur`,`kode_produk`,`nama_produk`,
					`jumlah`,`jumlah_diterima`,`satuan`,
					`harga`,`diskon`,`total`

				FROM ".$dbname.".trx_detail_penerimaan_produk

			");

			//hutang_usaha
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_hutang_usaha
				WHERE hutang_cabang_id = '".$cabang_id."'
			");			


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_hutang_usaha
				(
					`hutang_cabang_id`, `hutang_kode`,`hutang_supplier_id`,`hutang_tanggal_faktur`,`hutang_nomer_order`,`hutang_nomer_po`,
					`hutang_nomer_surat_jalan`,`hutang_mata_uang`,`hutang_jumlah`,`hutang_terbayar`,`hutang_saldo`,`hutang_akun`,
					`hutang_tanggal`,`hutang_type`,`hutang_status`
				)

				SELECT 
					
					'".$cabang_id."', `kode_hutang`,`vendor`,`tgl_faktur`,`no_order`,`no_po`,
					`no_invoice_sup`,`mata_uang`,`jumlah`,`terbayar`,`saldo`,`akun`,
					`tanggal`,`tipe`,`status`

				FROM ".$dbname.".hutang_usaha

			");

			$this->CI->db->query("
				UPDATE ".$this->CI->db->dbprefix."data_hutang_usaha SET
					`hutang_status` = '0'
				WHERE `hutang_status` = '' && hutang_cabang_id = '".$cabang_id."'
			");


			//dump data jurnal umum
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_jurnal_umum 
				WHERE jurnal_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_jurnal_umum_detail 
				WHERE jurnal_detail_cabang_id = '".$cabang_id."'
			");


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_jurnal_umum 
				(
					`jurnal_cabang_id`,`jurnal_kode`,`jurnal_tanggal`,`jurnal_type`,
					`jurnal_nomer_bukti`,`jurnal_keterangan`,`jurnal_tanggal_input`,`jurnal_tanggal_posting`,`jurnal_user_id`
				)

				SELECT 
					
					'".$cabang_id."',`kode_jurnal`,`tanggal`,`jenis_jurnal`,
					`nomor_bukti`,`keterangan`,`tanggal_input`,`tanggal_posting`,`adm`

				FROM ".$dbname.".jurnal_umum

			");


			$this->CI->db->query("

				INSERT INTO ".$this->CI->db->dbprefix."data_jurnal_umum_detail 
				(
					`jurnal_detail_cabang_id`,`jurnal_detail_kode_jurnal`,`jurnal_detail_kode_rekening`,
					`jurnal_detail_debit_kredit`,`jurnal_detail_nominal_debit`,`jurnal_detail_nominal_kredit`,
					`jurnal_detail_saldo`
				)

				SELECT 
					
					'".$cabang_id."', `kode_jurnal`,`kode_rekening`,
					`debit_kredit`,`debit`,`kredit`,
					`saldo`

				FROM ".$dbname.".jurnal_umum_detail

			");


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;		
	}

	public function dump_bank($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			$cabang_id =	$fetch['cabang_id'];

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_bank
				WHERE bank_cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_bank 
				(
					`bank_cabang_id`,`bank_kode`,`bank_nama`,`bank_no_rekening`,`bank_kode_rekening`
				)

				SELECT 

					'".$cabang_id."', `kode_bank`,`nama`,`nomor_rekening`,`kode_rek`

				FROM ".$dbname.".bank 
				 
			"); 


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;
	}


	public function dump_merk_produk($dbname)
	{
		if($dbname):


			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."setting_merk_produk
			");


			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_merk_produk 
				(
					`merk_kode`,`merk_nama`,`merk_jenis_produk`
				)

				SELECT 

					`kode_merk`,`jenis_produk`,`merk`

				FROM ".$dbname.".merk_produk 
				 
			"); 


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;
	}

	public function dump_kelompok_produk($dbname)
	{
		if($dbname):


			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."setting_produk_kategori
			");


			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_produk_kategori 
				(
					`category_kode`,`category_nama`,`category_departemen`,`category_sifat_dibeli`,
					`category_sifat_dijual`,`category_sifat_disimpan`,`category_rekening_hpp`,`category_rekening_penjualan`,
					`category_rekening_persediaan`,`category_gambar`,`category_hpp_user`,`category_hpp_dealer`
				)

				SELECT 

					`kode_kelompok`,`nama`,`departement`,`sifat_dibeli`,
					`sifat_dijual`,`sifat_disimpan`,`rek_harga_pokok`,`rek_penjualan`,
					`rek_persediaan`,`gambar`,`user`,`dealer`

				FROM ".$dbname.".kelompok_produk 
				 
			"); 


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;
	}


	public function dump_product($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			$cabang_id =	$fetch['cabang_id'];

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."setting_produk
				WHERE product_cabang_id = '".$cabang_id."'
			");


			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_produk 
				(
					`product_cabang_id`,
					`product_kode`,`product_jasa_non_jasa`,`product_aktifasi`,`product_nama`,`product_category_id`,`product_merk_id`,
					`product_harga_pokok`,`product_supplier`,`product_satuan_dasar`,`product_status_beli`,`product_status_jual`,`product_status_simpan`,
					`product_akun_simpan`,`product_akun_jual`,`product_akun_biaya`,`product_keterangan`,`product_gambar`,
					`product_hpp`,`product_jasa`,`product_rakitan`
				)

				SELECT 
					'".$cabang_id."',
					`kode_produk`,`jasa_non_jasa`,`aktifasi`,`nama_produk`,`kelompok_produk`,`merk`,
					`harga_pokok`,`supplier_utama`,`satuan_dasar`,`dibeli`,`dijual`,`disimpan`,
					`akun_simpan`,`akun_jual`,`akun_biaya`,`keterangan`,`gambar`,
					`hpp`,`produk_jasa`,`produk_rakitan`

				FROM ".$dbname.".produk 
				 
			"); 

			$this->CI->db->query("
				UPDATE ".$this->CI->db->dbprefix."setting_produk SET 
					`product_jasa_non_jasa` = 'Non Jasa'
				WHERE product_jasa_non_jasa != 'Jasa'
			");


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;
	}

	public function dump_klasifikasi_akun($dbname)
	{
		if($dbname):

			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."setting_klasifikasi_akun 
			");


			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_klasifikasi_akun 
				(
					`setting_ka_id`,`setting_ka_nama`,`setting_ka_saldo_normal`
				)

				SELECT 
					
					`idtbl_klasifikasi`,`nama`,`saldo_normal`

				FROM ".$dbname.".klasifikasi_akun 
				 
			"); 

			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_klasifikasi_akun 
				(
					`setting_ka_parent_id`,`setting_ka_nama`,`setting_ka_sub_no_akun`,`setting_ka_sub_cashflow_type`,`setting_ka_sub_hpp_account`
				)

				SELECT 
					
					`idtbl_klasifikasi`,`nama`,`no_akun`,`cashflow_type`,`hpp_account`

				FROM ".$dbname.".sub_klasifikasi_akun 
				 
			"); 


			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;			
	}

	public function dump_rekening($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			$cabang_id =	$fetch['cabang_id'];

			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_rekening 
				WHERE rekening_cabang_id = '".$cabang_id."'
			");


			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_rekening 
				(
					rekening_cabang_id,rekening_kode,rekening_no_akun,rekening_nama,rekening_nama_alias,
					rekening_kas_bank,rekening_aktifasi,rekening_kurs,rekening_type,
					rekening_employee,rekening_status,rekening_no_urut
				)

				SELECT 

					'".$cabang_id."', `kode_rekening`,`klasifikasi`,`nama`,`nama_alias`,
					`kas_bank`,`aktifasi`,`kurs`,`tipe`,
					`employee`,`status`,`urut` 			

				FROM ".$dbname.".rekening 
				 
			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;			
	}

	public function dump_employee($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			//$cabang_id =	($dbname == 'astonpri_asisasi') ?  '1' : $fetch['cabang_id'];
			$cabang_id =	$fetch['cabang_id'];


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_karyawan 
				WHERE karyawan_cabang_id = '".$cabang_id."'
			");


			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_karyawan 
				(
					`karyawan_cabang_id`,`karyawan_nama`,`karyawan_kode_identitas`,`karyawan_alamat`,`karyawan_hp`,
					`karyawan_email`,`karyawan_jabatan`,`karyawan_tgl_masuk_kerja`,`karyawan_foto`,`karyawan_status`
				)

				SELECT 
					
					'".$cabang_id."',`nama`,`kode_identitas`,`alamat`,`no_hp`,
					`email`, REPLACE(`jabatan`, '_',' '), `tgl_masukkerja`,`foto`,`status` 

				FROM ".$dbname.".employee 
				 
			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;		
	}


	//supplier
	public function dump_supplier($dbname)
	{
		if($dbname):

			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."data_supplier 
			");

			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_supplier 
				(
					`supplier_id`,`supplier_code`,`supplier_nama`,`supplier_alamat`,`supplier_nama_kota`,
					`supplier_telepon`,`supplier_hp`,`supplier_email`,`supplier_web`,
					`supplier_cp`,`supplier_cp_alamat`,`supplier_cp_kota`,`supplier_cp_telepon`,`supplier_cp_hp`,
					`supplier_cp_email`,`supplier_keterangan`,`supplier_file_picture`,`supplier_status`
				)

				SELECT 
					
					`id`,`kode_identitas`,`nama_p`,`alamat_p`,`kab_kota_p`,
					`telp_p`,`nohp_p`,`email_p`,`situs_p`,
					`nama_cp`,`alamat_cp`,`kab_kota_cp`,`telp_cp`,`nohp_cp`,
					`email_cp`,`keterangan`,`foto`,`status`

				FROM ".$dbname.".vendor 
				 
			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;			
	}


	//customer
	public function dump_customer($dbname)
	{
		if($dbname):

			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			//$cabang_id =	($dbname == 'astonpri_asisasi') ?  '1' : $fetch['cabang_id'];
			$cabang_id =	$fetch['cabang_id'];


			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."data_pelanggan 
				WHERE pelanggan_cabang_id = '".$cabang_id."'
			");

			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_pelanggan 
				(
					`pelanggan_cabang_id`,`pelanggan_code`,`pelanggan_type`,`pelanggan_nama`,`pelanggan_alamat`,`pelanggan_telepon`,
					`pelanggan_nama_kota`,`pelanggan_email`,`pelanggan_notes`,
					`pelanggan_instansi_nama`,`pelanggan_instansi_alamat`,`pelanggan_instansi_nama_kota`,`pelanggan_instansi_telepon`,
					`pelanggan_instansi_email`,`pelanggan_instansi_web`,
					`pelanggan_old_id`
				)

				SELECT 
					'".$cabang_id."',`kode_identitas`,`tipe_customer`,IF(`nama_cp` = '', `nama_p`, `nama_cp`),`alamat_cp`,`nohp_cp`,
					`kab_kota_cp`,`email_cp`,`keterangan`,
					`nama_p`,`alamat_p`,`kab_kota_p`,`telp_p`,
					`email_p`,`situs_p`,
					`id`
				FROM ".$dbname.".customer 
				 
			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;			
	}

	//dump_laba_rugi_marketing
	public function dump_laba_rugi_marketing($dbname)
	{
		if($dbname):

			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."data_rekening_laba_rugi
			");

			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."data_rekening_laba_rugi
				(`setting_lrm_kode_rekening`,`setting_lrm_klasifikasi`,`setting_lrm_sub_klasifikasi`)

				SELECT * FROM ".$dbname.".labarugi_marketing

			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;			
	}

	//dump table klasifikasi_akun
	public function dump_klasifikasi_akun_old($dbname)
	{

		if($dbname):

			$this->CI->db->query("
				TRUNCATE ".$this->CI->db->dbprefix."setting_klasifikasi_akun
			");

			//ambil data dari cabang sesuai database name post
			$this->CI->db->query("
				
				INSERT INTO ".$this->CI->db->dbprefix."setting_klasifikasi_akun 
				(`setting_ka_id`,`setting_ka_nama`,`setting_ka_saldo_normal`)

				SELECT * FROM ".$dbname.".klasifikasi_akun

			"); 

			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;	


	}

	//dump table pesan dari semua database
	public function dump_pesan($dbname)
	{
		
		if($dbname):
			
			$query	=	$this->CI->db->query("
							SELECT cabang_id FROM ".$this->CI->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						");

			$fetch 	=	$query->row_array();

			//$cabang_id =	($dbname == 'astonpri_asisasi') ?  '1' : $fetch['cabang_id'];
			$cabang_id =	$fetch['cabang_id'];


			//delete message from related cabang
			$this->CI->db->query("
				DELETE FROM ".$this->CI->db->dbprefix."pesan_sms WHERE cabang_id = '".$cabang_id."'
			");

			$this->CI->db->query("
				INSERT INTO ".$this->CI->db->dbprefix."pesan_sms
				
				(`cabang_id`,`pesan_to`,`pesan_from`,`pesan_subject`,`pesan_date`,`pesan_message`,`pesan_type`,`pesan_author`,`pesan_status`)

				SELECT 
					'".$cabang_id."', `to`,`from`,`subject`,`date`,`message`,`tipe`,`actor`,`status`
				FROM ".$this->dbname.".pesan
			");

			
			$callback 	=	array(
								'status' => 200,
								'message' => 'DONE',
								'button-text' => 'DONE',
								//'paket' => $_POST
							);

		else:

			$callback 	=	array(
									'status' => 209,
									'message' => 'Please Select A Database',
									'button-text' => 'EXECUTE'
								);

		endif;


		return $callback;	

	}

	public function generate_menu()
	{
		$this->CI->db->query("
			TRUNCATE ".$this->CI->db->dbprefix."setting_menu
		");

		$query 	=	$this->db_old->query("
						SELECT DISTINCT(caption) as caption, url FROM menu_atas
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'menu_name' => $result['caption'],
							'menu_status' => 'show',
							'menu_position' => 'left',
							'menu_access_level' => '1,2'
						);

			$this->CI->db->insert($this->CI->db->dbprefix."setting_menu", $data);

			$parent_id = $this->CI->db->insert_id();

			$q =	$this->db_old->query("
						SELECT * FROM menu_metro WHERE menu_group = '".$result['url']."' && title != '' ORDER BY no_urut ASC
					");

			foreach($q->result_array() as $fetch):

				$data2 =	array(
								'parent_id' => $parent_id,
								'menu_name' => $fetch['title'],
								'menu_status' => 'show',
								'menu_position' => 'left',
								'menu_access_level' => '1,2'
							);

				$this->CI->db->insert($this->CI->db->dbprefix."setting_menu", $data2);

			endforeach;

		endforeach;


		//update menu service
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_menu 
						WHERE menu_name = 'Service'
					");

		$f	=	$query->row_array();

		$this->CI->db->query("
			INSERT INTO ".$this->CI->db->dbprefix."setting_menu (`parent_id`,`menu_name`,`menu_status`,`menu_position`,`menu_access_level`) VALUES 
			('".$f['menu_id']."', 'Pesan Pelanggan', 'show', 'left', '1,2'),
			('".$f['menu_id']."', 'Service Customer', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Data Service Proses', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Edit Service Berhasil', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Pengembalian Service', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Panduan Cek Pulsa SMS', 'show','left', '1,2')
		");


		//update menu perbaikan
		$query 	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_menu 
						WHERE menu_name = 'Perbaikan'
					");

		$f	=	$query->row_array();

		$this->CI->db->query("
			INSERT INTO ".$this->CI->db->dbprefix."setting_menu (`parent_id`,`menu_name`,`menu_status`,`menu_position`,`menu_access_level`) VALUES 
			('".$f['menu_id']."', 'Perbaikan Penjualan', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Perbaikan Kas Masuk', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Perbaikan Kas Keluar', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Perbaikan Setoran Sales', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Perbaikan Setoran Harian', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Perbaikan Jurnal Umum', 'show','left', '1,2'),
			('".$f['menu_id']."', 'Hapus Service', 'show','left', '1,2')
		");



		//get konfig menu from charm_head and charm_detail
		$this->CI->db->query("
			INSERT INTO ".$this->CI->db->dbprefix."setting_menu (`menu_name`,`menu_status`,`menu_position`,`menu_access_level`) VALUE ('Konfigurasi Awal','show','top','1,2')
		");

		$konfig_id 	=	$this->CI->db->insert_id();

		$query 	=	$this->db_old->query("
						SELECT * FROM charm_detail WHERE link IS NOT NULL
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'parent_id' => $konfig_id,
							'menu_name' => $result['title'],
							'menu_status' => 'show',
							'menu_position' => 'top',
							'menu_access_level' => '1,2'							
						);

			$this->CI->db->insert($this->CI->db->dbprefix."setting_menu", $data);

		endforeach;


		//create urutan
		$query	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."setting_menu 
						WHERE parent_id = '0' ORDER BY menu_id ASC
					")->result_array();

		$no 	=	1;

		foreach($query as $result):

			$data 	=	array(
							'menu_ordering' => $no,
						);

			$this->CI->db->update($this->CI->db->dbprefix."setting_menu", $data, array('menu_id' => $result['menu_id']));

			$no++;

		endforeach;

		$callback 	=	array(
							'status' => 200,
							'message' => 'DONE',
							'button-text' => 'DONE',
						);

		return $callback;		

	}

	public function generate_user()
	{
		
		$this->CI->db->query("
			TRUNCATE ".$this->CI->db->dbprefix."data_user
		");

		$this->CI->db->query("
			INSERT INTO `aston_data_user` (`user_id`, `user_level_id`, `user_cabang_id`, `user_username`, `user_password`, `user_fullname`, `user_employee_code`, `user_email`, `user_phone`, `user_last_login`, `user_password_update`, `user_old_databasetting`, `user_old_id_perusahaan`) VALUES (NULL, '1', '0', 'root', MD5('123457'), 'abdurrahman muslim', '0', 'dreamcorner@gmail.com', '081542880795', NULL, 'no', NULL, NULL);
		");

		$this->CI->db->query("
			DELETE FROM ".$this->CI->db->dbprefix."data_user WHERE user_id > '1'
		");

		$query 	=	$this->CI->db->query("
						SELECT a.*, b.*, c.id as cabang_id, a.id as user_id 
						FROM astonpri_asisall.users a
						LEFT JOIN ".$this->CI->db->dbprefix."user_level b ON a.level = b.user_level_name
						LEFT JOIN astonpri_asisall.branch c ON a.dbname = c.dbname
					");

		foreach($query->result_array() as $result):

			$data =	array(
						'user_id' => $result['user_id'],
						'user_level_id' => $result['user_level_id'],
						'user_cabang_id' => $result['cabang_id'],
						'user_username' => $result['username'],
						'user_password' => $result['password'],
						'user_fullname' => $result['nama'],
						'user_employee_code' => $result['id_employee'],
						'user_password_update' => 'no',
						'user_old_databasetting' => $result['dbname'],
						'user_old_id_perusahaan' => $result['id_perusahaan']
					);

			$this->CI->db->insert($this->CI->db->dbprefix."data_user", $data);

			//print '<pre>';
			//print_r($data);
			//print '</pre>';

		endforeach;

		$callback 	=	array(
							'status' => 200,
							'message' => 'DONE',
							'button-text' => 'DONE',
						);

		return $callback;	

	}
	
	public function generate_branch()
	{
		
		//clear new table
		$this->CI->db->query("
			TRUNCATE ".$this->CI->db->dbprefix."setting_cabang
		");

		$query 	=	$this->db_old->query("
						SELECT * FROM branch 
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'cabang_id' => $result['id'],
							'cabang_code' => $result['kode_branch'],
							'cabang_nama' => $result['nama'],
							'cabang_alamat' => $result['alamat'],
							'cabang_contact_person' => $result['contact_person'],
							'cabang_owner' => $result['owner'],
							'cabang_phone' => $result['nohp'],
							'cabang_status' => $result['status'],
							'cabang_old_dbname' => $result['dbname']
						);

			$this->CI->db->insert($this->CI->db->dbprefix."setting_cabang", $data);

		endforeach;
		
		$callback 	=	array(
							'status' => 200,
							'message' => 'DONE',
							'button-text' => 'DONE',
						);

		return $callback;	

	}
	
	public function generate_user_level()
	{

		//clear new table
		$this->CI->db->query("
			TRUNCATE ".$this->CI->db->dbprefix."user_level
		");

		$this->CI->db->query("
			INSERT INTO ".$this->CI->db->dbprefix."user_level (`user_level_name`) VALUES ('root')
		");

		$query 	=	$this->db_old->query("
						SELECT DISTINCT(level) as ulevel FROM users 
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'user_level_name' => $result['ulevel']
						);

			$this->CI->db->insert($this->CI->db->dbprefix."user_level", $data);

		endforeach;

		$callback 	=	array(
							'status' => 200,
							'message' => 'DONE',
							'button-text' => 'DONE',
						);

		return $callback;	
	}

}
?>
