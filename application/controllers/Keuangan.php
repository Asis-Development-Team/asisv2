<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

	private $file_size	=	'';
	private	$image_ext	=	'';

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

		$this->load->library(array('data_lib','penjualan_lib','generate_lib','stock_lib'));

	}	

	public function index()
	{
		header('location:/keuangan/kas-keluar');
	}


	public function kas_keluar_form()
	{ 

		$view 	=	'/form/view_form';

		if(@$_GET['id']):

			$request = $this->request_data_penjualan_single();

			//request data pelanggan dan validasi berdasarkan user cabang id supaya ga cross data
			//$request 	=	$this->penjualan_lib->data_penjualan_inovice_single('no_order',@$_GET['order']);

			$result 	=	$request['result'];
			$detail 	=	$request['detail'];

			//print '<pre>';
			//print_r($request);
			//exit;

			$request 	=	$this->data_lib->data_pelanggan($result['invoice_cabang_id'],false,false,false);
			$kustomer 	=	$request['result'];

			$view 		=	($request['total']) ? $view : '/view_no_access';

		endif;

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							$view
						);
		
		$request_outlet 	=	$this->setting_lib->setting_data_outlet(false,false,false,$this->session->sess_user_cabang_id);
		//$request_rekening 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true,6);

		$attribute 	=	$this->utility->page_attribute(__FUNCTION__);

        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        
                        'page_title' => $attribute['page_title'],
                        'page_url' => $attribute['page_url'],
                        'page_url_main' => $attribute['page_url_main'],
                        'page_title_global' => $attribute['page_title_global'],                       

                        'form_identifier' => (@$_GET['id']) ? 'edit' : 'add',

                        'view_form' => 'view_' . __FUNCTION__,

                        'outlet' => $request_outlet['result'],
                        //'rekening' => $request_rekening['result'],
                        'today' => date('Y-m-d', strtotime($this->local_time)),

                        'total_detail' => '5',

                    	'result' => @$result,
                    	'detail' => @$detail,
                    	'kustomer' => @$kustomer,
                    );
        
        $this->template->change_site_title(
            $attribute['page_title'],
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin']. $view, $data);
        $this->template->build();   			
	}

	public function kas_keluar()
	{

		//get page privilleges access
		$view 		=	$this->setting_lib->setting_page_privilleges(
							$this->session->sess_user_level_id,
							'/'.$this->uri->segment(1).'/'.str_replace('-form','',$this->uri->segment(2)),
							'view_' . __FUNCTION__
						);

		//prepend request data more than 100
		$lock_perpage 	=	(@$_GET['show'] > 100) ? '100' : @$_GET['show'];
		$perpage 		=	(@$_GET['show']) ? $lock_perpage : '20';

		
		//lock cabang by user level selain super
		$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

		$request 		=	$this->penjualan_lib->data_penjualan_inovice(@$_GET['q'],$perpage,$cabang_id,@$_GET['from'],@$_GET['to'],@$_GET['pembayaran']);
		
		$request_outlet =	$this->setting_lib->setting_data_outlet(false,false);

		//single cabang
		$request_outlet_single 	=	(@$_GET['cabang']) ? $this->setting_lib->setting_data_outlet_single('id',$_GET['cabang']) : '';


        $data   =   array(
                        
                        'page_identifier' => __FUNCTION__,
                        'page_title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                        'page_url' => str_replace('_','-',__FUNCTION__),

                        'total' => $request['total'],
                        'result' => $request['result'],
                        'paging' => $request['paging'],

                        'outlets' => $request_outlet['result'],
                        'outlet' => $request_outlet_single,
                        
                    );

        
        $this->template->change_site_title(
            str_replace('_',' ', ucfirst(__FUNCTION__)),
            'Page Description', //desc
            'Meta Keyword Disini'//key
        );      
        
        $this->template->set_content('templates/'.$this->template->data['template_admin'].'/'.$this->uri->segment('1').'/' . $view, $data);
        $this->template->build();   	

	}


	public function request_akun_rekening()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->data_lib->data_rekening(false,false,false,false,'Yes',true,$this->input->post('cabang'));

			print '<option value=""></option>';

			foreach($request['result'] as $result):
				
				if($result['rekening_cabang_id'] == $this->input->post('cabang')):
					print '<option value="'.$result['rekening_kode'].'">'.$result['rekening_nama'].' ('.$result['rekening_kode'].')</option>';
				endif;

			endforeach;

		endif;
	}

	public function request_data_karyawan()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->data_lib->data_karyawan(false,false,$this->input->post('cabang'));

			$result 	=	$request['result'];

			print '<option value=""></option>';

			asort($result);

			foreach($result as $result):
				
				if($result['karyawan_cabang_id'] == $this->input->post('cabang')):
					print '<option value="'.$result['karyawan_kode_identitas'].'" data-karyawan-id="'.$result['karyawan_id'].'">'.$result['karyawan_nama'].'</option>';
				endif;

			endforeach;

		endif;
	}

	public function request_kas_keluar_clone_form()
	{

		print '
        <div class="master-form">
	        <div class="col-md-6">
	            <div class="form-group">

	                <div class="invoice_kode_akun_lunas" style="">

	                    <select name="kas_nama_akun[]" id="kas_nama_akun" class="form-control select2 select2-hidden-accessible requiredField kas_nama_akun" aria-hidden="true">
	                        <option value=""></option>
	                    </select>

	                </div>

	            </div>
	        </div>

	        <div class="col-md-6">
	            <div class="form-group">
	                <input type="text" name="kas_nilai[]" id="kas_nilai_1" class="form-control nomer-auto requiredField kas_nilai">
	            </div>
	        </div>  
        </div> 

		';

		print '
			<script src="/assets/admin/pages/scripts/components-select2.min.js" type="text/javascript"></script>
			<script>
				
				$(document).ready(function(){
				
					$(".nomer-auto").autoNumeric("init");

					$(".kas_nilai").keyup(function()
					{
						total_kas_keluar();
					});

					function total_kas_keluar()
					{
				        var sum = 0;
				        
				        $("input[class *= \'kas_nilai\']").each(function() {
				            sum += +$(this).autoNumeric("get");
				        });

					    terhutang_formated	=	numeral(sum);
					    terhutang_formated	=	terhutang_formated.format("0,0");

				        $("#kas_total_keluar").text(terhutang_formated);		

					}


				});

			</script>
		';

	}


}