<?php

class Authentication{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		
		$this->CI->load->library(array('tools'));
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}
	

	public function do_login($user,$password)
	{
		
		$query 	=	$this->CI->db->query("
						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_user a
						LEFT JOIN ".$this->CI->db->dbprefix."data_user_level b ON a.user_level_id = b.user_level_id 
						LEFT JOIN ".$this->CI->db->dbprefix."setting_cabang c ON a.user_cabang_id = c.cabang_id
						WHERE user_username = '".addslashes($user)."'
					");

		$fetch	=	$query->row_array();

		if($query->num_rows() > 0):

			//check password
			if($fetch['user_password_update'] == 'no' && ($fetch['user_password'] == md5($password)) ):
				$is_valid 	=	true;
			//elseif($fetch['user_password_update'] == 'yes' && ($this->CI->phpass->check($password, @$fetch['user_password'])) ):
			elseif($fetch['user_password_update'] == 'yes' && (password_verify($password, @$fetch['user_password'])) ):	
				$is_valid	=	true;
			else:
				$is_valid	=	false;
			endif;


			if($is_valid):
				//update to new password hash
				if($fetch['user_password_update'] == 'no'):

					$this->CI->db->query("
						UPDATE ".$this->CI->db->dbprefix."data_user SET 
							user_password = '".password_hash(addslashes($password),PASSWORD_DEFAULT)."',
							user_password_update = 'yes'
						WHERE user_id = '".$fetch['user_id']."'
					");

				endif;


				//create session login
				$this->CI->db->delete($this->CI->db->dbprefix . "data_user_session", array('user_id'=>$fetch['user_id']));
				
				$db_session	=	array(
									'user_id' => $fetch['user_id'],
									'session_date' => $this->local_time,
									'session_value' => $this->CI->session->session_id,
									'ip_address' => @$_SERVER['REMOTE_ADDR'],
									'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
									'last_activity' => $this->local_time,
								);
				
				$this->CI->db->insert($this->CI->db->dbprefix."data_user_session",$db_session);
				
				$last_login	=	array(
									'user_last_login' => $this->local_time
								);
								
				$this->CI->db->update($this->CI->db->dbprefix."data_user",$last_login, array('user_id' => $fetch['user_id']));

				$cabang_id 	=	($fetch['user_level_id'] < 3) ? false : $fetch['user_cabang_id'];

				$session =	array(
								'sess_user_id' => $fetch['user_id'],
								'sess_surname' => $fetch['user_fullname'],
								'sess_user_level_id' => $fetch['user_level_id'],
								'sess_user_level_name' => $fetch['user_level_name'],
								'sess_user_email' => $fetch['user_email'],
								'sess_user_phone' => $fetch['user_phone'],
								'sess_user_cabang_id' => $cabang_id, //$fetch['user_cabang_id'],
								'sess_user_login' => $fetch['user_username'],
							);
				
				$this->CI->session->set_userdata($session);		


				$callback	=	array(
									'status' => 200,
									'url' => '/admin/dashboard'
									//'result' => $fetch
								);

			else:
				$callback	=	array('status' => '204', 'message' => 'Invalid Username or Password'); 
			endif;

		else:
			$callback	=	array('status' => '204', 'message' => 'Invalid Username or Password'); 
		endif;

		return $callback;

	}

	public function is_login($user_id,$session)
	{
		
		$query	=	$this->CI->db->query("
						SELECT * 
						FROM ".$this->CI->db->dbprefix."data_user_session a
						LEFT JOIN ".$this->CI->db->dbprefix."data_user b ON a.user_id = b.user_id 
						WHERE a.user_id = '".$user_id."' && a.session_value = '".$session."'
					");
		
		if($fetch=$query->row_array()):
			//return $fetch;
			$callback 	=	array(
								'status' => true,
								//'data' => $fetch
							);
		else:
			//return false;
			$callback 	=	array(
								'status' => false,
							);
		endif;

		return json_encode($callback);
			
	}
	
	public function auth_login($username,$password)
	{
		$query	=	$this->CI->db->query("
						SELECT * FROM ".$this->CI->db->dbprefix."data_user WHERE username = '".addslashes($username)."' && password = '".addslashes(md5($password))."'
					");
		
		return $query->row_array();	
	}
	
	public function delete_prev_session_login($user_id)
	{
		
		$sql	=	"
					DELETE FROM ".$this->CI->db->dbprefix."data_user_session WHERE user_id = '".$user_id."'
					";
		
		$this->CI->db->query($sql);
		
	}
		
	public function create_session($user_id,$session_value,$user_level,$user_name,$is_admin)
	{
		
		//drop prev session
		$this->CI->db->delete($this->CI->db->dbprefix."data_user_session", array('user_id'=>$user_id));
		
		$session	=	array(
							'user_id' => $user_id,
							'session_date' => $this->CI->tools->dateTimeMysql(),
							'session_value' => $session_value,
							'ip_address' => $_SERVER['REMOTE_ADDR'],
							'user_agent' => $_SERVER['HTTP_USER_AGENT'],
							'last_activity' => $this->CI->tools->dateTimeMysql(),
						);
		
		$this->CI->db->insert(tableprefix."data_user_session",$session);
		
		$last_login	=	array(
							'last_login' => $this->CI->tools->dateTimeMysql(),
							'ip_address' => $_SERVER['REMOTE_ADDR'] 
						);
		
		$this->CI->db->update($this->CI->db->dbprefix."data_user",$last_login, array('user_id'=>$user_id));
		
		//$_SESSION['userid']		=	$user_id;
		//$_SESSION['username']	=	$user_name;
		//$_SESSION['userlevel']	=	$user_level;
		
		$newdata =	array(
						'surname'  => $user_name,
						'user_id'  => $user_id,
						'user_level' => $user_level,
						'is_admin' => $is_admin,
						//'is_subscribe' => '',
					);
		
		$this->CI->session->set_userdata($newdata);		
		
	}
	
	public function check_session_timeout($user_id,$session_value)
	{
		
		$query	=	$this->CI->db->query("
						SELECT *
							
							,IF(
								last_activity + INTERVAL 2 HOUR < now(), 'timeout','login'
							) as session_status
							
						FROM ".$this->CI->db->dbprefix."data_user_session
						WHERE user_id = '".$user_id."' && session_value = '".$session_value."'
					");
					
		return $query->row_array();
	}
	
	public function update_last_activity($user_id)
	{
		
		$timezone	=	$this->CI->tools->time_zone();
		
		$data	=	array(
						'last_activity' => $timezone->format('Y:m:d H:i:s')
					);
		
		$this->CI->db->update($this->CI->db->dbprefix."data_user_session",$data,array('user_id' => $user_id));
	}
	

	public function sweep_session($user_id,$session_value,$redirect_url)
	{
		
		$timeout	=	$this->check_session_timeout($user_id,$session_value);
		
		if($timeout['session_status'] == 'login'):
			$this->update_last_activity($user_id);
		else:
			
			$this->delete_prev_session_login($user_id);
					
			//clear session
			$session =	array(
							'surname'  => '',
							'user_id'  => '',
							'user_level' => '',
							'is_admin' => ''
						);
			
			$this->CI->session->unset_userdata($session);		
			
			//header('location:' . $redirect_url);
			
		endif;
		
	}
}
?>
