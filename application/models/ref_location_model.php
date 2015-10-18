<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_location_model extends CI_Model {

	// --------------------------------------------------------------------

	/**
	 * Get all ref location by type
	 *
	 * @access public
	 * @return object
	 */
	 
	function get_all_division()	
	{
		$resultSet=$this->db->query('SELECT DISTINCT
				location_bbs2011.division,
       location_bbs2011.*,       
       location_bbs2011.loc_type
  FROM location_bbs2011
 WHERE (location_bbs2011.loc_type = "DV") ORDER BY location_bbs2011.loc_name_en ASC');					
		return $resultSet->result();
		
	}
	
	function get_location_list_by_id($dvid=NULL,$dtid=NULL,$upid=NULL,$unid=NULL,$maid=NULL,$viid=NULL,$ltype)
	{	
	if($ltype=='DV')
		{
		$dbfield='division'; 
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype')  ORDER BY location_bbs2011.loc_name_en ASC";
		}	
	else if($ltype=='DT')
		{
		$dbfield='district'; 
		$parentdbfield='division';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$dvid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='UP')
		{
		$dbfield='upazila'; 
		$parentdbfield='district';	
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='UN')
		{
		$dbfield='unionid'; 
		$parentdbfield='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='MA')
		{
		$dbfield='mouza'; 
		$parentdbfield='unionid';
		$parentdbfield1='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$unid." AND location_bbs2011.".$parentdbfield1."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='VI')
		{
		$dbfield='village'; 
		$parentdbfield='mouza';	
		$parentdbfield1='unionid';
		$parentdbfield2='upazila';
		$parentdbfield3='district';
		
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$maid." AND location_bbs2011.".$parentdbfield1."=".$unid." AND location_bbs2011.".$parentdbfield2."=".$upid." AND location_bbs2011.".$parentdbfield3."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}	
		$resultSet=$this->db->query($sql);
		return $resultSet->result();
				
	}
	
	
	function get_location_name_by_id($dvid=NULL,$dtid=NULL,$upid=NULL,$unid=NULL,$maid=NULL,$viid=NULL,$ltype)
	{
	if($ltype=='DT')
		{
		$dbfield='district'; 
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype')  AND location_bbs2011.".$dbfield."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}	
	else if($ltype=='UP')
		{
		$dbfield='upazila'; 
		$parentdbfield='district';	
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$dbfield."=".$upid." AND location_bbs2011.".$parentdbfield."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";		
		}
	else if($ltype=='UN')
		{
		$dbfield='unionid'; 
		$parentdbfield='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$dbfield."=".$unid." AND location_bbs2011.".$parentdbfield."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}	
	else if($ltype=='MA')
		{
		$dbfield='mouza'; 
		$parentdbfield='unionid';
		$parentdbfield1='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$dbfield."=".$maid." AND location_bbs2011.".$parentdbfield."=".$unid." AND location_bbs2011.".$parentdbfield1."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='VI')
		{
		$dbfield='village'; 
		$parentdbfield='mouza';	
		$parentdbfield1='unionid';
		$parentdbfield2='upazila';
		$parentdbfield3='district';
		
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$dbfield."=".$viid." AND location_bbs2011.".$parentdbfield."=".$maid." AND location_bbs2011.".$parentdbfield1."=".$unid." AND location_bbs2011.".$parentdbfield2."=".$upid." AND location_bbs2011.".$parentdbfield3."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}	
		$resultSet=$this->db->query($sql);
		foreach ($resultSet->result_array() as $row)
			{
			  	
				$id=$row[$dbfield];
				$data=$row['loc_name_en'];	
				echo $data;
			}
		
	}
	
	function get_child_location($dvid,$dtid=NULL,$upid=NULL,$unid=NULL,$maid=NULL,$ltype)
	{				
	if($ltype=='DT')
		{
		$dbfield='district'; 
		$parentdbfield='division';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$dvid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='UP')
		{
		$dbfield='upazila'; 
		$parentdbfield='district';	
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='UN')
		{
		$dbfield='unionid'; 
		$parentdbfield='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='MA')
		{
		$dbfield='mouza'; 
		$parentdbfield='unionid';
		$parentdbfield1='upazila';
		$parentdbfield2='district';
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$unid." AND location_bbs2011.".$parentdbfield1."=".$upid." AND location_bbs2011.".$parentdbfield2."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
	else if($ltype=='VI')
		{
		$dbfield='village'; 
		$parentdbfield='mouza';	
		$parentdbfield1='unionid';
		$parentdbfield2='upazila';
		$parentdbfield3='district';
		
		$sql="SELECT DISTINCT location_bbs2011.".$dbfield.",
		   location_bbs2011.*,       
		   location_bbs2011.loc_type
	  FROM location_bbs2011 location_bbs2011
	 WHERE (location_bbs2011.loc_type = '$ltype') AND location_bbs2011.".$parentdbfield."=".$maid." AND location_bbs2011.".$parentdbfield1."=".$unid." AND location_bbs2011.".$parentdbfield2."=".$upid." AND location_bbs2011.".$parentdbfield3."=".$dtid." ORDER BY location_bbs2011.loc_name_en ASC";
		}
		
		$resultSet=$this->db->query($sql);
		echo '<option value="">'.lang('settings_select').'</option>';
		foreach ($resultSet->result_array() as $row)
			{
			  	
				$id=$row[$dbfield];
				$data=$row['loc_name_en'];	
				echo '<option value="'.$id.'">'.$data.'('.$id.')</option>';
			}							
	}
	

}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_location_model.php */