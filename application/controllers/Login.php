<?php 
/**
* 
*/
class Login extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');		
		$this->load->model('Muser');	
	}
	
	public function index()
	{
		$this->load->helper('form');
		if($_POST) $this->Muser->login();
		$this->load->view('login');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	function ChangePassword()
	{
		if($_POST) $this->Muser->ChangePassword();
		redirect('login');
	}
}
?>