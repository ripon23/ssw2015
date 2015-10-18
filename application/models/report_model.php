<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model {			
	
	function get_all_reg_services_registration_count()
	{
	$sql="SELECT count(*) AS total_row
  FROM    (   (   (   (   gramcar_registration_for_services gramcar_registration_for_services
                       INNER JOIN
                          gramcar_services gramcar_services
                       ON (gramcar_registration_for_services.services_id =
                              gramcar_services.services_id))
                   INNER JOIN
                      gramcar_registration gramcar_registration
                   ON (gramcar_registration_for_services.registration_no =
                          gramcar_registration.registration_no))
               INNER JOIN
                  gramcar_site gramcar_site
               ON (gramcar_registration_for_services.services_point_id =
                      gramcar_site.site_id))
           LEFT OUTER JOIN
              gramcar_services_package gramcar_services_package
           ON (gramcar_registration_for_services.services_package_id =
                  gramcar_services_package.package_id))
       LEFT OUTER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id)";
	$query = $this->db->query($sql);
	$row = $query->row()->total_row;			
	return $row;	
	
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_reg_services_registration_by_limit($limit, $start) {
		
		$sql="SELECT gramcar_registration_for_services.reg_for_service_id,
       gramcar_registration_for_services.registration_no,
       gramcar_registration.first_name,
       gramcar_registration.middle_name,
       gramcar_registration.last_name,
       gramcar_registration_for_services.services_point_id,
       gramcar_site.site_name,
       gramcar_registration_for_services.services_id,
       gramcar_services.services_name,
       gramcar_registration_for_services.services_package_id,
       gramcar_services_package.package_name,
       gramcar_registration_for_services.services_date,
       gramcar_registration_for_services.services_status,
       gramcar_payment_received.payment_received_date,
       gramcar_payment_received.received_amount,
       gramcar_payment_received.approved_status
  FROM    (   (   (   (   gramcar_registration_for_services gramcar_registration_for_services
                       INNER JOIN
                          gramcar_services gramcar_services
                       ON (gramcar_registration_for_services.services_id =
                              gramcar_services.services_id))
                   INNER JOIN
                      gramcar_registration gramcar_registration
                   ON (gramcar_registration_for_services.registration_no =
                          gramcar_registration.registration_no))
               INNER JOIN
                  gramcar_site gramcar_site
               ON (gramcar_registration_for_services.services_point_id =
                      gramcar_site.site_id))
           LEFT OUTER JOIN
              gramcar_services_package gramcar_services_package
           ON (gramcar_registration_for_services.services_package_id =
                  gramcar_services_package.package_id))
       LEFT OUTER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id) ORDER BY gramcar_registration_for_services.services_date Desc LIMIT $start, $limit";
	   $query = $this->db->query($sql);
		//$this->db->order_by('services_date', 'desc');        
		//$this->db->limit($limit, $start);		
        //$query = $this->db->get("gramcar_registration_for_services");
 		
		/*$this->load->dbutil();	
		$config = array (
                  'root'    => 'root',
                  'element' => 'element',
                  'newline' => "\n",
                  'tab'    => "\t"
                );
		echo $this->dbutil->xml_from_result($query, $config); */

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function all_report_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	
	function get_all_report_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	function get_all_report_querystring_for_excel($searchterm)
	{
	$resultSet = $this->db->query($searchterm);
	return $resultSet->result();			
	}
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_total_amount_received_without_searchterm()
	{
	$sql="Select sum(gramcar_payment_received.received_amount) as total_services_numver  
  FROM    (   (   (   (   gramcar_registration_for_services gramcar_registration_for_services
                       INNER JOIN
                          gramcar_services gramcar_services
                       ON (gramcar_registration_for_services.services_id =
                              gramcar_services.services_id))
                   INNER JOIN
                      gramcar_registration gramcar_registration
                   ON (gramcar_registration_for_services.registration_no =
                          gramcar_registration.registration_no))
               INNER JOIN
                  gramcar_site gramcar_site
               ON (gramcar_registration_for_services.services_point_id =
                      gramcar_site.site_id))
           LEFT OUTER JOIN
              gramcar_services_package gramcar_services_package
           ON (gramcar_registration_for_services.services_package_id =
                  gramcar_services_package.package_id))
       LEFT OUTER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id)";
	$query = $this->db->query($sql);	
	$result = $query->row();
	return $result->total_services_numver;	
	
	}
	
	function get_total_amount_received($searchterm)
	{
	//echo $searchterm."<br/><br/>";
	$str = $searchterm;
	$toFind = "FROM";	
	$pos = strpos($str,$toFind);
	$from= substr($str,$pos,strlen($str));	
	$query="Select sum(gramcar_payment_received.received_amount) as total_services_numver ".$from;
	$query1 = $this->db->query($query);
	$result = $query1->row();
	return $result->total_services_numver;			
	}
	
	
	function get_all_services()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_services');			
		$this->db->where('services_status',1);
		$this->db->order_by('services_id', 'asc');			
		return $this->db->get()->result();		
	}

	function get_package_number_from_service_id($service_id)
	{
		$this->db->where('services_id', $service_id);
		$this->db->where('package_status', 1);
		$this->db->order_by('package_id', 'asc');		
		$query = $this->db->get('gramcar_services_package');
		return $query->num_rows();	
	}
	
	
	function get_all_services_package_by_id($services_id)
	{
	$this->db->where('services_id', $services_id);
	$this->db->where('package_status', 1);
	$this->db->order_by('package_id', 'asc');		
	return $this->db->get('gramcar_services_package')->result();		
	}
	
	
	function createDateRangeArray($strDateFrom,$strDateTo)
	{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
	}
	
	function is_exists_services_point_in_given_date($site_id, $schedule_date)
	{
	$sql="SELECT gramcar_site.site_name as services_point_name
       FROM    gramcar_services_point_schedule gramcar_services_point_schedule
       INNER JOIN
          gramcar_site gramcar_site
       ON (gramcar_services_point_schedule.services_point_id =
              gramcar_site.site_id)
 WHERE (gramcar_services_point_schedule.schedule_date = '".$schedule_date."')
       AND(gramcar_services_point_schedule.site_id = ".$site_id.")";
	   	$query1 = $this->db->query($sql);
		$result = $query1->row();
		if($result)
	 	return true;	
		else
		return false;	
	}
	
	function get_services_point_name($site_id, $schedule_date)
	{
		$sql="SELECT gramcar_site.site_name as services_point_name
       FROM    gramcar_services_point_schedule gramcar_services_point_schedule
       INNER JOIN
          gramcar_site gramcar_site
       ON (gramcar_services_point_schedule.services_point_id =
              gramcar_site.site_id)
 WHERE (gramcar_services_point_schedule.schedule_date = '".$schedule_date."')
       AND(gramcar_services_point_schedule.site_id = ".$site_id.")";
	   	$query1 = $this->db->query($sql);
		$result = $query1->row();
		return $result->services_point_name;	
		
	}
	
	function get_booking_number($site_id, $schedule_date)
	{
	$sql="SELECT count(*) AS number_of_booking
  FROM gramcar_booking_for_services gramcar_booking_for_services
 WHERE gramcar_booking_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")) 
 AND (DATE(gramcar_booking_for_services.create_date) = '".$schedule_date."') ";
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->number_of_booking;
	}
	
	
	function get_number_of_registration($site_id, $schedule_date)
	{
	$sql="SELECT count(*) AS number_of_reg
  FROM  gramcar_registration_for_services  gramcar_registration_for_services
 WHERE  gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")) 
 AND (DATE(gramcar_registration_for_services.create_date) = '".$schedule_date."') ";
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->number_of_reg;
	}
	
	function get_registration_payment($site_id, $schedule_date)
	{
	$sql="SELECT SUM(received_amount)   AS total_amount   
  FROM    gramcar_registration_payment gramcar_registration_payment
       INNER JOIN
          gramcar_registration gramcar_registration
       ON (gramcar_registration_payment.registration_no =
              gramcar_registration.registration_no)
 WHERE (DATE(gramcar_registration_payment.payment_received_date) =
           '".$schedule_date."') AND(gramcar_registration.site_id = ".$site_id.")";
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->total_amount;
	}
	
	
	
	function get_revenue_daily_services_in_package($site_id, $schedule_date, $services_id, $package_id)
	{
	/*if($services_id==1)
	{$table_name='gramcar_college_bus'; $field_name='payment_received_date';}
	else if($services_id==2)	
	{$table_name='gramcar_health_checkup'; $field_name='payment_received_date';}
	else if($services_id==3)	
	{$table_name='gramcar_blood_grouping'; $field_name='payment_received_date';}
	else if($services_id==4)	
	{$table_name='gramcar_social_goods_order'; $field_name='payment_received_date';}
	else if($services_id==5)	
	{$table_name='gramcar_learning'; $field_name='payment_received_date';}
	else if($services_id==6)	
	{$table_name='gramcar_emergency'; $field_name='payment_received_date';}*/
	
	if($services_id==4)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) = '".$schedule_date."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) = '".$schedule_date."'";	
	}
	else
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_package_id = ".$package_id.")
       AND(gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) = '".$schedule_date."'";		
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->total_amount;   
	}
	
	
	function get_revenue_date_range_services_in_package($site_id, $date_from, $date_to, $services_id, $package_id)
	{
	
	
	if($services_id==4)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_package_id = ".$package_id.")
       AND(gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";		
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->total_amount;   
	}
	
	
	
	function get_revenue_date_range_services($site_id, $date_from, $date_to, $services_id)
	{
	
	
	if($services_id==4)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else
	{
	$sql="SELECT sum(received_amount) AS total_amount
  FROM    gramcar_payment_received gramcar_payment_received
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_payment_received.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE(gramcar_payment_received.payment_received_date) BETWEEN '".$date_from."' AND '".$date_to."'";		
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	if($result->total_amount=='')
	return "0";
	else
	return $result->total_amount;   
	}
	
	function get_count_daily_services_in_package($site_id, $schedule_date, $services_id, $package_id)
	{
	if($services_id==1)
	{$table_name='gramcar_college_bus'; $field_name='services_receive_date';}
	else if($services_id==2)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==3)	
	{$table_name='gramcar_blood_grouping'; $field_name='checkup_date';}
	else if($services_id==4)	
	{$table_name='gramcar_social_goods_order'; $field_name='order_time';}
	else if($services_id==5)	
	{$table_name='gramcar_learning'; $field_name='services_receive_date';}
	else if($services_id==6)	
	{$table_name='gramcar_emergency'; $field_name='services_receive_date';}
	else if($services_id==8)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==9)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	
	if($services_id==4)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) = '".$schedule_date."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) = '".$schedule_date."'";	
	}
	else
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_package_id = ".$package_id.")
       AND(gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) = '".$schedule_date."'";		
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->number_of_services;   
	}


