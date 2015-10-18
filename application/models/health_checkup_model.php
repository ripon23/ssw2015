<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Health_checkup_model extends CI_Model {			
	
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function get_health_checkup_info_by_reg_services_id($reg_services_id)
	{	
	$this->db->where('reg_for_service_id',$reg_services_id);
	$query = $this->db->get('gramcar_health_checkup');
	return $query->row();
	}
	
	
	function all_health_checkup_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_health_checkup_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	function get_all_health_checkup_registration()
	{
	$this->db->where('services_id',2); 	 // 2= General Health Check Up 
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
	
	function get_all_urban_health_checkup_registration_count()
	{
	$this->db->where('services_id',9); 	 // 2= General Health Check Up 
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_health_checkup_registration_count()
	{
	$this->db->where('services_id',2); 	 // 2= General Health Check Up 
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	
	function get_all_urban_health_checkup_registration_by_limit($limit, $start) {
		$this->db->where('services_id',9); 	 // 2= General Health Check Up 
		$this->db->where('services_status <',3); //3= cancle 4=deleted
		$this->db->order_by('services_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_registration_for_services");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function get_all_health_checkup_registration_by_limit($limit, $start) {
		$this->db->where('services_id',2); 	 // 2= General Health Check Up 
		$this->db->or_where('services_id',9); 	 // 9= Urban Health Check Up 
		$this->db->where('services_status <',3); //3= cancle 4=deleted
		$this->db->order_by('services_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_registration_for_services");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function get_result_status($test_name, $test_value, $sex)
	{
		/*** BMI ***/
		if($test_name=='BMI') 
		{
			if($test_value < 25)
			{
			$color= "green";	
			}
			else if(($test_value > 24)&&($test_value < 30))
			{
			$color= "yellow";	
			}
			else if(($test_value > 29)&&($test_value < 35))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}				
		}
		
		/*** Waist ***/
		if($test_name=='waist') 
		{
			if($sex=='Male') 
			{
				if($test_value < 90)
				{
				$color= "green";	
				}			
				else
				{
				$color= "yellow";		
				}				
			}
			
			if($sex=='Female') 
			{
				if($test_value < 80)
				{
				$color= "green";	
				}			
				else
				{
				$color= "yellow";		
				}				
			}
			
		}
		
		/*** Waist Hip Ratio***/
		if($test_name=='waist_hip_ratio') 
		{
			if($sex=='Male') 
			{
				if($test_value < 0.90)
				{
				$color= "green";	
				}			
				else
				{
				$color= "yellow";		
				}				
			}
			
			if($sex=='Female') 
			{
				if($test_value < 0.85)
				{
				$color= "green";	
				}			
				else
				{
				$color= "yellow";		
				}				
			}
			
		}
		
		/*** Temperature ***/
		if($test_name=='temperature') 
		{
			if($test_value < 98.6)
			{
			$color= "green";	
			}
			else if(($test_value > 98.5)&&($test_value < 99.5))
			{
			$color= "yellow";	
			}		
			else
			{
			$color= "orange";		
			}								
		}
		
		/*** oxygen_of_blood_hemoglobin ***/
		if($test_name=='oxygen_of_blood_hemoglobin') 
		{
			if($test_value > 95)
			{
			$color= "green";	
			}
			else if(($test_value > 92)&&($test_value < 96))
			{
			$color= "yellow";	
			}
			else if(($test_value > 89)&&($test_value < 93))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}								
		}
		
		/*** bp_sys ***/
		/*if($test_name=='bp_sys') 
		{
			if($test_value < 140)
			{
			$color= "green";	
			}
			else if(($test_value > 139)&&($test_value < 160))
			{
			$color= "yellow";	
			}
			else if(($test_value > 159)&&($test_value < 180))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}								
		}*/
			
		/*** bp_dia ***/
		/*if($test_name=='bp_dia') 
		{
			if($test_value < 90)
			{
			$color= "green";	
			}
			else if(($test_value > 89)&&($test_value < 100))
			{
			$color= "yellow";	
			}
			else if(($test_value > 99)&&($test_value < 110))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}								
		}*/
		
		/*** bp_sys ***/
		if($test_name=='bp_sys') 
		{
			if($test_value < 130)
			{
			$color= "green";	
			}
			else if(($test_value > 129)&&($test_value < 140))
			{
			$color= "yellow";	
			}
			else if(($test_value > 139)&&($test_value < 180))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}								
		}
		
		
		/*** bp_dia ***/
		if($test_name=='bp_dia') 
		{
			if($test_value < 85)
			{
			$color= "green";	
			}
			else if(($test_value > 84)&&($test_value < 90))
			{
			$color= "yellow";	
			}
			else if(($test_value > 89)&&($test_value < 110))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}								
		}
		/*** blood_gluckose ***/
		
		if($test_name=='blood_gluckose') 
		{
	
			if($sex=='FBS') 
			{
				if($test_value < 100)
				{
				$color= "green";	
				}
				else if(($test_value > 99)&&($test_value < 126))
				{
				$color= "yellow";	
				}
				else if(($test_value > 125)&&($test_value < 200))
				{
				$color= "orange";	
				}
				else
				{
				$color= "red";		
				}
			}
			if($sex=='PBS') 
			{
				if($test_value < 140)
				{
				$color= "green";	
				}
				else if(($test_value > 139)&&($test_value < 200))
				{
				$color= "yellow";	
				}
				else if(($test_value > 199)&&($test_value < 300))
				{
				$color= "orange";	
				}
				else
				{
				$color= "red";		
				}
			}
			
		}
		
		/*** blood_hemoglobin ***/
		if($test_name=='blood_hemoglobin') 
		{
			if($test_value > 11.9)
			{
			$color= "green";	
			}
			else if(($test_value > 9.9)&&($test_value < 12))
			{
			$color= "yellow";	
			}
			else if(($test_value > 7.9)&&($test_value < 10))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}			
		}
		
		/*** urine_sugar ***/
		if($test_name=='urine_sugar') 
		{
			if($test_value == '-')
			{
			$color= "green";	
			}	
			else if($test_value == '+-')
			{
			$color= "yellow";	
			}
			else
			{
			$color= "orange";		
			}				
		}
		
		/*** urine_protein ***/
		if($test_name=='urine_protein') 
		{
			if($test_value == '-')
			{
			$color= "green";	
			}	
			else if($test_value == '+-')
			{
			$color= "yellow";	
			}
			else
			{
			$color= "orange";		
			}			
		}
		
		
		
		/*** urinary_urobilinogen ***/
		if($test_name=='urinary_urobilinogen') 
		{
			if($test_value == '+-')
			{
			$color= "green";	
			}		
			else
			{
			$color= "orange";		
			}			
		}
		
		/*** urinary_ph ***/
		if($test_name=='urinary_ph') 
		{
			if(($test_value > 7)&& ($test_value <= 9))
			{
			$color= "green";	
			}
			else if(($test_value > 6)&& ($test_value <= 7))
			{
			$color= "yellow";	
			}		
			else if(($test_value >= 5)&& ($test_value <= 6))
			{
			$color= "orange";	
			}
			else
			{
			$color= "red";		
			}			
		}
		
		/*** pulse_ratio ***/
		if($test_name=='pulse_ratio') 
		{
			if(($test_value > 59) && ($test_value < 100))
			{
			$color= "green";	
			}
			else if(($test_value > 49)&&($test_value < 60))
			{
			$color= "yellow";	
			}
			else if(($test_value > 99)&&($test_value < 120))
			{
			$color= "yellow";	
			}
			else if(($test_value < 50)||($test_value > 119))
			{
			$color= "orange";	
			}
			else
			{
			$color= "";		
			}									
		}
		
		/*** Arrhythmia ***/
		if($test_name=='rhythm') 
		{
			if($test_value == 'Normal')
			{
			$color= "green";	
			}		
			else
			{
			$color= "orange";		
			}			
		}
		
		/*** cholesterol ***/
	if($test_name=='cholesterol') 
	{
		if($test_value <= 200)
		{
		$color= "green";	
		}
		else if(($test_value > 200)&&($test_value <= 225))
		{
		$color= "yellow";	
		}
		else if(($test_value > 225)&&($test_value <= 239))
		{
		$color= "orange";	
		}
		else if($test_value > 239)
		{
		$color= "red";	
		}		
	}
	
	/*** uric_acid ***/
	if($test_name=='uric_acid') 
	{
		
		if($sex=='Male') 
		{
			if(($test_value > 3.5 ) && ($test_value <= 7))
			{
			$color= "green";	
			}
			else if(($test_value > 7) && ($test_value < 8))
			{
			$color= "orange";	
			}		
			else if($test_value >= 8)
			{
			$color= "red";	
			}
			else
			{
			$color= "green";		
			}
		}
		
		if($sex=='Female') 
		{
			if(($test_value > 2.4 ) && ($test_value <= 6))
			{
			$color= "green";	
			}
			else if(($test_value > 6) && ($test_value < 7))
			{
			$color= "orange";	
			}		
			else if($test_value >= 7)
			{
			$color= "red";	
			}
			else
			{
			$color= "green";		
			}
		}
							
	}
	/*** hbsag ***/
	if($test_name=='hbsag') 
	{
		if($test_value == "negative")
		{
		$color= "green";	
		}
		else if($test_value == "positive")
		{
		$color= "red";	
		}
		
	}	
	
	
	$this->health_checkup_model->overall_health_status($color);	
	return $color;
	}

function overall_health_status($color){
	
	if($color=='green')	{
	$color_value=1;
	}
	elseif($color=='yellow')	{
	$color_value=2;
	}
	elseif($color=='orange')	{
	$color_value=3;
	}
	elseif($color=='red')	{
	$color_value=4;
	}
	else
	{
	$color_value=0;	
	}
	
	
	if($GLOBALS["overall_health_status"]<$color_value)
	{
		$GLOBALS["overall_health_status"]=$color_value;		
		
	}
	
}

	function save_health_checkup($reg_data_table1)
	{
	$this->db->insert('gramcar_health_checkup',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	
	function update_health_checkup($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);
	$this->db->update('gramcar_health_checkup',$reg_data_table1);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function update_health_checkup_services_status($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	/*
	function all_registration_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	
	function get_all_registration_info_by_id($reg_id)
	{
	$this->db->where('registration_no',$reg_id);
	$query = $this->db->get('gramcar_registration');
	return $query->row();	
	}
	
	
	function get_all_services_by_regid($reg_id)
	{
	$this->db->select('*');
	$this->db->where('registration_no',$reg_id);	
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
		
	
	
	
	function get_all_registration_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	
	
	
	function searchterm_handler($searchterm)
	{
		if($searchterm)
		{
			$this->session->set_userdata('searchterm', $searchterm);
			return $searchterm;
		}
		elseif($this->session->userdata('searchterm'))
		{
			$searchterm = $this->session->userdata('searchterm');
			return $searchterm;
		}
		else
		{
			$searchterm ="";
			return $searchterm;
		}
	}*/
	


}


/* End of file health_checkup_model.php */
/* Location: ./application/models/health_checkup_model.php */