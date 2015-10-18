<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends CI_Model {	
	
	
	/*function get_fullname_by_id($reg_id)
	{
	$query2 = $this->db->query('SELECT first_name ,middle_name,last_name FROM gramcar_registration');
	$row = $query2->row();
	echo $row->name;	
	}*/
	
	function get_all_registration()
	{
	$this->db->order_by('create_date', 'desc');		
	return $this->db->get('gramcar_registration')->result();	
	}
	
	function all_registration_count()
	{
	$this->db->where('status',1);    			 // 1= active registration 2=deleted registration
	$query = $this->db->get('gramcar_registration');
	return $query->num_rows();	
	}
	
	function all_registration_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	
	function get_all_registration_info_by_id($reg_id)
	{
	$this->db->where('registration_no',$reg_id);
	$query = $this->db->get('gramcar_registration');
	return $query->row();	
	}
	
	
	function get_all_services_by_regid($reg_id)
	{
	$this->db->select('*');
	$this->db->where('registration_no',$reg_id);	
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
		
	
	function get_all_registration_by_limit($limit, $start) {
		$this->db->where('status',1);			// 1= active registration 2=deleted registration
		$this->db->order_by('create_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_registration");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	
	function get_all_registration_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	
	function save_registration($reg_data_table1)
	{
	$this->db->insert('gramcar_registration',$reg_data_table1);
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function save_registration_services($reg_data_table2)
	{	
	$this->db->insert('gramcar_registration_for_services',$reg_data_table2);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function save_registration_payment($reg_data_table2)
	{	
	$this->db->insert('gramcar_registration_payment',$reg_data_table2);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function add_new_services($reg_data_table1)
	{
	$this->db->insert('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	
	function update_registration($reg_data_table1,$reg_no)
	{
	$this->db->where('registration_no', $reg_no);	
	$this->db->update('gramcar_registration',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	function update_services_by_reg_for_service_id($reg_data_table1,$reg_for_service_id)
	{
	$this->db->where('reg_for_service_id', $reg_for_service_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function delete_registration($reg_no)
	{
	/*
	$this->db->delete('gramcar_registration', array('registration_no' => $reg_no));	
	$this->db->delete('gramcar_registration_for_services', array('registration_no' => $reg_no));	
	return ($this->db->affected_rows() > 0) ? true : false;
	*/
	/****************** We not delete any record just change the status *********************/
	$this->db->where('registration_no', $reg_no);
	$this->db->update('gramcar_registration',array('status' => 2));	
	
	$this->db->where('registration_no', $reg_no);
	$this->db->update('gramcar_registration_for_services',array('services_status' => 4));
	return ($this->db->affected_rows() > 0) ? true : false;
	}
	
	function set_registration_status($reg_no,$status)
	{
	$this->db->where('registration_no', $reg_no);	
	$this->db->update('gramcar_registration',array('status' => $status));	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
		
	
	function searchterm_handler($searchterm)
	{
		if($searchterm)
		{
			$this->session->set_userdata('searchterm', $searchterm);
			return $searchterm;
		}
		elseif($this->session->userdata('searchterm'))
		{
			$searchterm = $this->session->userdata('searchterm');
			return $searchterm;
		}
		else
		{
			$searchterm ="";
			return $searchterm;
		}
	}
	
	
	function registration_no_exits($reg_no)
	{
	/*$this->db->select('count(*) registration_no_exits');
	$this->db->from('gramcar_registration');
	$this->db->where('registration_no',$reg_no);
	$result = $this->db->get();
	return $result->row()->registration_no_exits;*/	
		$this->db->where('registration_no',$reg_no);
		$query = $this->db->get('gramcar_registration');
		return $query->num_rows();
	}
	
	
	


}


/* End of file account_model.php */
/* Location: ./application/account/models/farmer_model.php */