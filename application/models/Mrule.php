<?php 
	/**
	* 
	*/
	class Mrule extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		function getallrules()
		{
			$this->db->order_by('TimeUpdate DESC');
			$query = $this->db->get('rules');
			return $query->result_array();
		}

		function addrule($rule){
			$this->db->insert('rules', $rule);
		}

		function getrulebyid($id = '')
		{
			$this->db->where('Id', $id);
			$query = $this->db->get('rules');
			return $query->row_array();
		}

		function updaterule($newRule, $id = -1){
			$this->db->where('Id', $id);
			$this->db->update('rules', $newRule);
		}

		function deleterule($id= -1)
		{
			$this->db->where('Id', $id);
			$this->db->delete('rules');
		}
	}
?>