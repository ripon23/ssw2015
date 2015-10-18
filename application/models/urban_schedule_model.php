<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Urban_schedule_model extends CI_Model {	
		
	
	
	
	public function have_urban_health_schedule($event_date)
	{
	$this->db->where('schedule_date',$event_date);  
	$query = $this->db->get('gramcar_urban_health_schedule');
	return ($query->num_rows() >0) ? true : false;
	}
	
	
	public function have_urban_health_schedule_sp_id($event_date,$site_id,$sp_id)
	{
	$where = '(schedule_date="'.$event_date.'" AND  schedule_type = 1) AND ( 10am_12pm="office_day,'.$site_id.','.$sp_id.'" OR 12pm_13pm="office_day,'.$site_id.','.$sp_id.'" OR 13pm_14pm="office_day,'.$site_id.','.$sp_id.'" OR 14pm_15pm="office_day,'.$site_id.','.$sp_id.'" OR 15pm_17pm="office_day,'.$site_id.','.$sp_id.'")';
    $this->db->where($where);	   
	$query = $this->db->get('gramcar_urban_health_schedule');
	//echo $this->db->last_query();
	return ($query->num_rows() >0) ? true : false;
	}
	
	
	public function have_in_the_slot($searchterm)
	{	
	$result = $this->db->query($searchterm);
	return ($result->num_rows() >0) ? true : false;
	}
	
	public function has_a_urban_health_schedule_in_the_date($event_date)
	{				
		$sql="SELECT * FROM gramcar_urban_health_schedule WHERE (gramcar_urban_health_schedule.schedule_date = '".$event_date."')";		
		$query = $this->db->query($sql);	
		return $query->result();		
	}
	
	public function remove_services_point_schedule($services_point_id, $event_schedule)
	{
	$this->db->where('services_point_id', $services_point_id);
	$this->db->where('schedule_date', $event_schedule);
	$this->db->delete('gramcar_services_point_schedule');		
	}
	
}


/* End of file account_model.php */
/* Location: ./application/models/services_point_schedule_model.php */