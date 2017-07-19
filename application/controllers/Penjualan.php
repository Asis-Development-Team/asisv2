<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

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

		$this->load->library(array('data_lib','penjualan_lib','generate_lib','stock_lib'));

	}	

	public function index()
	{
		header('location:/penjualan/penjualan');
	}

	public function pembayaran_piutang_save()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$this->db->trans_start();

			if($this->input->post('identifier') == 'add'):
				$request =	$this->penjualan_lib->data_piutang_kode_pembayaran($this->input->post('piutang_cabang_id'));
			endif;

			$pembayaran_kode = ($this->input->post('identifier') == 'add') ? @$request['result']['nomer_po'] : $this->input->post('pembayaran_kode');
			

			$pembayaran 	=	array(
									'pembayaran_cabang_id' => addslashes($this->input->post('piutang_cabang_id')),
									'pembayaran_tipe' => addslashes($this->input->post('pembayaran_tipe')),
									'pembayaran_kode' => $pembayaran_kode, 
									'pembayaran_no_invoice' => addslashes($this->input->post('piutang_invoice')),
									'pembayaran_tanggal_faktur' => addslashes($this->input->post('piutang_tanggal')),
									'pembayaran_rekening' => addslashes($this->input->post('piutang_rekening')),
									'pembayaran_total' => str_replace(',','',$this->input->post('piutang_jumlah_pembayaran')),
									'pembayaran_keterangan' => addslashes($this->input->post('piutang_keterangan')),
									'pembayaran_user_id' => $this->session->sess_user_id
								);		

			$pembayaran_detail 	=	array(
										'pembayaran_detail_cabang_id' => addslashes($this->input->post('piutang_cabang_id')),
										'pembayaran_detail_tipe' => 'piutang',
										'pembayaran_detail_kode_pembayaran' => $pembayaran_kode,
										'pembayaran_detail_no_invoice' => addslashes($this->input->post('piutang_invoice')),
										'pembayaran_detail_saldo' => str_replace(',','',$this->input->post('piutang_sisa_invoice')),
										'pembayaran_detail_bayar' => str_replace(',','',$this->input->post('piutang_jumlah_pembayaran'))
									);

			if($this->input->post('identifier') == 'add'):
				$request 		=	$this->generate_lib->generate_jurnal_kode($this->input->post('piutang_cabang_id'));
				$jurnal_kode 	=	$request['result']['nomer_jurnal'];
			endif;

			$jurnal =	array(
							'jurnal_cabang_id' => $this->input->post('piutang_cabang_id'),
							'jurnal_kode' => @$jurnal_kode,
							'jurnal_tanggal' => addslashes($this->input->post('piutang_tanggal')),
							'jurnal_type' => 'umum',
							'jurnal_nomer_bukti' => $pembayaran_kode,
							'jurnal_keterangan' => addslashes($this->input->post('piutang_keterangan')),
							'jurnal_tanggal_input' => $this->local_time,
							'jurnal_tanggal_posting' => $this->local_time,
							'jurnal_user_id' => $this->session->sess_user_id,
							'jurnal_status_penerimaan_uang' => 'pembayaran kredit'
						);

			$jurnal_detail_debit 	=	array(
											'jurnal_detail_cabang_id' => $this->input->post('piutang_cabang_id'),
											'jurnal_detail_kode_jurnal' => @$jurnal_kode,
											'jurnal_detail_kode_rekening' => addslashes($this->input->post('piutang_rekening')),
											'jurnal_detail_debit_kredit' => 'D',
											'jurnal_detail_nominal_debit' => str_replace(',','',$this->input->post('piutang_jumlah_pembayaran'))
										);

			$request 		=	$this->penjualan_lib->data_penjualan_piutang_usaha_single('id',$this->input->post('piutang_id'));

			$jurnal_detail_kredit 	=	array(
											'jurnal_detail_cabang_id' => $this->input->post('piutang_cabang_id'),
											'jurnal_detail_kode_jurnal' => @$jurnal_kode,
											'jurnal_detail_kode_rekening' => round($request['result']['piutang_akun']),
											'jurnal_detail_debit_kredit' => 'K',
											'jurnal_detail_nominal_kredit' => str_replace(',','',$this->input->post('piutang_jumlah_pembayaran'))
										);


			if($this->input->post('identifier') == 'add'): 

				$total_invoice 	=	str_replace(',','',$this->input->post('piutang_sisa_invoice'));
				$total_bayar 	=	str_replace(',','',$this->input->post('piutang_jumlah_pembayaran'));

				$terbayar 			=	$this->input->post('piutang_terbayar'); 
				$piutang_terbayar	= 	$terbayar + $total_bayar;
				$piutang_saldo 		=	$total_invoice - $total_bayar;
				$status 			=	($total_invoice == $total_bayar) ? '2' : '1';			

				$this->db->insert($this->db->dbprefix."data_pembayaran", $pembayaran);
				$this->db->insert($this->db->dbprefix."data_pembayaran_detail", $pembayaran_detail);

				//simpan ke jurnal
				$this->db->insert($this->db->dbprefix."data_jurnal_umum",$jurnal);
				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail",$jurnal_detail_debit);
				$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail",$jurnal_detail_kredit);				

				//update table data piutang usaha
				$piutang_usaha 	=	array(
										'piutang_terbayar' => $piutang_terbayar,
										'piutang_saldo' => $piutang_saldo,
										'piutang_status' => $status
									);

				$this->db->update($this->db->dbprefix."data_piutang_usaha", $piutang_usaha, array('piutang_id' => $this->input->post('piutang_id')));

				$url 	=	'/penjualan/pembayaran-piutang';
				$action =	false; 		

				$message 	=	'Data telah disimpan';				

			else:


				unset(
					$pembayaran['pembayaran_user_id'],
					$pembayaran['pembayaran_cabang_id'],
					$pembayaran['pembayaran_tipe'],
					$pembayaran['pembayaran_kode'],
					$pembayaran['pembayaran_no_invoice'],

					$pembayaran_detail['pembayaran_detail_cabang_id'],
					$pembayaran_detail['pembayaran_detail_tipe'],
					$pembayaran_detail['pembayaran_detail_kode_pembayaran'],
					$pembayaran_detail['pembayaran_detail_no_invoice'],
					$pembayaran_detail['pembayaran_detail_saldo']
				);

				//update data pembayaran
				$this->db->update($this->db->dbprefix."data_pembayaran", $pembayaran, array('pembayaran_id' => $this->input->post('pembayaran_id')));

				//update data pembayaran detail
				$this->db->update($this->db->dbprefix."data_pembayaran_detail", $pembayaran_detail, array('pembayaran_detail_kode_pembayaran' => $this->input->post('pembayaran_kode')));

				//update piutang usaha
				//cek jika jumlah pembayaran update lebih kecil dari pembayaran sebelum update
				//$kelebihan 	=	( (float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran')) < (float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran_lama')) ) ? (float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran_lama')) - (float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran')) : '0';

				$pembayaran 		=	(float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran'));
				$pembayaran_lama 	=	(float) str_replace(',','',$this->input->post('piutang_jumlah_pembayaran_lama'));

				if($pembayaran < $pembayaran_lama):

					$kelebihan 	=	$pembayaran_lama - $pembayaran;

					
					$this->db->query("

						UPDATE ".$this->db->dbprefix."data_piutang_usaha SET 

							piutang_saldo = piutang_saldo + '".$kelebihan."',
							piutang_terbayar = piutang_terbayar - '".$kelebihan."',

							piutang_status = IF(piutang_saldo = 0, '2', '1')

						WHERE piutang_id = '".addslashes($this->input->post('piutang_id'))."'

					");
					

				elseif($pembayaran > $pembayaran_lama):

					$kelebihan 	=	$pembayaran - $pembayaran_lama;

					$this->db->query("
						
						UPDATE ".$this->db->dbprefix."data_piutang_usaha SET 

							piutang_saldo = piutang_saldo - '".$kelebihan."',
							piutang_terbayar = piutang_terbayar + '".$kelebihan."',

							piutang_status = IF(piutang_saldo = 0, '2', '1')

						WHERE piutang_id = '".addslashes($this->input->post('piutang_id'))."'

					");										

				endif;

				//update jurnal ada di trigger

				$url 		=	'/penjualan/pembayaran-piutang-form/?id='.$this->input->post('pembayaran_id').'&msg=2';//false;
				$action 	=	false;//'stay'; 
				$message 	=	'Data telah diperbarui';

			endif;

			$this->db->trans_complete();


			$callback 	=	array(
								'status' => 200,
								'message' => $message, //($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => @$url,
								'action' => $action
							);

			print json_encode($callback);	

		endif;

	}

	public function pembayaran_piutang_form()
	{ 

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			$request 	=	$this->penjualan_lib->data_penjualan_pembayaran_piutang_single('id',@$_GET['id'],$this->session->sess_user_cabang_id);

			//print '<pre>';
			//print_r($request);
			//exit;

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
		
		$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);
		$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true);

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
                        'today' => date('Y-m-d', strtotime($this->local_time)),

                        'total_detail' => '5',

                    	'result' => @$result,
                    	'detail' => @$detail,
                    	'kustomer' => @$kustomer,
                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function pembayaran_piutang()
	{
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];
		$perpage 		=	(@$_GET['show']) ? $lock_perpage : '20';

		
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 		=	$this->penjualan_lib->data_penjualan_pembayaran_piutang(@$_GET['q'],$perpage,$cabang_id,@$_GET['from'],@$_GET['to'],false);


		$request_outlet =	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging'],

                        'outlets' => $request_outlet['result'],
                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/'.$this->uri->segment('1').'/' . $view, $data);
        $this->template->build();   	

	}

	public function pembayaran_piutang_request_invoice()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->penjualan_lib->data_piutang_invoice_konsumen('kustomer_kode',$this->input->post('konsumen'),9);

			print '<option value=""></option>';

			foreach($request['result'] as $request):

				print '<option value="'.$request['piutang_no_invoice'].'" data-saldo="'.$request['piutang_saldo'].'" data-piutang-terbayar="'.$request['piutang_terbayar'].'" data-piutang-id="'.$request['piutang_id'].'">'.$request['piutang_no_invoice'].'</option>';

			endforeach;

		endif;
	}

	public function pembayaran_piutang_request_konsumen()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$cabang 	=	$this->input->post('cabang');

			$request 	=	$this->penjualan_lib->data_piutang_kustomer($cabang);

			print '<option value=""></option>';

			foreach($request['result'] as $request):

				print '<option value="'.$request['piutang_kustomer_kode'].'">'.$request['pelanggan_nama'].'</option>';

			endforeach;

		endif;

	}

	public function piutang_usaha()
	{
		$this->load->library('hutang_lib');

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];
		$perpage 		=	(@$_GET['show']) ? $lock_perpage : '20';

		
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 		=	$this->penjualan_lib->data_penjualan_piutang_usaha(@$_GET['q'],$perpage,$cabang_id,@$_GET['from'],@$_GET['to'],@$_GET['pembayaran'],true);
		
		$request_outlet =	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging'],

                        'outlets' => $request_outlet['result'],
                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/'.$this->uri->segment('1').'/' . $view, $data);
        $this->template->build();   	

	}


	public function penjualan_save()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			if($this->input->post('invoice_total_setelah_pajak') < 1):

				$callback	=	array('status' => '201', 'message' => 'Pilih data produk.');
				print json_encode($callback);

				exit;

			endif;

			$produk_kode 		=	$this->input->post('invoice_detail_kode_produk');
			$produk_kategori 	=	$this->input->post('invoice_produk_kategori_id');
			$produk_jumlah		=	$this->input->post('invoice_detail_jumlah_produk');

			$serial_number 		=	$this->input->post('invoice_detail_serial_number');

			for($i=0; $i<=count($this->input->post('invoice_detail_kode_produk'))-1;$i++ ):

				$jumlah 	=	$produk_jumlah[$i]; //jumlah qty input
				$explode 	=	explode(',', $serial_number[$i]); //hitung jumlah SN
				$jumlah_sn	=	count($explode);

				if($produk_kode[$i] > 0 && $produk_kategori[$i] != 13): 
					
					if($serial_number[$i] == ''):
						
						$callback	=	array('status' => '201', 'message' => 'Masukkan Serial Number');		
						print json_encode($callback);
						exit;				

					elseif($jumlah != $jumlah_sn):

						$callback	=	array('status' => '201', 'message' => 'Jumlah Serial Number Tidak Sesuai Jumlah Produk');
						print json_encode($callback);
						exit;

					endif;		

				endif;

			endfor;

		
			$this->db->trans_start();

			if($this->input->post('invoice_type') == 'Penjualan'):
				$request 			=	$this->generate_lib->generate_invoice_penjualan($this->input->post('invoice_cabang_id'),'10');
				$invoice_no_order	=	$request['result']['nomer_invoice'];
			endif;

			//no invoice tidak mengurangi stock
			if($this->input->post('invoice_type') == 'Penjualan Non Stock' && $this->input->post('invoice_no_so') == '0'):
				
				$request 			=	$this->generate_lib->generate_invoice_penjualan_tunda($this->input->post('invoice_cabang_id'),'9');
				$invoice_no_so		=	$request['result']['nomer_invoice'];

			elseif($this->input->post('invoice_type') == 'Penjualan' && $this->input->post('invoice_no_so') != ''):
				$invoice_no_so 	=	$this->input->post('invoice_no_so');
			endif;

			$invoice_no_so =	(@$invoice_no_so == '0') ? '' : @$invoice_no_so; 

			$invoice_hari_jatuh_tempo = ($this->input->post('invoice_status_pembayaran') == 'Tunai') ? '0' : $this->input->post('invoice_hari_jatuh_tempo');
			$invoice_kode_akun_lunas  = ($this->input->post('invoice_status_pembayaran') == 'Tunai') ? $this->input->post('invoice_kode_akun_lunas') : '';

			$invoice_status_penjualan_non_stock = (@$invoice_no_so && $this->input->post('invoice_type') == 'Penjualan') ? 'posting' : 'pending';

			//edit mode
			$invoice_no_order 	=	($this->input->post('identifier') == 'edit') ? $this->input->post('invoice_no_order') : $invoice_no_order;
			$invoice_no_so 		=	($this->input->post('identifier') == 'edit') ? $this->input->post('invoice_no_so') : $invoice_no_so;

			$data 	=	array(
						    'invoice_cabang_id' => $this->input->post('invoice_cabang_id'),
						    'invoice_tanggal_input' => $this->local_time, //hilangkan waktu mode update
						    'invoice_type' => $this->input->post('invoice_type'),
						    'invoice_no_order' => @$invoice_no_order,
						    'invoice_no_so' => @$invoice_no_so,
						    'invoice_sales_id' => $this->input->post('invoice_sales_id'),
						    'invoice_customer_kode' => $this->input->post('invoice_customer_code'),
						    'invoice_tanggal_faktur' => $this->input->post('invoice_tanggal_faktur'),
						    'invoice_keterangan' => $this->input->post('invoice_keterangan'),
						    'invoice_tanggal_pengantaran' => '',
						    'invoice_user_id' => $this->session->sess_user_id, //hilangkan waktu mode update
						    'invoice_hari_jatuh_tempo' => $invoice_hari_jatuh_tempo,
						    'invoice_kredit' => '',
						    'invoice_biaya_lain' => $this->input->post('invoice_biaya_lain'),
						    'invoice_total_setelah_pajak' => $this->input->post('invoice_total_setelah_pajak'),
						    'invoice_uang_muka' => str_replace(',','',$this->input->post('invoice_uang_muka')),
						    'invoice_piutang' => $this->input->post('invoice_piutang'),
						    'invoice_kode_akun_lunas' => $invoice_kode_akun_lunas,
						    'invoice_status_pembayaran' => $this->input->post('invoice_status_pembayaran'),
						    						    
						    'invoice_status_penjualan_non_stock' => $invoice_status_penjualan_non_stock,
						);

			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_invoice", $data);

			else:

				unset($data['invoice_tanggal_input']);

				$this->db->update(
					$this->db->dbprefix."data_invoice", $data, 
					array(
						'invoice_id' => $this->input->post('invoice_id')
					)
				);

			endif;			

			$debug['data_invoice'] = $data;
			
			//bikin alur kalo datanya dari penjualan non stock bukan simpan tapi update
			//if($this->input->post('identifier') == 'add' && !empty(@$invoice_no_so)):
				//print '1';
			//else:
				//print '2';
			//endif;

			//ambil kode jurnal
			$request 		=	$this->generate_lib->generate_jurnal_kode($this->input->post('invoice_cabang_id'));
			$jurnal_kode 	=	$request['result']['nomer_jurnal'];

			//edit mode
			$jurnal_kode 	=	($this->input->post('identifier') == 'edit') ? $this->input->post('jurnal_kode') : $jurnal_kode;


			if($this->input->post('identifier') == 'edit'):

				//jika mode edit hapus dulu entri2 terkait
				//baru kemudian create baru

				//hapus data invoice detail
				$this->db->delete($this->db->dbprefix."data_invoice_detail", array('invoice_detail_no_order' => $invoice_no_order));

				//hapus data jurnal umum + jurnal umum detail
				//$this->db->delete($this->db->dbprefix."data_jurnal_umum", array('jurnal_nomer_bukti' => $invoice_no_order));
				$this->db->delete($this->db->dbprefix."data_jurnal_umum_detail", array('jurnal_detail_kode_jurnal' => $jurnal_kode));

				//hapus data_piutang_usaha
				$this->db->delete($this->db->dbprefix."data_piutang_usaha", array('piutang_no_invoice' => $invoice_no_order));

				//hapus data_buku_besar_sales
				$this->db->delete($this->db->dbprefix."data_buku_besar_sales", array('buku_besar_invoice_no_order' => $invoice_no_order));

				//update data_stock dan data_stock_produk


			endif;//eof hapus

			$jurnal 	=	array(
								'jurnal_cabang_id' => $this->input->post('invoice_cabang_id'),
								'jurnal_kode' => $jurnal_kode,
								'jurnal_tanggal' => $this->input->post('invoice_tanggal_faktur'),
								'jurnal_type' => 'umum',
								'jurnal_nomer_bukti' => @$invoice_no_order,
								'jurnal_keterangan' => $this->input->post('invoice_keterangan'),
								'jurnal_tanggal_input' => $this->local_time,
								'jurnal_tanggal_posting' => $this->local_time,
								'jurnal_user_id' => $this->session->sess_user_id,
								'jurnal_status_penerimaan_uang' => '',
								'jurnal_status_penerimaan_relasi_id' => '',
							);

			if($this->input->post('invoice_type') == 'Penjualan'):
				
				if($this->input->post('identifier') == 'add'):
				
					$this->db->insert($this->db->dbprefix."data_jurnal_umum", $jurnal);

				else:

					$this->db->update(
						$this->db->dbprefix."data_jurnal_umum", $jurnal,
						array(
							'jurnal_kode' => $jurnal_kode
						)
					);

				endif;

			endif;

			$debug['jurnal'] = $jurnal;

			$produk_kode 		=	$this->input->post('invoice_detail_kode_produk');
			$produk_nama 		=	$this->input->post('invoice_detail_nama_produk');
			$produk_jumlah		=	$this->input->post('invoice_detail_jumlah_produk');
			$produk_harga 		=	str_replace(',','',$this->input->post('invoice_detail_harga'));
			$produk_total 		=	str_replace(',','',$this->input->post('invoice_detail_total'));
			$produk_kategori 	=	$this->input->post('invoice_produk_kategori_id');
			$produk_hpp 		=	str_replace(',','',$this->input->post('invoice_hpp'));
			$produk_stok 		=	$this->input->post('invoice_stock');

			$produk_sn 			=	$this->input->post('invoice_detail_serial_number');

			$stock_id 			=	$this->input->post('stock_id');
			$stok_id 			=	$this->input->post('stok_id');
			$old_jumlah 		=	$this->input->post('old_jumlah_pembelian');
			$invoice_stock 		=	$this->input->post('invoice_stock');

			$serial_number 		=	$this->input->post('invoice_detail_serial_number');

			for($i=0; $i<=count($this->input->post('invoice_detail_kode_produk'))-1;$i++ ):

				//checking produk submitted
				if($produk_kode[$i] > 0): 

					$nama_produk 	=	explode('(', $produk_nama[$i]);
					$nama_produk 	=	trim($nama_produk['0']);
					$nama_produk 	=	trim(str_replace('-', '', $nama_produk));

					$detail_invoice =	array(
											'invoice_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
											'invoice_detail_no_order' => @$invoice_no_order,
											'invoice_detail_no_so' => @$invoice_no_so,
											'invoice_detail_tanggal_faktur' => $this->input->post('invoice_tanggal_faktur'),
											'invoice_detail_kode_produk' => $produk_kode[$i],
											'invoice_detail_nama_produk' => $nama_produk, //$produk_nama[$i],
											'invoice_detail_jumlah_produk' => $produk_jumlah[$i],
											'invoice_detail_jumlah_diterima' => '',
											'invoice_detail_satuan' => '',
											'invoice_detail_harga' => $produk_harga[$i],
											'invoice_detail_diskon' => '',
											'invoice_detail_pajak' => '',
											'invoice_detail_total' => $produk_total[$i],
											'invoice_detail_produk_kategori_id' => $produk_kategori[$i],
											'invoice_detail_produk_hpp' => $produk_hpp[$i],
											'invoice_detail_produk_stok' => $produk_stok[$i] - $produk_jumlah[$i],

											'invoice_detail_serial_number' => $produk_sn[$i],
										);

					$this->db->insert($this->db->dbprefix."data_invoice_detail", $detail_invoice);

					//update stock_produk_detail
					if($serial_number[$i] != '' && $produk_kategori[$i] != 13):

						$this->penjualan_update_stock_detail(
							$this->input->post('invoice_cabang_id'),
							$produk_kode[$i],
							$produk_jumlah[$i],
							$invoice_no_order,
							$this->local_time,
							$produk_harga[$i],
							$serial_number[$i]
						);

					endif;
					
					$debug['detail_invoice'] = $detail_invoice;

					//simpan kalo type penjualan
					if($this->input->post('invoice_type') == 'Penjualan'):

						//masukkan data ke jurnal detail
						//ambil kode penjualan dan kelompok produk
						$request 	=	$this->setting_lib->setting_produk_kategori_single('id',$produk_kategori[$i]);

						//simpan jurnal penjualan
						$jurnal_penjualan 	=	array(
													'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
													'jurnal_detail_kode_jurnal' => $jurnal_kode,
													'jurnal_detail_kode_rekening' => $request['result']['category_rekening_penjualan'],
													'jurnal_detail_debit_kredit' => 'K',
													'jurnal_detail_nominal_debit' => '0',
													'jurnal_detail_nominal_kredit' => $produk_total[$i]
												);

						$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_penjualan);

						$debug['jurnal_penjualan'] = $jurnal_penjualan;
							

						if($request['result']['category_id'] != '13'):

							$hpp 		=	($produk_hpp[$i] < 1) ? '1' : $produk_hpp[$i];
							$total_hpp	=	$hpp * $produk_jumlah[$i];

							//simpan jurnal biaya dan persediaan
							$jurnal_biaya 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => $request['result']['category_rekening_hpp'],
														'jurnal_detail_debit_kredit' => 'D',
														'jurnal_detail_nominal_debit' => $total_hpp,
														'jurnal_detail_nominal_kredit' => '0',
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_biaya);

							$jurnal_persediaan 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => $request['result']['category_rekening_persediaan'],
														'jurnal_detail_debit_kredit' => 'K',
														'jurnal_detail_nominal_debit' => '0',
														'jurnal_detail_nominal_kredit' => $total_hpp,
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_persediaan);

							$debug['jurnal_biaya']		= $jurnal_biaya;
							$debug['jurnal_persediaan'] = $jurnal_persediaan;

						endif;
		

						//jurnal tambahan biaya pengantaran
						if($this->input->post('invoice_biaya_lain') > '0'):

							$request 			=	$this->generate_lib->generate_kode_akun('1');//akun biaya lain
							
							$jurnal_biaya_lain 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => $request['result']['akun_nomer'],
														'jurnal_detail_debit_kredit' => 'K',
														'jurnal_detail_nominal_debit' => '0',
														'jurnal_detail_nominal_kredit' => $this->input->post('invoice_biaya_lain'),
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_biaya_lain);

							$debug['jurnal_biaya_lain'] = $jurnal_biaya_lain;

						endif;

						//cara pembayaran
						if($this->input->post('invoice_status_pembayaran') == 'Tempo' && str_replace(',','', $this->input->post('invoice_uang_muka')) > '0'):

							$uang_muka 	=	str_replace(',','', $this->input->post('invoice_uang_muka'));
							$request 	=	$this->generate_lib->generate_kode_akun('12');//akun piutang

							$jurnal_piutang 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => $request['result']['akun_nomer'],
														'jurnal_detail_debit_kredit' => 'K',//'D',
														'jurnal_detail_nominal_debit' => '0',//$this->input->post('invoice_piutang'),
														'jurnal_detail_nominal_kredit' => $this->input->post('invoice_piutang'),//'0',
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_piutang);

							//$request 	=	$this->generate_lib->generate_kode_akun('12');//akun uang muka
							$jurnal_piutang 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => '11001', //$request['result']['akun_nomer'],
														'jurnal_detail_debit_kredit' => 'D',
														'jurnal_detail_nominal_debit' => $uang_muka,
														'jurnal_detail_nominal_kredit' => '0',
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_piutang);

							$debug['jurnal_piutang'] = $jurnal_piutang;

						endif;

						if($this->input->post('invoice_status_pembayaran') == 'Tunai'):

							$jurnal_tunai 	=	array(
													'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
													'jurnal_detail_kode_jurnal' => $jurnal_kode,
													'jurnal_detail_kode_rekening' => $invoice_kode_akun_lunas,
													'jurnal_detail_debit_kredit' => 'D',
													'jurnal_detail_nominal_debit' => $this->input->post('invoice_total_setelah_pajak'),
													'jurnal_detail_nominal_kredit' => '0',
												);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_tunai);			

							$debug['jurnal_tunai'] = $jurnal_tunai;			

						endif;

						if($this->input->post('invoice_status_pembayaran') == 'Tempo' && str_replace(',','', $this->input->post('invoice_uang_muka')) == '0'):

							$request 		=	$this->generate_lib->generate_kode_akun('12');//akun piutang
							$piutang_kode 	=	$request['result']['akun_nomer'];

							$jurnal_piutang 	=	array(
														'jurnal_detail_cabang_id' => $this->input->post('invoice_cabang_id'),
														'jurnal_detail_kode_jurnal' => $jurnal_kode,
														'jurnal_detail_kode_rekening' => $request['result']['akun_nomer'],
														'jurnal_detail_debit_kredit' => 'D',
														'jurnal_detail_nominal_debit' => $this->input->post('invoice_total_setelah_pajak'),
														'jurnal_detail_nominal_kredit' => '0',
													);

							$this->db->insert($this->db->dbprefix."data_jurnal_umum_detail", $jurnal_piutang);	

							$debug['jurnal_piutang'] =	$jurnal_piutang;					

						endif;


					endif;//eof save penjualan only

				endif; //eof checking produk submitted
				

				//update stock product diluar jasa jika terjadi penjualan - bukan penjualan non stock
				if($produk_kategori[$i] != '13' && $produk_kode[$i] > '0' && $this->input->post('invoice_type') == 'Penjualan'):

					//hapus data_stok_product 
					//ambil jumlah stock terakhir
					if($this->input->post('identifier') == 'add'):

						$request 	=	$this->stock_lib->stock_product_single('produk_kode',$produk_kode[$i],$this->input->post('invoice_cabang_id'),true);				
						$stock 		=	$request['result'];	

					else:

						/* SALAH BRO
						//tambahkan dulu angka jumlah pembelian asli ke jumlah data_stok
						$this->db->query("
							UPDATE ".$this->db->dbprefix."data_stock SET 
								
								`stok_jumlah` = stok_jumlah + ".$old_jumlah[$i]."

							WHERE stok_id = '".addslashes($stok_id[$i])."'
						");
						*/

						//ambil stok terakhir sebelum ID terpilih
						$request 	=	$this->stock_lib->stock_product_single('produk_kode',$produk_kode[$i],$this->input->post('invoice_cabang_id'),false,$stock_id[$i]);				
						$stock 		=	$request['result'];	

					endif;

					$stok['akhir'] 			=	($stock['stock_stok_akhir']) ? $stock['stock_stok_akhir'] : '0' ;

					$stok['nilai_barang']	=	($stock['stock_nilai_barang']) ? $stock['stock_nilai_barang'] : '0';
					$stok['hpp'] 			=	($stock['stock_hpp']) ? $stock['stock_hpp']  : '0' ;
					//$stok['harga_beli']	=	$result['po_detail_harga']; //($stock['stock_harga_beli']) ? $stock['stock_harga_beli']  : '0'; 

					$stok['akhir_baru'] 		=	$stok['akhir'] - $produk_jumlah[$i];
					$stok['nilai_barang_baru'] 	=	$produk_harga[$i] * $stok['akhir_baru'];
					$stok['hpp_baru'] 			=	@$stok['nilai_barang_baru'] / @$stok['akhir_baru'];
 
					$data_stok_produk 	=	array(
												'stock_cabang_id' => $this->input->post('invoice_cabang_id'),
												'stock_produk_kode' => $produk_kode[$i],
												'stock_nomer_order' => $invoice_no_order,
												'stock_type_transaksi' => 'jual',
												'stock_jenis' => '',
												'stock_harga_beli' => '',
												
												'stock_jumlah' => $produk_jumlah[$i], //$stok['akhir'],

												'stock_stok_awal' => $stok['akhir'],
												'stock_stok_sisa' => $stok['akhir_baru'],

												'stock_stok_akhir' => $stok['akhir_baru'],
												'stock_nilai_barang' => $stok['nilai_barang_baru'],
												'stock_hpp' => $stok['hpp_baru'],
												'stock_tanggal_transaksi' => $this->local_time,
												'stock_user_id' => $this->session->sess_user_id,
											);

					if($this->input->post('identifier') == 'add'):		

						$this->db->insert($this->db->dbprefix."data_stock_product", $data_stok_produk);

						//$stok_id 	=	$this->db->insert_id();	

					else:
						
						$this->db->update(
							$this->db->dbprefix."data_stock_product", $data_stok_produk,
							array(
								'stock_id' => $stock_id[$i]
							)
						);

						$stok_id =	$stock_id[$i];

					endif;//eof identifier

					$debug['data_stok_produk'] = $data_stok_produk;
					
					//hitung selisih update jumlah produk lama dan jumlah produk baru
					$stok_asli 	=	$invoice_stock[$i];

					$stok_akhir =	($this->input->post('identifier') == 'add') ? $stok['akhir_baru'] : $stok_asli;

					if($this->input->post('identifier') == 'edit' && $produk_kategori[$i] != 13):
						
						if($old_jumlah[$i] == $produk_jumlah[$i]):
							
							$selisih 		=	'';
							$stok_akhir 	=	$stok_asli; //$stok['akhir_baru'];

							/*
							$this->db->query("		
								UPDATE ".$this->db->dbprefix."data_stock_product SET

									stock_stok_awal = stock_stok_awal,

								WHERE stock_id > '".$stock_id[$i]."' && stock_cabang_id = '".$this->input->post('invoice_cabang_id')."' 
								&& stock_produk_kode = '".$produk_kode[$i]."'
							");			
							*/				

						elseif($old_jumlah[$i] < $produk_jumlah[$i]):

							$selisih 	=	$produk_jumlah[$i] - $old_jumlah[$i];
							$stok_akhir =	$stok_asli - $selisih;			

							$this->db->query("		
								UPDATE ".$this->db->dbprefix."data_stock_product SET

									stock_stok_awal = stock_stok_awal - '".$selisih."',
									stock_stok_sisa = stock_stok_sisa - '".$selisih."',
									stock_stok_akhir = stock_stok_akhir - '".$selisih."',
									stock_hpp = stock_nilai_barang / stock_stok_akhir

								WHERE stock_id > '".$stock_id[$i]."' && stock_cabang_id = '".$this->input->post('invoice_cabang_id')."' 
								&& stock_produk_kode = '".$produk_kode[$i]."'
							");	


						elseif($old_jumlah[$i] > $produk_jumlah[$i]):

							$selisih 	=	$old_jumlah[$i] - $produk_jumlah[$i];
							$stok_akhir =	$stok_asli + $selisih;

							$this->db->query("		
								UPDATE ".$this->db->dbprefix."data_stock_product SET

									stock_stok_awal = stock_stok_awal + '".$selisih."',
									stock_stok_sisa = stock_stok_sisa + '".$selisih."',
									stock_stok_akhir = stock_stok_akhir + '".$selisih."',
									stock_hpp = stock_nilai_barang / stock_stok_akhir

								WHERE stock_id > '".$stock_id[$i]."' && stock_cabang_id = '".$this->input->post('invoice_cabang_id')."' 
								&& stock_produk_kode = '".$produk_kode[$i]."'
							");					

							//bikin log retur produk karena ada selisih
							$retur 	=	array(
											'retur_tanggal' => $today,
											'retur_invoice_tanggal' => $this->input->post('invoice_tanggal_faktur'),
											'retur_invoice_no_order' => $invoice_no_order,
											'retur_keterangan' => '',
											'retur_status' => 'pengeditan'
										);		

							$this->db->insert($this->db->dbprefix."data_retur", $retur);

							$retur_id 	=	$this->db->insert_id();

							$retur_detail 	=	array(
													'retur_detail_retur_id' => $retur_id,
													'retur_detail_kode_produk' => $produk_kode[$i],
													'retur_detail_jumlah' => $selisih,
													'retur_detail_produk_sn' => $detail['invoice_detail_serial_number']
												);

							$this->db->insert($this->db->dbprefix."data_retur_detail", $retur_detail);

						endif;	

						//penyesuaian ulang seluruh jumlah dan HPP dalam tabel data_stock_product
						//dengan ID setelahnya
						/*
						$this->db->query("		
							UPDATE ".$this->db->dbprefix."data_stock_product SET

								stock_stok_awal = '".$update_awal."'

							WHERE stock_id > '".$stock_id[$i]."' && stock_cabang_id = '".$this->input->post('invoice_cabang_id')."' 
							&& stock_produk_kode = '".$produk_kode[$i]."'
						");
						*/


					endif;					

					//update data_stock
					$data_stock 	=	array(
											'stok_jumlah' => $stok_akhir, //$stok['akhir_baru'],
										);

					$this->db->update(
						$this->db->dbprefix."data_stock", $data_stock,
						array(
							'stok_cabang_id' => $this->input->post('invoice_cabang_id'),
							'stok_produk_kode' => $produk_kode[$i]
						)
					);								

				endif; //eof update stock

			endfor;

			//kalau diambil dari penjualan non stok update statusnya jadi posting
			if(@$invoice_no_so && $this->input->post('invoice_type') == 'Penjualan'):
				
				
				$this->db->query("
					UPDATE ".$this->db->prefix."data_invoice SET 
						
						`invoice_status_penjualan_non_stock` = 'posting',
						`invoice_no_order` = '".$invoice_no_order."',
						`invoice_type` = 'Penjualan'

					WHERE invoice_no_so = '".@$invoice_no_so."'
				");
				

			endif;

			//simpan data piutang jika tempo
			if($this->input->post('invoice_status_pembayaran') == 'Tempo' && $this->input->post('invoice_type') == 'Penjualan'):

				//request kode piutang
				$request =	$this->generate_lib->generate_kode_piutang($this->input->post('invoice_cabang_id'));

				$piutang_usaha 	=	array(
										'piutang_cabang_id' => $this->input->post('invoice_cabang_id'),
										'piutang_kode' => $request['result']['kode_piutang'],
										'piutang_kustomer_kode' => $this->input->post('invoice_customer_code'),
										'piutang_tanggal_faktur' => $this->input->post('invoice_tanggal_faktur'),
										'piutang_no_invoice' => $invoice_no_order,
										'piutang_no_order' => $invoice_no_so,
										'piutang_mata_uang' => '',
										'piutang_jumlah' => $this->input->post('invoice_piutang'),
										'piutang_terbayar' => '0',
										'piutang_saldo' => $this->input->post('invoice_piutang'),
										'piutang_akun' => @$piutang_kode,
										'piutang_status' => '0'
									);				

				$this->db->insert($this->db->dbprefix."data_piutang_usaha", $piutang_usaha);

				$debug['piutang_usaha'] = $piutang_usaha;

			endif;

			//cek balance jurnal antara debit dan kredit
			$query =	$this->db->query("	
							SELECT 

								IF(
									SUM(jurnal_detail_nominal_debit) = SUM(jurnal_detail_nominal_kredit), '0','1'
								) as jurnal_balance

							FROM aston_data_jurnal_umum_detail
							WHERE jurnal_detail_kode_jurnal = '".$jurnal_kode."'

						")->row_array();

			$debug['jurnal_balance'] = $query['jurnal_balance'];

			//buku besar sales
			//if($query['jurnal_balance'] == '1' && ($this->session->sess_user_level_name == 'sales' || $this->session->sess_user_level_name == 'admin_teknisi')):
			if( $this->session->sess_user_level_name == 'sales' || $this->session->sess_user_level_name == 'admin_teknisi' ):
 
				//simpan ke buku besar sales
				$nomer_akun	=	($this->input->post('invoice_status_pembayaran') == 'Tempo') ? '13001' : $invoice_kode_akun_lunas;

				//ini kalau tempo tapi pake uang muka

				//simpan data DP / UM
				//cek didata models/buku_besar.php baris ke 35 

				//$akun_sales =	$this->penjualan_lib->data_penjualan_inovice_single('sales_id',$this->input->post('invoice_sales_id'));
				$akun_rekening_sales 	=	$this->setting_lib->setting_rekening_single($this->input->post('invoice_cabang_id'),'employee',$this->input->post('invoice_sales_id'));

				//buku_besar_sales_kode
				$request_bbs 			=	$this->generate_lib->generate_buku_besar_sales($this->input->post('invoice_cabang_id'));

				//if($this->input->post('invoice_uang_muka') != '' || $this->input->post('invoice_uang_muka') != '0'):
				if(($this->input->post('invoice_uang_muka') != '' || $this->input->post('invoice_uang_muka') != '0') && $this->input->post('invoice_status_pembayaran') == 'Tempo' ):	

					

					$buku_besar_sales 	=	array(
												'buku_besar_cabang_id' => $this->input->post('invoice_cabang_id'),
												'buku_besar_transaksi_kode' => $request_bbs['result']['kode_buku_besar'],
												'buku_besar_invoice_no_order' => $invoice_no_order,
												'buku_besar_akun' => $akun_rekening_sales['result']['rekening_kode'],
												'buku_besar_tanggal' => $this->input->post('invoice_tanggal_faktur'),
												'buku_besar_keterangan' => $this->input->post('invoice_keterangan') . ' - xxx',
												'buku_besar_sales_id' => $this->input->post('invoice_sales_id'),
												'buku_besar_debit_kredit' => 'D',
												'buku_besar_debit_nominal' => str_replace(',', '', $this->input->post('invoice_uang_muka')),
												'buku_besar_kredit_nominal' => '0',
												'buku_besar_tanggal_input' => $this->local_time, 		
												'buku_besar_user_id' => $this->session->sess_user_id
											);

					$this->db->insert($this->db->dbprefix."data_buku_besar_sales", $buku_besar_sales);

				endif;

				if($this->input->post('invoice_status_pembayaran') == 'Tunai'):

					$buku_besar_sales 	=	array(
												'buku_besar_cabang_id' => $this->input->post('invoice_cabang_id'),
												'buku_besar_transaksi_kode' => $request_bbs['result']['kode_buku_besar'],
												'buku_besar_invoice_no_order' => $invoice_no_order,
												'buku_besar_akun' => $nomer_akun,
												'buku_besar_tanggal' => $this->input->post('invoice_tanggal_faktur'),
												'buku_besar_keterangan' => $this->input->post('invoice_keterangan'),
												'buku_besar_sales_id' => $this->input->post('invoice_sales_id'),
												'buku_besar_debit_kredit' => 'D',
												'buku_besar_debit_nominal' => str_replace(',', '', $this->input->post('invoice_piutang')),
												'buku_besar_kredit_nominal' => '0',
												'buku_besar_tanggal_input' => $this->local_time, 		
												'buku_besar_user_id' => $this->session->sess_user_id
											);

					$this->db->insert($this->db->dbprefix."data_buku_besar_sales", $buku_besar_sales);

				endif;

			endif;

			$this->db->trans_complete();

			$url 	=	'/penjualan';

			$callback 	=	array(
								'status' => 200,
								'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => @$url,
								'action' => 'stay',
								'debug' => $debug
							);

			print json_encode($callback);		


		endif;
	}

	public function penjualan_delete_single()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->penjualan_lib->data_penjualan_inovice_single('no_order',$this->input->post('order'));

			$result 	=	$request['result'];
			$detail 	=	$request['detail'];

			$this->db->trans_start();

			$this->db->delete($this->db->dbprefix."data_invoice", array('invoice_no_order' => $result['invoice_no_order']));
			$this->db->delete($this->db->dbprefix."data_invoice_detail", array('invoice_detail_no_order' => $result['invoice_no_order']));

			//hapus data jurnal umum + jurnal umum detail
			$this->db->delete($this->db->dbprefix."data_jurnal_umum", array('jurnal_nomer_bukti' => $result['invoice_no_order']));

			//hapus data_piutang_usaha
			$this->db->delete($this->db->dbprefix."data_piutang_usaha", array('piutang_no_invoice' => $result['invoice_no_order']));

			//hapus data_buku_besar_sales
			$this->db->delete($this->db->dbprefix."data_buku_besar_sales", array('buku_besar_invoice_no_order' => $result['invoice_no_order']));

			//kembalikan jumlah stok
			foreach($detail as $detail):			

				if($detail['invoice_detail_produk_kategori_id'] != 13):

					//split serial number
					$sn 	=	explode(',', $detail['invoice_detail_serial_number']);

					for($i=0;$i<=count($sn)-1;$i++):

						//hapus data_stock_detail yang terjual berdasarkan serial number
						$this->db->query("
							DELETE FROM ".$this->db->dbprefix."data_stock_product_detail WHERE
							stock_detail_serial_number = '".$sn[$i]."' &&
							stock_detail_cabang_id = '".$detail['invoice_detail_cabang_id']."' && 
							stock_detail_type = 'keluar' && 
							stock_detail_invoice_no_order = '".$detail['invoice_detail_no_order']."'
						");

						//update data dari terjual menjadi ada berdasarkan serial number
						$this->db->query("
							UPDATE ".$this->db->dbprefix."data_stock_product_detail SET 

								stock_detail_status = 'ada',
								stock_detail_harga_jual = '',
								stock_detail_tanggal_keluar = ''

							WHERE stock_detail_type = 'masuk' && 
							stock_detail_serial_number = '".$sn[$i]."' && 
							stock_detail_cabang_id = '".$detail['invoice_detail_cabang_id']."'
						");

					endfor;

					//balikin jumlah stok data
					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_stock SET 
							stok_jumlah = stok_jumlah + '".$detail['invoice_detail_jumlah_produk']."'
						WHERE stok_cabang_id = '".$detail['invoice_detail_cabang_id']."' && 
						stok_produk_kode = '".$detail['invoice_detail_kode_produk']."' && 
						stok_id = '".$detail['stok_id']."'
					");
					
					//update selisih data berikutnya setelah data yang terhapus					
					$this->db->query("		
						UPDATE ".$this->db->dbprefix."data_stock_product SET

							stock_stok_awal = stock_stok_awal + '".$detail['invoice_detail_jumlah_produk']."',
							stock_stok_sisa = stock_stok_sisa + '".$detail['invoice_detail_jumlah_produk']."',
							stock_stok_akhir = stock_stok_akhir + '".$detail['invoice_detail_jumlah_produk']."',
							stock_hpp = stock_nilai_barang / stock_stok_akhir

						WHERE stock_id > '".$detail['stock_id']."' && 
						stock_cabang_id = '".$detail['invoice_detail_cabang_id']."' && 
						stock_produk_kode = '".$detail['invoice_detail_kode_produk']."'
					");		

					//bikin log retur produk karena ada selisih
					$retur 	=	array(
									'retur_tanggal' => $this->local_time,
									'retur_invoice_tanggal' => $detail['invoice_detail_tanggal_faktur'],
									'retur_invoice_no_order' => $detail['invoice_detail_no_order'],
									'retur_keterangan' => '',
									'retur_status' => 'penghapusan'
								);		

					$this->db->insert($this->db->dbprefix."data_retur", $retur);

					$retur_id 	=	$this->db->insert_id();

					$retur_detail 	=	array(
											'retur_detail_retur_id' => $retur_id,
											'retur_detail_kode_produk' => $detail['invoice_detail_kode_produk'],
											'retur_detail_jumlah' => $detail['invoice_detail_jumlah_produk'],
											'retur_detail_produk_sn' => $detail['invoice_detail_serial_number'],
										);

					$this->db->insert($this->db->dbprefix."data_retur_detail", $retur_detail);

					$this->db->query("SET @trigger_asis_delete_penjualan_stock = TRUE");

					$this->db->delete($this->db->dbprefix."data_stock_product", array('stock_id' => $detail['stock_id']));

					$this->db->query("SET @trigger_asis_delete_penjualan_stock = FALSE");

				endif;

			endforeach;

			$this->db->delete($this->db->dbprefix."data_stock_product", array('stock_id' => $detail['stock_id']));

			$this->db->trans_complete();

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus.', 'total' => $this->db->count_all($this->db->dbprefix."data_invoice"));

			print json_encode($callback);			

		endif;
	}

	public function penjualan_save_customer()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			foreach($this->input->post() as $key => $val):
				$data[$key] = addslashes(trim($val));
			endforeach;

			$query 	=	"
							SELECT * FROM ".$this->db->dbprefix."data_pelanggan 
							WHERE pelanggan_telepon = '".trim($this->input->post('pelanggan_telepon'))."' 
							&& pelanggan_cabang_id = '".$this->input->post('pelanggan_cabang_id')."'
						";

			//chek duplikasi
			if($this->db->count_all($this->db->dbprefix."data_pelanggan WHERE pelanggan_telepon = '".trim($this->input->post('pelanggan_telepon'))."' && pelanggan_cabang_id = '".$this->input->post('pelanggan_cabang_id')."' ") > '0'):
				
				$status 	=	'201';
				$message 	=	'Data pelanggan sudah ada!';
			
			else:


				//foreach($this->input->post() as $key => $val):
				//	$data[$key] = addslashes($val);
				//endforeach;

				$this->db->insert($this->db->dbprefix."data_pelanggan", $this->input->post());

				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_pelanggan SET
						`pelanggan_code` = 'CUST-".$this->db->insert_id()."'
					WHERE pelanggan_id = '".$this->db->insert_id()."'
				");

				//hapus cache data pelanggan
				$this->db->cache_delete('penjualan','request-data-customer');
				$this->db->cache_delete('data','data-pelanggan');
				

				$status 		=	'200';
				$message 		=	'Data telah disimpan';
				$customer_code  =	'CUST-' . $this->db->insert_id();

			endif;

			$callback 	=	array(
								'status' => $status,
								'message' => $message,
								'customer_code' =>  @$customer_code,
								'cabang_id' => $this->input->post('pelanggan_cabang_id')
							);

			print json_encode($callback);	

		endif;
	}

	public function request_data_penjualan_single()
	{
	
		$no_invoice 	=	($this->input->post('mode') == 'ajax') ? $this->input->post('no_invoice') : @$_GET['order'];

		$request 	=	$this->penjualan_lib->data_penjualan_inovice_single('no_order',$no_invoice);

		$return 	=	array(
							'result' => $request['result'],
							'detail' => $request['detail'],
							//'debug' => $_POST
						);

		if($this->input->post('mode') == 'ajax'):
			unset($return['result']);
			print json_encode($return);
		else:
			return $return;
		endif;
	}

	public function penjualan_form()
	{ 

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			$request = $this->request_data_penjualan_single();

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			//$request 	=	$this->penjualan_lib->data_penjualan_inovice_single('no_order',@$_GET['order']);

			$result 	=	$request['result'];
			$detail 	=	$request['detail'];

			//print '<pre>';
			//print_r($request);
			//exit;

			$request 	=	$this->data_lib->data_pelanggan($result['invoice_cabang_id'],false,false,false);
			$kustomer 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
		
		$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);
		//$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true,6);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_' . __FUNCTION__ . '_01',

                        'outlet' => $request_outlet['result'],
                        //'rekening' => $request_rekening['result'],
                        'today' => date('Y-m-d', strtotime($this->local_time)),

                        'total_detail' => '5',

                    	'result' => @$result,
                    	'detail' => @$detail,
                    	'kustomer' => @$kustomer,
                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function penjualan()
	{

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];
		$perpage 		=	(@$_GET['show']) ? $lock_perpage : '20';

		
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 		=	$this->penjualan_lib->data_penjualan_inovice(@$_GET['q'],$perpage,$cabang_id,@$_GET['from'],@$_GET['to'],@$_GET['pembayaran']);
		
		$request_outlet =	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging'],

                        'outlets' => $request_outlet['result'],
                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/'.$this->uri->segment('1').'/' . $view, $data);
        $this->template->build();   	

	}

	public function request_data_stock_and_service_list()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			//ambil stok produk
			$request 	=	$this->data_lib->data_stok($this->input->post('cabang'),false,false,false,true);

			print '<option value""></option>';

			foreach($request['result'] as $result):
				print '<option value="'.$result['stok_produk_kode'].'" data-stok="'.$result['stok_jumlah'].'" data-harga="'.$result['product_hpp'].'" data-produk-kategori-id="'.$result['produk_kategori_id'].'" data-hpp="'.$result['product_hpp'].'">'.$result['product_nama'].' ['. $result['merk_nama'] .' - '. $result['category_nama'] .'] - ('.$result['stok_jumlah'].' Stok)</option>';
			endforeach;	

			//ambil data service
			$request 	=	$this->setting_lib->setting_produk(false,false,'13',false,false,false,false);

			foreach($request['result'] as $result):
				print '<option value="'.$result['product_kode'].'" data-stok="1000" data-harga="" data-produk-kategori-id="'.$result['produk_kategori_id'].'">'.$result['category_nama'].' '. $result['merk_nama'] .' - '. $result['product_nama'] .'</option>';
			endforeach;
		
		endif;
	}

	public function request_data_penjualan_belum_posting()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			$cabang	 =	($this->input->post('cabang')) ? $this->input->post('cabang') : 'x';

			$request 	=	$this->penjualan_lib->data_penjualan_inovice(false,false,'Penjualan Non Stock',false,'pending',$cabang);
		
			print '<option value="0"></option>';

			foreach($request['result'] as $result):

				print '<option value="'.$result['invoice_no_so'].'" data-id="'.$result['invoice_no_so'].'">'.$result['invoice_no_so']. '</option>';

			endforeach;

		endif;
	}

	public function request_akun_rekening()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true,$this->input->post('cabang'));

			print '<option value=""></option>';

			foreach($request['result'] as $result):
				
				if($result['rekening_cabang_id'] == $this->input->post('cabang')):
					print '<option value="'.$result['rekening_kode'].'">'.$result['rekening_nama'].'</option>';
				endif;

			endforeach;

		endif;
	}

	public function request_data_karyawan()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$cabang	 =	($this->input->post('cabang')) ? $this->input->post('cabang') : 'x';

			//$request =	$this->data_lib->data_karyawan(false,false,$cabang);

			//request data user
			$request 	=	$this->data_lib->data_pengguna($cabang,false,false);

			print '<option value=""></option>';

			$hide = '';

			$result 	=	$request['result'];

			asort($result);

			foreach($result as $result):
				
				if($this->session->sess_user_level_id > 3):
					//$hide 	=	($result['user_id'] != $this->session->sess_user_id) ? 'style="display:none;"' : '';
				endif;
				
				//if($this->session->sess_user_level_id > 3 && $result['user_id'] == $this->session->sess_user_id):
					
					print '<option value="'.$result['user_employee_code'].'" '.@$hide.'>'.$result['user_fullname'].'</option>';
				
				//endif;

			endforeach;

		endif;
		
	}

	public function request_data_customer()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):	
			
			$cabang	 =	($this->input->post('cabang')) ? $this->input->post('cabang') : 'x';

			$request 	=	$this->data_lib->data_pelanggan($cabang,false,false,false);
				
			print '<option value=""></option>';

			foreach($request['result'] as $result):

				print '<option value="'.$result['pelanggan_code'].'" data-id="'.$result['pelanggan_id'].'">'.$result['pelanggan_nama']. ' - '. $result['pelanggan_telepon'] .'</option>';

			endforeach;

		endif;

	}

	public function request_penjualan_tunda()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):	
			
			$request =	$this->penjualan_lib->data_penjualan_inovice_single('no_so',$this->input->post('invoice_no_so')); 

			$callback =	array(
							'result' => $request['result'],
							'detail' => $request['detail'],
							'total' => $request['total']
						);

			print json_encode($callback);

		endif;

	}

	//global delete for menu under setting
	public function delete_single()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$id 	=	$this->input->post('id');
			$page 	=	$this->input->post('page'); //nama page = nama database
			$field 	=	$this->input->post('field'); //primary field dalam table


			//hapus cache terkait dengan setting produk
			//$this->delete_cache_setting_produk();

			if($page == 'pembayaran_piutang'):

				//pembayaran_id
				$page 	=	'data_pembayaran';

				//liat trigger untuk delete dan update related table

				/*
				//ambil pembayaran_kode / invoice untuk update jumlah di table piutang_usaha
				$request 	=	$this->penjualan_lib->data_penjualan_pembayaran_piutang_single('id',$id,false);

				$pembayaran_kode 	= 	$request['result']['pembayaran_kode'];
				$pembayaran_invoice =	$request['result']['pembayaran_no_invoice'];
				$pembayaran_total 	=	$request['result']['pembayaran_total'];

				$this->db->query("

					UPDATE ".$this->db->dbprefix."data_piutang_usaha SET 

						piutang_saldo = piutang_saldo + '".$pembayaran_total."',
						piutang_terbayar = piutang_terbayar - '".$pembayaran_total."',
						piutang_status = IF(piutang_saldo > 0, '1', '0')

					WHERE piutang_no_invoice = '".$pembayaran_invoice."'

				");
				*/


			endif;

			$delete 	=	$this->utility->delete_single($id,$page,$field);

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

			//delete cache
			$this->delete_cache_setting_produk();

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus', 'total' => $delete);

			print json_encode($callback);

		endif;
	}

	private function penjualan_update_stock_detail($cabang,$produk_kode,$jumlah,$invoice_no_order,$today,$produk_harga,$sn=false)
	{

		//bikin status stok keluar di table data_stock_product_detail
		//kalo mode add
		$array 	=	explode(',',$sn);

		$where 	=	" && (";	

		for($i=0;$i<=count($array)-1;$i++):
			$where .=	" stock_detail_serial_number = '".$array[$i]."' || ";
		endfor;		

		$where 	=	substr($where,0,-3) . ')';

		$query 	=	$this->db->query("
						SELECT * 
						FROM ".$this->db->dbprefix."data_stock_product_detail 
						WHERE stock_detail_cabang_id = '".$cabang."' && 
						stock_detail_produk_kode = '".$produk_kode."' && 
						stock_detail_type = 'masuk' && stock_detail_status = 'ada'
						$where
					");

		/*
		$query 	=	$this->db->query("
						SELECT * 
						FROM ".$this->db->dbprefix."data_stock_product_detail 
						WHERE stock_detail_cabang_id = '".$cabang."' && 
						stock_detail_produk_kode = '".$produk_kode."' && 
						stock_detail_type = 'masuk' && stock_detail_status = 'ada'
						ORDER BY stock_detail_id ASC
						LIMIT ".$jumlah." 
					");
		*/

		foreach($query->result_array() as $result):

			//bikin child
			$stock_detail 	=	array(
									'stock_detail_parent_id' => $result['stock_detail_id'],
									'stock_detail_cabang_id' => $result['stock_detail_cabang_id'],
									'stock_detail_type' => 'keluar',
									'stock_detail_nomer_order' => $result['stock_detail_nomer_order'],
									'stock_detail_invoice_no_order' => $invoice_no_order,
									'stock_detail_produk_kode' => $result['stock_detail_produk_kode'],
									'stock_detail_produk_nama' => $result['stock_detail_produk_nama'],
									'stock_detail_barcode' => $result['stock_detail_barcode'],
									'stock_detail_serial_number' => $result['stock_detail_serial_number'],
									'stock_detail_tanggal_masuk' => $result['stock_detail_tanggal_masuk'],
									'stock_detail_tanggal_keluar' => $today,
									'stock_detail_status' => 'terjual',
									'stock_detail_harga_jual' => $produk_harga
								);

			$this->db->insert($this->db->dbprefix."data_stock_product_detail", $stock_detail);

			//update status stock utama
			$stock_terjual 	=	array(
									'stock_detail_tanggal_keluar' => $today,
									'stock_detail_status' => 'terjual'
								);

			$this->db->update(

				$this->db->dbprefix."data_stock_product_detail", $stock_terjual, 
				array(
					'stock_detail_id' => $result['stock_detail_id']
				)

			);

		endforeach;

	}

}
