<?php
$con = mysql_connect("ssw2014.db.4572704.hostedresource.com","ssw2014","GramCar@2014");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("ssw2014", $con);


/*$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("ssw2015", $con);
*/
	$todayDate = strtotime(date('Y-m-d'));// current date
	$currentTime = time($todayDate); //Change date into time 
	$timeAfterSixHour = $todayDate+60*60*13;
	$entrydate=date("Y-m-d",$timeAfterSixHour);
		
	
	$currentDate = strtotime(date('H:i:s'));
	$futureDate1 = $currentDate+(60*60*13);
	$futureDate2 = $currentDate+(60*60*15);
	
	$formatDate1 = date("H:i:s", $futureDate1);
	$formatDate2 = date("H:i:s", $futureDate2);
	//echo $formatDate;
	
	$query="Select * FROM  car_booking INNER JOIN
          car_drt_cost car_drt_cost
       ON (car_booking.booking_id = car_drt_cost.booking_id) WHERE car_booking.sms_notify=0 AND car_booking.status=3 AND car_booking.date_of_booking >='".$entrydate."' AND car_booking.pickup_time > '".$formatDate1."' AND car_booking.pickup_time < '".$formatDate2."'";
	//echo $query."<br>";
	//$query="Select * FROM  car_booking WHERE sms_notify=0 AND status=3 AND booking_id =28";
	$result=mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$user_info=get_all_table_info_by_id('a3m_account','id', $row['user_id']);	
		//echo "phone=".$user_mobile['phone'];
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