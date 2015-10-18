<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services_point_schedule_model extends CI_Model {	
		
	function save_services_point_schedule($reg_data_table1)
	{
	$this->db->insert('gramcar_services_point_schedule',$reg_data_table1);
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function check_allready_booked($site_id,$book_date)
	{
	$this->db->where('site_id ',$site_id);  
	$this->db->where('schedule_date  ',$book_date);  
	$query = $this->db->get('gramcar_services_point_schedule');
	return $query->num_rows();	
	}
	
	
	public function is_event_date($event_date)
	{
	$this->db->where('schedule_date',$event_date);  
	$query = $this->db->get('gramcar_services_point_schedule');
	return ($query->num_rows() >0) ? true : false;
	}
	
	public function has_a_event_in_the_date($event_date)
	{
		
		
		$sql="SELECT  gramcar_site.site_name as services_point_name, gramcar_services_point_schedule.services_point_id as services_point_id
  FROM    gramcar_services_point_schedule gramcar_services_point_schedule
       INNER JOIN
          gramcar_site gramcar_site
       ON (gramcar_services_point_schedule.services_point_id =
              gramcar_site.site_id)
 WHERE (gramcar_services_point_schedule.schedule_date = '".$event_date."')";		
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