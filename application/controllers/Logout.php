<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

	private $local_time;

	public function __construct()
	{
		parent::__construct();		

		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

	}	

	public function index()
	{

		if($this->session->sess_user_id):
			
			$this->db->delete($this->db->dbprefix."data_user_session", array('session_value' => $this->session->session_id, 'user_id' => $this->session->sess_user_id));
			
			$this->session->sess_destroy();
			
			header('location:/');
			
		endif;

	}

	public function logout_idle()
	{
		$this->db->delete($this->db->dbprefix."data_user_session", array('session_value' => $this->session->session_id, 'user_id' => $this->session->sess_user_id));		
		$this->session->sess_destroy();		
	}

}
