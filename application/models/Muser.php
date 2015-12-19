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
			else $this->session->set_flashdata('errorlogin', 'Your username or password are incorrect');
		}

	}
 ?>