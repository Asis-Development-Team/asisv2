<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	private $local_time;

	public function __construct()
	{
		parent::__construct();		
		
		$is_login 	=	json_decode($this->authentication->is_login($this->session->sess_user_id,$this->session->session_id),true);

		if($is_login['status'] == false):
			header('location:/');
			exit;
		endif;		

		$this->load->library(array('data_lib','generate_lib','pembelian_lib'));
	}	

	public function index()
	{

		header('location:/data/data-pelanggan');

	}

	public function data_serial_number_update()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$id 		=	$this->input->post('id');
			$barcode 	=	$this->input->post('barcode');
			$sn 		=	$this->input->post('sn');

			if($this->db->count_all($this->db->dbprefix."data_stock_product_detail WHERE stock_detail_id != '".addslashes($id)."' &&  stock_detail_serial_number = '".addslashes($sn)."' ") > 0):

				$status 	=	201;
				$message 	=	'Data Serial Number sudah ada!';

			else:

				$data 	=	array(
								'stock_detail_barcode' => $barcode,
								'stock_detail_serial_number' => $sn
							);

				$this->db->update($this->db->dbprefix."data_stock_product_detail", $data, array('stock_detail_id' => $id));

				$status 	=	200;
				$message 	=	'Data telah diperbarui';

			endif;


			$callback 	=	array(
								'status' => $status,
								'message' => $message,
								'url' => false,
								'action' => 'stay'
							);				


			print json_encode($callback);				

		endif;

	}

	public function data_serial_number()
	{		
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;


		$request 	=	$this->data_lib->data_stok_sn($cabang_id,@$_GET['q'],$perpage,'masuk');

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

	public function data_produk_rakitan_save()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):
		
			$kode_produk_rakitan 	=	$this->input->post('rakitan_detail_kode_produk');
			$kode_komponen 			=	$this->input->post('rakitan_detail_rakitan_kode');
			$rakitan_jumlah 		=	$this->input->post('rakitan_detail_jumlah');
			$cabang_id 				=	$this->input->post('rakitan_detail_cabang_id');
			$nama_produk 			=	$this->input->post('rakitan_detail_kode_produk_name');

			//cek duplikasi
			if($this->db->count_all($this->db->dbprefix."setting_produk_rakitan_detail WHERE rakitan_detail_kode_produk = '".$kode_produk_rakitan."' && rakitan_detail_cabang_id = '".$cabang_id."' ") > 0 && $this->input->post('identifier') == 'add'):

				$status 	=	212;
				$message 	=	'Produk<br /><strong>'.$nama_produk.'</strong><br />sudah ada.';
				$url 		=	'';
				$action 	=	'';

			else:

				$status 	=	200;
				$message 	=	'';

				if($this->input->post('identifier') == 'edit'):
				
					$this->db->query("
						DELETE FROM ".$this->db->dbprefix."setting_produk_rakitan_detail 
						WHERE rakitan_detail_kode_produk = '".$kode_produk_rakitan."'
					");

					$url 	=	false;
					$action =	'stay'; 	
					$message = 	'Data telah diperbarui';

				else:

					$url 	=	'/data/data-produk-rakitan-form/?id=' . $kode_produk_rakitan . '&cabang='.$cabang_id.'&msg=1';
					$action =	false; 	

				endif;

				for($i=0;$i < count($this->input->post('rakitan_detail_rakitan_kode'));$i++):

					if($rakitan_jumlah[$i] > 0):

						$data 	=	array(
										'rakitan_detail_cabang_id' => $cabang_id,
										'rakitan_detail_kode_produk' => $kode_produk_rakitan,
										'rakitan_detail_rakitan_kode' => $kode_komponen[$i],
										'rakitan_detail_jumlah' => $rakitan_jumlah[$i]	
									);

						$this->db->insert($this->db->dbprefix."setting_produk_rakitan_detail", $data);					

					endif;

			
				endfor;



			endif;

			$callback 	=	array(
								'status' => $status,
								'message' => $message, //($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => $url,
								'action' => $action
							);		

			print json_encode($callback);

		endif;

	}

	public function data_produk_rakitan_form()
	{

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

			$request 	=	$this->data_lib->data_produk_rakitan_detail_single('kode_produk',@$_GET['id'],$cabang_id);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
		
		
		$request_rakitan =	$this->setting_lib->setting_produk(
								false,false,false,false,false,false,true
							);

		$request_produk	=	$this->setting_lib->setting_produk(false,false);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false,false);		
		

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_data_produk_rakitan',

                        'rakitan' => $request_rakitan['result'],
                        'produk' => $request_produk['result'],
                        'outlet' => $request_outlet['result'],

                    	'result' => @$result,

                    );
       
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function data_produk_rakitan()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_produk_rakitan'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';


		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_produk_rakitan(
							@$_GET['q'],$perpage,$cabang_id
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false,false);

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


	public function data_harga_jual_update()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$identifier 	=	$this->input->post('identifier');
			$product_hpp	=	addslashes(str_replace(',','',$this->input->post('product_hpp')));
			$product_kode 	=	$this->input->post('product_kode');			

			if($identifier == 'hpp'):
				$field 	=	'product_hpp';
			elseif($identifier == 'hpp-dealer'):
				$field 	=	'product_harga_jual_dealer';
			elseif($identifier == 'hpp-user'):
				$field	=	'product_harga_jual_user';
			endif;

			$this->db->query("
				UPDATE ".$this->db->dbprefix."setting_produk SET
					`".$field."` = '".$product_hpp."'
				WHERE product_kode = '".addslashes($product_kode)."'
			");


			$callback 	=	array(
								'status' => 200,
								'message' => $this->tools->format_angka($product_hpp,0),
							);


			print json_encode($callback);

		endif;
	}

	public function data_harga_jual()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_harga_jual'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_harga_jual(
							@$_GET['q'],$perpage,$cabang_id
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet();

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

	public function data_bank_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$data 	=	array(
							'bank_nama' => addslashes($this->input->post('bank_nama')),
							'bank_no_rekening' => addslashes($this->input->post('bank_no_rekening')),
							'bank_kode_rekening' => addslashes($this->input->post('bank_kode_rekening')),
						);


			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_bank", $data);

				$id 	=	$this->db->insert_id();

				$url 	=	'/data/data-bank-form/?id=' . $id . '&msg=1';
				$action =	false; 					

			else:

				$this->db->update(
					$this->db->dbprefix."data_bank",$data, 
					array(
						'bank_id' => addslashes($this->input->post('bank_id'))
					)
				);

				$id 	=	$this->input->post('id');

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

	public function data_bank_form()
	{

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_bank_single('id',@$_GET['id']);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
		$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,'12');
     	

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_data_bank',

                        'rekening' => $request_rekening['result'],

                    	'result' => @$result,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function data_bank()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_bank'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_bank(
							@$_GET['q'],$perpage
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet();

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


	public function data_stok()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_stok'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_stok(
							$cabang_id,@$_GET['q'],$perpage,@$_GET['urut']
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

	public function data_stok_form()
	{

		$view 	=	'/data/view_data_pelanggan_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_pelanggan_single(
								'id',@$_GET['id'],$this->session->sess_user_cabang_id
							);

			$pelanggan 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        'outlets' => $request_outlet['result'],

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'pelanggan' => @$pelanggan,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}	

	public function data_user_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$status 	=	'';

			//cek duplikasi
			if($this->input->post('identifier') == 'add'):

				$request 	=	$this->data_lib->data_pengguna_single('username',$this->input->post('user_username'));
				
				if($request['total'] > 0):

					$callback 	=	array(
										'status' => 201,
										'message' => 'Username sudah digunakan!'
									);

				else:

					$request 	=	$this->data_lib->data_pengguna_single('fullname',$this->input->post('user_fullname'));
					$result		=	$request['result'];

					if(
						$result['user_fullname'] == $this->input->post('user_fullname') &&  $result['user_cabang_id'] == $this->input->post('user_cabang_id')
					):
						$callback 	=	array(
											'status' => 201,
											'message' => 'Pengguna sudah memiliki akun!'
										);
					else:

						$callback 	=	array(
											'status' => 200,
											'message' => ''
										);


					endif;

				endif;

			else:

				$callback 	=	array(
									'status' => 200,
									'message' => ''
								);

			endif;//eof check if add identifier




			if($callback['status'] == '200'):

					$password 	=	password_hash($this->input->post('user_password'),PASSWORD_DEFAULT);

					$data 	=	array(
									'user_level_id' => addslashes($this->input->post('user_level_id')),
									'user_cabang_id' => addslashes($this->input->post('user_cabang_id')),
									'user_username' => addslashes($this->input->post('user_username')),						
									'user_fullname' => addslashes($this->input->post('user_fullname')),
									'user_employee_code' => addslashes($this->input->post('user_employee_code')),
									'user_password_update' => 'yes'
								);

					
					if($this->input->post('identifier') == 'add'):

						$this->db->insert(
							$this->db->dbprefix."data_user", $data
						);

						$id 	=	$this->db->insert_id();

						$this->db->query("
							UPDATE ".$this->db->dbprefix."data_user SET 
								`user_password` = '".$password."'
							WHERE user_id = '".$id."'
						");


						$url 	=	'/data/data-user-form/?id=' . $id . '&msg=1';
						$action =	false; 		


					else:

						$this->db->update(
							$this->db->dbprefix."data_user", $data,
							array(
								'user_id' => addslashes($this->input->post('user_id'))
							)
						);

						if($this->input->post('user_password')):

							$this->db->query("
								UPDATE ".$this->db->dbprefix."data_user SET 
									`user_password` = '".$password."'
								WHERE user_id = '".addslashes($this->input->post('user_id'))."'
							");

						endif;

						$url 	=	false;
						$action =	'stay'; 	

					endif;


					$callback 	=	array(
										'status' => 200,
										'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
										'url' => $url,
										'action' => $action
									);				


			else:

				$callback 	=	array(
									'status' => $callback['status'],
									'message' => $callback['message'],
								);

			endif;

			print json_encode($callback);


		endif;
	}	

	public function data_user_form()
	{

		$view 	=	'/data/view_data_pengguna_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_pengguna_single(
								'id',@$_GET['id']
							);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//semua cabang
		//$request_outlet 		=	$this->setting_lib->setting_data_outlet();

		//single cabang
		//$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

		$request_user_level 	=	$this->data_lib->data_user_level();	

		$request_karyawan 		=	$this->data_lib->data_karyawan();


		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result,

                        //'outlets' => $request_outlet['result'],
                        //'outlet' => $request_outlet_single,
                        'user_level' => $request_user_level['result'],
                        'karyawan' => $request_karyawan['result'],
                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	
	public function data_user()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_pengguna'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_pengguna(
							@$_GET['cabang'],@$_GET['q'],$perpage
						);


		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		//$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                        'outlets' => $request_outlet['result'],

                        //'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   		
	}

	public function data_rekening_save()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$data =	array(
						'rekening_no_akun' => addslashes($this->input->post('rekening_no_akun')),
						'rekening_kode' => addslashes($this->input->post('rekening_kode')),
						'rekening_nama' => addslashes($this->input->post('rekening_nama')),
						'rekening_kas_bank' => addslashes($this->input->post('rekening_kas_bank')),
						'rekening_aktifasi' => addslashes($this->input->post('rekening_aktifasi')),
						'rekening_cabang_id' => $this->input->post('rekening_cabang_id')
					);


			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_rekening", $data);

				$id 	=	$this->db->insert_id();

				$url 	=	'/data/data-rekening-form/?id=' . $id . '&msg=1';
				$action =	false; 					

			else:

				$this->db->update(
					$this->db->dbprefix."data_rekening",$data, 
					array(
						'rekening_id' => addslashes($this->input->post('rekening_id'))
					)
				);

				$id 	=	$this->input->post('rekening_id');

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


	public function data_rekening_form()
	{

		$view 	=	'/data/view_data_rekening_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_rekening_single(
								'id',@$_GET['id']
							);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//semua cabang
		$request_klasifikasi 	=	$this->setting_lib->setting_klasifikasi_akun(false,false,-1);	

		$request_outlet 		=	$this->setting_lib->setting_data_outlet();

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        'klasifikasi' => $request_klasifikasi['result'],

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result,

                        'outlets' => $request_outlet['result']

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function data_rekening()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_rekening'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_rekening(
							@$_GET['q'],$perpage,false,false,false,false,$cabang_id
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet();

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

	public function data_karyawan_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$data 	=	array(

							'karyawan_nama' => addslashes($this->input->post('karyawan_nama')),
							'karyawan_kode_identitas' => addslashes($this->input->post('karyawan_kode_identitas')),
							'karyawan_alamat' => addslashes($this->input->post('karyawan_alamat')),
							'karyawan_email' => addslashes($this->input->post('karyawan_email')),
							'karyawan_jabatan' => addslashes($this->input->post('karyawan_jabatan')),
							'karyawan_cabang_id' => addslashes($this->input->post('karyawan_cabang_id')),
							'karyawan_hp' => addslashes($this->input->post('karyawan_hp')),

 						);


			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_karyawan", $data);

				$karyawan_id 	=	$this->db->insert_id();

				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_karyawan SET
						`karyawan_kode_identitas` = 'EMLP-".$karyawan_id."'
					WHERE karyawan_id = '".$karyawan_id."'
				");

				$url 	=	'/data/data-karyawan-form/?id=' . $karyawan_id . '&msg=1';
				$action =	false; 				

			else:
					
				$this->db->update($this->db->dbprefix."data_karyawan", $data, array('karyawan_id' => $this->input->post('karyawan_id')));

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

	public function data_karyawan()
	{
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_karyawan'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_karyawan(
							@$_GET['q'],$perpage,$cabang_id
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


	public function data_karyawan_form()
	{

		$view 	=	'/data/view_data_karyawan_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_karyawan_single(
								'id',@$_GET['id'],$this->session->sess_user_cabang_id
							);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        'outlets' => $request_outlet['result'],

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function data_supplier_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$data 	=	array(
							'supplier_code' => addslashes($this->input->post('supplier_code')),
							'supplier_nama' => addslashes($this->input->post('supplier_nama')),
							'supplier_alamat' => addslashes($this->input->post('supplier_alamat')),
							'supplier_nama_kota' => addslashes($this->input->post('supplier_nama_kota')),
							'supplier_kecamatan_nama' => addslashes($this->input->post('supplier_kecamatan_nama')),
							'supplier_telepon' => addslashes($this->input->post('supplier_telepon')),
							'supplier_email' => addslashes($this->input->post('supplier_email')),
							'supplier_cp' => addslashes($this->input->post('supplier_cp')),
							'supplier_cp_telepon' => addslashes($this->input->post('supplier_cp_telepon')),
							'supplier_cp_email' => addslashes($this->input->post('supplier_cp_email')), 
						);

			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."data_supplier", $data);

				$supplier_id 	=	$this->db->insert_id();

				$this->db->query("
					UPDATE ".$this->db->dbprefix."data_supplier SET 
						
						`supplier_code` = 'VNDR-".$supplier_id."'

					WHERE supplier_id = '".$supplier_id."'
				");
					
				$url 	=	'/data/data-supplier-form/?id=' . $supplier_id . '&msg=1';
				$action =	false; 

			else:
					
				$this->db->update($this->db->dbprefix."data_supplier", $data, array('supplier_id' => $this->input->post('supplier_id')));

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

	public function data_supplier()
	{
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_supplier'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		$request 	=	$this->data_lib->data_supplier(
							@$_GET['q'],$perpage
						);


		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging'],

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   		
	}

	public function data_supplier_form()
	{

		$view 	=	'/data/view_data_supplier_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_supplier_single(
								'id',@$_GET['id']
							);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                 
                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}


	public function data_pelanggan_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			//define cabang_id
			//kalau save dari root / admin cabang_id diambil dari dropdown
			//kalo save dari non root cabang_id diambil dari session

			$cabang_id 	=	($this->session->sess_user_level_id < 3) ? $this->input->post('pelanggan_cabang_id') : $this->session->sess_user_cabang_id;

			//cek duplikasi
			if($this->db->count_all($this->db->dbprefix."data_pelanggan WHERE pelanggan_cabang_id = '".$cabang_id."' && pelanggan_type = '".$this->input->post('pelanggan_type')."' && pelanggan_telepon = '".$this->input->post('pelanggan_telepon')."' ") > 0 && $this->input->post('identifier') == 'add'):
				
				$callback 	=	array(
									'status' => 502,
									'message' => 'Data Pelanggan Sudah Ada!',
								);

			else:


				$data =	array(

							'pelanggan_cabang_id' => $cabang_id,
							'pelanggan_code' => addslashes($this->input->post('pelanggan_code')),
							'pelanggan_nomer_membership' => addslashes($this->input->post('pelanggan_nomer_membership')),
							'pelanggan_type' => addslashes($this->input->post('pelanggan_type')),
							'pelanggan_nama' => addslashes($this->input->post('pelanggan_nama')),
							'pelanggan_alamat' => addslashes($this->input->post('pelanggan_alamat')),
							'pelanggan_telepon' => addslashes($this->input->post('pelanggan_telepon')),
							'pelanggan_nama_kota' => addslashes($this->input->post('pelanggan_nama_kota')),
							'pelanggan_kecamatan_nama' => addslashes($this->input->post('pelanggan_kecamatan_nama')),
							'pelanggan_kabupaten_nama' => addslashes($this->input->post('pelanggan_kabupaten_nama')),
							'pelanggan_propinsi_nama' => addslashes($this->input->post('pelanggan_propinsi_nama')),
							'pelanggan_email' => addslashes($this->input->post('pelanggan_email')),

							'pelanggan_instansi_nama' => addslashes($this->input->post('pelanggan_instansi_nama')),
							'pelanggan_instansi_alamat' => addslashes($this->input->post('pelanggan_instansi_alamat')),

							'pelanggan_user_id' => $this->session->sess_user_id,
							//'pelanggan_code' => addslashes($this->input->post('')),
							//'pelanggan_code' => addslashes($this->input->post('')),
							//'pelanggan_code' => addslashes($this->input->post('')),
							//'pelanggan_code' => addslashes($this->input->post('')),

						);

				
				if($this->input->post('identifier') == 'add'):

					$this->db->insert($this->db->dbprefix."data_pelanggan", $data);

					$pelanggan_id 	=	$this->db->insert_id();

					//generate new customer code
					$pelanggan_code 	=	"CUST-" . $pelanggan_id;		

					//update customer code	
					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_pelanggan SET 
							`pelanggan_code` = '".$pelanggan_code."'
						WHERE `pelanggan_id` = '".$pelanggan_id."'
					");

					$url 	=	'/data/data-pelanggan-form/?id=' . $pelanggan_id . '&msg=1';
					$action =	false; 

				else:

					$this->db->update(
						$this->db->dbprefix."data_pelanggan", $data, array('pelanggan_id' => $this->input->post('pelanggan_id'))
					);

					$url 	=	false;
					$action =	'stay';

				endif;

				//delete pelanggan cache
				$this->db->cache_delete('data','data-pelanggan');
				$this->db->cache_delete('penjualan','request-data-customer');

				$callback 	=	array(
									'status' => 200,
									'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
									'url' => $url,
									'action' => $action
								);				

			endif;

			

			print json_encode($callback);

		endif;
	}

	public function data_pelanggan()
	{
		
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_pelanggan'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';

		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 	=	$this->data_lib->data_pelanggan(
							$cabang_id,@$_GET['q'],$perpage,@$_GET['type']
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

	public function data_pelanggan_form()
	{

		$view 	=	'/data/view_data_pelanggan_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_pelanggan_single(
								'id',@$_GET['id'],$this->session->sess_user_cabang_id
							);

			$pelanggan 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        'outlets' => $request_outlet['result'],

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'pelanggan' => @$pelanggan,

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	/*
	public function data_pelanggan_add()
	{

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							'/data/view_data_pelanggan_add'
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet();


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),
                        'page_url_main' => str_replace('-add','',str_replace('-edit','', str_replace('_','-',__FUNCTION__))),
                        
                        'outlets' => $request_outlet['result'],

                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function data_pelanggan_edit()
	{

		//get single data
		//validasi apakah root / super atau bukan pake session

		$request 	=	$this->data_lib->data_pelanggan_single(
							'id',@$_GET['id'],$this->session->sess_user_cabang_id
						);

		$view 		=	($request['total']) ? '/data/view_data_pelanggan_edit' : '/view_no_access';

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-edit','',str_replace('-add','',$this->uri->segment(2))),
							$view
						);

		//semua cabang
		$request_outlet 		=	$this->setting_lib->setting_data_outlet();


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),
                        'page_url_main' => str_replace('-add','',str_replace('-edit','', str_replace('_','-',__FUNCTION__))),
                        
                        'outlets' => $request_outlet['result'],

                        'pelanggan' => $request['result'],

                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}
	*/


	public function data_rekening_laba_rugi_form()
	{
		$view 	=	'/data/view_data_rekening_laba_rugi_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->data_lib->data_rek_laba_rugi_single(
								'id', @$_GET['id']
							);

			$result 	=	$request['result'];


			$request_sub 	=	$this->data_lib->data_rek_laba_rugi_sub_kasifikasi('klasifikasi','setting_lrm_klasifikasi',$request['result']['setting_lrm_sub_klasifikasi']);

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);

		//request
		$request_lr 		=	$this->data_lib->data_rek_laba_rugi();
		$request_rekening	=	$this->data_lib->data_rekening();

		$request_sub_klas 	=	$this->data_lib->data_rek_laba_rugi_sub_kasifikasi('sub','setting_lrm_sub_klasifikasi');

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],
                        
                        
                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result, 
						'result_sub' => @$request_sub['result'], 

                        'laba_rugi' => $request_lr['result'],

                        'rekening' => $request_rekening['result'],

                        'sub_klas' => $request_sub_klas['result'],

                    );



        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}


	public function data_rekening_laba_rugi()
	{
        
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.$this->uri->segment(2),
							'/data/view_data_rekening_laba_rugi'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];

		$perpage 	=	(@$_GET['show']) ? $lock_perpage : '20';


		$request 	=	$this->data_lib->data_rek_laba_rugi(
							@$_GET['q'],$perpage
						);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => @$request['total'],
                        'result' => @$request['result'],
                        'paging' => @$request['paging']
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	//global delete
	public function delete_single()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$id 	=	$this->input->post('id');
			$page 	=	$this->input->post('page'); //nama page = nama database
			$field 	=	$this->input->post('field'); //primary field dalam table

			$statement 	=	false;

			if($page == 'data_pelanggan'):

				if($this->session->sess_user_cabang_id):
					$statement	=	" WHERE pelanggan_cabang_id = '".addslashes($this->session->sess_user_cabang_id)."' ";
				endif;

				$this->db->cache_delete('penjualan','request-data-customer');
				$this->db->cache_delete('data','data-pelanggan');

			elseif($page == 'data_karyawan'):

				if($this->session->sess_user_cabang_id):
					$statement	=	" WHERE karyawan_cabang_id = '".addslashes($this->session->sess_user_cabang_id)."' ";
				endif;

			elseif($page == 'setting_produk_rakitan_detail'):

				if($this->session->sess_user_cabang_id):
					//$statement .=	" WHERE rakitan_detail_cabang_id = '".addslashes($this->session->sess_user_cabang_id)."' ";
				endif;

				//$statement 	.=	" GROUP BY rakitan_detail_kode_produk ";

			endif;


			
			$delete 	=	$this->utility->delete_single($id,$page,$field,$statement);

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

			if($page == 'data_pelanggan'):

				if($this->session->sess_user_cabang_id):
					$statement	=	" WHERE pelanggan_cabang_id = '".addslashes($this->session->sess_user_cabang_id)."' ";
				endif;
				
				$this->db->cache_delete('penjualan','request-data-customer');
				$this->db->cache_delete('data','data-pelanggan');				

			endif;

			$delete =	$this->utility->delete_multi($this->input->post('id'),$page,$field,$statement);	

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus', 'total' => $delete);

			print json_encode($callback);

		endif;
	}	

}