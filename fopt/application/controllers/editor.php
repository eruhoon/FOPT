<?php
require_once 'fopt_Controller.php';

class Editor extends Fopt_Controller {

	function __construct(){
		parent::__construct();

		$this->data['menu'] = 'test';
	}

	public function index(){
		$this->edit();
	}

	public function edit($code_idx=0)
	{
		
		### VALIDATION TEST - LOGIN ###		
		if(@$this->session->userdata['logged_in'] == FALSE){
			redirect('/auth/login?return_url='.$this->uri->segment(1));
		}
		
		
		### INCLUDE MODULE ###
		$this->load->model('code_m');
		$this->load->helper('alert');
		
		
		### IF ALREADY PROCESSING ###
		$user_idx = $this->session->userdata['idx'];
		$processing_code_idx = $this->code_m->get_processing_code($user_idx);
		if($processing_code_idx){
			alert('현재 다른 코드의 작업이 진행 중입니다.', site_url('code/code_status/'.$processing_code_idx));
		}
		
		
		### DATA FORMAT ###
		$data = array(
			'is_modify' => false,
			'views' => null,
			'content' => null
		);
		
		
		### IF EDIT MODE ###
		if($code_idx!=0){
			
			### INCLUDE MODULE ###
			$this->load->helper('file');
			$this->load->model('code_m');

			### READ FILE ###
			$code_content = read_file(RESULT_DIR.'./'.$code_idx.'/input.cpp');
			
			### READ DB ###			
			$views = $this->code_m->get_view($code_idx);

			### SET DATA ###
			$data = array(
				'is_modify' => true,
				'views' => $views,
				'content' => $code_content
			);
			
		}
		
		
		### VIEW ###
		$this->load->view('reg/editor_v', $data);
	}
	

}

?>