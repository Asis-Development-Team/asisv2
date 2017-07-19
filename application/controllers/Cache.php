<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}	

	public function index()
	{

		print '<h2>Cache Bank</h2>';
		$this->db->cache_on();

		$query 	=	$this->db->query("
						SELECT * FROM ".$this->db->dbprefix."data_bank
					");

	}

	public function delete_cache()
	{
		$this->db->cache_delete('cache', 'baca');
	}

	public function baca()
	{
		$query 	=	$this->db->query("
						SELECT * FROM ".$this->db->dbprefix."data_bank
					");	

		foreach($query->result_array() as $fetch):

			print $fetch['bank_nama'] . '<br />';

		endforeach;
	}

}
