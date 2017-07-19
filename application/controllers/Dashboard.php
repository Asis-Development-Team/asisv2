<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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

        $data   =   array(
                        'page_identifier' => 'dashboard',
                    );

        
        $this->template->change_site_title(
            'Dashboard',
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/view_dashboard', $data);
        $this->template->build();   


	}

}
