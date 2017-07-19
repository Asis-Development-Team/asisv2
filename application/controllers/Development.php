<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Development extends CI_Controller {

	private $local_time;

	public function __construct()
	{
		parent::__construct();		
		
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');		

        $remote     =   array(

                            'hostname' => 'localhost',
                            'username' => 'root',
                            'password' => 'root',
                            'database' => 'astonpri_asisall', //$_SESSION['c']['database']
                            'dbdriver' => 'mysql',
                            'dbprefix' => '',
                            'pconnect' => '1',
                            'db_debug' => '1',
                            'cache_on' => '',
                            'cachedir' => '',
                            'char_set' => 'utf8',
                            'dbcollat' => 'utf8_general_ci',
                            'swap_pre' => '',
                            'autoinit' => '1',
                            'stricton' => '',

                        );

		$config['hostname'] = 'localhost';
		$config['username'] = 'root';
		$config['password'] = 'root';
		$config['database'] = 'astonpri_asisall';
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';


        $this->db_old =   $this->load->database($config,TRUE,TRUE);    


	}	

	public function index()
	{

		print __METHOD__;

	}

	public function test_query()
	{
		
		$kode 	=	'4410';
		$kode 	=	$kode . date('m') . date('y');

		$last_num 	=	'441010160010';
		$last_num 	=	substr($last_num, 8, 12) + 1;

		print $kode . '<br />' . sprintf("%04s", $last_num);

		//$query 	=	$this->db->query("

		//			");
	}


	public function check_menu()
	{
		$query 	=	$this->db->query("
						SELECT * FROM ".$this->db->dbprefix."setting_menu WHERE parent_id = '0'
					");

		print '<ul>';
		foreach($query->result_array() as $result):

			print '<li>'.$result['menu_name'].'</li>';

			print '<ul>';

			$query2 =	$this->db->query("
							SELECT * FROM ".$this->db->dbprefix."setting_menu WHERE parent_id = '".$result['menu_id']."'
						");	

			foreach($query2->result_array() as $fetch):

				print '<li>'.$fetch['menu_name'].'</li>';

			endforeach;

			print '</ul>';

		endforeach;
		print '</ul>';
	}

	public function dump_menu()
	{
		
		$this->db->query("
			TRUNCATE ".$this->db->dbprefix."setting_menu
		");	

		$query 	=	$this->db_old->query("
						SELECT DISTINCT(caption) as caption, url FROM menu_atas
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'menu_name' => $result['caption'],
							'menu_status' => 'show'
						);

			$this->db->insert($this->db->dbprefix."setting_menu", $data);

			$parent_id = $this->db->insert_id();

			$q =	$this->db_old->query("
						SELECT * FROM menu_metro WHERE menu_group = '".$result['url']."' && title != ''
					");

			foreach($q->result_array() as $fetch):

				$data2 =	array(
								'parent_id' => $parent_id,
								'menu_name' => $fetch['title'],
								'menu_status' => 'show'
							);

				$this->db->insert($this->db->dbprefix."setting_menu", $data2);

			endforeach;

			print '<pre>';
			print_r($data);
			print '</pre>';

		endforeach;

	}

	public function dump_user()
	{

		$this->db->query("
			DELETE FROM ".$this->db->dbprefix."user WHERE user_id > '1'
		");

		$query 	=	$this->db->query("
						SELECT a.*, b.*, c.id as cabang_id, a.id as user_id 
						FROM astonpri_asisall.users a
						LEFT JOIN ".$this->db->dbprefix."user_level b ON a.level = b.user_level_name
						LEFT JOIN astonpri_asisall.branch c ON a.dbname = c.dbname
					");

		foreach($query->result_array() as $result):

			$data =	array(
						'user_id' => $result['user_id'],
						'user_level_id' => $result['user_level_id'],
						'user_cabang_id' => $result['cabang_id'],
						'user_username' => $result['username'],
						'user_password' => $result['password'],
						'user_fullname' => $result['nama'],
						'user_employee_code' => $result['id_employee'],
						'user_password_update' => 'no',
						'user_old_databasetting' => $result['dbname'],
						'user_old_id_perusahaan' => $result['id_perusahaan']
					);

			$this->db->insert($this->db->dbprefix."user", $data);

			print '<pre>';
			print_r($data);
			print '</pre>';

		endforeach;

	}

	public function dump_branch()
	{
		
		//clear new table
		$this->db->query("
			TRUNCATE ".$this->db->dbprefix."setting_cabang
		");

		$query 	=	$this->db_old->query("
						SELECT * FROM branch 
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'cabang_id' => $result['id'],
							'cabang_code' => $result['kode_branch'],
							'cabang_nama' => $result['nama'],
							'cabang_alamat' => $result['alamat'],
							'cabang_contact_person' => $result['contact_person'],
							'cabang_owner' => $result['owner'],
							'cabang_phone' => $result['nohp'],
							'cabang_status' => $result['status'],
							'cabang_old_dbname' => $result['dbname']
						);

			$this->db->insert($this->db->dbprefix."setting_cabang", $data);

			print '<pre>';
			print_r($data);
			print '</pre>';

		endforeach;
	}

	public function dump_user_level()
	{

		//clear new table
		$this->db->query("
			TRUNCATE ".$this->db->dbprefix."user_level
		");

		$this->db->query("
			INSERT INTO ".$this->db->dbprefix."user_level (`user_level_name`) VALUES ('root')
		");

		$query 	=	$this->db_old->query("
						SELECT DISTINCT(level) as ulevel FROM users 
						ORDER BY level ASC
					");

		foreach($query->result_array() as $result):

			$data 	=	array(
							'user_level_name' => $result['ulevel']
						);

			$this->db->insert($this->db->dbprefix."user_level", $data);

			print '<pre>';
			print_r($data);
			print '</pre>';

		endforeach;
	
	}	


}
