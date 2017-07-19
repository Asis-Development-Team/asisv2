<?php
//checking session

if($this->session->user_id): 
	$this->authentication->sweep_session(
		$this->session->sess_user_id, $this->session->session_id, '/'
	);
endif;

//load template
$this->load->view('templates/'.$this->template->data['template_admin'].'/includes/view_header_v2');

print $content;

$this->load->view('templates/'.$this->template->data['template_admin'].'/includes/view_footer_v2');
//eof load template

?>