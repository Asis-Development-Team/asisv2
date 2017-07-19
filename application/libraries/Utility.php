<?php

class Utility{

	var $CI;
	
	private $local_time;

	function __construct(){
		$this->CI =& get_instance();
		
		$this->CI->load->dbutil();

		$this->CI->load->library(array('tools','pagination'));
		$this->local_time 	=	unix_to_human(now('ASIA/Jakarta'),TRUE,'eu');	
	}
	
	public function delete_single($id,$page,$field,$where=false)
	{
		

		$statement 	=	($where) ? $where : "";

		if($page == 'setting_produk_rakitan_detail'):

			$array 	=	explode('-', $id);

			$this->CI->db->delete(
				$this->CI->db->dbprefix . $page, array($field => $array['0'], 'rakitan_detail_cabang_id' => $array['1'])
			);

		else:

			if($page == 'data_pembayaran'):
				
				$this->CI->db->query("
					 SET @aston_trigger_delete_pembayaran_piutang = TRUE
				");
			
			endif;

			$this->CI->db->delete(
				$this->CI->db->dbprefix . $page, array($field => $id)
			);

		endif;
		

		$total 	=	$this->CI->db->count_all($this->CI->db->dbprefix . $page . $statement);

		return $total;
	}

	public function delete_multi($data_id,$page,$field,$where=false)
	{
		
		$id = 	$data_id;

		$id =	substr($id, 0, -1);
		$id	=	explode(',', $id);
		
		$new_id	=	'';
		
		for($i=0;$i<=count($id)-1;$i++):
			
			$new_id	.= "'".$id[$i]."',";
			
		endfor;
		
		$id	=	substr($new_id, 0, -1);

		$statement 	=	($where) ? $where : "";

		$this->CI->db->query("
			DELETE FROM ".$this->CI->db->dbprefix . $page . " WHERE ".$field." IN (".$id.")
		");					
		
		$total	=	$this->CI->db->count_all($this->CI->db->dbprefix . $page . $statement);

		return $total;	
	}

	public function page_attribute($page)
	{
		
		$steril 	=	str_replace('_',' ',$page);

		$attribute 	=	array(
							'page_title' => ucwords($steril),
							'page_url' => str_replace(' ','-',$steril),
							'page_url_main' => str_replace(' ','-',str_replace(' form','',$steril)),
							'page_title_global' => ucwords(str_replace(' form','',$steril)),
						);

		return $attribute;
	}

	public function list_database()
	{
		$dbs = $this->CI->dbutil->list_databases();

		return $dbs;
	}

	public function list_fields($table)
	{
		$fields =	$this->CI->db->list_fields($table);
		
		return $fields;
	}


	public function pagination_setting($qurl,$total_data,$perpage)
	{
		
		$config['base_url']				= $qurl;
		$config['page_query_string']	=	TRUE; // ini untuk supaya number paging dalam bentuk query string di URL. kalo false nanti untuk url friendly
		
		$config['total_rows']	= $total_data;
		$config['num_links']	= 3;
		
		$config['query_string_segment'] = 'page';
		
		$config['first_tag_open']	= '<li>';
		$config['first_tag_close'] 	= '</li>';
		
		$config['per_page']			= $perpage; 
		
		//$config['full_tag_open'] = '<li>';
		//$config['full_tag_close'] = '</li>';
		
		$config['cur_tag_open']		= '<li class="active"><a href="javascript:;">';
		$config['cur_tag_close']	= '</a></li>';
		
		$config['num_tag_open']		= '<li>';
		$config['num_tag_close']	= '</li>';

		$config['prev_tag_open']	= '<li>';
		$config['prev_tag_close']	= '</li>';	
		
		$config['next_tag_open'] 	= '<li>';
		$config['next_tag_close'] 	= '</li>';							
		
		//$config['anchor_class'] = 'class="active" ';
		
		$this->CI->pagination->initialize($config);
		
		$paging	=	$this->CI->pagination->create_links();
		
		return $paging;

	}	

}
?>
