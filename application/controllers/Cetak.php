<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller {

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

		$this->load->library(array('pembelian_lib','penerimaan_lib','pembayaran_lib','penjualan_lib'));

	}	

	public function index()
	{

		print 'this is print page area';

	}

	public function rakitan()
	{
		print '<pre>';
		print_r($_GET);
		print '</pre>';
	}

	public function pembayaran_piutang()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->penjualan_lib->data_penjualan_pembayaran_piutang_single('kode',@$_GET['no'],$this->session->sess_user_id);

			$query 	=	$this->db->query("
							SELECT pembayaran_detail_saldo FROM ".$this->db->dbprefix."data_pembayaran_detail WHERE 
							pembayaran_detail_kode_pembayaran = '".$request['result']['pembayaran_kode']."'
						")->row_array();

			$data 	=	array(
							'result' => $request['result'],
							'piutang' => $query
						);

			$view 	=	($request['total']) ? 'cetak/view_cetak_' . __FUNCTION__ : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;
	}

	public function penjualan()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->penjualan_lib->data_penjualan_inovice_single('no_order',@$_GET['no']);

			$data 	=	array(
							'result' => $request['result'],
							'detail' => $request['detail'],
						);

			$view 	=	($request['total']) ? 'cetak/view_cetak_' . __FUNCTION__ : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;
	}

	public function pembayaran_hutang()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->pembayaran_lib->data_pembayaran_single('kode',@$_GET['no'],$this->session->sess_user_cabang_id);

			$detail 	=	$this->pembayaran_lib->data_pembayaran_single_detail('kode_pembayaran',@$_GET['no']);			

			$data 	=	array(
							'result' => $request['result'],
							'detail' => $detail['result'],
						);

			$view 	=	($request['total']) ? 'cetak/view_cetak_' . __FUNCTION__ : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;
	}

	public function po_penerimaan()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			$array	=	explode('/pembelian/', $this->agent->referrer());
			
			$mode 		=	($array['1'] == 'po-supplier') ? 'no_po' : 'no_penerimaan';

			$request 		=	$this->penerimaan_lib->data_penerimaan_single($mode,@$_GET['no'],$this->session->sess_user_cabang_id);
			$request_detail =	$this->penerimaan_lib->data_penerimaan_detail_single('no_penerimaan',$request['result']['penerimaan_no_penerimaan']);

			$data =	array(
						'result' => $request['result'],
						'detail' => $request_detail['result'],
					);
			
			$view 	=	($request['total']) ? 'cetak/view_cetak_po_penerimaan' : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;
	}

	public function po_supplier()
	{

		if($this->agent->referrer() && $this->session->sess_user_id):

			$request 	=	$this->pembelian_lib->po_supplier_single(
								'nomer_po',@$_GET['no'],$this->session->sess_user_cabang_id
							);

			$request_detail =	$this->pembelian_lib->po_supplier_detail_single('nomer_po',$_GET['no']);

			$data 	=	array(
							'result' => $request['result'],
							'detail' => $request_detail['result']
						);

			$view 	=	($request['total']) ? 'cetak/view_cetak_po_supplier' : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;

	}

	public function po_outlet()
	{
		if($this->agent->referrer() && $this->session->sess_user_id):

			//lock cabang id
			$cabang_id 	=	($this->session->sess_user_level_id < 3) ? @$_GET['cabang'] : $this->session->sess_user_cabang_id;

			$request 	=	$this->pembelian_lib->po_outlet_single(
								'nomer',$_GET['no'],$this->session->sess_user_cabang_id
							);

			$request_detail	=	$this->pembelian_lib->po_outlet_single_detail('nomer',@$_GET['no']);

			$data 	=	array(
							'result' => $request['result'],
							'detail' => $request_detail['result'],
						);


			$view 	=	($request['total']) ? 'cetak/view_cetak_po_outlet' : 'view_no_access';
			
			$this->load->view('templates/'.$this->template->data['template_admin'].'/'. $view, $data);

		endif;

	}

}
