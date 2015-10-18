<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_model extends CI_Model {	
	
	
	/*function get_fullname_by_id($reg_id)
	{
	$query2 = $this->db->query('SELECT first_name ,middle_name,last_name FROM gramcar_registration');
	$row = $query2->row();
	echo $row->name;	
	}*/
	function get_all_schedule_date_by_services_point_id($services_point_id)
	{
	$this->db->where('services_point_id',$services_point_id);
	$this->db->where('schedule_date >=',date('Y-m-d')); 
	$this->db->order_by('schedule_date', 'asc');		
	return $this->db->get('gramcar_services_point_schedule')->result();	
	}
	
	function save_booking($reg_data_table1)
	{
	$this->db->insert('gramcar_booking',$reg_data_table1);
	//return ($this->db->affected_rows() != 1) ? false : true;
	return mysql_insert_id();
	}
	
	function save_booking_services($reg_data_table2)
	{	
	$this->db->insert('gramcar_booking_for_services',$reg_data_table2);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	function all_booking_count()
	{
	$this->db->where('status',0);    			 // 1= active registration 2=deleted registration
	$query = $this->db->get('gramcar_booking');
	return $query->num_rows();	
	}
	
	
	function get_all_booking_by_limit($limit, $start) {
		$this->db->where('status',0);			// 1= active registration 2=deleted registration
		$this->db->order_by('create_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_booking");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
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
	
	
	function all_booking_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_booking_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	
	function get_all_booking_info_by_id($reg_id)
	{
	$this->db->where('booking_id',$reg_id);
	$query = $this->db->get('gramcar_booking');
	return $query->row();	
	}
	
	
	function get_all_services_by_bookingid($reg_id)
	{
	$this->db->select('*');
	$this->db->where('booking_id',$reg_id);	
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_booking_for_services')->result();	
	}
		
	function update_booking($reg_data_table1,$booking_id)
	{
	$this->db->where('booking_id', $booking_id);	
	$this->db->update('gramcar_booking',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	function update_services_by_booking_for_service_id($reg_data_table1,$booking_for_service_id)
	{
	$this->db->where('booking_for_service_id', $booking_for_service_id);	
	$this->db->update('gramcar_booking_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function set_booking_status($reg_no,$status)
	{
	$this->db->where('booking_id', $reg_no);	
	$this->db->update('gramcar_booking',array('status' => $status));	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function delete_booking($booking_id)
	{
	/*
	$this->db->delete('gramcar_registration', array('registration_no' => $reg_no));	
	$this->db->delete('gramcar_registration_for_services', array('registration_no' => $reg_no));	
	return ($this->db->affected_rows() > 0) ? true : false;
	*/
	/****************** We not delete any record just change the status *********************/
	$this->db->where('booking_id', $booking_id);
	$this->db->update('gramcar_booking',array('status' => 2));	
	
	$this->db->where('booking_id', $booking_id);
	$this->db->update('gramcar_booking_for_services',array('services_status' => 4));
	return ($this->db->affected_rows() > 0) ? true : false;
	}
	
	
	/************* Need to delete   **********/
	function get_all_registration()
	{
	$this->db->order_by('create_date', 'desc');		
	return $this->db->get('gramcar_registration')->result();	
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
	
	
	function add_new_services($reg_data_table1)
	{
	$this->db->insert('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
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