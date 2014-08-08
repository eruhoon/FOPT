<?php
require_once 'fopt_Controller.php';

class Code extends fopt_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('alert');
		$this->load->helper('file');

		$this->load->model('code_m');
		$this->data['menu'] = 'list';
		//$this->output->enable_profiler(TRUE);
	}



	/*********************************************************
		INDEX

		SITE : /code/
		DEFAULT : -
	*********************************************************/
	public function index(){
	}



	/*********************************************************
		LISTS 

		SITE : /code/lists/
	*********************************************************/
	public function lists()
	{
			
		### LOAD USER INFO ###
		$user_id = $this->input->get('id');

		### LOAD DATASET ###
		$total_rows = $this->code_m->get_total_rows($user_id);
		$per_page = 10;
		$uri_segment = 4;
		$pagination_set = $this->_get_pagination(ROOT_DIR.'./code/lists/page/', $user_id?('?id='.$user_id):'', $total_rows, $per_page, $uri_segment);

		### INDEX TEST ###
		if($pagination_set['start'] > $total_rows){
			$list_result = FALSE;
			
		}
		else if($pagination_set['start'] < 0){
			$list_result = FALSE;
			
		}
		else{
			$list_result = $this->code_m->get_list($pagination_set['start'], $pagination_set['limit'], $user_id);
		}
		

		### SET DATA ###
		$data = array(
			'list' => $list_result,
			'pagination' => $pagination_set['pagination']
		);

		
		### VIEW ###
		$this->load->view('code/list_v', $data);

	}



	/*********************************************************
		MY CODE 

		SITE : /code/my_code/
	*********************************************************/
	public function my_code()
	{
			
		### LOAD USER INFO ###
		$user_id = $this->session->userdata['username'];
		
		### LOAD DATASET ###
		$total_rows = $this->code_m->get_total_rows($user_id);
		$per_page = 10;
		$uri_segment = 4;
		$pagination_set = $this->_get_pagination(ROOT_DIR.'./code/my_code/page/', $user_id?('?id='.$user_id):'', $total_rows, $per_page, $uri_segment);

		### INDEX TEST ###
		if($pagination_set['start'] > $total_rows){
			$list_result = FALSE;
			
		}
		else if($pagination_set['start'] < 0){
			$list_result = FALSE;
			
		}
		else{
			$list_result = $this->code_m->get_list($pagination_set['start'], $pagination_set['limit'], $user_id);
		}
		

		### SET DATA ###
		$data = array(
			'list' => $list_result,
			'pagination' => $pagination_set['pagination']
		);

		
		### VIEW ###
		$this->load->view('code/my_code_v', $data);

	}





	/*********************************************************
		UPLOAD

		SITE : /code/upload/
	*********************************************************/
	public function upload()
	{

		### LOG-IN TEST ###
		if(!@$this->session->userdata['logged_in']){
			alert('잘못된 접근 입니다.', site_url('editor'));
			exit;
		}
		
		
		### IF ALREADY PROCESSING ###
		$user_idx = $this->session->userdata['idx'];
		$processing_code_idx = $this->code_m->get_processing_code($user_idx);
		if($processing_code_idx){
			alert('현재 다른 코드의 작업이 진행 중입니다.', site_url('code/code_status/'.$processing_code_idx));
		}


		### FORM TEST ###
		if(!$this->input->post()){
			alert('업로드 실패 - Form 작업 에러.', site_url('editor'));
			exit;
		}


		### UPLOAD ###
		$auth_level = $this->input->post('open_level');
		$db_result = $this->code_m->upload($auth_level);


		### VALIDATION TEST - DB ###
		if(!$db_result){
			alert('업로드 실패 - DB 작업 에러.', site_url('editor'));
			exit;
		}


		### DIRECTORY TASK ###
		$code_idx = $db_result;
		if(!is_dir(RESULT_DIR.'./'.$code_idx.'/')){
			$old_umask = umask(0);
			mkdir(RESULT_DIR.'./'.$code_idx.'/', 0777);
			umask($old_umask);
		}


		### WRITE FILE ###
		$content = $this->input->post('content');
		$file_result = write_file(RESULT_DIR.'./'.$code_idx.'/input.c', $content);

		
		### VALIDATION TEST - FILE ###
		if(!$file_result) {
			alert('업로드 실패 - 파일 작업 에러', site_url('editor'));
			exit;
		}
		
		
		### EXECUTE CODE ###		
		$this->execute($code_idx);
		
		
		### OUTPUT ###
		alert('업로드가 완료되었습니다.', site_url('code/code_status/'.$code_idx));
	}

	
	
	/*********************************************************
	 * 	EXECUTE FILE
	 * 
	 * 	PARAMETER
	 * 		$code_idx : CODE_IDX (NUMBER)
	 * 	SITE : /code/execute/$code_idx
	 *********************************************************/
	public function execute($code_idx)
	{
		
		### READ DB ###
		$views = $this->code_m->get_view($code_idx);
		
		
		### VALIDATION TEST ###
		if(!$views){
			return FALSE;
		}
		
		
		### EXECUTE CODE ###
		exec("python /var/www/html/fopt/module/fopt_manager.py ".$code_idx." > /dev/null &", $output);
		//exec("python /var/www/html/fopt/module/fopt_manager.py ".$code_idx, $output);
		//print_r($output);
	}



	/*********************************************************
		UPDATE
		
		PARAMETER
			$code_idx : CODE_IDX (NUMBER)
		SITE : /code/update/$code_idx
	*********************************************************/
	public function update($code_idx)
	{

		### LOG-IN TEST ###
		if(!@$this->session->userdata['logged_in']){
			alert('잘못된 접근 입니다.', site_url('editor/edit/'.$code_idx));
			exit;
		}


		### REFERER TEST ###
		if(site_url('editor/edit/'.$code_idx)!=@$this->input->server('HTTP_REFERER')){
			alert('잘못된 접근 입니다.', site_url('editor/edit/'.$code_idx));
			exit;	
		}


		### AUTHORIZATION ###
		if(!$this->code_m->is_mine($this->code_m->get_view($code_idx)->reg_user_idx)){
			alert('권한이 없습니다.', site_url('editor/edit/'.$code_idx));
			exit;	
		}


		### FORM TEST ###
		if(!$this->input->post()){
			alert('업데이트 실패 - 폼 작업 에러', site_url('editor/edit/'.$code_idx));
			exit;
		}


		### UPDATE ###
		$auth_level = $this->input->post('open_level');
		$db_result = $this->code_m->update($code_idx, $auth_level);


		### VALIDATION TEST - DB ###
		if(!$db_result){
			alert('업데이트 실패 - DB 작업 에러.', site_url('editor'));
			exit;
		}


		### DIRECTORY TASK ###
		$code_idx = $db_result;
		if(!is_dir(RESULT_DIR.'./'.$code_idx.'/')){
			$old_umask = umask(0);	
			mkdir(RESULT_DIR.'./'.$code_idx.'/', 0777);
			umask($old_umask);
		}


		### WRITE FILE ###
		$content = $this->input->post('content');
		$file_result = write_file(RESULT_DIR.'./'.$code_idx.'/input.c', $content);


		### VALIDATION TEST - FILE ###
		if(!$file_result){
			alert('업데이트 실패 - 파일 작업 에러', site_url('editor'));
			exit;
		}
		
		
		### EXECUTE CODE ###		
		$this->execute($code_idx);
		

		### OUTPUT ###
		alert('업데이트가 완료되었습니다.', site_url('code/code_status/'.$code_idx));
		
	}



	/*********************************************************
		DELETE

		PARAMETER
			$code_idx : CODE_IDX (NUMBER)
		SITE : /code/delete/$code_idx
	*********************************************************/
	public function delete($code_idx)
	{
		### LOG-IN TEST ###
		if(!@$this->session->userdata['logged_in']){
			alert('잘못된 접근 입니다.', site_url('code/code_view/'.$code_idx));
			exit;
		}


		### REFERER TEST ###
		if(site_url('code/code_view/'.$code_idx)!=@$this->input->server('HTTP_REFERER')){
			alert('잘못된 접근 입니다.', site_url('code/code_view/'.$code_idx));
			exit;
		}


		### AUTHORIZATION ###
		if(!$this->code_m->is_mine($this->code_m->get_view($code_idx)->reg_user_idx)){
			alert('권한이 없습니다.', site_url('code/code_view/'.$code_idx));
			exit;
		}


		### DELETE ###
		$db_result = $this->code_m->delete($code_idx);
		

		### VALIDATION TEST - DB ###
		if(!$db_result){
			alert('삭제 실패 - DB 작업 에러.', site_url('code/code_view/'.$code_idx));
			exit;
		}

		### OUTPUT ###
		alert('삭제가 완료되었습니다.', site_url('code/lists'));

	}


	
	/*********************************************************
		RESULT_VIEW

		PARAMETER
			$code_idx : CODE_IDX (NUMBER)
		SITE : /code/result_view/$code_idx
	*********************************************************/
	public function result_view($code_idx)
	{
		### READ RESULT XML ###
		$xml_object = $this->code_m->get_result_xml_object($code_idx);

		### READ DB ###
		$views = $this->code_m->get_view($code_idx);

		### SET DATA ###
		$data = array(
			'code_idx' => $code_idx,
			'views' => $views,
			'num_of_ber_cases' => 10,
			'num_of_cases' => 2,
			'content' => $xml_object
		);
		
		### VIEW ###
		$this->load->view('code/result_view_v', $data);
	}



	/*********************************************************
		CODE_VIEW

		PARAMETER
			$code_idx : CODE_IDX (NUMBER)
		SITE : /code/code_view/$code_idx
	*********************************************************/
	public function code_view($code_idx)
	{
		### READ SOURCE FILE ###
		$code_content = $this->code_m->get_raw_code($code_idx);
		
		### READ DB ###
		$views = $this->code_m->get_view($code_idx);

		### SET DATA ###
		$data = array(
			'views' => $views,
			'content' => $code_content
		);

		### VIEW ###
		$this->load->view('code/code_view_v', $data);
	}



	/*********************************************************
		CODE_STATUS

		PARAMETER
			$code_idx : CODE_IDX (NUMBER)
		SITE : /code/code_status/$code_idx
	*********************************************************/
	public function code_status($code_idx)
	{
		### READ DB ###
		$views = $this->code_m->get_view($code_idx);
		
		### SET DATA ###
		$data = array(
			'views' => $views
		);

		### VIEW ###
		$this->load->view('code/code_status_v', $data);
	}



	/*********************************************************
		CODE_STATUS

		SITE : /code/rank/
	*********************************************************/
	public function rank()
	{
		### VIEW ###
		$this->load->view('code/rank_v');
	}



	/*********************************************************
		CODE_STATUS

		PARAMETER
			$base_url : PAGINATION URL (STRING)
			$suffix : PAGINATION URL SUFFIX (STRING)
			$total_rows : TOTAL NUMBER OF ROWS (NUMBER)
			$per_page : ROWS PER PAGES (NUMBER)
			$uri_segment : SEGMENT which has START ROW (NUMBER)
		RETURN
			$result : ARRAY(
				pagination : PAGENATION TAG (STRING)
				start : START ROW (NUMBER)
				limit : ROWS PER PAGES (NUMBER)
			)
		SITE : -
		AUTHORIZATION : PRIVATE
	*********************************************************/
	private function _get_pagination($base_url, $suffix, $total_rows, $per_page, $uri_segment)
	{
		
		### INCLUDE MODULE ###	
		$this->load->library('pagination');
		

		### INIT PAGINATION ###
		$config['base_url'] = $base_url;
		$config['suffix'] = $suffix;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = $uri_segment;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '«';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '»';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);


		### PAGING PROCESS ###
		$page = $this->uri->segment($uri_segment, 1);
		if($page > 1){
			$start = ($page/$config['per_page']) * $config['per_page'];
		}
		else{
			$start = ($page-1) * $config['per_page'];
		}
		$limit = $config['per_page'];


		### SET DATA ###
		$result = array(
			'pagination' => $this->pagination->create_links(),
			'start' => $start,
			'limit' => $limit
		);

		### RETURN ###
		return $result;
	}
}

?>
