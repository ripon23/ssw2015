<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blood_grouping_model extends CI_Model {			
	
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_blood_grouping_info_by_reg_services_id($reg_services_id)
	{	
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_blood_grouping');
	return $query->row();
	}
	
	
	function all_blood_grouping_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_blood_grouping_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	function get_all_blood_grouping_registration()
	{
	$this->db->where('services_id',3); 	 // 3= Blood Grouping
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
	
	function get_all_blood_grouping_registration_count()
	{
	$this->db->where('services_id',3); 	 // 3= Blood Grouping
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_blood_grouping_registration_by_limit($limit, $start) {
		$this->db->where('services_id',3); 	 // 3= General Health Check Up 
		$this->db->where('services_status <',3); //3= cancle 4=deleted
		$this->db->order_by('services_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_registration_for_services");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}	

	function save_blood_grouping($reg_data_table1)
	{
	$this->db->insert('gramcar_blood_grouping',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_blood_grouping($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_blood_grouping',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function update_blood_grouping_services_status($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	/*
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
		
	
	
	
	function get_all_registration_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
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
	}*/
	


}


/* End of file blood_grouping_model.php */
/* Location: ./application/models/blood_grouping_model.php */