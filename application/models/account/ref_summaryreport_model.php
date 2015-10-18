<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_summaryreport_model extends CI_Model {


	// --------------------------------------------------------------------

	/**
	 * Get all ref region
	 *
	 * @access public
	 * @return object
	 */
	function get_all_region()
	{
		$this->db->order_by('region_id', 'asc');
		return $this->db->get('tbl_region')->result();
	}
	
	
	function get_all_district_by_region_and_lid($lid,$ltype)
	{
		if($ltype==1) ////1= region
		{
		$sql="SELECT tbl_region_location_map.lid,
		   location.l_id,
		   location.l_name,
		   location.l_name_bn,
		   tbl_region_location_map.region_id
	  FROM    tbl_region_location_map tbl_region_location_map
		   INNER JOIN
			  location location
		   ON (tbl_region_location_map.lid = location.l_id)
	 WHERE (tbl_region_location_map.region_id = $lid)
	ORDER BY location.l_name ASC";	
		}
		else
		{
		$sql="SELECT * FROM location WHERE l_sub_main= $lid ORDER BY l_name asc";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->result();			
	}
	
	
	function total_farmer_number_in_region($lid,$season_id,$ltype)
	{	
	if($ltype==1) //$ltype=1 means region
	{
	$sql="SELECT count(*) as total_farmer
  FROM    tbl_region_location_map tbl_region_location_map
       INNER JOIN
          season_wise_member_info season_wise_member_info
       ON (tbl_region_location_map.lid = season_wise_member_info.dt_id)
 WHERE (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)";
	}
	else if($ltype==2) //$ltype=2 means district
	{
	$sql="SELECT count(*) as total_farmer  FROM season_wise_member_info WHERE season_wise_member_info.dt_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
	}
	else if($ltype==3) //$ltype=2 means Upazila
	{
	$sql="SELECT count(*) as total_farmer  FROM season_wise_member_info WHERE season_wise_member_info.up_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
	}
	else if($ltype==4) //$ltype=2 means union
	{
	$sql="SELECT count(*) as total_farmer  FROM season_wise_member_info WHERE season_wise_member_info.union_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
	}
	
	
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_farmer;
	}
	
	function total_area_in_the_region($lid,$season_id,$ltype)
	{
		if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    tbl_region_location_map tbl_region_location_map
       INNER JOIN
          season_wise_member_info season_wise_member_info
       ON (tbl_region_location_map.lid = season_wise_member_info.dt_id)
 WHERE (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)";	
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor FROM season_wise_member_info WHERE season_wise_member_info.dt_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor FROM season_wise_member_info WHERE season_wise_member_info.up_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor FROM season_wise_member_info WHERE season_wise_member_info.union_id = $lid AND(season_wise_member_info.season_id = $season_id)";	
		}
		
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor;
	}
	
	function get_baseline_fertilizer_info($lid,$season_id,$ltype)
	{
		if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as baseline_fertilizer_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as baseline_fertilizer_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as baseline_fertilizer_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as baseline_fertilizer_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->baseline_fertilizer_farmer;   
	}
	
	function total_baseline_fertilizer_area($lid,$season_id,$ltype)
	{
		if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.base_fertilizer_date IS NOT NULL) AND(tbl_growth_condition.base_fertilizer_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor;   
		
	}
	
	function get_line_sowing_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 	
	}
	
	function total_line_sowing_area($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date IS NOT NULL) AND(tbl_growth_condition.line_sowing_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor; 	
	}
	
	function get_non_line_sowing_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
      AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer;	
	}
	
	function total_non_line_sowing_area($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.line_sowing_date = '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor; 	
	}
	
	function get_interfiling_weeding_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 
	}
	
	function total_interfiling_weeding_area($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.interfiling_weeding_date IS NOT NULL) AND(tbl_growth_condition.interfiling_weeding_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor; 	
	}
	
	function get_flowering_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 	
	}
	
	function total_flowering_area($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.flowerring_date IS NOT NULL) AND(tbl_growth_condition.flowerring_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor;	
	}
	
	function get_pods_5cm_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 	
	}
	
	function total_pods_5cm_area($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_growth_condition tbl_growth_condition
           ON (season_wise_member_info.user_id =
                  tbl_growth_condition.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_growth_condition.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(season_wise_member_info.cultivation_area_hactor) as total_cultivation_area_hactor 
		FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_growth_condition tbl_growth_condition
       ON (season_wise_member_info.user_id = tbl_growth_condition.member_id)
          AND(season_wise_member_info.season_id = tbl_growth_condition.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_growth_condition.pods_5cm_date IS NOT NULL) AND(tbl_growth_condition.pods_5cm_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_cultivation_area_hactor;	
	}
	
	function get_harvest_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_harvest_purchase tbl_harvest_purchase
           ON (season_wise_member_info.user_id =
                  tbl_harvest_purchase.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_harvest_purchase.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.harvest_date IS NOT NULL) AND(tbl_harvest_purchase.harvest_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.harvest_date IS NOT NULL) AND(tbl_harvest_purchase.harvest_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.harvest_date IS NOT NULL) AND(tbl_harvest_purchase.harvest_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.harvest_date IS NOT NULL) AND(tbl_harvest_purchase.harvest_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 	
	}
	
	function total_harvest_amount($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(harvest_amount) as total_harvest_amount FROM  
		(   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_harvest_purchase tbl_harvest_purchase
           ON (season_wise_member_info.user_id =
                  tbl_harvest_purchase.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_harvest_purchase.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(harvest_amount) as total_harvest_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(harvest_amount) as total_harvest_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(harvest_amount) as total_harvest_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_harvest_amount;			   
	}
	
	function get_purchase_info($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT count(*) as line_sowing_farmer
  FROM    (   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_harvest_purchase tbl_harvest_purchase
           ON (season_wise_member_info.user_id =
                  tbl_harvest_purchase.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_harvest_purchase.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.purchase_date IS NOT NULL) AND(tbl_harvest_purchase.purchase_date != '0000-00-00')";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.purchase_date IS NOT NULL) AND(tbl_harvest_purchase.purchase_date != '0000-00-00')";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.purchase_date IS NOT NULL) AND(tbl_harvest_purchase.purchase_date != '0000-00-00')";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT count(*) as line_sowing_farmer FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)
       AND(tbl_harvest_purchase.purchase_date IS NOT NULL) AND(tbl_harvest_purchase.purchase_date != '0000-00-00')";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->line_sowing_farmer; 		
	}
	
	function total_purchase_amount($lid,$season_id,$ltype)
	{
	if($ltype==1) //$ltype=1 means region
		{
		$sql="SELECT SUM(purchase_amount) as total_purchase_amount FROM  
		(   season_wise_member_info season_wise_member_info
           INNER JOIN
              tbl_harvest_purchase tbl_harvest_purchase
           ON (season_wise_member_info.user_id =
                  tbl_harvest_purchase.member_id)
              AND(season_wise_member_info.season_id =
                     tbl_harvest_purchase.season_id))
       INNER JOIN
          tbl_region_location_map tbl_region_location_map
       ON (season_wise_member_info.dt_id = tbl_region_location_map.lid)
 WHERE     (tbl_region_location_map.region_id = $lid)
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==2) //$ltype=2 means district
		{
		$sql="SELECT SUM(purchase_amount) as total_purchase_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.dt_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==3) //$ltype=2 means Upazila
		{
		$sql="SELECT SUM(purchase_amount) as total_purchase_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.up_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
		else if($ltype==4) //$ltype=2 means union
		{
		$sql="SELECT SUM(purchase_amount) as total_purchase_amount FROM  season_wise_member_info season_wise_member_info
       INNER JOIN
          tbl_harvest_purchase tbl_harvest_purchase
       ON (season_wise_member_info.user_id = tbl_harvest_purchase.member_id)
          AND(season_wise_member_info.season_id = tbl_harvest_purchase.season_id)
		  WHERE season_wise_member_info.union_id = $lid 
       AND(season_wise_member_info.season_id = $season_id)";
		}
	$resultSet = $this->db->query($sql);	
	return $resultSet->row()->total_purchase_amount;	
	}
	
	function show_bercurbe($lid,$ltype)
	{
		
		$language = $this->session->userdata('site_lang');
		
		if($ltype==1)
		{
		$this->db->select('region_name');
		$this->db->select('region_name_bn');
		$this->db->from('tbl_region');
		$this->db->where('region_id', $lid);	
		$result_set = $this->db->get();
				
			if($language)
			{
				if($language=='english')
				echo "&rArr; ".$result_set->row()->region_name;
				
				if($language=='bangla')
				echo "&rArr; ".$result_set->row()->region_name_bn;
			}
			else
			{
			echo "&rArr; ".$result_set->row()->region_name;
			}	
		}
		else if($ltype==2)
		{
		$sql="SELECT region_name, region_name_bn, tbl_region.region_id
  FROM    (   location location
           INNER JOIN
              tbl_region_location_map tbl_region_location_map
           ON (location.l_id = tbl_region_location_map.lid))
       INNER JOIN
          tbl_region tbl_region
       ON (tbl_region.region_id = tbl_region_location_map.region_id)
 WHERE (location.l_id = $lid)";
 		$resultSet = $this->db->query($sql);				
		if($language=='bangla')
		echo "&rArr; <a href=javascript:void(0) onclick=getResult(".$resultSet->row()->region_id.",1);>".$resultSet->row()->region_name_bn."</a>";
		else
		echo "&rArr; <a href=javascript:void(0) onclick=getResult(".$resultSet->row()->region_id.",1);>".$resultSet->row()->region_name."</a>";
		
		echo " &rArr; ".$this->ref_farmer_model->get_location_name_by_id($lid);
		}
		else if($ltype==3)
		{
		$d_id=$this->ref_location_model->get_parent_location_id($lid); //$d_id means district id

		$sql="SELECT region_name, region_name_bn, tbl_region.region_id
  FROM    (   location location
           INNER JOIN
              tbl_region_location_map tbl_region_location_map
           ON (location.l_id = tbl_region_location_map.lid))
       INNER JOIN
          tbl_region tbl_region
       ON (tbl_region.region_id = tbl_region_location_map.region_id)
 WHERE (location.l_id = $d_id)";
 		$resultSet = $this->db->query($sql);
		$r_id=$resultSet->row()->region_id;
		
			if($language=='bangla')
			{
			echo "&rArr; <a href=javascript:void(0) onclick=getResult(".$r_id.",1);>".$resultSet->row()->region_name_bn."</a>";
			echo " &rArr; <a href=javascript:void(0) onclick=getResult(".$d_id.",2);>".$this->ref_farmer_model->get_location_name_by_id($d_id)."</a>";
			}
			else
			{
			echo "&rArr; <a href=javascript:void(0) onclick=getResult(".$r_id.",1);>".$resultSet->row()->region_name."</a>";
			echo " &rArr; <a href=javascript:void(0) onclick=getResult(".$d_id.",2);>".$this->ref_farmer_model->get_location_name_by_id($d_id)."</a>";
			}
			
		echo " &rArr; ".$this->ref_farmer_model->get_location_name_by_id($lid);	
		
		}
		
		
	}
	
}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_country_model.php */