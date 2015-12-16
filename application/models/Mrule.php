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

		function getAllRules()
		{
			$this->db->order_by('TimeUpdate DESC');
			$query = $this->db->get('rules');
			return $query->result_array();
		}

		function addRule($rule){
			$this->db->insert('rules', $rule);
		}

		function getRuleById($id = '')
		{
			$this->db->where('Id', $id);
			$query = $this->db->get('rules');
			return $query->row_array();
		}

		function updateRule($newRule, $id = -1){
			$this->db->where('Id', $id);
			$this->db->update('rules', $newRule);
		}

		function deleteRule($id= -1)
		{
			$this->db->where('Id', $id);
			$this->db->delete('rules');
		}
	}
?>