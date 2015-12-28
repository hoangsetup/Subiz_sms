<?php

	class Muser extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
		}

		function login()
		{
			$username = $this->input->post('username');
			$password = sha1($this->input->post('password'));
			$this->db->from('user');
			$this->db->where('username', $username);
			$this->db->where('password', $password);
			$query = $this->db->get();

			if($query->num_rows()=='1') {
				$this->session->set_userdata('loggedin', 'TRUE');
				$this->session->set_userdata('level', $query->row('level'));
				$this->session->set_userdata('id_user', $query->row('id_user'));
				$this->session->set_userdata('username', $query->row('username'));	
				if($this->input->post('remember_me')) $this->session->set_userdata('remember_me', TRUE);	
				redirect('home');
			}
			else $this->session->set_userdata('errorlogin', 'Your username or password are incorrect.');
		}

		function changepassword(){
			$username = $this->input->post('username');
			$oldpass = sha1($this->input->post('oldpassword'));
			$newpass = sha1($this->input->post('newpassword'));
			$this->db->where('username', $username);
			$this->db->where('password', $oldpass);
			$this->db->set('password', $newpass);
			$this->db->update('user');
			if($this->db->affected_rows() >= 1){
				$this->session->set_userdata('errorlogin', 'Your password has been changed!');
			}else{
				$this->session->set_userdata('errorlogin', 'Update failed! Your username or password are incorrect.');
			}
		}

	}
 ?>