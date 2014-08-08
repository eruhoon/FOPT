<?php if(!defined('BASEPATH')) exit('No Direct Script Access Allowed');

class Code_ajax extends CI_Controller {

	function __construct(){
		parent::__construct();
		
		$this->load->model('code_m');
	}



	public function index(){
	}
	
	
	
	/* 
		SITE : /code_ajax/code_sample/
	*/
	public function code_sample(){
		$source_code = $this->code_m->get_sample_code();
		echo $source_code;
	}


	/*
		SITE : /code_ajax/code_json/$code_idx/
	*/
	public function code_json($code_idx){

		$views = $this->code_m->get_view($code_idx);

		if($views == null){
			$result->error = true;
			$result->msg = "잘못된 접근 입니다.";
		}else if($views->is_open!=true){
			$result->error = true;
			$result->msg = "비공개 된 코드정보 입니다.";
		}else{
			$result = $views;
			unset($result->is_mine);
			unset($result->is_open);
			$result->result_report = htmlspecialchars($result->result_report);
			$result->result_runtime_report = htmlspecialchars($result->result_runtime_report);
		}
		echo json_encode($result);
	}
	
	
	
	/*
		SITE : /code_ajax/code_xml/$code_idx/
	*/
	public function code_xml($code_idx){
		$result = $this->code_m->get_xml($code_idx);
		header('Content-type: text/xml'); 
		echo "<?xml version='1.0' encoding='UTF-8'?>";
		echo $result;
	}
	
	

	/*
		SITE : /code_ajax/rank_json/$filter/$per_page
	*/
	public function rank_json($filter, $per_page){

		$views = $this->code_m->get_rank(null, null, $filter);

		if(!$views) return FALSE;

		unset($views->reg_user_idx);
		unset($views->state_id);
		unset($views->state_mark);

		echo json_encode($views);
	}
	
	
	/*
		SITE : /code_ajax/code_graph/$code_idx/$thumbnail
	*/
	public function code_graph($code_idx=0, $thumbnail=false){
		### INCLUDE MODULE ###		
		$this->load->helper('svggraph');
		
		### READ RESULT XML ###
		$xml_object = $this->code_m->get_result_xml_object($code_idx);

		### READ DB ###
		$views = $this->code_m->get_view($code_idx);

		### SET DATA ###
		$data = array(
			'code_idx' => $code_idx,
			'thumbnail' => $thumbnail,
			'views' => $views,
			'content' => $xml_object
		);

		### GRAPH LOAD ###
		$this->load->view('code/code_graph_v', $data);
		
	}
	
	
	/*
		SITE : /code_ajax/code_pdf/$code_idx
	*/
	public function code_pdf($code_idx=0){
		
		### INCLUDE MODULE ###		
		$this->load->helper('pdf');
		
		### IS PRINT? ###
		$is_print = ($this->input->get('print'))?true:false;		
		
		### READ RESULT XML ###
		$xml_object = $this->code_m->get_result_xml_object($code_idx);

		### READ DB ###
		$views = $this->code_m->get_view($code_idx);

		### SET DATA ###
		$data = array(
			'code_idx' => $code_idx,
			'views' => $views,
			'content' => $xml_object,
			'is_print' => $is_print
		);
		
		### VALIDATION TEST ###
		if($views == null){
			$this->load->view('40x_v');
			return;
		}else if($views->is_open!=true){
			$this->load->view('40x_v');
			return;
		}
		
		### PDF OUTPUT ###
		$this->load->view('code/code_pdf_v', $data);
	}
	
	/*
		SITE : /code_ajax/code_pdf/$code_idx	 
	*/
	public function code_zip($code_idx=0){
		
		### INCLUDE MODULE ###
		$this->load->library('zip');
		
		if($code_idx == 0){
			$result->error = true;
			$result->msg = "잘못된 접근 입니다.";
			echo json_encode($result);
			return;
		}		
		
		$views = $this->code_m->get_view($code_idx);
		if($views == null){
			$result->error = true;
			$result->msg = "잘못된 접근 입니다.";
			echo json_encode($result);
			return;
		}
		
		if($views->is_open!=true){
			$result->error = true;
			$result->msg = "비공개 된 코드정보 입니다.";
			echo json_encode($result);
			return;
		}
		
		echo RESULT_DIR.$code_idx;
		
		## RETURN ZIP ###
		$this->zip->read_dir(RESULT_DIR.$code_idx.'/', FALSE);
		$this->zip->download('fopt_result_'.$code_idx.'.zip');
	}

}
	
?>