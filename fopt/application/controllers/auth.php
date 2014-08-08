<?php
require_once 'fopt_Controller.php';

class Auth extends fopt_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('auth_m');
		$this->load->helper('form');

		$this->data['menu'] = 'auth';
	}



	/*********************************************************
		UPDATE
		
		SITE : /auth/
		DEFAULT : login
	*********************************************************/
	public function index(){
		$this->login();
	}




	/*********************************************************
		LOGIN
		
		SITE : /auth/login/
	*********************************************************/
	public function login()
	{

		### INCLUDE MODULE ###
		$this->load->library('form_validation');
		$this->load->helper('alert');


		### VALIDATION TEST ###
		$this->form_validation->set_rules('username', '아이디', 'required');
		$this->form_validation->set_rules('password', '비밀번호', 'required');
		if(!$this->form_validation->run()){

			### INPUT GET ###
			$err = $this->input->get('err');
			$return_url = $this->input->get('return_url');

			### ON ERROR @ ID, PW ###
			if($err){
				$data['warning_message'] = '아이디나 비밀번호를 확인해 주세요.';
				$this->load->view('auth/login_v', $data);
				return;
			}

			### OUTPUT FORMAT ###
			$data = array(
				'return_url' => $return_url,
				'warning_message' => ($return_url)?'로그인을 해야하는 컨텐츠 입니다.':null
			);

			### LOAD ###
			$this->load->view('auth/login_v', $data);
			return;
		}


		### INPUT FORMAT ###
		$auth_data = array(
			'username' => $this->input->post('username', TRUE),
			'password' => $this->input->post('password', TRUE)
		);


		### LOGIN ###
		$result = $this->auth_m->login($auth_data);


		### LOGIN FAIL ###
		if(!$result){
			$return_controller = $this->input->get('return_url');
			alert('아이디나 비밀번호를 확인해 주세요.', site_url('auth/login?return_url='.$return_controller.'&err=1'));
			exit;
		}

		
		### INPUT FORMAT ###
		$new_session_data = array(
			'idx' => $result->user_idx,
			'username' => $result->user_id,
			'email' => $result->email,
			'logged_in'	=> TRUE
		);

		### REGISTER SESSION ###
		$this->session->set_userdata($new_session_data);

		### LOGIN SUCCESS ###
		$return_controller = $this->input->get('return_url');
		log_message('info', $return_controller);
		$return_url = site_url($return_controller ? $return_controller : 'home');
		alert('로그인 되었습니다.', $return_url);
	}



	/*********************************************************
		LOGOUT
		
		SITE : /auth/logout/
	*********************************************************/
	public function logout()
	{

		### INCLUDE MODULE ###
		$this->load->helper('alert');


		### DESTROY SESSION ###
		$this->session->sess_destroy();

		alert('로그아웃되었습니다.', site_url('home'));
		exit;
	}



	/*********************************************************
		JOIN
		
		SITE : /auth/join/
	*********************************************************/
	public function join()
	{

		### INCLUDE MODULE ###
		$this->load->library('form_validation');
		$this->load->helper('alert');


		### SET VALIDATION ###
		$this->form_validation->set_rules('username', '아이디', 'required|min_length[5]|max_length[12]|alpha_dash|callback_username_check');
		$this->form_validation->set_rules('password', '비밀번호', 'required|matches[passconf]');
		$this->form_validation->set_rules('passconf', '비밀번호 확인', 'required');
		$this->form_validation->set_rules('email', '이메일', 'required|valid_email');
		

		### VALIDATION TEST - FORM ###
		if(!$this->form_validation->run())
		{
			$this->load->view('auth/join_v');
			return;
		}

//echo 1;
		### INPUT FORMAT ###
		$user_info = array(
			'user_id' => $this->input->post('username', TRUE),
			'password' => $this->input->post('password', TRUE),
			'email' => $this->input->post('email', TRUE)
		);


		### JOIN ###
		$result = $this->auth_m->join($user_info);


		### VALIDATION TEST - JOIN ###
		if(!$result){
			$this->load->view('auth/join_v');
			return;
		}


		### JOIN SUCCESS ###
		alert('가입되었습니다.', ROOT_DIR.'./home/');

	}



	/*********************************************************
		USERNAME_CHECK
		
		PARAMETER
			$user_id : user_id (NUMBER)
		SITE : /auth/username_check/$id/
		DEFAULT : login
	*********************************************************/
	public function username_check($id){
		$this->load->database();

		if($id){
			$result = array();
			
			$query_condition = array(
				'user_id' => $this->input->post('username', TRUE)
			);
			$this->db->where($query_condition);
			$result = @$this->db->get('user')->row();

			if($result){
				$this->form_validation->set_message('username_check', $id.'은(는) 중복된 아이디입니다.');
				return FALSE;
			}
			else{
				return TRUE;
			}
		}
		else{
			return FALSE;
		}
	}
}
?>