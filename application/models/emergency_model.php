<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emergency_model extends CI_Model {			
	
	function get_charge_calculator_info()
	{
	$this->db->where('id',1); 	 
	$query = $this->db->get('gramcar_charge_calculator_setting');
	return $query->row();
	}
	
	
	function get_all_emergency_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	function all_emergency_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_emergency_registration_count()
	{
	$this->db->where('services_id',6); 	 // 6= emergency
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_emergency_registration_by_limit($limit, $start) {
		$this->db->where('services_id',6); 	 // 6= emergency
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
	
	function get_all_emergency_registration()
	{
	$this->db->where('services_id',5); 	 // 5= Learning
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
	
		

	function save_emergency($reg_data_table1)
	{
	$this->db->insert('gramcar_emergency',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_emergency($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_emergency',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function update_emergency_services_status($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function update_charge_calculator_setting($setting_table)
	{
	$this->db->where('id ', 1);	  // only 1 row with id =1
	$this->db->update('gramcar_charge_calculator_setting',$setting_table);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_emergency_info_by_reg_services_id($reg_services_id)
	{	
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_emergency');
	return $query->row();
	}
	
		
}


/* End of file learning_model.php */
/* Location: ./application/models/learning_model.php */