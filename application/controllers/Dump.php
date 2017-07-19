<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dump extends CI_Controller {


	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;
	
	private $dbname;
	private $cabang_id;

	private $db_old;

	public function __construct()
	{
		parent::__construct();		

		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

		$this->dbname 	=	'astonpri_asissl3'; //($this->input->post('dbname')) ? $this->input->post('dbname') : 'astonpri_asisall';

		$this->cabang_id 	=	'1';
		
		$config['hostname'] = 'localhost';
		$config['username'] = 'root';
		$config['password'] = 'root';
		$config['database'] = $this->dbname;
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';


        $this->db_old =   $this->load->database($config,TRUE,TRUE);    

	}	

	public function index()
	{
		
		$query 	=	$this->db->query("
						SELECT cabang_id FROM ".$this->db->dbprefix."setting_cabang 
						WHERE cabang_old_dbname = '".$this->dbname."'
					")->row_array();

		//print_r($query);

		print '<h2>Urutan Dump Stock Produk</h2>';

		print '<ul>';
		
		print '<li><a href="/dump/step_one">Dump Table stok_produk</a></li>';
		print '<li><a href="/dump/step_two">Dump Table data_stock</a></li>';

		print '</ul>';

	}


	public function step_one()
	{
		print '<h2>' . str_replace('_','', __FUNCTION__) .  ' - ' .@$_GET['id'] . '</h2>';

		$min 	=	$this->db->query("
						SELECT MIN(id) as min_id FROM ".$this->dbname.".stok_produk 
					")->row_array();

		$max 	=	$this->db->query("
						SELECT MAX(id) as max_id FROM ".$this->dbname.".stok_produk 
					")->row_array();


		if(!@$_GET['id']):

			if($this->db->count_all($this->db->dbprefix."data_stock_product")):
		
				$this->db->query("
					DELETE FROM ".$this->db->dbprefix."data_stock_product WHERE stock_cabang_id = '".$this->cabang_id."'
				");

			endif;


		endif;

		
		//dump data
		$this->db->query("
			INSERT INTO ".$this->db->dbprefix."data_stock_product
			(
				`stock_cabang_id`,`stock_produk_kode`,`stock_nomer_order`,`stock_type_transaksi`,
				`stock_jenis`,`stock_harga_beli`,`stock_jumlah`,`stock_stok_awal`,`stock_stok_sisa`,
				`stock_stok_akhir`,`stock_nilai_barang`,`stock_hpp`,`stock_tanggal_transaksi`,`stock_ambil_dari`
			)

			SELECT
				
				'".$this->cabang_id."', `kode_produk`,`no_order`,`tipe_transaksi`,
				`jenis_stok`,`harga_beli`,`jumlah`,`stok_awal`,`sisa`,
				`stok_akhir`,`nilai_barang`,`hpp`,`tgl_transaksi`,`ambil_dari`

			FROM ".$this->dbname.".stok_produk WHERE id = '".@$_GET['id']."'
		");
		


		if(@$_GET['id'] < $max['max_id']):

			$up 	=	$_GET['id'] + 1;

			print '<meta http-equiv="refresh" content="0;URL=\'/dump/step-one?id='.$up.'\'" /> ';    

		else:

			print '<h3>DONE</h3>';
			exit;

		endif;
			
	}

	public function step_two()
	{
		//dump data stock

		//$this->db->query("
		//	DELETE FROM ".$this->db->dbprefix."data_stock_product WHERE stock_cabang_id = '".$cabang_id."'
		//");

		
		$this->db->query("
			DELETE FROM ".$this->db->dbprefix."data_stock WHERE stok_cabang_id = '".$this->cabang_id."'
		");

		$this->db->query("

			INSERT INTO ".$this->db->dbprefix."data_stock 
			(
				`stok_produk_kode`,`stok_cabang_id`
			)

			SELECT 

				DISTINCT(`stock_produk_kode`), '".$this->cabang_id."'

			FROM ".$this->db->dbprefix."data_stock_product WHERE stock_cabang_id = '".$this->cabang_id."'
		");		

	}

	public function step_three()
	{
		
		$min 	=	$this->db->query("
						SELECT MIN(stock_id) as min_id FROM ".$this->db->dbprefix."data_stock_product 
						WHERE stock_cabang_id = '".$this->cabang_id."'
					")->row_array();

		$max 	=	$this->db->query("
						SELECT MAX(stock_id) as max_id FROM ".$this->db->dbprefix."data_stock_product 
						WHERE stock_cabang_id = '".$this->cabang_id."'
					")->row_array();


		$stock_id 	=	(@$_GET['id']) ? @$_GET['id'] : $min['min_id'];


		$query 	=	$this->db->query("

						SELECT * 
						FROM ".$this->db->dbprefix."data_stock_product a 

						INNER JOIN (
							SELECT stock_cabang_id, stock_produk_kode, MIN(stock_stok_akhir) stok_akhir
							FROM ".$this->db->dbprefix."data_stock_product GROUP BY stock_cabang_id, stock_produk_kode
						) b ON a.stock_cabang_id = b.stock_cabang_id AND 

						a.stock_produk_kode = b.stock_produk_kode AND a.stock_stok_akhir = b.stok_akhir 

						WHERE a.stock_cabang_id = '".$this->cabang_id."' && a.stock_id = '".$stock_id."'

					")->result_array();

		foreach($query as $result):

			//perbaiki stock_nilai_barang
			$stock_nilai_barang = $result['stock_stok_akhir'] * $result['stock_hpp'];
			
			$this->db->query("
				UPDATE ".$this->db->dbprefix."data_stock_product SET 
					`stock_nilai_barang` = '".$stock_nilai_barang."'
				WHERE stock_id = '".$result['stock_id']."'
			");

			$this->db->query("
				UPDATE ".$this->db->dbprefix."data_stock SET 
					`stok_jumlah` = '".$result['stok_akhir']."'
				WHERE stok_cabang_id = '".$result['stock_cabang_id']."' && stok_produk_kode = '".$result['stock_produk_kode']."'
			");

		endforeach;

		

		if(@$_GET['id'] < $max['max_id']):

			$up 	=	$stock_id + 1;

			print '<meta http-equiv="refresh" content="0;URL=\'/dump/step-three?id='.$up.'\'" /> ';    

		else:

			print '<h3>DONE</h3>';
			exit;

		endif;

	}

	public function step_four()
	{

		$min 	=	$this->db->query("
						SELECT MIN(stock_id) as min_id FROM ".$this->db->dbprefix."data_stock_product 
						WHERE stock_cabang_id = '".$this->cabang_id."'
					")->row_array();

		$max 	=	$this->db->query("
						SELECT MAX(stock_id) as max_id FROM ".$this->db->dbprefix."data_stock_product 
						WHERE stock_cabang_id = '".$this->cabang_id."'
					")->row_array();


		$stock_id 	=	(@$_GET['id']) ? @$_GET['id'] : $min['min_id'];

		if(!@$_GET['id']):

			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_stock_product_detail WHERE stock_detail_cabang_id = '".$this->cabang_id."'
			");

		endif;

		$query	=	$this->db->query("

						SELECT *
							,(SELECT product_nama FROM ".$this->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk
						FROM ".$this->db->dbprefix."data_stock_product a 
						WHERE stock_cabang_id = '".$this->cabang_id."' && stock_id = '".$stock_id."'

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

				$this->db->insert($this->db->dbprefix."data_stock_product_detail", $data);

			endfor;

		endforeach;


		if(@$_GET['id'] < $max['max_id']):

			$up 	=	$stock_id + 1;

			print '<meta http-equiv="refresh" content="0;URL=\'/dump/step-four?id='.$up.'\'" /> ';    

		else:

			print '<h3>DONE</h3>';
			exit;

		endif;	

	}

	public function step_five()
	{
			//dump data produk_rakitan
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."setting_produk_rakitan 
				WHERE rakitan_cabang_id = '".$this->cabang_id."'
			");

			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."setting_produk_rakitan_detail 
				WHERE rakitan_detail_cabang_id = '".$this->cabang_id."'
			");


			$this->db->query("

				INSERT INTO ".$this->db->dbprefix."setting_produk_rakitan 
				(
					`rakitan_cabang_id`,`rakitan_kode`,`rakitan_product_kode`,`rakitan_tanggal`,
					`rakitan_jumlah`,`rakitan_hpp`,`rakitan_user_id`,`rakitan_status`
				)

				SELECT 

					'".$this->cabang_id."', kode_rakitan,kode_produk,tgl_rakit,
					jumlah,hpp,actor,status

				FROM ".$this->dbname.".rakitan a 

			");	

			$this->db->query("

				INSERT INTO ".$this->db->dbprefix."setting_produk_rakitan_detail 
				(
					`rakitan_detail_cabang_id`,`rakitan_detail_kode_produk`,`rakitan_detail_rakitan_kode`,`rakitan_detail_jumlah`
				)

				SELECT 

					'".$this->cabang_id."', kode_produk, detail_rakitan, jumlah

				FROM ".$this->dbname.".rakitan_detail a 

			");			
	}

	/*
	public function step_fourx()
	{

		$this->db->query("
			DELETE FROM ".$this->db->dbprefix."data_stock_product_detail WHERE stock_detail_cabang_id = '".$this->cabang_id."' && stock_detail_type =  'masuk'
		");

		$query	=	$this->db->query("

						SELECT *
							,(SELECT product_nama FROM ".$this->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk
						FROM ".$this->db->dbprefix."data_stock_product a 
						WHERE stock_type_transaksi = 'beli' && stock_cabang_id = '".$this->cabang_id."'

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

				$this->db->insert($this->db->dbprefix."data_stock_product_detail", $data);

			endfor;

		endforeach;


		print '<h2>done</h2>';		

	}

	public function step_fivex()
	{

		$this->db->query("
			DELETE FROM ".$this->db->dbprefix."data_stock_product_detail WHERE stock_detail_cabang_id = '".$this->cabang_id."' && stock_detail_type =  'keluar'
		");

		$query	=	$this->db->query("

						SELECT *
							,(SELECT product_nama FROM ".$this->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk 
						FROM ".$this->db->dbprefix."data_stock_product a 
						WHERE stock_type_transaksi = 'jual' && stock_cabang_id = '".$this->cabang_id."'

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

				$this->db->insert($this->db->dbprefix."data_stock_product_detail", $data);

			endfor;

		endforeach;


		print '<h2>done</h2>';		

	}
	*/

	public function dump_stock_product()
	{

		$dbname 	=	$this->dbname;

		if($dbname):

			$query	=	$this->db->query("
							SELECT cabang_id FROM ".$this->db->dbprefix."setting_cabang 
							WHERE cabang_old_dbname = '".$this->dbname."'
						")->row_array();

			$cabang_id =	$query['cabang_id'];


			//hapus stock dari cabang terpilih

			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_stock_product WHERE stock_cabang_id = '".$cabang_id."'
			");

			//step 2
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_stock WHERE stok_cabang_id = '".$cabang_id."'
			");

			//dump data
			$this->db->query("
				INSERT INTO ".$this->db->dbprefix."data_stock_product
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

			$this->db->query("

				INSERT INTO ".$this->db->dbprefix."data_stock 
				(
					`stok_produk_kode`,`stok_cabang_id`
				)

				SELECT 

					DISTINCT(`stock_produk_kode`), '".$cabang_id."'

				FROM ".$this->db->dbprefix."data_stock_product
			");

			//eof step 2

			//masukkan jumlah stok akhir
			/*
			$query =	$this->db->query("

							SELECT a.stok_id 
								,(SELECT stock_stok_akhir FROM ".$this->db->dbprefix."data_stock_product WHERE stock_type_transaksi = 'beli' && stock_produk_kode = a.stok_produk_kode ORDER BY stock_id DESC LIMIT 1) as jumlah_stok_akhir
							FROM ".$this->db->dbprefix."data_stock a
							

						")->result_array();
			*/

			//step3 

			$query 	=	$this->db->query("

							SELECT * 
							FROM ".$this->db->dbprefix."data_stock_product a 

							INNER JOIN (
								SELECT stock_cabang_id, stock_produk_kode, MIN(stock_stok_akhir) stok_akhir
								FROM ".$this->db->dbprefix."data_stock_product GROUP BY stock_cabang_id, stock_produk_kode
							) b ON a.stock_cabang_id = b.stock_cabang_id AND 
							a.stock_produk_kode = b.stock_produk_kode AND a.stock_stok_akhir = b.stok_akhir

						")->result_array();

			foreach($query as $result):

				//perbaiki stock_nilai_barang
				$stock_nilai_barang = $result['stock_stok_akhir'] * $result['stock_hpp'];
				
				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_stock_product SET 
						`stock_nilai_barang` = '".$stock_nilai_barang."'
					WHERE stock_id = '".$result['stock_id']."'
				");

				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_stock SET 
						`stok_jumlah` = '".$result['stok_akhir']."'
					WHERE stok_cabang_id = '".$result['stock_cabang_id']."' && stok_produk_kode = '".$result['stock_produk_kode']."'
				");

			endforeach;

			//eof step 3


			//input data stock detail
			//bersihkan dulu data lama

			//step 4
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_stock_product_detail WHERE stock_detail_cabang_id = '".$cabang_id."'
			");

			$query	=	$this->db->query("

							SELECT *
								,(SELECT product_nama FROM ".$this->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk
							FROM ".$this->db->dbprefix."data_stock_product a 
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

					$this->db->insert($this->db->dbprefix."data_stock_product_detail", $data);

				endfor;

			endforeach;

			$query	=	$this->db->query("

							SELECT *
								,(SELECT product_nama FROM ".$this->db->dbprefix."setting_produk WHERE product_kode = a.stock_produk_kode ORDER BY product_id DESC LIMIT 1) as nama_produk 
							FROM ".$this->db->dbprefix."data_stock_product a 
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

					$this->db->insert($this->db->dbprefix."data_stock_product_detail", $data);

				endfor;

			endforeach;

			//eof step 4 - 5


			//dump data produk_rakitan
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."setting_produk_rakitan 
				WHERE rakitan_cabang_id = '".$cabang_id."'
			");

			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."setting_produk_rakitan_detail 
				WHERE rakitan_detail_cabang_id = '".$cabang_id."'
			");


			$this->db->query("

				INSERT INTO ".$this->db->dbprefix."setting_produk_rakitan 
				(
					`rakitan_cabang_id`,`rakitan_kode`,`rakitan_product_kode`,`rakitan_tanggal`,
					`rakitan_jumlah`,`rakitan_hpp`,`rakitan_user_id`,`rakitan_status`
				)

				SELECT 

					'".$cabang_id."', kode_rakitan,kode_produk,tgl_rakit,
					jumlah,hpp,actor,status

				FROM ".$dbname.".rakitan a 

			");	

			$this->db->query("

				INSERT INTO ".$this->db->dbprefix."setting_produk_rakitan_detail 
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


}
