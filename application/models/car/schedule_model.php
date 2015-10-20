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
	  $this->db->from('car_schedule');
	  $this->db->join('car_info', 'car_schedule.car_id=car_info.car_id');
	  $this->db->where('car_info.driver_id', $account_id);
	  $this->db->where('car_schedule.schedule_date', $schedule_date);
	  $query = $this->db->get();
	  return $query->result();
	 }
	
}


/* End of file booking_model.php */
/* Location: ./application/models/general_model.php */