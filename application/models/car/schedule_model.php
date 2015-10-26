<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_model extends CI_Model {		
	
	function get_car_schedule($schedule_date = NULL, $car_id = NULL, $stime = NULL, $etime = NULL)
	{
		$query = $this->db->query("SELECT * FROM `car_schedule` WHERE car_id = $car_id  and `schedule_date` = '$schedule_date' and ((`start_time` <='$stime' and `end_time` >= '$stime') OR (`start_time` <='$etime' and `end_time` >= '$etime'))");
		
		return $query->result();
	}

	function check_schedule_update($schedule_date = NULL, $car_id = NULL, $stime = NULL, $etime = NULL, $schedule_id = NULL){
		$query = $this->db->query("SELECT * FROM `car_schedule` WHERE `schedule_id` != $schedule_id and car_id = $car_id  and `schedule_date` = '$schedule_date' and ((`start_time` <='$stime' and `end_time` >= '$stime') OR (`start_time` <='$etime' and `end_time` >= '$etime'))");
		
		return $query->result();
	}	
	 
	 
	 public function get_drt_driver_schedule($account_id, $schedule_date)
	 {
	  $this->db->select('car_schedule.schedule_id, car_schedule.start_time, car_schedule.end_time, car_schedule.schedule_date, car_info.licence_no');
	  $this->db->from('car_schedule');
	  $this->db->join('car_info', 'car_schedule.car_id=car_info.car_id');
	  $this->db->where('car_info.driver_id', $account_id);
	  $this->db->where('car_schedule.schedule_date', $schedule_date);
	  $this->db->order_by("car_schedule.start_time", "asc");
	  $query = $this->db->get();
	  return $query->result();
	 }
	
	 public function get_sche_drt_driver_schedule($account_id, $schedule_date)
	 {
	  $this->db->select('car_sdrt_schedule.schedule_id, car_sdrt_schedule.route_id, car_sdrt_schedule.start_time, car_sdrt_schedule.arrival_time, car_sdrt_schedule.start_node, car_sdrt_schedule.destination_node, car_sdrt_schedule.schedule_date, car_info.licence_no, car_route.route_name_en');
	  $this->db->from('car_sdrt_schedule');
	  $this->db->join('car_info', 'car_sdrt_schedule.car_id=car_info.car_id');
	  $this->db->join('car_route', 'car_sdrt_schedule.route_id=car_route.route_id');
	  $this->db->where('car_info.driver_id', $account_id);
	  $this->db->where('car_sdrt_schedule.schedule_date', $schedule_date);
	  $this->db->order_by("car_sdrt_schedule.start_time", "asc");
	  $query = $this->db->get();
	  return $query->result();
	 }
	
	 public function get_on_drt_trip_summary($schedule_id)
	 {
	  // $this->db->select('car_sdrt_schedule.schedule_id, car_sdrt_schedule.start_time, car_sdrt_schedule.arrival_time, car_sdrt_schedule.start_node, car_sdrt_schedule.destination_node, car_sdrt_schedule.schedule_date, car_info.licence_no');
	  $this->db->from('car_booking');
	  $this->db->join('car_info', 'car_sdrt_schedule.car_id=car_info.car_id');
	  $this->db->where('car_booking.schedule_id', $schedule_id);
	  $this->db->where('car_sdrt_schedule.schedule_date', $schedule_date);
	  $this->db->order_by("car_sdrt_schedule.start_time", "asc");
	  $query = $this->db->get();
	  return $query->result();
	 }
	
	 public function get_s_drt_trip_summary($schedule_id)
	 {
	  // $this->db->select('car_sdrt_schedule.schedule_id, car_sdrt_schedule.start_time, car_sdrt_schedule.arrival_time, car_sdrt_schedule.start_node, car_sdrt_schedule.destination_node, car_sdrt_schedule.schedule_date, car_info.licence_no');
	  $this->db->from('car_schedule_booking');
	  $this->db->where('car_schedule_booking.schedule_id', $schedule_id);
	  $this->db->where('car_sdrt_schedule.schedule_date', $schedule_date);
	  $this->db->order_by("car_sdrt_schedule.start_time", "asc");
	  $query = $this->db->get();
	  return $query->result();
	 }
	 
	 
	
}


/* End of file booking_model.php */
/* Location: ./application/models/general_model.php */