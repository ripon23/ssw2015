<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model {
	
	function get_site_name_by_id($s_id)
	{
		$this->db->select('*') ;
		$this->db->from('gramcar_site');	
		$this->db->where('site_id',$s_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			{return $result_set->row()->site_name;}
			
			if($language=='bangla')
			{return $result_set->row()->site_name_bn;}
		}
		else
		{
		return $result_set->row()->site_name;
		}	
	}
	
	function approved_unapproved_payment($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_payment_received',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;	
	}
	
	function payment_wating_for_approval_count()
	{
	$this->db->select('count(*) AS wating_for_approval');
	$this->db->from('gramcar_payment_received');
	$this->db->where('approved_status',0);
	$result = $this->db->get();
	return $result->row()->wating_for_approval;	
	}
	
	function get_payment_received_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT * FROM gramcar_payment_received WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();
	return $row;
	}
	
	function get_registration_payment_received_info_by_reg_id($reg_id)
	{
	$query = $this->db->query('SELECT * FROM gramcar_registration_payment WHERE registration_no='.$reg_id);
	$row = $query->row();
	return $row;
	}
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_reg_services_info_by_reg_services_id($reg_services_id)
	{	
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->row();
	}
		
	function get_all_registration_payment_info_by_id($registration_id)
	{
	$this->db->where('registration_no',$registration_id);
	$query = $this->db->get('gramcar_registration_payment');
	return $query->row();	
	}	
	
	function all_payment_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_payment_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	function get_all_payment_registration()
	{
	$this->db->where('services_id',3); 	 // 3= Blood Grouping
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
	
	function all_registration_count()
	{
	$this->db->where('status',1);    			 // 1= active registration 2=deleted registration
	$query = $this->db->get('gramcar_registration');
	return $query->num_rows();	
	}
	
	function get_all_payment_registration_count()
	{
	//$this->db->where('services_id',3); 	 // All
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
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
	
	function get_all_payment_registration_by_limit($limit, $start) {
		//$this->db->where('services_id',3); 	 // 3= General Health Check Up 
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

	function save_payment($reg_data_table1)
	{
	$this->db->insert('gramcar_payment_received',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function save_registration_payment($reg_data_table1)
	{
	$this->db->insert('gramcar_registration_payment',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_payment($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_payment_received',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function update_registration_payment($reg_data_table1,$registration_id)
	{
	$this->db->where('registration_no', $registration_id);
	$this->db->update('gramcar_registration_payment',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_payment_services_status($reg_data_table1,$reg_services_id)
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


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */