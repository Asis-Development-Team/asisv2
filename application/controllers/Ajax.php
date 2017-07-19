<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;



	public function __construct()
	{
		parent::__construct();		
		
		$this->file_size	=	33302400;		
	    $this->image_ext	=	array('jpg','jpeg','gif','png');	
		$this->file_ext		=	array('doc','pdf','rar','zip','xls','xlsx');		
		
		//require_once "assets/classes/class.upload.php";
		//require_once 'assets/classes/simple_html_dom.php';

		$this->load->library(array('setup','generate_lib'));


		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

		$is_login 	=	json_decode($this->authentication->is_login($this->session->sess_user_id,$this->session->session_id),true);



		//print '<pre>';
		//print_r($is_login);
		//exit;

		if($is_login['status'] == false):
			//header('location:/');
			//exit;
		endif;		

		$this->load->library(array('data_lib'));

	}	

	public function index()
	{

	}

	public function save_data_rekening_laba_rugi()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$data 	=	array(
							'setting_lrm_kode_rekening' => $this->input->post('setting_lrm_kode_rekening'),
							'setting_lrm_klasifikasi' => $this->input->post('setting_lrm_klasifikasi'),
							'setting_lrm_sub_klasifikasi' => $this->input->post('setting_lrm_sub_klasifikasi')
						);

			if($this->input->post('identifier') == 'add'):

				$this->db->insert($this->db->dbprefix."setting_laba_rugi_marketing", $data);

				$id 	=	$this->db->insert_id();

				$url 	=	'/data/data-rekening-laba-rugi-form/?id=' . $id . '&msg=1';
				$action =	false; 					

			else:

				$this->db->update(
					$this->db->dbprefix."setting_laba_rugi_marketing",$data, 
					array(
						'setting_lrm_id' => addslashes($this->input->post('setting_lrm_id'))
					)
				);

				$id 	=	$this->input->post('setting_lrm_id');

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

	public function get_sub_klasifikasi()
	{	
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$parent 	=	$this->input->post('parent');

			$request 	=	$this->data_lib->data_rek_laba_rugi_sub_kasifikasi('klasifikasi','setting_lrm_klasifikasi',$parent);

			print '<option value=""></option>';

			foreach($request['result'] as $result):

				print '<option value="'.$result['nama_field'].'">'.$result['nama_field'].'</option>';

			endforeach;

		endif;

	}





	public function save_data_karyawan()
	{

	}
	
	public function save_data_supplier()
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

				$this->db->insert($this->db->dbprefix."setting_supplier", $data);

				$supplier_id 	=	$this->db->insert_id();

				$this->db->query("
					UPDATE ".$this->db->dbprefix."setting_supplier SET 
						
						`supplier_code` = 'VNDR-".$supplier_id."'

					WHERE supplier_id = '".$supplier_id."'
				");
					
				$url 	=	'/data/data-supplier-form/?id=' . $supplier_id . '&msg=1';
				$action =	false; 

			else:
					
				$this->db->update($this->db->dbprefix."setting_supplier", $data, array('supplier_id' => $this->input->post('supplier_id')));

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

	public function save_data_pelanggan()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			//define cabang_id
			//kalau save dari root / admin cabang_id diambil dari dropdown
			//kalo save dari non root cabang_id diambil dari session

			$cabang_id 	=	($this->session->sess_user_level_id < 3) ? $this->input->post('customer_cabang_id') : $this->session->sess_user_cabang_id;

			//cek duplikasi
			if($this->db->count_all($this->db->dbprefix."data_customer WHERE customer_cabang_id = '".$cabang_id."' && customer_type = '".$this->input->post('customer_type')."' && customer_telepon = '".$this->input->post('customer_telepon')."' ") > 0):
				
				$callback 	=	array(
									'status' => 502,
									'message' => 'Data Pelanggan Sudah Ada!',
								);

			else:


				$data =	array(

							'customer_cabang_id' => $cabang_id,
							'customer_code' => addslashes($this->input->post('customer_code')),
							'customer_nomer_membership' => addslashes($this->input->post('customer_nomer_membership')),
							'customer_type' => addslashes($this->input->post('customer_type')),
							'customer_nama' => addslashes($this->input->post('customer_nama')),
							'customer_alamat' => addslashes($this->input->post('customer_alamat')),
							'customer_telepon' => addslashes($this->input->post('customer_telepon')),
							'customer_nama_kota' => addslashes($this->input->post('customer_nama_kota')),
							'customer_kecamatan_nama' => addslashes($this->input->post('customer_kecamatan_nama')),
							'customer_kabupaten_nama' => addslashes($this->input->post('customer_kabupaten_nama')),
							'customer_propinsi_nama' => addslashes($this->input->post('customer_propinsi_nama')),
							'customer_email' => addslashes($this->input->post('customer_email')),
							'customer_instansi_nama' => addslashes($this->input->post('customer_instansi_nama')),
							'customer_instansi_alamat' => addslashes($this->input->post('customer_instansi_alamat')),

							'customer_user_id' => $this->session->sess_user_id,
							//'customer_code' => addslashes($this->input->post('')),
							//'customer_code' => addslashes($this->input->post('')),
							//'customer_code' => addslashes($this->input->post('')),
							//'customer_code' => addslashes($this->input->post('')),

						);

				
				if($this->input->post('identifier') == 'add'):

					$this->db->insert($this->db->dbprefix."data_customer", $data);

					$customer_id 	=	$this->db->insert_id();

					//generate new customer code
					$customer_code 	=	"CUST-" . $customer_id;		

					//update customer code	
					$this->db->query("
						UPDATE ".$this->db->dbprefix."data_customer SET 
							`customer_code` = '".$customer_code."'
						WHERE `customer_id` = '".$customer_id."'
					");

					$url 	=	'/data/data-pelanggan-form/?id=' . $customer_id . '&msg=1';
					$action =	false; 

				else:

					$this->db->update(
						$this->db->dbprefix."data_customer", $data, array('customer_id' => $this->input->post('customer_id'))
					);

					$url 	=	false;
					$action =	'stay';

				endif;



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

	public function save_setting_menu()
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
						
						);

			if($this->input->post('identifier') == 'edit'):

				$this->db->update(
					$this->db->dbprefix."setting_menu", $data, array('menu_id' => $this->input->post('id'))
				);

				$url 	=	false;

			else:

				$this->db->insert(
					$this->db->dbprefix."setting_menu", $data
				);

				$url 	=	'/admin/setting-menu-edit/?id=' . $this->db->insert_id() .'&msg=1';

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

	public function delete_multi()
	{
		
		$identifier	=	$this->input->post('identifier');
		$id			=	substr($this->input->post('id'), 0, -1);
		
		$id			=	explode(',', $id);
		
		$new_id	=	'';
		
		for($i=0;$i<=count($id)-1;$i++):
			
			$new_id	.= "'".$id[$i]."',";
			
		endfor;
		
		$id	=	substr($new_id, 0, -1);


		if($this->session->sess_user_level_id < 3):

			switch($identifier):

				case 'data_karyawan':

					$this->db->query("
						DELETE FROM ".$this->db->dbprefix."data_karyawan WHERE karyawan_id IN (".$id.")
					");					
					
					$total	=	$this->db->count_all($this->db->dbprefix."data_karyawan");	

				break;

				case 'data_supplier':

					$this->db->query("
						DELETE FROM ".$this->db->dbprefix."setting_supplier WHERE supplier_id IN (".$id.")
					");					
					
					$total	=	$this->db->count_all($this->db->dbprefix."setting_supplier");					

				break;

			endswitch;

			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus', 'total' => $total);


		else:
			$callback	=	array('status' => '401', 'message' => 'Unauthorized Request');
		endif;
		
		print json_encode($callback);

	}

	public function delete_single()
	{
		
		if($this->session->sess_user_id):
			
			$identifier	=	$this->input->post('identifier');		
			
			switch ($identifier): 

				case 'data_rekening_laba_rugi':

					$this->db->delete(
						$this->db->dbprefix.'setting_laba_rugi_marketing', array('setting_lrm_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."setting_laba_rugi_marketing");	

				break;


				case 'data_pengguna':

					$this->db->delete(
						$this->db->dbprefix.'user', array('user_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."user WHERE user_id > '1'");	

				break;

				case 'data_rekening':

					$this->db->delete(
						$this->db->dbprefix.'setting_rekening', array('rekening_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."setting_rekening");	

				break;

				case 'data_karyawan':

					$this->db->delete(
						$this->db->dbprefix.'data_karyawan', array('karyawan_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."data_karyawan");

				break;

				case 'data_supplier':

					$this->db->delete(
						$this->db->dbprefix.'setting_supplier', array('supplier_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."setting_supplier");

				break;

				case 'data_pelanggan':
					
					$this->db->delete(
						$this->db->dbprefix."data_customer", array('customer_id' => $this->input->post('id'))
					);

					$where 	=	($this->session->sess_user_cabang_id) ? " WHERE customer_cabang_id = '".$this->session->sess_user_cabang_id."' " : "";

					$total =	$this->db->count_all($this->db->dbprefix."data_customer $where");

				break;
				
				case 'setting_menu':
					
					$this->db->delete(
						$this->db->dbprefix.'setting_menu', array('menu_id' => $this->input->post('id'))
					);

					$total 	=	$this->db->count_all($this->db->dbprefix."setting_menu");

				break;

			endswitch;


			$callback	=	array('status' => '200', 'message' => 'Data telah dihapus.', 'total' => $total);
		
		else:
			$callback	=	array('status' => '401', 'message' => 'Unauthorized Request');
		endif;



		print json_encode($callback);
	}


	//handle all dump request
	public function dump_content()
	{
	
		if($this->agent->referrer() && $this->session->sess_user_id):

			$class 	=	$this->input->post('class');

			$request 	=	$this->setup->$class($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				


	}

	public function dump_supplier()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}

	public function dump_customer()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}

	public function dump_laba_rugi_marketing()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}


	public function dump_rekening()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}

	public function dump_klasifikasi_akun()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}

	public function dump_pesan()
	{
		
		if($this->agent->referrer() && $this->session->sess_user_id):

			$function 	=	__FUNCTION__;

			$request 	=	$this->setup->$function($this->input->post('dbname'));

			$callback 	=	array(
								'status' => $request['status'],
								'message' => $request['message'],
								'button-text' => $request['button-text']
							);

			print json_encode($callback);

		endif;				

	}

	public function generate_menu()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->setup->generate_menu();

			$callback 	=	array(
								'status' => $request['status'],
								'message' => 'Done'
							);

			print json_encode($callback);

		endif;		
	}

	public function generate_user()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->setup->generate_user();

			$callback 	=	array(
								'status' => $request['status'],
								'message' => 'Done'
							);

			print json_encode($callback);

		endif;
	}

	public function generate_branch()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->setup->generate_branch();

			$callback 	=	array(
								'status' => $request['status'],
								'message' => 'Done'
							);

			print json_encode($callback);

		endif;
	}

	public function generate_user_level()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->setup->generate_user_level();

			$callback 	=	array(
								'status' => $request['status'],
								'message' => 'Done'
							);

			print json_encode($callback);

		endif;
	}


	public function login()
	{
		
		if($this->agent->referrer()):

			$do_login	=	$this->authentication->do_login(
								$this->input->post('username'),
								$this->input->post('password')
							);

			$callback	=	$do_login;

		else:
			$callback	=	array('status' => '409', 'message' => 'Invalid Request');
		endif;

		print json_encode($callback);
	}


	public function logout()
	{
		if($this->session->user_id):
			
			$this->db->delete($this->db->dbprefix."user_session", array('session_value' => $this->session->session_id, 'user_id' => $this->session->user_id));
			
			$this->session->sess_destroy();
			
			header('location:/');
			
		endif;
	}				

}
