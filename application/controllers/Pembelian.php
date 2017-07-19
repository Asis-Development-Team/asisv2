<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;

	public function __construct()
	{
		parent::__construct();		
		
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

		$is_login   =   json_decode($this->authentication->is_login($this->session->sess_user_id,$this->session->session_id),true);

		if($is_login['status'] == false):
		    header('location:/');
			exit;
		endif;

		$this->load->library(array('data_lib','pembelian_lib','generate_lib','number_lib','penerimaan_lib','hutang_lib','pembayaran_lib'));

	}	

	public function index()
	{
		//
	}

	public function pembayaran_hutang_form()
	{

		$view 	=	'/form/view_form';

		$total_detail 	=	5;

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->pembayaran_lib->data_pembayaran_single('kode',@$_GET['kode'],$this->session->sess_user_cabang_id);
			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

     	$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false);

     	$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true);
     	$request_supplier 	=	$this->data_lib->data_supplier();

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_' . __FUNCTION__,

                    	'outlet' => $request_outlet['result'],
                    	'rekening' => $request_rekening['result'],
                    	'supplier' => $request_supplier['result'],

                    	'result' => @$result,

                    	'today' => date('Y-m-d', strtotime($this->local_time)),
                   
                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function pembayaran_hutang_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$pembayaran_kode = $this->input->post('pembayaran_kode');

			//cek duplikasi
			if($this->input->post('identifer') == 'add' && $this->db->count_all($this->db->dbprefix."data_pembayaran WHERE pembayaran_kode = '".addslashes($pembayaran_kode)."' ")):

				$request = $this->generate_lib->generate_pembayaran_hutang_kode($this->input->post('pembayaran_cabang_id'));

				$pembayaran_kode =	$request['result']['nomer_kode'];

			endif;

			//update data hutang usaha
			$hutang_jumlah 		=	$this->input->post('hutang_jumlah');
			$hutang_terbayar	=	$this->input->post('hutang_terbayar');
			$hutang_saldo 		=	$this->input->post('hutang_saldo');

			$jumlah_bayar 		=	str_replace(',','', $this->input->post('pembayaran_total'));

			if($hutang_terbayar == '0.00'):
				$hutang_terbayar 	=	$jumlah_bayar;
				$hutang_saldo 		=	$hutang_jumlah - $hutang_terbayar;
			else:
				$hutang_terbayar 	=	$hutang_terbayar + $jumlah_bayar;
				$hutang_saldo 		=	$hutang_jumlah - $hutang_terbayar;
			endif;

			$status_pembayaran 	=	($hutang_saldo < '1') ? '2' : '1';

			/*
			$resp 	=	array(
							'hutang_jumlah' => $hutang_jumlah,
							'hutang_terbayar' => $hutang_terbayar,
							'hutang_saldo' => $hutang_saldo,
							'status' => $status_pembayaran
						);	
			*/
			$update =	array(
							'hutang_terbayar' => $hutang_terbayar,
							'hutang_saldo' => $hutang_saldo,
							'hutang_status' => $status_pembayaran,
						);

			$this->db->update(
				$this->db->dbprefix."data_hutang_usaha", $update,
				array(
					'hutang_cabang_id' => $this->input->post('pembayaran_cabang_id'),
					'hutang_kode' => $this->input->post('pembayaran_detail_no_invoice'),
					'hutang_supplier_id' => $this->input->post('pembayaran_dari_ke')
				)
			);


			$data 	=	array(
							'pembayaran_cabang_id' => $this->input->post('pembayaran_cabang_id'),
							'pembayaran_tipe' => $this->input->post('pembayaran_tipe'),
							'pembayaran_kode' => $pembayaran_kode,
							'pembayaran_tanggal_faktur' => $this->input->post('pembayaran_tanggal_faktur'),
							'pembayaran_dari_ke' => $this->input->post('pembayaran_dari_ke'),
							'pembayaran_rekening' => $this->input->post('pembayaran_rekening'),
							'pembayaran_total' => str_replace(',','', $this->input->post('pembayaran_total')),
							'pembayaran_keterangan' => $this->input->post('pembayaran_keterangan'),
							//'pembyaran_no_invoice' => $this->input->post('pembayaran_detail_no_invoice'),
							'pembayaran_user_id' => $this->session->sess_user_id
						);

			//$this->db->insert($this->db->dbprefix."data_pembayaran", $data);

			$detail =	array(
							'pembayaran_detail_cabang_id' => $this->input->post('pembayaran_cabang_id'),
							'pembayaran_detail_tipe' => $this->input->post('pembayaran_tipe'),
							'pembayaran_detail_kode_pembayaran' => $pembayaran_kode,
							'pembayaran_detail_no_invoice' => $this->input->post('pembayaran_detail_no_invoice'),
							'pembayaran_detail_saldo' => str_replace(',','', $this->input->post('pembayaran_jumlah_invoice')),
							'pembayaran_detail_bayar' => str_replace(',','', $this->input->post('pembayaran_total')),
						);

			//$this->db->insert($this->db->dbprefix."data_pembayaran_detail", $detail);


			//simpan ke jurnal
			$request 		=	$this->generate_lib->generate_jurnal_kode($this->input->post('pembayaran_cabang_id'));
			$kode_jurnal 	=	$request['result']['nomer_jurnal'];


			$jurnal =	array(
							'jurnal_cabang_id' => $this->input->post('pembayaran_cabang_id'),
							'jurnal_kode' => $kode_jurnal,
							'jurnal_tanggal' => $this->input->post('pembayaran_tanggal_faktur'), //$this->local_time,
							'jurnal_type' => 'umum',
							'jurnal_nomer_bukti' => $this->input->post('pembayaran_detail_no_invoice'),
							'jurnal_keterangan' => $this->input->post('pembayaran_keterangan'),
							'jurnal_tanggal_input' => $this->local_time,
							'jurnal_tanggal_posting' => $this->local_time,
							'jurnal_user_id' => $this->session->sess_user_id
						);

			//$this->db->insert($this->db->dbprefix."data_jurnal_umum", $jurnal);


			$jurnal_detail_kredit =	array(
										'jurnal_detail_cabang_id' => $this->input->post('pembayaran_cabang_id'),
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $this->input->post('pembayaran_rekening'),
										'jurnal_detail_debit_kredit' => 'K',
										'jurnal_detail_nominal_kredit' => str_replace(',','',$this->input->post('pembayaran_total')),
									);

			//$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail_kredit);


			$jurnal_detail_debit =	array(
										'jurnal_detail_cabang_id' => $this->input->post('pembayaran_cabang_id'),
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $this->input->post('pembayaran_rekening'),
										'jurnal_detail_debit_kredit' => 'D',
										'jurnal_detail_nominal_debit' => str_replace(',','',$this->input->post('pembayaran_total')),
									);

			//$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail_debit);


			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_pembayaran", $data);
				$this->db->insert($this->db->dbprefix."data_pembayaran_detail", $detail);
				$this->db->insert($this->db->dbprefix."data_jurnal_umum", $jurnal);
				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail_kredit);
				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail_debit);


				$url 	=	'/pembelian/pembayaran-hutang-form/?id=' . $this->db->insert_id() .'&kode='.$pembayaran_kode.'&msg=1';
				$action =	false; 		

			else:

				//$this->db->update(
				//	$this->db->dbprefix."data_po", $data,
				//	array('po_id' => $this->input->post('po_id'))
				//);

				//$this->db->query("
				//	DELETE FROM ".$this->db->dbprefix."data_po_detail 
				//	WHERE po_detail_nomer_po = '".$po_nomer_po."' 
				//");

				$url 	=	false;
				$action =	'stay'; 

			endif;


			$callback 	=	array(
								'status' => 200,
								'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => $url,
								'action' => $action
							);


			print json_encode($callback);


		endif;
	}

	public function pembayaran_hutang()
	{
		
		$this->load->library(array('pembayaran_lib'));

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->pembayaran_lib->data_pembayaran(
							'hutang',@$_GET['q'],$perpage,@$_GET['supplier'],$cabang_id,@$_GET['from'],@$_GET['to']
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';
		
		$request_supplier 	=	$this->data_lib->data_supplier(false,false);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        //'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        //'page_url' => str_replace('_','-',__FUNCTION__),

                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                              

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => $request_outlet['result'],

                        'outlet' => $request_outlet_single,

                        'today' => $this->local_time,

                        'supplier' => $request_supplier['result'],
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  		
	}	

	public function hutang_usaha()
	{
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->hutang_lib->data_hutang(
							@$_GET['q'],$perpage,@$_GET['supplier'],$cabang_id,true
						);

		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';


		$request_supplier 	=	$this->data_lib->data_supplier(false,false);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => @$request_outlet['result'],
                        'outlet' => @$request_outlet_single,

                        'supplier' => $request_supplier['result'],
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  			
	}

	public function penerimaan_produk()
	{

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->penerimaan_lib->data_penerimaan(
							@$_GET['q'],$perpage,$cabang_id,@$_GET['supplier'],@$_GET['from'],@$_GET['to']
						);

		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

		$request_supplier 	=	$this->data_lib->data_supplier(false,false);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => @$request_outlet['result'],
                        'outlet' => @$request_outlet_single,

                        'supplier' => $request_supplier['result'],
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  				

	}

	public function po_supplier_penerimaan_save()
	{
		$this->load->library(array('stock_lib','penerimaan_lib'));

		if($this->agent->referrer() && $this->session->sess_user_id):

			$this->db->trans_start();
			
			//decode content
			$content 	=	json_decode($this->input->post('content'),true);

			//generate nomer kode penerimaan 53
			$request 			=	$this->pembelian_lib->po_penerimaan_number($content['penerimaan_cabang_id']);
			$nomer_penerimaan 	=	$request['result']['nomer'];

			//simpan ke data penerimaan
			$data 	=	array(
							'penerimaan_cabang_id' => $content['penerimaan_cabang_id'],
							'penerimaan_tanggal' => addslashes($this->input->post('penerimaan_tanggal')),
							'penerimaan_user_id' => $this->session->sess_user_id,
							'penerimaan_no_penerimaan' => $nomer_penerimaan,
							'penerimaan_no_po' => $content['penerimaan_no_po'],
							'penerimaan_no_surat_jalan' => addslashes($this->input->post('penerimaan_no_surat_jalan')),
							'penerimaan_supplier_id' => $content['penerimaan_supplier_id'],
							'penerimaan_tanggal_faktur' =>addslashes($content['penerimaan_tanggal_faktur']),
							'penerimaan_keterangan' => $content['penerimaan_keterangan'],
							'penerimaan_tanggal_pengiriman' => addslashes($this->input->post('penerimaan_tanggal')),
							'penerimaan_total_setelah_pajak' => $content['penerimaan_total_setelah_pajak'],
							'penerimaan_uang_muka' =>  $content['penerimaan_uang_muka'],
							'penerimaan_hutang' => $content['penerimaan_hutang'],
							'penerimaan_status_pembayaran' => $content['penerimaan_status_pembayaran'], 
							'penerimaan_biaya_lain' => '0',
						);

			$this->db->insert($this->db->dbprefix."data_penerimaan", $data);

			//simpan ke hutang usaha
			if($content['po_cara_bayar'] == 'tempo'):

				//kode hutang sama dengan kode penerimaan
				$request 		=	$this->pembelian_lib->po_penerimaan_number($content['penerimaan_cabang_id']);
				$kode_hutang 	=	$request['result']['nomer'];

				$request 	=	$this->number_lib->number_kode_akun_single('id','11');
				$kode_akun 	=	$request['result']['akun_nomer'];

				$hutang = 	array(
								'hutang_cabang_id' => $content['penerimaan_cabang_id'],
								'hutang_tanggal_input' => $this->local_time,
								'hutang_kode' => $kode_hutang,
								'hutang_supplier_id' => $content['penerimaan_supplier_id'],
								'hutang_tanggal_faktur' => $content['penerimaan_tanggal_faktur'],
								'hutang_nomer_order' => $nomer_penerimaan,
								'hutang_nomer_po' => $content['penerimaan_no_po'],
								'hutang_nomer_surat_jalan' => addslashes($this->input->post('penerimaan_no_surat_jalan')),
								'hutang_mata_uang' => 'IDR',
								'hutang_jumlah' => $content['penerimaan_hutang'],
								'hutang_terbayar' => '0',
								'hutang_saldo' => $content['penerimaan_hutang'],
								'hutang_akun' => $kode_akun,
								'hutang_tanggal' => $this->local_time,
								'hutang_type' => 'transaksi',
								'hutang_status' => '0'
							);

				$this->db->insert($this->db->dbprefix."data_hutang_usaha", $hutang);
				

			endif;

			//simpan ke penerimaan detail
			$request =	$this->pembelian_lib->po_supplier_detail_single('nomer_po',$content['penerimaan_no_po']);
			$result  =	$request['result'];

			foreach($result as $result):

				$detail = 	array(
								'penerimaan_detail_cabang_id' => $content['penerimaan_cabang_id'],
								'penerimaan_detail_no_penerimaan' => $nomer_penerimaan,
								'penerimaan_detail_no_po' => $content['penerimaan_no_po'],
								'penerimaan_detail_tanggal_faktur' => $content['penerimaan_tanggal_faktur'],
								'penerimaan_detail_product_kode' => $result['po_detail_product_kode'],
								'penerimaan_detail_product_nama' => $result['detail_nama_produk'],
								'penerimaan_detail_jumlah' => $result['po_detail_jumlah_permintaan'],
								'penerimaan_detail_jumlah_terima' => $result['po_detail_jumlah_permintaan'],
								'penerimaan_detail_satuan' => '',
								'penerimaan_detail_harga' => $result['po_detail_harga'],
								'penerimaan_detail_diskon' => $result['po_detail_diskon'],
								'penerimaan_detail_total' => $result['po_detail_total'],
							);

				$this->db->insert($this->db->dbprefix."data_penerimaan_detail",$detail);

			endforeach;	

			//update status
			$this->db->query("
				UPDATE ".$this->db->dbprefix."data_po SET 
					`po_status` = '3'
				WHERE po_nomer_po = '".$content['penerimaan_no_po']."'
			");

			//tambahkan data_stok + data_stok_produk + data_stok_produk_detail
			$result  =	$request['result']; //dari request diatas

			//tambahkan ke data_stok_produk
			foreach($result as $result):

				//ambil jumlah stock terakhir
				$request 	=	$this->stock_lib->stock_product_single('produk_kode',$result['po_detail_product_kode'],$content['penerimaan_cabang_id'],true);				
				$stock 		=	$request['result'];

				$stock_akhir 		=	($stock['stock_stok_akhir']) ? $stock['stock_stok_akhir'] : '0' ;
				$stock_nilai_barang	=	($stock['stock_nilai_barang']) ? $stock['stock_nilai_barang'] : '0';
				$stock_hpp 			=	($stock['stock_hpp']) ? $stock['stock_hpp']  : '0' ;
				$stock_harga_beli	=	$result['po_detail_harga']; //($stock['stock_harga_beli']) ? $stock['stock_harga_beli']  : '0'; 

				//stock akhir baru
				$stock_akhir 		=	$stock_akhir + $result['po_detail_jumlah_permintaan'];

				//nilai barang baru
				$stock_nilai_barang =	$result['po_detail_harga'] * $stock_akhir;

				//stock_hpp baru
				$stock_hpp 			=	$stock_nilai_barang / $stock_akhir;

				$data 	=	array(
								'stock_cabang_id' => $content['penerimaan_cabang_id'],
								'stock_produk_kode' => $result['po_detail_product_kode'],
								'stock_nomer_order' => $nomer_penerimaan,
								'stock_type_transaksi' => 'beli',
								'stock_jenis' => '',
								'stock_harga_beli' => $stock_harga_beli,

								'stock_jumlah' => $result['po_detail_jumlah_beli'],
								'stock_stok_awal' => $stock['stock_stok_akhir'],
								'stock_stok_sisa' => $result['po_detail_jumlah_beli'],
								'stock_stok_akhir' => $stock_akhir,
								
								'stock_nilai_barang' => $stock_nilai_barang,
								'stock_hpp' => $stock_hpp,
								'stock_tanggal_transaksi' => $this->local_time,
								'stock_user_id' => $this->session->sess_user_id,
							);
				
				$this->db->insert($this->db->dbprefix."data_stock_product", $data);

				$stok_id 	=	$this->db->insert_id();

				//update data_stock
				//cek data produk sudah ada atau belum kalo belum tambahkan kalo sudah update
				$cek 	=	$this->stock_lib->stock_data_stock_single('produk_kode',$result['po_detail_product_kode'],$content['penerimaan_cabang_id']);

				if($cek['total']):

					$data_stock 	=	array(
											'stok_jumlah' => $stock_akhir,
										);

					$this->db->update(
						$this->db->dbprefix."data_stock", $data_stock,
						array(
							'stok_cabang_id' => $content['penerimaan_cabang_id'],
							'stok_produk_kode' => $result['po_detail_product_kode']
						)
					);

				else:

					$data_stock 	=	array(
											'stok_cabang_id' => $content['penerimaan_cabang_id'],
											'stok_produk_kode' => $result['po_detail_product_kode'],
											'stok_jumlah' => $stock_akhir
										);

					$this->db->insert($this->db->dbprefix."data_stock", $data_stock);

				endif; //eof cek

				//simpan detail stock
				for($i=1;$i<=$result['po_detail_jumlah_beli'];$i++):

					$detail =	array(
									'stock_detail_cabang_id' => $content['penerimaan_cabang_id'],
									'stock_detail_stock_id' => $stok_id,
									'stock_detail_type' => 'masuk',
									'stock_detail_nomer_order' => $nomer_penerimaan,
									'stock_detail_produk_kode' => $result['po_detail_product_kode'],
									'stock_detail_produk_nama' => $result['detail_nama_produk'],
									'stock_detail_serial_number' => '',
									'stock_detail_barcode' => '',
									'stock_detail_tanggal_masuk' => $this->local_time,
									'stock_detail_status' => 'ada'
								);

					$this->db->insert($this->db->dbprefix."data_stock_product_detail", $detail);

				endfor;


			endforeach;
			//eof data stock_product

			###simpan data jurnal###
			//ambil data akun bayar dari po supplier
			$request 	=	$this->pembelian_lib->po_supplier_single('nomer_po',$content['penerimaan_no_po'],$content['penerimaan_cabang_id']);
			$akun_bayar =	$request['result']['po_akun_bayar'];	

			//generate akun biaya
			$akun_biaya_lain 	=	$this->number_lib->number_kode_akun_single('id',1); //kode ongkir pembelian
			$akun_biaya_lain	=	$akun_biaya_lain['result']['akun_nomer'];

			$akun_uang_muka 	=	$this->number_lib->number_kode_akun_single('id',13); //kode uang muka
			$akun_uang_muka		=	$akun_uang_muka['result']['akun_nomer'];

			$akun_hutang_usaha 	=	$this->number_lib->number_kode_akun_single('id',11); //kode hutang usaha
			$akun_hutang_usaha	=	$akun_hutang_usaha['result']['akun_nomer'];


			//masuk ke jurnal
			$request 		=	$this->generate_lib->generate_jurnal_kode($content['penerimaan_cabang_id']);
			$kode_jurnal 	=	$request['result']['nomer_jurnal'];

			$request 	=	$this->penerimaan_lib->data_penerimaan_single('no_penerimaan',$nomer_penerimaan,$this->session->sess_user_cabang_id);
			$penerimaan =	$request['result'];
			
			$jurnal 	=	array(
								'jurnal_cabang_id' => $content['penerimaan_cabang_id'],
								'jurnal_kode' => $kode_jurnal,
								'jurnal_tanggal' => $this->local_time,
								'jurnal_type' => 'umum',
								'jurnal_nomer_bukti' => $nomer_penerimaan,
								'jurnal_keterangan' => $content['penerimaan_keterangan'],
								'jurnal_tanggal_input' => $this->local_time,
								'jurnal_tanggal_posting' => $this->local_time,
								'jurnal_user_id' => $this->session->sess_user_id
							);

			$this->db->insert($this->db->dbprefix."data_jurnal_umum", $jurnal);

			//query data penerimaan untuk masuk ke jurnal_detail
			$request 	=	$this->penerimaan_lib->data_penerimaan_custom($nomer_penerimaan);

			foreach($request['result'] as $result):

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $result['rekening_persediaan'],
										'jurnal_detail_debit_kredit' => 'D',
										'jurnal_detail_nominal_debit' => $result['total'],
										'jurnal_detail_nominal_kredit' => '0',
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

			endforeach;


			$penerimaan_setelah_pajak 	=	$penerimaan['penerimaan_total_setelah_pajak'];
			$penerimaan_uang_muka 		=	$penerimaan['penerimaan_uang_muka'];
			$penerimaan_biaya_lain 		=	$penerimaan['penerimaan_biaya_lain'];
			$penerimaan_hutang			=	$penerimaan['penerimaan_hutang'];
			$penerimaan_uang_muka 		=	$penerimaan['penerimaan_uang_muka'];

			//if($penerimaan_biaya_lain != '0' || !is_null($penerimaan_biaya_lain)):
			if($penerimaan_biaya_lain > '0'):			

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $akun_biaya_lain,
										'jurnal_detail_debit_kredit' => 'D',
										'jurnal_detail_nominal_debit' => $penerimaan_biaya_lain,
										'jurnal_detail_nominal_kredit' => '0',
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

			endif;	

			if($penerimaan_hutang != '0' && $penerimaan_uang_muka != '0'):

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $akun_uang_muka,
										'jurnal_detail_debit_kredit' => 'K',
										'jurnal_detail_nominal_debit' => '0',
										'jurnal_detail_nominal_kredit' => $penerimaan_uang_muka,
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $akun_hutang_usaha,
										'jurnal_detail_debit_kredit' => 'K',
										'jurnal_detail_nominal_debit' => '0',
										'jurnal_detail_nominal_kredit' => $penerimaan_hutang,
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

			endif;	

			if($penerimaan_hutang == $penerimaan_setelah_pajak):

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $akun_hutang_usaha,
										'jurnal_detail_debit_kredit' => 'K',
										'jurnal_detail_nominal_debit' => '0',
										'jurnal_detail_nominal_kredit' => $penerimaan_hutang,
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

			endif;	

			if($penerimaan_hutang == '0' && $penerimaan_uang_muka == '0' && $penerimaan_setelah_pajak != '0'):

				$jurnal_detail 	=	array(
										'jurnal_detail_cabang_id' => $content['penerimaan_cabang_id'],
										'jurnal_detail_kode_jurnal' => $kode_jurnal,
										'jurnal_detail_kode_rekening' => $akun_bayar,
										'jurnal_detail_debit_kredit' => 'K',
										'jurnal_detail_nominal_debit' => '0',
										'jurnal_detail_nominal_kredit' => $penerimaan_setelah_pajak,
									);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_detail);

			endif;		

			$this->delete_cache();

			$this->db->trans_complete();

			$callback 	=	array(
								'status' => 200,
								'message' => 'Data penerimaan telah disimpan.',
								'no_penerimaan' => $nomer_penerimaan
							);

			print json_encode($callback);

		endif;
	}

	public function po_supplier_save_beli()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			$data 	=	array(
							'po_status' => '2'
						);

			$this->db->update(
				$this->db->dbprefix."data_po", $data,
				array(
					'po_id' => $this->input->post('id'),
					'po_nomer_po' => $this->input->post('po'),
				)
			);

			//bikin jurnal kalo tempo dan ada uang muka
			if($this->input->post('cara') == 'tempo' && $this->input->post('um')):

				$request 		=	$this->generate_lib->generate_jurnal_kode($this->input->post('cabang-id'));
				$nomer_jurnal 	=	$request['result']['nomer_jurnal'];	

				$request 		=	$this->generate_lib->generate_kode_akun('14');
				$nomer_akun 	=	$request['result']['akun_nomer'];

				$keterangan 	=	'Uang Mula ' . $this->input->post('keterangan');

				$jurnal 	=	array(
								'jurnal_cabang_id' => $this->input->post('cabang-id'),
								'jurnal_kode' => $nomer_jurnal,
								'jurnal_tanggal' => $this->local_time,
								'jurnal_type' => 'umum',
								'jurnal_nomer_bukti' => $this->input->post('po'),
								'jurnal_keterangan' => $keterangan,
								'jurnal_tanggal_input' => $this->local_time,
								'jurnal_tanggal_posting' => $this->input->post('jurnal_tanggal_posting'),
								'jurnal_user_id' => $this->session->sess_user_id,
								'jurnal_status_penerimaan_uang' => $this->input->post('jurnal_status_penerimaan_uang'),
								'jurnal_status_penerimaan_relasi_id' => $this->input->post('jurnal_status_penerimaan_relasi_id')
							);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum", $jurnal);

				$debit 	=	array(
								'jurnal_detail_cabang_id' => $this->input->post('cabang-id'),
								'jurnal_detail_kode_jurnal' => $nomer_jurnal,
								'jurnal_detail_kode_rekening' => $nomer_akun,
								'jurnal_detail_debit_kredit' => 'D',
								'jurnal_detail_nominal_debit' => $this->input->post('um'),
								'jurnal_detail_nominal_kredit' => '0', 
								'jurnal_detail_saldo' => '0'
							);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $debit);

				$kredit =	array(
								'jurnal_detail_cabang_id' => $this->input->post('cabang-id'),
								'jurnal_detail_kode_jurnal' => $nomer_jurnal,
								'jurnal_detail_kode_rekening' => $nomer_akun,
								'jurnal_detail_debit_kredit' => 'K',
								'jurnal_detail_nominal_debit' => '0',
								'jurnal_detail_nominal_kredit' => $this->input->post('um'), 
								'jurnal_detail_saldo' => '0'
							);

				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $kredit);


			endif;

			//hapus cache
			
			$this->delete_cache();
			
			$callback 	=	array(
								'status' => 200,
								'message' => 'Data pembelian telah diproses.',
							);

			print json_encode($callback);

		endif;
	}

	public function pembelian_purchasing_detail_po()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->pembelian_lib->po_supplier_detail_single('nomer_po',$this->input->post('nomer-po'));

			$callback 	=	array(
								'result' => $request['result'],
								//'total' => $request['total'],
							);

			print json_encode($callback);

		endif;

	}

	public function pembelian_purchasing_form()
	{

		$view 	=	'/form/view_form';

		$total_detail 	=	5;

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->pembelian_lib->po_supplier_single('id',@$_GET['id'],$this->session->sess_user_cabang_id);
			$result 	=	$request['result'];

			//request detail po
			$request_detail =	$this->pembelian_lib->po_supplier_detail_single('nomer_po', $result['po_nomer_po']);
			$total_detail 	=	($request_detail['total'] > 5) ? $request_detail['total'] : $total_detail;

			$view 		=	($request['total'] && $result['po_status'] > 10) ? '/view_no_access' : $view;

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

     	$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false);
     	$request_jasa 		=	$this->setting_lib->setting_produk(false,false,false,false,false,false);

     	$requets_po_supplier 	=	$this->pembelian_lib->po_supplier(false,false,$cabang_id,'1');

     	$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true);
     	$request_supplier 	=	$this->data_lib->data_supplier();

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);


		//$request_po_numbe 	=	$this->pembelian_lib->po_outlet_po_number($cabang_id);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_pembelian_purchasing',

                    	'outlet' => $request_outlet['result'],
                    	'jasa' => $request_jasa['result'],

                    	'po' => $requets_po_supplier['result'],
                    	'rekening' => $request_rekening['result'],
                    	'supplier' => $request_supplier['result'],

                    	'result' => @$result,

                    	'total_detail' => @$total_detail

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function pembelian_purchasing()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->pembelian_lib->po_supplier(
							@$_GET['q'],$perpage,$cabang_id,@$_GET['proses'],@$_GET['from'],@$_GET['to']
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => $request_outlet['result'],

                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  		

	}

	public function po_supplier_detail_penawaran()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->pembelian_lib->po_supplier_detail_single('nomer_po', $this->input->post('nomer-po'));

			$callback 	=	array(
								'result' => $request['result'],
								//'total' => $request['total'],
							);

			print json_encode($callback);

		endif;

	}

	public function po_supplier_generate_number()
	{

		if($this->agent->referrer() && $this->session->sess_user_id && $this->input->post('cabang-id')):

			$request 	=	$this->pembelian_lib->po_supplier_po_number($this->input->post('cabang-id'));
			$nomer_po 	=	$request['result']['nomer_po'];

			//get detail po
			//$request 		=	$this->pembelian_lib->po_supplier_detail_single('nomer_po',$nomer_po);
			//$total_detail	=	$request['total'];

			print $request['result']['nomer_po'];

			/*
			$callback 	=	array(
								//'total' => $total_detail,
								'nomer_po' => $nomer_po,
								'cabang' => $this->input->post('cabang-id')
							);

			print json_encode($callback);
			*/
			

		endif;
	}

	public function po_supplier_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			//validasi data produk sudah masuk atau belum
			if($this->input->post('po-outlet-identifier') == 1):
				
				$request = $this->pembelian_lib->po_outlet_single_detail('nomer', $this->input->post('po_nomer_penawaran'));
				$total 	 = $request['total'];

			else:
				
				$temporary 	=	($this->input->post('identifier') == 'edit') ? false : true;

				$request = $this->pembelian_lib->po_supplier_detail_single('nomer_po',$this->input->post('po_nomer_po'),$temporary);
				$total 	 = $request['total'];

			endif;


			if($total < 1):

				$status 	=	201;
				$message 	=	'Pilih data produk';
				$url 		=	'';
				$action 	=	'';

			else:

				$this->db->trans_start();

				//cek duplikasi nomer po supplier ketika insert data
				$po_nomer_po 	=	$this->input->post('po_nomer_po');

				if($this->input->post('identifer') == 'add' && $this->db->count_all($this->db->dbprefix."data_po WHERE po_nomer_po = '".addslashes($po_nomer_po)."' ")):

					//buat nomer penawaran baru
					$request 	=	$this->pembelian_lib->po_supplier_po_number($this->input->post('po_cabang_id'));

					$po_nomer_po 	=	$request['result']['nomer_po'];

				endif;

				$tgl_pesan 	=	($this->input->post('po_tgl_pesan') == '') ? $this->input->post('po_tgl_input') : $this->input->post('po_tgl_pesan');

				if($this->input->post('po_cara_bayar') == 'tempo'):
					$status_bayar 	=	($this->input->post('po_uang_muka')) ? '1' : '0';
				else:
					$status_bayar 	=	'2';
				endif;

				$po_akun_bayar 			=	($this->input->post('po_cara_bayar') == 'tempo') ? '' : $this->input->post('po_akun_bayar');
				$po_hari_jatuh_tempo	=	($this->input->post('po_cara_bayar') == 'tempo') ? $this->input->post('po_hari_jatuh_tempo') : '';
				$po_hutang 				=	($this->input->post('po_cara_bayar') == 'tempo') ? $this->input->post('po_hutang') : '0';

				$data 	=	array(
								'po_cabang_id' => addslashes($this->input->post('po_cabang_id')),
								'po_nomer_po' => $po_nomer_po, //addslashes($this->input->post('po_nomer_po')),
								'po_no_penawaran' => addslashes($this->input->post('po_nomer_penawaran')),
								'po_no_invoice' => addslashes($this->input->post('po_no_invoice')),
								'po_supplier_id' => addslashes($this->input->post('po_supplier_id')),
								'po_tgl_pesan' => $tgl_pesan, //addslashes($this->input->post('po_tgl_pesan')),
								'po_keterangan' => addslashes($this->input->post('po_keterangan')),
								'po_tgl_input' => addslashes($this->input->post('po_tgl_input')),
								'po_user_id' => $this->session->sess_user_id,
								'po_hari_jatuh_tempo' => addslashes($po_hari_jatuh_tempo),
								
								'po_total_setelah_pajak' => addslashes($this->input->post('po_total_setelah_pajak')),
								'po_uang_muka' => addslashes($this->input->post('po_uang_muka')),
								'po_hutang' => addslashes($po_hutang),

								'po_cara_bayar' => addslashes($this->input->post('po_cara_bayar')),
								'po_akun_bayar' => $po_akun_bayar,
								'po_status_pembayaran' => $status_bayar,
								'po_status' => $this->input->post('po_status'),
							);

				if($this->input->post('identifier') == 'add'):

					$this->db->insert($this->db->dbprefix."data_po", $data);

					//$url 	=	'/pembelian/po-supplier-form/?id=' . $this->db->insert_id() .'&po='.$po_nomer_po.'&msg=1';
					$url 	=	'/pembelian/po-supplier';
					$action =	false; 		

				else:

					$this->db->update(
						$this->db->dbprefix."data_po", $data,
						array('po_id' => $this->input->post('po_id'))
					);

					//$this->db->query("
					//	DELETE FROM ".$this->db->dbprefix."data_po_detail 
					//	WHERE po_detail_nomer_po = '".$po_nomer_po."' 
					//");

					$url 	=	false;
					$action =	'stay'; 

				endif;

				//save data_po_detail
				if($this->input->post('po-outlet-identifier') == 1):

					$request = $this->pembelian_lib->po_outlet_single_detail('nomer', $this->input->post('po_nomer_penawaran'));

					foreach($request['result'] as $result):

						$detail =	array(
										'po_detail_cabang_id' => addslashes($this->input->post('po_cabang_id')),
										'po_detail_nomer_po' => addslashes($this->input->post('po_nomer_po')),
										'po_detail_nomer_penawaran' => addslashes($this->input->post('po_nomer_penawaran')),
										'po_detail_product_kode' => $result['penawaran_detail_product_kode'],
										'po_detail_product_nama' => $result['penawaran_detail_product_nama'],
										'po_detail_jumlah_permintaan' => $result['penawaran_detail_jumlah'],
										'po_detail_jumlah_acc' => $result['penawaran_detail_jumlah'],
										'po_detail_jumlah_beli' => $result['penawaran_detail_jumlah'],
										'po_detail_satuan' => '',
										'po_detail_harga' => $result['penawaran_detail_harga'],
										'po_detail_diskon' => '',
										'po_detail_total' => $result['sub_total']
									);

						$this->db->insert($this->db->dbprefix."data_po_detail", $detail);

					endforeach;	

				else:


					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_po_detail SET 
							po_detail_temporary = 'no'
						WHERE po_detail_nomer_po = '".$po_nomer_po."' && po_detail_cabang_id = '".$this->input->post('po_cabang_id')."'
					");

				endif;

				//update status PO outlet
				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_penawaran_outlet SET 
						`penawaran_status` = '1'
					WHERE penawaran_nomer = '".addslashes($this->input->post('po_nomer_penawaran'))."'
				");

				//update total rupiah
				$request = $this->pembelian_lib->po_supplier_detail_single('nomer_po',$po_nomer_po,false);

				$total_rupiah 	=	0;

				foreach($request['result'] as $result):
					$total_rupiah += $result['po_detail_total'];
				endforeach;

				$update 	=	array(
									'po_total_setelah_pajak' => $total_rupiah,
									'po_hutang' => $total_rupiah
								);

				$this->db->update(
					$this->db->dbprefix."data_po", $update, 
					array(
						'po_nomer_po' => $po_nomer_po
					)
				);

				//$this->delete_cache();

				$this->db->trans_complete();


				$status 	=	200;
				$message 	=	($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui';


			endif;//eof checkin total produk

			$callback 	=	array(
								'status' => $status,
								'message' => $message,
								'url' => $url,
								'action' => 'stay'
							);

			print json_encode($callback);	

		endif;
	}

	public function po_supplier_form()
	{

		$view 	=	'/form/view_form';

		$total_detail 	=	5;

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->pembelian_lib->po_supplier_single('nomer_po',@$_GET['po'],$this->session->sess_user_cabang_id);
			$result 	=	$request['result'];

			$data_cabang_id 	=	$result['po_cabang_id'];

			//request detail po
			$request_detail =	$this->pembelian_lib->po_supplier_detail_single('nomer_po', $result['po_nomer_po']);
			$total_detail 	=	($request_detail['total'] > 5) ? $request_detail['total'] : $total_detail;

			$view 		=	($request['total'] && $result['po_status'] > 10) ? '/view_no_access' : $view;

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

     	$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);

     	$request_jasa 		=	$this->setting_lib->setting_produk(false,false,false,false,false,false);
     	$requets_po_outlet 	=	$this->pembelian_lib->po_outlet(false,false,$cabang_id,'0');

     	$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true);
     	$request_supplier 	=	$this->data_lib->data_supplier();

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

		//$request_po_numbe 	=	$this->pembelian_lib->po_outlet_po_number($cabang_id);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_po_supplier',

                    	'outlet' => $request_outlet['result'],
                    	'jasa' => $request_jasa['result'],

                    	'po' => $requets_po_outlet['result'],
                    	'rekening' => $request_rekening['result'],
                    	'supplier' => $request_supplier['result'],

                    	'result' => @$result,

                    	'total_detail' => @$total_detail,
                    	'detail' => @$request_detail['result'],
						//'data_cabang_id' => $data_cabang_id,                

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function po_supplier()
	{
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->pembelian_lib->po_supplier(
							@$_GET['q'],$perpage,$cabang_id,@$_GET['proses'],@$_GET['from'],@$_GET['to']
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);



        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        //'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        //'page_url' => str_replace('_','-',__FUNCTION__),

                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                              

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => $request_outlet['result'],

                        'outlet' => $request_outlet_single,

                        'today' => $this->local_time,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  		
	}	

	public function po_outlet_detail_penawaran()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->pembelian_lib->po_outlet_single_detail('nomer',$this->input->post('nomer-po'));
			$total 		=	'0';

			foreach($request['result'] as $result):

				print '
                    <div class="captionx">
                        <div class="col-md-5 text-left bold">'.$result['penawaran_detail_product_nama'].'</div>
                        <div class="col-md-1 text-center bold">
                            
                            <input type="text" class="form-control number-only text-center penawaran_supplier_detail_jumlah hidden" value="'.$result['penawaran_detail_jumlah'].'" maxlength="2" id="penawaran_supplier_detail_jumlah_'.$result['penawaran_detail_id'].'" data-id="'.$result['penawaran_detail_id'].'">

                            <span>'.$result['penawaran_detail_jumlah'].'</span>

                        </div>

                        <div class="col-md-2 text-right bold">

                            <input type="text" class="form-control text-right penawaran_supplier_detail_harga nomer-auto" value="'.$result['penawaran_detail_harga'].'" id="penawaran_supplier_detail_harga_'.$result['penawaran_detail_id'].'" data-id="'.$result['penawaran_detail_id'].'" data-po="'.$result['penawaran_detail_nomer'].'">

                        </div>
                        <div class="col-md-3 text-right bold"><span class="penawaran_supplier_detail_total" id="penawaran_supplier_detail_total_'.$result['penawaran_detail_id'].'">'.$result['sub_total'].'</span></div>                                                                                    
                        <div class="col-md-1" style="display:nonex">
                        	<i style="display:none" class="fa fa-spinner fa-spin" id="penawaran_supplier_detail_spin_'.$result['penawaran_detail_id'].'"></i>
	                        <a href="javascript:;" class="delete_po_supplier_detail_temp hidden" id="delete_po_supplier_detail_temp_'.$result['penawaran_detail_id'].'" data-id="'.$result['penawaran_detail_id'].'"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </div>
                    <br clear="all">
                    <br clear="all">

				';

				$total 	+=	$result['sub_total'];

			endforeach;

			print '
				<div class="chat-form">
				                                                                                    
				    <div class="col-md-8 text-right">
				        Total
				    </div>
				    <div class="col-md-3 text-center bold" id="total_po_outlet">'.@$total.'</div>
				    <div class="col-md-1 text-right bold" id="total-work-order">&nbsp;</div>

				    <div class="form-group hidden">

				        <div style="border: 0px solid #ff0000; width: 20%; float: left">
				            <input class="form-control  text-center" value="1"  id="jumlah" type="text" placeholder="Jumlah" tabindex="1" maxlength="2" />
				        </div>

				        <div style="border: 0px solid #ff0000; width: 80%; float: left">
				            <div class="input-group">
				            <input class="form-control" id="kode" type="text" placeholder="Barcode / Produk Kode" tabindex="2" />

				            <span class="input-group-addon">
				                <a href="javascript:;"><i class="fa fa-level-up font-blue"></i></a>
				            </span>                  
				            </div>

				        </div>

				    </div>


				</div>

                    <script>
                    	$(".nomer-auto,.penawaran_supplier_detail_total,#total_po_outlet").autoNumeric("init");
               
						$(".penawaran_supplier_detail_harga").change(function(){

							var total 	= 0;

							var id 		= $(this).attr("data-id")
							var harga 	= $(this).val();
							var jumlah 	= $("#penawaran_supplier_detail_jumlah_" + id).val();

							var po 		= $(this).attr("data-po");

							harga 		= harga.replace(",","");
							harga 		= harga.replace(",","");

							total = parseFloat(harga) * parseFloat(jumlah);

							$("#penawaran_supplier_detail_total_" + id).autoNumeric("init");
							$("#penawaran_supplier_detail_total_" + id).autoNumeric("set", total);

							$("#penawaran_supplier_detail_spin_" + id).show();

							var formInput = "penawaran_detail_id=" + id + "&harga=" + harga + "&po=" + po;

							$.post("/pembelian/po_supplier_update_temp_outlet",formInput, function(data){

								$("#penawaran_supplier_detail_spin_" + id).hide();

								var json = $.parseJSON(data);							

								$("#total_po_outlet").autoNumeric("set",json["total"]);

							});

						});

                    </script>				
			';

			/*
			$callback 	=	array(
								'result' => $request['result'],
								//'total' => $request['total'],
							);

			print json_encode($callback);
			*/

		endif;

	}

	public function po_supplier_update_temp_outlet()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			$this->db->query("
				UPDATE ".$this->db->dbprefix."data_penawaran_outlet_detail SET 
					penawaran_detail_harga = '".str_replace(',','',$this->input->post('harga'))."'
				WHERE penawaran_detail_id = '".addslashes($this->input->post('penawaran_detail_id'))."'
			");

			$request 	=	$this->pembelian_lib->po_outlet_single_detail('nomer',$this->input->post('po'));

			$total 	=	'0';
			
			foreach($request['result'] as $result):
				$total 	+= $result['sub_total'];	
			endforeach;

			$callback = array(
							'total' => $total
						);

			print json_encode($callback);

		endif;
	}

	public function po_supplier_temp_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			$this->db->trans_start();

			$cabang 		=	$this->input->post('cabang');
			$po 			=	$this->input->post('nopo');
			$cabang_before	=	$this->input->post('cabang_id_before');
			$jumlah 		=	$this->input->post('jumlah');
			$harga 			=	str_replace(',','', $this->input->post('harga'));
			$kode 			=	$this->input->post('kode');
			$nama 			=	$this->input->post('nama');

			if($cabang_before):

				$this->db->query("
					DELETE FROM ".$this->db->dbprefix."data_po_detail WHERE 
					po_detail_temporary = 'yes' && po_detail_cabang_id = '".$cabang_before."'
				");

			endif;

			//delete duplikat data
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_po_detail WHERE 
				po_detail_cabang_id = '".$cabang."' && po_detail_nomer_po = '".$po."' && po_detail_temporary = 'yes' 
				&& po_detail_product_kode = '".$kode."' 
			");

			$data 	=	array(
							'po_detail_cabang_id' => $cabang,
							'po_detail_nomer_po' => $po,
							'po_detail_product_kode' => $kode,
							'po_detail_product_nama' => $nama,
							'po_detail_jumlah_permintaan' => $jumlah,
							'po_detail_jumlah_acc' => $jumlah,
							'po_detail_jumlah_beli' => $jumlah,
							'po_detail_harga' => $harga,
							'po_detail_total' => $harga * $jumlah,
							'po_detail_temporary' => 'yes'
						);

			$this->db->insert($this->db->dbprefix."data_po_detail", $data);

			$reload = $this->po_supplier_temp_reload($po,1);

			$this->db->trans_complete();

			$callback 	=	array(
								'total' => $reload['total'],
								'html' => $reload['html']
							);

			print json_encode($callback);

		endif;
	}
	
	public function po_supplier_temp_delete()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$this->db->trans_start();

			$po 	=	$this->input->post('po');
			$id 	=	$this->input->post('id');

			$append =	'';

			if($this->input->post('temporary') > 0):
				$append .=	" && po_detail_temporary = 'yes' "; 
			endif;

			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_po_detail WHERE 
				po_detail_id = '".$id."' && po_detail_nomer_po = '".$po."' $append
			");

			$reload = $this->po_supplier_temp_reload($po,$this->input->post('temporary'));

			$this->db->trans_complete();

			$callback 	=	array(
								'total' => $reload['total'],
								'html' => $reload['html']
							);

			print json_encode($callback);			

		endif;
	}

	private function po_supplier_temp_reload($po,$temporary)
	{

		$request 	=	$this->pembelian_lib->po_supplier_detail_single('nomer_po',$po,$temporary);

		$html 	=	'';
		$total 	=	'0';

		foreach($request['result'] as $result):

			$html .= '
                <div class="captionx" id="caption_'.$result['po_detail_id'].'">
                    <div class="col-md-5 text-left bold">'.$result['po_detail_product_nama'].'</div>
                    <div class="col-md-1 text-center bold">
                        
                        <input type="text" class="form-control number-only text-center penawaran_supplier_detail_jumlah hidden" value="'.$result['po_detail_jumlah_permintaan'].'" maxlength="2" id="penawaran_supplier_detail_jumlah_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'">

                        <span>'.$result['po_detail_jumlah_permintaan'].'</span>

                    </div>

                    <div class="col-md-2 text-right bold">

                        <input type="text" class="hidden form-control text-right penawaran_supplier_detail_harga nomer-auto" value="'.$result['po_detail_harga'].'" id="penawaran_supplier_detail_harga_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'" data-po="'.$result['po_detail_nomer_po'].'">

                        <span class="nomer-auto">'.$result['po_detail_harga'].'</span>
                    </div>
                    <div class="col-md-3 text-right bold"><span class="penawaran_supplier_detail_total" id="penawaran_supplier_detail_total_'.$result['po_detail_id'].'">'.$result['po_detail_total'].'</span></div>                                                                                    
                    <div class="col-md-1" style="display:nonex">
                    	<i style="display:none" class="fa fa-spinner fa-spin" id="penawaran_supplier_detail_spin_'.$result['po_detail_id'].'"></i>
                        <a href="javascript:;" class="delete_po_supplier_detail_temp" id="delete_po_supplier_detail_temp_'.$result['po_detail_id'].'" data-id="'.$result['po_detail_id'].'" data-po="'.$result['po_detail_nomer_po'].'"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
                <br clear="all">
                <br clear="all">

			';

			$total 	+=	$result['po_detail_total'];

		endforeach;

		$html .= '
			<div class="chat-form">
			                                                                                    
			    <div class="col-md-8 text-right">
			        Total
			    </div>
			    <div class="col-md-3 text-center bold" id="total_po_outlet">'.@$total.'</div>
			    <div class="col-md-1 text-right bold" id="total-work-order">&nbsp;</div>

			    <div class="form-group hidden">

			        <div style="border: 0px solid #ff0000; width: 20%; float: left">
			            <input class="form-control  text-center" value="1"  id="jumlah" type="text" placeholder="Jumlah" tabindex="1" maxlength="2" />
			        </div>

			        <div style="border: 0px solid #ff0000; width: 80%; float: left">
			            <div class="input-group">
			            <input class="form-control" id="kode" type="text" placeholder="Barcode / Produk Kode" tabindex="2" />

			            <span class="input-group-addon">
			                <a href="javascript:;"><i class="fa fa-level-up font-blue"></i></a>
			            </span>                  
			            </div>

			        </div>

			    </div>


			</div>
		';			

		$html .= '
                <script>
                	$(".nomer-auto,.penawaran_supplier_detail_total,#total_po_outlet").autoNumeric("init");
           

					$(".delete_po_supplier_detail_temp").click(function(){

						var id = $(this).attr("data-id");
						var po = $(this).attr("data-po");

						$("#caption_" + id).slideUp();

						formInput = "id=" + id  + "&po=" + po + "&temporary=1";

						$.post("/pembelian/po_supplier_temp_delete",formInput, function(data){

							var json = $.parseJSON(data);

							$("#total_po_outlet").autoNumeric("set", json["total"]);

						});

					});

                </script>				
		';
		

		$callback 	=	array(
							'total' => $total,
							'html' => $html
						);

		return $callback;
	}

	public function po_outlet_delete_temp()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$this->db->delete(
				$this->db->dbprefix."data_penawaran_outlet_detail", 
				array(
					'penawaran_detail_id' => $this->input->post('id'),
					'penawaran_detail_nomer' => $this->input->post('order'),
					'penawaran_detail_cabang_id' => $this->input->post('cabang')
				)
			);

			$total 	=	$this->db->query("
							SELECT SUM(penawaran_detail_jumlah) as  total FROM ".$this->db->dbprefix."data_penawaran_outlet_detail WHERE
							penawaran_detail_nomer = '".$this->input->post('order')."' && penawaran_detail_cabang_id = '".$this->input->post('cabang')."' 
						")->row_array();

			$callback = array(
							'total' => $total['total']
						);		

			print json_encode($total);

		endif;
	}

	public function po_outlet_save_temp()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$time 	=	time();

			//cek duplikasi
			$this->db->query("
				DELETE FROM ".$this->db->dbprefix."data_penawaran_outlet_detail WHERE
				penawaran_detail_cabang_id = '".$this->input->post('cabang')."' &&
				penawaran_detail_nomer = '".$this->input->post('order')."' && 
				penawaran_detail_product_kode = '".$this->input->post('kode')."' && 
				penawaran_detail_temporary = 'yes'
			");

			$data =	array(
						'penawaran_detail_temporary_id' => $time,
						'penawaran_detail_cabang_id' => $this->input->post('cabang'),
						'penawaran_detail_product_kode' => $this->input->post('kode'),
						'penawaran_detail_product_nama' => $this->input->post('nama'),
						'penawaran_detail_jumlah' => $this->input->post('qty'),
						'penawaran_detail_nomer' => $this->input->post('order'),
						'penawaran_detail_temporary' => 'yes'
					);

			$this->db->insert($this->db->dbprefix."data_penawaran_outlet_detail", $data);

			$query 	=	$this->db->query("
							SELECT * FROM ".$this->db->dbprefix."data_penawaran_outlet_detail WHERE 
							penawaran_detail_nomer = '".$this->input->post('order')."' && penawaran_detail_cabang_id = '".$this->input->post('cabang')."' 
							ORDER BY penawaran_detail_id DESC
						");

			$html 	=	'';

			foreach($query->result_array() as $fetch):

			$html 	.=	'
	            <div class="caption" id="caption_'.$fetch['penawaran_detail_id'].'">
	            
	                <div class="col-md-8 text-left">'.$fetch['penawaran_detail_product_nama'].'</div>
	                <div class="col-md-3 text-center">'.$fetch['penawaran_detail_jumlah'].'</div>      
	                <div class="col-md-1">
	                    <a href="javascript:;" id="delete_temp_po_outlet_'.$fetch['penawaran_detail_id'].'" class="delete_temp_po_outlet" data-id="'.$fetch['penawaran_detail_id'].'" data-cabang="'.$fetch['penawaran_detail_cabang_id'].'" data-no-order="'.$fetch['penawaran_detail_nomer'].'"><i class="fa fa-trash-o"></i></a>
	                </div>                                                                           
	                <br clear="all">
	                <hr>
	            </div>';

	       	endforeach; 

	       	$html .=	'
	            <script>
				$(".delete_temp_po_outlet").click(function(){
					
					var id 		=	$(this).attr("data-id");
					var order 	=	$(this).attr("data-no-order");
					var cabang 	=	$(this).attr("data-cabang");

					formInput 	=	"id=" + id + "&order=" + order + "&cabang=" + cabang;

					//request data stok produk dan service
					$.post("/pembelian/po-outlet-delete-temp",formInput, function(data)
					{					
						
						var json = $.parseJSON(data);
						$("#total_po_outlet").html(json["total"]);
					});		

					$("#caption_" + id).remove();				


				});	    
				</script>        
			';

			$total 	=	$this->db->query("
							SELECT SUM(penawaran_detail_jumlah) as  total FROM ".$this->db->dbprefix."data_penawaran_outlet_detail WHERE
							penawaran_detail_nomer = '".$this->input->post('order')."' && penawaran_detail_cabang_id = '".$this->input->post('cabang')."' 
						")->row_array();

			$callback 	=	array(
								'html' => $html,
								'total' => $total['total']
							);

			print json_encode($callback);

		endif;

	}

	public function po_outlet_save()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			//cek data produk sudah ada atau belum
			$request 	=	$this->pembelian_lib->po_outlet_single_detail(
								'nomer',$this->input->post('penawaran_nomer')
							);


			if($request['total'] < 1):

				$status 	=	201;
				$message 	=	'Pilih data produk';

			else:

				//cek duplikasi nomer po ketika insert data
				$penawaran_nomer_post 	=	$this->input->post('penawaran_nomer');
				$penawaran_nomer 		=	$this->input->post('penawaran_nomer');

				if($this->input->post('identifer') == 'add' && $this->db->count_all($this->db->dbprefix."data_penawaran_outlet WHERE penawaran_nomer = '".addslashes($penawaran_nomer)."' ")):

					//buat nomer penawaran baru
					$request 	=	$this->pembelian_lib->po_outlet_po_number($this->input->post('penawaran_cabang_id'));

					$penawaran_nomer 	=	$request['result']['nomer_po'];

				endif;

				$keterangan =	($this->input->post('penawaran_bulanan') == '1') ? $this->input->post('penawaran_keterangan') .' - Bulanan' : $this->input->post('penawaran_keterangan');

				$penawaran 	=	array(
									'penawaran_cabang_id' => addslashes($this->input->post('penawaran_cabang_id')),
									'penawaran_tanggal_input' => $this->local_time,
									'penawaran_tanggal_pesan' => addslashes($this->input->post('penawaran_tanggal_pesan')),
									'penawaran_nomer' => $penawaran_nomer,
									'penawaran_keterangan' => addslashes($keterangan),
									'penawaran_status' => '0',
									'penawaran_user_id' => $this->session->sess_user_id,
									'penawaran_bulanan' => addslashes($this->input->post('penawaran_bulanan')),
								);

				if($this->input->post('identifier') == 'add'):

					$this->db->insert(
						$this->db->dbprefix."data_penawaran_outlet", $penawaran
					);

					//cek temporary data
					if($penawaran_nomer != $penawaran_nomer_post):

						$this->db->update("
							UPDATE ".$this->db->dbprefix."data_penawaran_outlet_detail SET 
								penawaran_detail_nomer = '".$penawaran_nomer."'
							WHERE penawaran_detail_nomer = '".$penawaran_nomer_post."'
						");

					endif;

					//update temporary status
					/*
					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_penawaran_outlet_detail SET 
							penawaran_detail_temporary = 'no'
						WHERE penawaran_detail_nomer = '".$penawaran_nomer_post."'						
					");
					*/

					//$url 	=	'/pembelian/po-outlet-form/?id=' . $this->db->insert_id() .'&msg=1&po=' . $penawaran_nomer;
					$url 	=	'/pembelian/po-outlet';
					$action =	false; 		

					$message 	=	'Data telah disimpan';


				else:

					unset($penawaran['penawaran_tanggal_input']);

					$this->db->update(
						$this->db->dbprefix."data_penawaran_outlet", $penawaran,
						array('penawaran_id' => $this->input->post('penawaran_id'))
					);

					/*
					//$this->db->query("
					//	DELETE FROM ".$this->db->dbprefix."data_penawaran_outlet_detail 
					//	WHERE penawaran_detail_nomer = '".$penawaran_nomer."' 
					//");

					//update temporary status
					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_penawaran_outlet_detail SET 
							penawaran_detail_temporary = 'no'
						WHERE penawaran_detail_nomer = '".$penawaran_nomer_post."'						
					");
					*/

					$url 	=	false;
					$action =	'stay'; 
					$message 	=	'Data telah diperbarui';

				endif;

				//update temporary status
				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_penawaran_outlet_detail SET 
						penawaran_detail_temporary = 'no'
					WHERE penawaran_detail_nomer = '".$penawaran_nomer_post."'						
				");

				$status 	=	200;

				//save penawaran detail
				//ambil dari temp
				/*
				$product_kode 					=	$this->input->post('penawaran_product_kode');
				$penawaran_detail_jumlah		=	$this->input->post('penawaran_jumlah');
				$penawaran_detail_product_nama	=	$this->input->post('penawaran_detail_product_nama');

				for($i=0;$i <= count($this->input->post('penawaran_product_kode'))-1; $i++):

					if($penawaran_detail_jumlah[$i] > 0):

						$data 	=	array(
										'penawaran_detail_cabang_id' => $this->input->post('penawaran_cabang_id'),
										'penawaran_detail_nomer' => $penawaran_nomer,
										'penawaran_detail_product_kode' => $product_kode[$i],
										'penawaran_detail_product_nama' => $penawaran_detail_product_nama[$i],
										'penawaran_detail_jumlah' => $penawaran_detail_jumlah[$i],
										'penawaran_detail_status' => '0'
									);

						$this->db->insert($this->db->dbprefix."data_penawaran_outlet_detail", $data);

					endif;

				endfor;			
				*/

				//$this->delete_cache();

			endif;//eof duplikasi

			$callback 	=	array(
								'status' => $status,
								'message' => $message, //($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => @$url,
								'action' => 'stay'
							);

			print json_encode($callback);			

		endif;

	}

	public function po_outlet_generate_number()
	{

		if($this->agent->referrer() && $this->session->sess_user_id && $this->input->post('cabang-id')):

			$request 	=	$this->pembelian_lib->po_outlet_po_number($this->input->post('cabang-id'));

			print $request['result']['nomer_po'];

		endif;
	}

	public function po_outlet_form()
	{

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->pembelian_lib->po_outlet_single('nomer',@$_GET['po'],$this->session->sess_user_cabang_id);

			$result 	=	$request['result'];
			$detail 	=	$request['detail'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
     	$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false);
     	$request_jasa 		=	$this->setting_lib->setting_produk(false,false,false,false,false,false);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);


		//$request_po_numbe 	=	$this->pembelian_lib->po_outlet_po_number($cabang_id);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_po_outlet',

                    	'outlet' => $request_outlet['result'],
                    	'jasa' => $request_jasa['result'],

                    	'result' => @$result,
                    	'detail' => @$detail,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function po_outlet()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/'.$this->uri->segment('1').'/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->pembelian_lib->po_outlet(
							@$_GET['q'],$perpage,$cabang_id,@$_GET['proses'],@$_GET['from'],@$_GET['to']
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => $request_outlet['result'],

                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();  		
	}



	public function generate_product_list()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			print '<option value=""></option>';

			if($this->input->post('cabang-id')):

				$request 	=	$this->setting_lib->setting_produk(false,false,false,false,false,false,false,$this->input->post('cabang-id'));

				foreach($request['result'] as $result):

	                if($result['product_kode'] > 0):
	                    print '<option value="'.$result['product_kode'].'" data-harga="'.$this->tools->format_angka2($result['product_hpp'],0).'">'.$result['product_nama']. ' ('. $result['category_nama'] . ' - '. $result['merk_nama'] . ')</option>';
	                endif;

				endforeach;

			endif;

		endif;

	}

	public function request_nomer_pembayaran_hutang()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$cabang 	=	($this->input->post('cabang')) ? $this->input->post('cabang') : '0';
 
			$request = $this->generate_lib->generate_pembayaran_hutang_kode($cabang);

			print $request['result']['nomer_kode'];

		endif;
	}

	public function request_data_invoice()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			//print_r($_POST);

			$request 	=	$this->hutang_lib->data_hutang(false,false,$this->input->post('supplier'),$this->input->post('cabang'),false,'belum');

			print '<option value=""></option>';

			foreach($request['result'] as $result):

				print '<option value="'.$result['hutang_kode'].'" data-saldo="'.$result['hutang_saldo'].'" data-jumlah="'.$result['hutang_jumlah'].'" data-terbayar="'.$result['hutang_terbayar'].'">'.$result['hutang_kode'].' - SPJ: '.$result['hutang_nomer_surat_jalan'].'</option>';

			endforeach;

		endif;
	}

	//global delete for menu under pembelian
	public function delete_single()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$where 	=	'';

			$id 	=	$this->input->post('id');
			$page 	=	$this->input->post('page'); //nama page = nama database
			$field 	=	$this->input->post('field'); //primary field dalam table
		
			if($page == 'data_penawaran_outlet'):

				//pause trigger biar ga error
				$this->db->query("
					SET @trigger_po_outlet_pause = TRUE
				");
				//release trigger lagi ada didalam triggernya

			else:

				$this->db->query("
					SET @trigger_po_outlet_pause = FALSE
				");

			endif;

			if($page == 'pembayaran_hutang'):
				$this->db->query("
					SET @aston_trigger_delete_pembayaran = TRUE
				");
			endif;

			if($page == 'data_pembayaran'):
				$where 	=	" WHERE pembayaran_tipe = 'hutang' ";
			endif;

			$delete 	=	$this->utility->delete_single($id,$page,$field,$where);

			//$this->delete_cache();

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus.', 'total' => $delete);

			print json_encode($callback);

		endif;

	}

	public function delete_multi()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$page 	=	$this->input->post('page'); //nama page = nama database
			$field 	=	$this->input->post('field'); //primary field dalam table		

			$statement	=	false;

			$delete =	$this->utility->delete_multi($this->input->post('id'),$page,$field,$statement);	

			//$this->delete_cache();

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus', 'total' => $delete);

			print json_encode($callback);

		endif;
	}

	public function delete_cache()
	{
		$this->db->cache_delete('pembelian','generate-product-list');
		
		$this->db->cache_delete('pembelian','po-supplier');
		$this->db->cache_delete('pembelian','po-supplier-form');

		$this->db->cache_delete('pembelian','po-outlet');	
		$this->db->cache_delete('pembelian','po-outlet-form');		
	}

}
