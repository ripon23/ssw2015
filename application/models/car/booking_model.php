<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_model extends CI_Model {		
	
	function get_car_schedule($date = NULL, $time = NULL)
	{
		$this->db->select('car_schedule.schedule_id,
  			car_schedule.start_time,
  			car_schedule.end_time, 
  			car_schedule.schedule_date');
  		$this->db->from('car_schedule');
  		$this->db->join('car_info', 'car_schedule.car_id = car_info.car_id');
  		$this->db->where('car_schedule.schedule_date', $date);
  		$this->db->where('car_schedule.start_time', $date);
  		$this->db->where('car_schedule.start_time', $date);
		return $this->db->get()->row();
	}

  function get_schedule($schedule_date = NULL, $stime = NULL, $etime = NULL)
  {
    $this->db->where('schedule_date', $schedule_date);
    $this->db->where('start_time <=', $stime);
    $this->db->where('end_time >=', $etime);
    return $this->db->get('car_schedule')->row();
    // $query = $this->db->query("SELECT * FROM `car_schedule` WHERE `schedule_date` = '$schedule_date' and ((`start_time` <='$stime' and `end_time` >= '$stime') OR (`start_time` <='$etime' and `end_time` >= '$etime'))");
    // return $query->row();
  }

  function check_this_booking($stime = NULL, $etime = NULL){
    $this->db->where('pickup_time <=', $stime);
    $this->db->where('arrival_time >=', $etime);
    return $this->db->get('car_booking')->row();
  }

  function get_booking($schedule_id = NULL)
  {
    $this->db->where('schedule_id', $schedule_id);
    return $this->db->get('car_booking')->row();
  }

  function get_schedule_advance_delay($schedule_date = NULL, $stime = NULL, $etime = NULL)
  {
    $query = $this->db->query("SELECT * FROM `car_schedule` WHERE `schedule_date` = '$schedule_date' and ((`start_time` <='$stime' and `end_time` >= '$stime') OR (`start_time` <='$etime' and `end_time` >= '$etime'))");
    return $query->row();
  }

  function get_node_route($table, $select = NULL, $where = NULL){
    if ($where!==NULL) {
      $this->db->where($where);
    }
    if ($select !== NULL) {
      $this->db->select($select);
    }
    return $this->db->get($table)->row();
  }
  
  
  function get_available_seat_in_a_car($schedule_id, $car_id)
  {
	$sql1="select no_of_set FROM car_info Where car_id=".$car_id;
	$resultSet = $this->db->query($sql1);
	$car_no_of_set=$resultSet->row()->no_of_set;
	
	$sql2="SELECT SUM(no_of_seat) as num_of_row FROM `car_schedule_booking` WHERE schedule_id=".$schedule_id;
	$resultSet = $this->db->query($sql2);
	$booked_seat=$resultSet->row()->num_of_row;  
	
	$available_seat=$car_no_of_set-$booked_seat;
	return $available_seat;
	
  }
  
  function get_car_name($car_id)
  {
	$sql1="select model, brand, licence_no FROM car_info Where car_id=".$car_id;
	$resultSet = $this->db->query($sql1);
	return $resultSet->row()->model."-".$resultSet->row()->brand."-".$resultSet->row()->licence_no;	
  }
  
  
  
  
  function get_all_node_arrival_time_of_a_route($route_id,$first_passenger_pickup_node,$first_passenger_first_node_arrival_time)
  {
	$all_node_arrival_time_of_a_route=array();
	$time_to_minus=0;
	$time_to_minus2=0;
	
	$query1="Select node_id, previous_node, distance_previous, time_previous from car_node Where node_id=".$first_passenger_pickup_node;
	$resultSet = $this->db->query($query1);
	$temp_previous_node=$resultSet->row()->previous_node;
	$temp_previous_node2=$first_passenger_pickup_node;
	$time_to_minus=$resultSet->row()->time_previous;
	
	$temp_array=array('node_id'=>$first_passenger_pickup_node,'node_arrival_time'=>$first_passenger_first_node_arrival_time);	
	array_push($all_node_arrival_time_of_a_route, $temp_array);	
	
	if($temp_previous_node2)
	{
		
		if($temp_previous_node)
		{
			do{
			$previous_node=	$temp_previous_node;
			$query="Select node_id, previous_node, distance_previous, time_previous from car_node Where node_id=".$previous_node;
			$resultSet = $this->db->query($query);
			$node_arrival_time= strtotime($first_passenger_first_node_arrival_time)-($time_to_minus*60);		
			$time_to_minus=$time_to_minus+($resultSet->row()->time_previous);
			$temp_previous_node=$resultSet->row()->previous_node;		
			//echo "id=".$temp_previous_node."<br>";
			$temp_array=array('node_id'=>$resultSet->row()->node_id, 'node_arrival_time'=>date('H:i:s',$node_arrival_time));	
			array_push($all_node_arrival_time_of_a_route, $temp_array);
			}while($temp_previous_node);
		}
		
		do{
		$node_id=	$temp_previous_node2;
		$query="Select node_id, previous_node, distance_previous, time_previous from car_node Where previous_node=".$node_id;
		$resultSet = $this->db->query($query);
		$time_previous=$resultSet->row()->time_previous;
		$time_to_minus2=$time_to_minus2+$time_previous;
		$node_arrival_time= strtotime($first_passenger_first_node_arrival_time)+($time_to_minus2*60);	
		$temp_previous_node2=$resultSet->row()->node_id;				
		$temp_array=array('node_id'=>$resultSet->row()->node_id, 'node_arrival_time'=>date('H:i:s',$node_arrival_time));	
		//echo "id=".$temp_previous_node2."<br>";
		array_push($all_node_arrival_time_of_a_route, $temp_array);
			if(!$this->general->is_exist_in_a_table('car_node','previous_node',$temp_previous_node2))
			{
			$temp_previous_node2=false;
			}
			
		}while($temp_previous_node2);
		
		
	}	
	return $all_node_arrival_time_of_a_route;   
  }
  
  function get_number_of_passenger_in_a_car($car_id,$booking_date)
  {
	$query="SELECT SUM(no_of_set) as booked_seat FROM `car_booking` WHERE car_id=".$car_id." AND date_of_booking ='".$booking_date."' AND status=3";
    $resultSet = $this->db->query($query);
	return $resultSet->row()->booked_seat;
  }
  
  function get_car_capacity($car_id)
  {
	$this->db->select('no_of_set') ;
	$this->db->from('car_info');	
	$this->db->where('car_id',$car_id);
	$result_set = $this->db->get();  
	return $result_set->row()->no_of_set;
  }
  
  
  function get_node_name_from_id($node_id)
  {
	 $this->db->select('*') ;
		$this->db->from('car_node');	
		$this->db->where('node_id',$node_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->node_name_en;
			
			if($language=='bangla')
			return $result_set->row()->node_name_bn;
		}
		else
		{
		return $result_set->row()->node_name_en;
		} 
  }
  
  function get_all_pickup_point_in_a_route($route_id)
  {
	$sql="SELECT * FROM car_pickup_point WHERE node_id IN(SELECT node_id FROM car_node WHERE route_id=".$route_id.") ORDER BY pickup_point_en";
	$resultSet = $this->db->query($sql);
	return $resultSet->result();	
  }
  
  
  function get_pickup_point_name_from_id($pickup_point_id)
  {
	 $this->db->select('*') ;
		$this->db->from('car_pickup_point');	
		$this->db->where('pickup_point_id',$pickup_point_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->pickup_point_en;
			
			if($language=='bangla')
			return $result_set->row()->pickup_point_bn;
		}
		else
		{
		return $result_set->row()->pickup_point_en;
		} 
  }
  
  function get_route_name_from_id($route_id)
  {
	 $this->db->select('*') ;
		$this->db->from('car_route');	
		$this->db->where('route_id',$route_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->route_name_en;
			
			if($language=='bangla')
			return $result_set->row()->route_name_bn;
		}
		else
		{
		return $result_set->row()->route_name_en;
		} 
  }
  
  
  function get_list_view_with_date_range($table_name=NULL, $field_name=NULL, $id=NULL, $select=NULL, $order_by=NULL, $asc_or_desc=NULL, $start=NULL, $limit=NULL, $sdate = NULL, $edate = NULL, $user_id = NULL)
 {  
  if ($select!=NULL):
  $this->db->select($select);
  endif;
  if($order_by!=NULL && $asc_or_desc!=NULL):
   $this->db->order_by($order_by, $asc_or_desc);
  endif;
  if($field_name!=NULL):
   $this->db->where($field_name);
  endif;
  if ($user_id!==NULL) :
   $this->db->where($user_id);
  endif;
  if ($sdate!==NULL) {
   $this->db->where('date_of_booking >=',$sdate);
   $this->db->where('date_of_booking <=',$edate);
  }
  if($limit!=NULL):
   $this->db->limit($limit, $start);
  endif;

  $query = $this->db->get($table_name);
  //if($query->num_rows()>0):
  return $query->result();
 }
 
 
 public function get_driver_wise_booking($driver_id)
 {
  $this->db->select('car_booking.booking_id, car_booking.user_id, a3m_account_details.fullname, a3m_account.username, car_availed_actual_time.booking_id as attendance');
  $this->db->from('car_booking');
  $this->db->join('car_info', 'car_booking.car_id=car_info.car_id');
  $this->db->join('a3m_account_details', 'car_booking.user_id = a3m_account_details.account_id');
  $this->db->join('a3m_account', 'car_booking.user_id = a3m_account.id');
  $this->db->join('car_availed_actual_time', 'car_booking.booking_id = car_availed_actual_time.booking_id', 'left');
  $this->db->where('car_info.driver_id', $driver_id);
  $this->db->where('car_booking.date_of_booking', date('Y-m-d', now()));
  $this->db->where_in('status',3);
  $query = $this->db->get();
  return $query->result();
 }
 
 
 public function get_all_node_of_a_route($route_id)
 {
	$all_node_of_a_route=array();
	 
	$query1="Select node_id, route_id, previous_node, node_name_en, node_name_bn  FROM  car_node WHERE  previous_node IS NULL AND route_id=".$route_id;
	$resultSet = $this->db->query($query1);
	$temp_previous_node2=$resultSet->row()->node_id;
	
	$temp_array=array('node_id'=>$temp_previous_node2,'node_name_en'=>$resultSet->row()->node_name_en,'node_name_bn'=>$resultSet->row()->node_name_bn );	
	array_push($all_node_of_a_route, $temp_array);
	
	do{
		$node_id=	$temp_previous_node2;
		$query2="Select node_id, previous_node, node_name_en, node_name_bn from car_node Where previous_node=".$node_id;
		$resultSet = $this->db->query($query2);
		$temp_previous_node2=$resultSet->row()->node_id;				
		
		$temp_array=array('node_id'=>$resultSet->row()->node_id, 'node_name_en'=>$resultSet->row()->node_name_en,'node_name_bn'=>$resultSet->row()->node_name_bn );	
		array_push($all_node_of_a_route, $temp_array);
		
			if(!$this->general->is_exist_in_a_table('car_node','previous_node',$temp_previous_node2))
			{
			$temp_previous_node2=false;
			}
			
		}while($temp_previous_node2);
	
	 
	return  $all_node_of_a_route;
 }
 
  
}


/* End of file booking_model.php */
/* Location: ./application/models/general_model.php */