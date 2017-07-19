<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;

	private $fname;

	public function __construct()
	{
		parent::__construct();		
		
		$this->file_size	=	33302400;		
	    $this->image_ext	=	array('jpg','jpeg','gif','png');	
		$this->file_ext		=	array('doc','pdf','rar','zip','xls','xlsx');		
		
		//require_once "assets/classes/class.upload.php";
		//require_once 'assets/classes/simple_html_dom.php';

		$this->load->helper('date');
		$this->load->library(array('data_lib'));

		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

		$this->fname 		=	str_replace('-','_',trim($this->uri->segment(2)));



	}	

	public function version()
	{
		print __FUNCTION__ . ' - ' . CI_VERSION ; 

	}

	public function index()
	{
		$is_login 	=	json_decode($this->authentication->is_login($this->session->sess_user_id,$this->session->session_id),true);

		if($is_login['status'] == true):
			header('location:/dashboard');
		endif;

		$data	=	array(
						'page_identifier' => 'login'
					);
					
		//$this->load->view('templates/'.$this->template->data['template_admin'].'/view_login_header',$data);
		$this->load->view('templates/'.$this->template->data['template_admin'].'/view_login');
		//$this->load->view('templates/'.$this->template->data['template_admin'].'/view_login_footer');

	}

	public function dashboard()
	{
		$data	=	array(					
						'page_identifier' => 'dashboard',
						'page_title' => 'Admin Panel / Dashboard'					
 					);
		
		
		$this->template->change_site_title(
			'Admin Dashboard',
			'desc', //desc
			'keyword'//key
		);
				
		
		$this->template->set_content('templates/'.$this->template->data['template_admin'].'/view_dashboard', $data);
		$this->template->build();	
	}



	public function setting_menu()
	{
        
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
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/view_setting_menu', $data);
        $this->template->build();   	

	}

	public function setting_menu_add()
	{
     	

     	$request_menu	=	$this->setting_lib->setting_main_menu(false,false,'0',false,false);
		$request_user 	=	$this->data_lib->data_user_level();

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),
                        'page_url_main' => str_replace('-add','',str_replace('-edit','', str_replace('_','-',__FUNCTION__))),
                    	
                    	'root_menu' => $request_menu['result'],
                    	'ulevel' => $request_user['result'],

                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucwords(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/view_setting_menu_add', $data);
        $this->template->build();   	

	}

	public function setting_menu_edit()
	{
        
		$request_data 	=	$this->setting_lib->setting_main_menu_single('id',@$_GET['id']);
		$request_menu	=	$this->setting_lib->setting_main_menu(false,false,'0',false,false);
		$request_user 	=	$this->data_lib->data_user_level();

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),
                        'page_url_main' => str_replace('-add','',str_replace('-edit','', str_replace('_','-',__FUNCTION__))),
                    	
                    	'total' => $request_data['total'],
                    	'result' => $request_data['result'],

                    	'root_menu' => $request_menu['result'],
                    	'ulevel' => $request_user['result'],

                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/view_setting_menu_edit', $data);
        $this->template->build();   	

	}

	public function setting_generate_database()
	{
        
        if($this->session->sess_user_level_id > 2):
        	header('location:/admin/dashboard');
        	exit;
        endif;

        $this->load->library('utility');

       	//$dblist 	=	$this->utility->list_database();

        $data   =   array(
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        //'databases' => $dblist,
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/setting/view_setting_generate_database', $data);
        $this->template->build();   		
	}


	public function logout()
	{
		if($this->session->sess_user_id):
			
			$this->db->delete($this->db->dbprefix."user_session", array('session_value' => $this->session->session_id, 'user_id' => $this->session->sess_user_id));
			
			$this->session->sess_destroy();
			
			header('location:/');
			
		endif;
	}				

}
