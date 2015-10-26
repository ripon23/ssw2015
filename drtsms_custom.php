<?php
$con = mysql_connect("localhost","gramweb_gramcar","gr@mCar@2015");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("gramweb_gramcar2015", $con);


/*$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("ssw2015", $con);*/


	$todayDate = strtotime(date('Y-m-d')); // current date
	$currentTime = time($todayDate); //Change date into time 
	//$timeAfterSixHour = $todayDate+60*60*13;	//server old
	$timeAfterSixHour = $todayDate;   			// Local
	$entrydate=date("Y-m-d",$timeAfterSixHour);
		
	
	$currentDate = strtotime(date('H:i:s'));
	//$futureDate1 = $currentDate+(60*60*13);	//server old
	//$futureDate2 = $currentDate+(60*60*14);	//server old
	
	$futureDate1 = $currentDate;				// Local
	$futureDate2 = $currentDate+(60*60*1);		// Local
	
	$formatDate1 = date("H:i:s", $futureDate1);
	$formatDate2 = date("H:i:s", $futureDate2);
	//echo $formatDate;
	
	$query="Select * FROM  car_booking INNER JOIN
          car_drt_cost car_drt_cost
       ON (car_booking.booking_id = car_drt_cost.booking_id) WHERE car_booking.sms_notify=0 AND car_booking.status=3 AND car_booking.date_of_booking >='".$entrydate."' AND car_booking.pickup_time > '".$formatDate1."' AND car_booking.pickup_time < '".$formatDate2."'";

	$result=mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$user_info=get_all_table_info_by_id('a3m_account','id', $row['user_id']);	
			if($user_info['phone'])
				{
				$msg="Hi, ".$user_info['username'].", Your pickup time is:".$row['pickup_time']." from ".get_pickup_point_name_from_id($row['start_pickup_point']).". Your fare is ".$row['fare_cost']." TK";	
				echo $msg.", ";
				
				$mydata = array('SSW02301120151012','01974726227',$user_info['phone'],$msg,date('Y-m-d H:i:s'),'SSW');
				$serialized = rawurlencode(serialize($mydata));					
				$apisaid =file_get_contents('http://gramweb.com/gccsmsserver/index.php?smsdata='.$serialized);	
				
				$update_sql="UPDATE car_booking SET sms_notify=1 WHERE booking_id = ".$row['booking_id'];
				mysql_query($update_sql) or die(mysql_error());
				}				
		
		}
	
	/*****************   Schedule booking ***************************/	
	
	//echo date('Y-m-d H:i:s');
	$query="SELECT car_schedule_booking.*,
       car_sdrt_schedule.*,
       car_sdrt_cost.*,
       car_sdrt_schedule.start_time,
       car_sdrt_schedule.schedule_date
  FROM    (   car_schedule_booking car_schedule_booking
           INNER JOIN
              car_sdrt_schedule car_sdrt_schedule
           ON (car_schedule_booking.schedule_id =
                  car_sdrt_schedule.schedule_id))
       INNER JOIN
          car_sdrt_cost car_sdrt_cost
       ON (car_schedule_booking.sbooking_id = car_sdrt_cost.sbooking_id)
 WHERE car_schedule_booking.sms_notify=0 AND car_schedule_booking.booking_status=3 AND (car_sdrt_schedule.start_time > '".$formatDate1."') AND (car_sdrt_schedule.start_time < '".$formatDate2."')
       AND(car_sdrt_schedule.schedule_date >='".$entrydate."')";
	//echo $query."<br>";   
	
	$result=mysql_query($query) or die(mysql_error());	
		while($row = mysql_fetch_array($result))
		{
		$user_info=get_all_table_info_by_id('a3m_account','id', $row['user_id']);	
		//echo "phone=".$user_mobile['phone'];
			if($user_info['phone'])
				{
				$user_pickup_node=get_node_id_from_pickup_point($row['pickup_point']);
				
				$all_node_arrival_time_array=get_all_node_arrival_time_of_a_route($row['route_id'],$row['start_node'],$row['start_time']);
								
				//echo "<pre>";
				//print_r($all_node_arrival_time_array);
				//echo "</pre>";
				
				$pickup_node_array_key=searchForId($user_pickup_node, $all_node_arrival_time_array);
				//echo "Pickup Node:".$user_pickup_node.", Pickup point:".$row['pickup_point'].", Pickup time:". $all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time'];
				$msg="Hi, ".$user_info['username'].", We will pickup you from ".get_pickup_point_name_from_id($row['pickup_point'])." at ".$all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time']." (Approximately). Your fare is ".$row['fare_cost']." TK";	
				echo $msg.", ";
				
				$mydata = array('SSW02301120151012','01974726227',$user_info['phone'],$msg,date('Y-m-d H:i:s'),'SSW');
				$serialized = rawurlencode(serialize($mydata));					
				$apisaid =file_get_contents('http://gramweb.com/gccsmsserver/index.php?smsdata='.$serialized);	
				
				$update_sql="UPDATE car_schedule_booking SET sms_notify=1 WHERE sbooking_id = ".$row['sbooking_id'];
				mysql_query($update_sql) or die(mysql_error());
				}				
		
		}
	
	
	function get_all_node_arrival_time_of_a_route($route_id,$first_passenger_pickup_node,$first_passenger_first_node_arrival_time)
  	{
	$all_node_arrival_time_of_a_route=array();
	$time_to_minus=0;
	$time_to_minus2=0;
	
	$query1="Select node_id, previous_node, distance_previous, time_previous from car_node Where node_id=".$first_passenger_pickup_node;
	$data = mysql_query($query1);
	$row = mysql_fetch_row($data);		
	$temp_previous_node=$row[1];
	$temp_previous_node2=$first_passenger_pickup_node;
	$time_to_minus=$resultSet->$row[3];
	
	$temp_array=array('node_id'=>$first_passenger_pickup_node,'node_arrival_time'=>$first_passenger_first_node_arrival_time);	
	array_push($all_node_arrival_time_of_a_route, $temp_array);	
	
	if($temp_previous_node2)
	{
		
		if($temp_previous_node)
		{
			do{
			$previous_node=	$temp_previous_node;
			$query="Select node_id, previous_node, distance_previous, time_previous from car_node Where node_id=".$previous_node;
			$data = mysql_query($query);
			$row = mysql_fetch_row($data);
	
			$node_arrival_time= strtotime($first_passenger_first_node_arrival_time)-($time_to_minus*60);		
			$time_to_minus=$time_to_minus+($row[3]);
			$temp_previous_node=$row['previous_node'];		
			//echo "id=".$temp_previous_node."<br>";
			$temp_array=array('node_id'=>$row[0], 'node_arrival_time'=>date('H:i:s',$node_arrival_time));	
			array_push($all_node_arrival_time_of_a_route, $temp_array);
			}while($temp_previous_node);
		}
		
		do{
		$node_id=	$temp_previous_node2;
		$query="Select node_id, previous_node, distance_previous, time_previous from car_node Where previous_node=".$node_id;
		$data = mysql_query($query);
		$row = mysql_fetch_row($data);
		$time_previous=$row[3];
		$time_to_minus2=$time_to_minus2+$time_previous;
		$node_arrival_time= strtotime($first_passenger_first_node_arrival_time)+($time_to_minus2*60);	
		$temp_previous_node2=$row[0];
		$temp_array=array('node_id'=>$row[0], 'node_arrival_time'=>date('H:i:s',$node_arrival_time));	
		//echo "id=".$temp_previous_node2."<br>";
		array_push($all_node_arrival_time_of_a_route, $temp_array);
			
			if($temp_previous_node2)
			{
				if(!is_exist_in_a_table('car_node','previous_node',$temp_previous_node2))
				{
				$temp_previous_node2=false;
				}
			}
			
		}while($temp_previous_node2);
		
		
	}	
	return $all_node_arrival_time_of_a_route;   
  }
	
	
	function searchForId($id, $array) {
	   foreach ($array as $key => $val) {
		   if ($val['node_id'] === $id) {
			   return $key;
		   }
	   }
	   return null;
	}
	
	function get_node_id_from_pickup_point($pickup_point_id)
	{
	$query="Select node_id from car_pickup_point WHERE pickup_point_id=".$pickup_point_id;
	$data = mysql_query($query);
	$info= mysql_fetch_row( $data );
	return $info[0];
	}
	
	function is_exist_in_a_table($table_name,$table_field,$table_field_value)
	{
	$query="SELECT * FROM ".$table_name." WHERE ".$table_field." =".$table_field_value;	
	$data = mysql_query($query);
	$num_rows = mysql_num_rows($data);
	if($num_rows>0)
	return true;
	else
	return false;
	}
	
	
	function get_all_table_info_by_id($table_name, $field_name, $id){
	$query="SELECT * FROM ".$table_name." WHERE ".$field_name." =".$id;
	$data = mysql_query($query);
	$info= mysql_fetch_array( $data );
	return $info;
	}
	
	function get_pickup_point_name_from_id($pickup_point_id)
	{
	$query="Select pickup_point_en FROM car_pickup_point WHERE pickup_point_id=".$pickup_point_id;	
	$result= mysql_query($query);
	$pickup_point = mysql_fetch_row($result);
	return $pickup_point[0];
	}	

?>