function get_count_date_range_services_in_package($site_id, $date_from, $date_to,$services_id, $package_id)
	{
	if($services_id==1)
	{$table_name='gramcar_college_bus'; $field_name='services_receive_date';}
	else if($services_id==2)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==3)	
	{$table_name='gramcar_blood_grouping'; $field_name='checkup_date';}
	else if($services_id==4)	
	{$table_name='gramcar_social_goods_order'; $field_name='order_time';}
	else if($services_id==5)	
	{$table_name='gramcar_learning'; $field_name='services_receive_date';}
	else if($services_id==6)	
	{$table_name='gramcar_emergency'; $field_name='services_receive_date';}
	else if($services_id==8)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==9)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	
	if($services_id==4)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_package_id = ".$package_id.")
       AND(gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name)BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->number_of_services;   
	}

function get_count_date_range_services_in_package_sum($site_id, $date_from, $date_to,$services_id, $package_id)
	{
	if($services_id==1)
	{$table_name='gramcar_college_bus'; $field_name='services_receive_date';}
	else if($services_id==2)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==3)	
	{$table_name='gramcar_blood_grouping'; $field_name='checkup_date';}
	else if($services_id==4)	
	{$table_name='gramcar_social_goods_order'; $field_name='order_time';}
	else if($services_id==5)	
	{$table_name='gramcar_learning'; $field_name='services_receive_date';}
	else if($services_id==6)	
	{$table_name='gramcar_emergency'; $field_name='services_receive_date';}
	else if($services_id==8)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	else if($services_id==9)	
	{$table_name='gramcar_health_checkup'; $field_name='checkup_date';}
	
	if($services_id==4)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else if($services_id==6)
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE    (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name) BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	else
	{
	$sql="SELECT count(*) AS number_of_services
  FROM    $table_name $table_name
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON ($table_name.reg_for_service_id =
              gramcar_registration_for_services.reg_for_service_id)
 WHERE     (gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_id = ".$services_id.")
       AND(gramcar_registration_for_services.services_point_id IN (SELECT gramcar_site.site_id
  FROM gramcar_site gramcar_site
 WHERE (gramcar_site.site_type = 'SP') AND (gramcar_site.site_parent_id =".$site_id.")))
       AND DATE($table_name.$field_name)BETWEEN '".$date_from."' AND '".$date_to."'";	
	}
	
	$query1 = $this->db->query($sql);
	$result = $query1->row();
	return $result->number_of_services;   
	}



	function get_all_expense_by_site_and_date_range($site_id, $strDateFrom,$strDateTo)
	{
	$sql="SELECT gramcar_expenses.*,
       gramcar_expenses.expense_date,
       gramcar_expenses.expense_site_id
  FROM gramcar_expenses gramcar_expenses
 WHERE (gramcar_expenses.expense_date BETWEEN '".$strDateFrom."' AND '".$strDateTo."')
       AND(gramcar_expenses.expense_site_id = ".$site_id.") Order By expense_date";
	$query1 = $this->db->query($sql);
	return $query1->result();;	
	
	}
	
	/*************** Need to delete following **************/
	
	
		
}


/* End of file learning_model.php */
/* Location: ./application/models/learning_model.php */