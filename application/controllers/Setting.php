<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

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

		$this->load->library(array('data_lib','generate_lib'));

	}	

	public function index()
	{

	}	

	public function profile()
	{
		$view 	=	'/form/view_form';

		//request data user dan validasi berdasarkan user cabang id supaya ga cross data
		$request 	=	$this->data_lib->data_pengguna_single(
							'id',$this->session->sess_user_id
						);

		$result 	=	$request['result'];

		$view 		=	($request['total']) ? $view : '/view_no_access';



		//get page privilleges access
		/*
		$view 		=	$this->seting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
		*/


		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],            

                        'view_form' => 'view_' . __FUNCTION__,           

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'result' => @$result,
                    );

        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/form/view_profile', $data);
        $this->template->build();  		
	}

	public function profile_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):
			
			$data 	=	array(
							'user_username' => addslashes($this->input->post('user_username')),
							'user_fullname' => addslashes($this->input->post('user_fullname'))
						);


			if($this->input->post('user_password')):
				$data['user_password'] = password_hash($this->input->post('user_password'),PASSWORD_DEFAULT);
			endif;

			$this->db->update(
				$this->db->dbprefix."data_user", $data,
				array(
					'user_id' => addslashes($this->session->sess_user_id)
				)
			);

			$url 	=	false;
			$action =	'stay'; 

			$callback 	=	array(
								'status' => 200,
								'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => @$url,
								'action' => @$action
							);


			print json_encode($callback);


		endif;
	}

	public function setting_produk_generate_number()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$param 	=	$this->input->post('nomer');

			$request 	=	$this->generate_lib->generate_produk_kode($param);

			$callback['kode_produk']	= $request['result']['kode_produk'];

			print json_encode($callback);

		endif;

	}	

	public function setting_produk_save()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$data 	=	array(

							'product_kode' => addslashes($this->input->post('product_kode')),
							'product_jasa_non_jasa' => addslashes($this->input->post('product_jasa_non_jasa')),
							'product_aktifasi' => 'Aktif', //addslashes($this->input->post('product_aktifasi')),
							'product_nama' => addslashes($this->input->post('product_nama')),
							'product_category_id' => addslashes($this->input->post('product_category_id')),
							'product_merk_id' => addslashes($this->input->post('product_merk')),

							'product_akun_simpan' => addslashes($this->input->post('product_akun_simpan')),
							'product_akun_jual' => addslashes($this->input->post('product_akun_jual')),
							'product_akun_biaya' => addslashes($this->input->post('product_akun_biaya')),
							'product_status_beli' => addslashes($this->input->post('product_status_beli')),
							'product_status_jual' => addslashes($this->input->post('product_status_jual')),
							'product_status_simpan' => addslashes($this->input->post('product_status_simpan')),

							'product_barcode' => addslashes($this->input->post('product_barcode')),
							'product_jasa' => addslashes($this->input->post('product_jasa')),
							'product_rakitan' => addslashes($this->input->post('product_rakitan')),

							'product_hpp' => addslashes($this->input->post('product_hpp')),

						);

			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."setting_produk", $data);

				$url 	=	'/setting/setting-produk-form/?id=' . $this->db->insert_id() .'&msg=1';
				$action =	false; 		

			else:

				$this->db->update(
					$this->db->dbprefix."setting_produk", $data,
					array('product_id' => $this->input->post('id'))
				);

				$url 	=	false;
				$action	=	'stay';

			endif;

			//hapus cache terkait dengan setting produk
			$this->delete_cache_setting_produk();

			$callback 	=	array(
								'status' => 200,
								'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => $url,
								'action' => 'stay'
							);

			print json_encode($callback);


		endif;

	}

	public function setting_produk_form()
	{

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->setting_lib->setting_produk_single('id',@$_GET['id']);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	
     	$request_produk_kategori =	$this->setting_lib->setting_produk_kategori();
     	$request_merk		=	$this->setting_lib->setting_produk_merk();
     	$request_rekening	=	$this->data_lib->data_rekening();

     	$request_jasa 		=	$this->setting_lib->setting_produk(false,false,false,false,false,false);

     	$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_form_setting_produk',

                        'type' => 'Non Jasa',

                    	'produk_kategori' => $request_produk_kategori['result'],
                    	'merk' => $request_merk['result'],
                    	'rekening' => $request_rekening['result'],
                    	'jasa' => $request_jasa['result'],

                    	'result' => @$result,

                    	'outlet' => $request_outlet['result'],

                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function setting_produk()
	{
        
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_setting_produk'
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];
		$perpage 		=	(@$_GET['show']) ? $lock_perpage : '20';

		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		//request menu
		$request 	=	$this->setting_lib->setting_produk(
							@$_GET['q'],$perpage,@$_GET['category'],@$_GET['merk'],false,false,false,$cabang_id);

		$request_kategori	=	$this->setting_lib->setting_produk_kategori(false,false);

		$request_outlet =	$this->setting_lib->setting_data_outlet(false,false);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging'],
                        
                        'kategori' => $request_kategori['result'],

                        'outlets' => $request_outlet['result'],

                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/' . $view, $data);
        $this->template->build();   	

	}


	public function setting_menu_save()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			foreach($this->input->post('menu-level') as $key => $val):
				@$level	.=	$val . ',';
			endforeach;

			$level	=	substr($level,0,-1);	

			$data 	=	array(

							'parent_id' => addslashes($this->input->post('parent')),
							'menu_name' => addslashes($this->input->post('nama')),
							'menu_url' => addslashes($this->input->post('link')),
							'menu_icon' => addslashes($this->input->post('icon')),
							'menu_access_level' => $level,
							'menu_position' => addslashes($this->input->post('posisi')),
							'menu_status' => addslashes($this->input->post('status')),
							'menu_ordering' => addslashes($this->input->post('menu_ordering')),
						
						);

			if($this->input->post('identifier') == 'edit'):

				$this->db->update(
					$this->db->dbprefix."setting_menu", $data, array('menu_id' => $this->input->post('id'))
				);

				$url 	=	false;
				$action =	'stay'; 				

			else:

				$this->db->insert(
					$this->db->dbprefix."setting_menu", $data
				);

				$url 	=	'/setting/setting-menu-form/?id=' . $this->db->insert_id() .'&msg=1';
				$action =	false; 		

			endif;


			$callback 	=	array(
								'status' => 200,
								'message' => ($this->input->post('identifier') == 'add') ? 'Data telah disimpan' : 'Data telah diperbarui',
								'url' => $url,
								'action' => 'stay'
							);

			print json_encode($callback);

		endif;		
	}

	public function setting_menu_form()
	{

		$view 	=	'/form/view_form';

		
		if(@$_GET['id']):

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			$request 	=	$this->setting_lib->setting_main_menu_single('id',@$_GET['id']);

			$result 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;
		

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
	

     	$request_menu	=	$this->setting_lib->setting_main_menu(false,false,'0',false,false);
		$request_user 	=	$this->data_lib->data_user_level();

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                    	'root_menu' => $request_menu['result'],
                    	'ulevel' => $request_user['result'],

                    	'result' => @$result,

                    	'view_form' => 'view_form_setting_menu',


                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function setting_menu()
	{
        
		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_' . __FUNCTION__
  						);

		//request menu
		$request 	=	$this->setting_lib->setting_main_menu(@$_GET['q'],false,'0',false,false,true);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging']
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/' . $view, $data);
        $this->template->build();   	

	}

	//global delete for menu under setting
	public function delete_single()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$id 	=	$this->input->post('id');
			$page 	=	$this->input->post('page'); //nama page = nama database
			$field 	=	$this->input->post('field'); //primary field dalam table

			//hapus cache terkait dengan setting produk
			$this->delete_cache_setting_produk();

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

	public function generate_database()
	{
        $data   =   array(
                        'page_identifier' => 'setting-generate-database',
                    );

        
        $this->template->change_site_title(
            'Dashboard',
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/view_setting_generate_database', $data);
        $this->template->build();   		
	}

	private function delete_cache_setting_produk()
	{
		//hapus cache terkait dengan setting produk
		$this->db->cache_delete('setting','setting-produk');
		$this->db->cache_delete('setting','setting-produk-form');
		$this->db->cache_delete('pembelian','generate-product-list');
		$this->db->cache_delete('pembelian','po-outlet-form');
		$this->db->cache_delete('pembelian','po-supplier-form');		
	}

}
