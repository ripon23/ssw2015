<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class College_bus_model extends CI_Model {			
	
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_college_bus_info_by_reg_services_id($reg_services_id)
	{	
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_college_bus');
	return $query->row();
	}
	
	
	function all_college_bus_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_services_type_name_by_id($services_type_id)
	{
	$this->db->select('*');
	$this->db->from('gramcar_college_bus_type');			
	$this->db->where('college_bus_type_id',$services_type_id);		
	$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->type_name;
			
			if($language=='bangla')
			return $result_set->row()->type_name_bn;
		}
		else
		{
		return $result_set->row()->type_name;
		}	
	}
	
	
	
	function get_all_college_bus_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	function get_all_college_bus_registration()
	{
	$this->db->where('services_id',1); 	 // 1= college_bus
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
	
	function get_all_college_bus_registration_count()
	{
	$this->db->where('services_id',1); 	 // 1= college_bus
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_college_bus_registration_by_limit($limit, $start) {
		$this->db->where('services_id',1); 	 // 1= College Bus
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

	function reg_services_id_already_exits($reg_services_id)
	{
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_college_bus');
	if ($query->num_rows() > 0) {
		return false;
		}
	else
		{
		return true;
		}
		
	}

	function save_college_bus($reg_data_table1)
	{
	$this->db->insert('gramcar_college_bus',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_college_bus($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_college_bus',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function update_college_bus_services_status($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	


}


/* End of file college_bus_model.php */
/* Location: ./application/models/college_bus_model.php */