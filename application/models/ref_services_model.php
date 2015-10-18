<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_services_model extends CI_Model {

	// --------------------------------------------------------------------

	/**
	 * Get all ref location by type
	 *
	 * @access public
	 * @return object
	 */
	 
	function get_all_services()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_services');			
		$this->db->where('services_status',1);
		$this->db->order_by('services_id', 'asc');			
		return $this->db->get()->result();		
	}


	function get_all_services_package_by_id($services_id)
	{
	$this->db->where('services_id', $services_id);
	$this->db->where('package_status', 1);
	$this->db->order_by('package_id', 'asc');		
	return $this->db->get('gramcar_services_package')->result();		
	}
	
	
	function get_services_name_by_id($sid)
	{
		$this->db->select('*') ;
		$this->db->from('gramcar_services');	
		$this->db->where('services_id',$sid);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->services_name;
			
			if($language=='bangla')
			return $result_set->row()->services_name_bn;
		}
		else
		{
		return $result_set->row()->services_name;
		}	
	}
	
	function get_package_name_by_id($pid)
	{
		$this->db->select('*') ;
		$this->db->from('gramcar_services_package');	
		$this->db->where('package_id',$pid);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->package_name;
			
			if($language=='bangla')
			return $result_set->row()->package_name_bn;
		}
		else
		{
		return $result_set->row()->package_name;
		}	
	}
	
	function get_package_info_by_pacakgeid($pid)
	{
		$this->db->select('*') ;
		$this->db->from('gramcar_services_package');	
		$this->db->where('package_id',$pid);
		return $this->db->get()->row();
	}
	
/*
	function get_location_name_by_id($lid)
	{
	$this->db->select('l_name');
	$this->db->select('l_name_bn');
	$this->db->from('location');
	$this->db->where('l_id', $lid);	
	$result_set = $this->db->get();
	
	$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->l_name;
			
			if($language=='bangla')
			return $result_set->row()->l_name_bn;
		}
		else
		{
		return $result_set->row()->l_name;
		}
				
	}
	
	
	
	function get_all_group_under_district_season($district_id,$season_id)	
	{
		
		$this->db->distinct();
		$this->db->select('member_group');
		$this->db->from('season_wise_member_info');
		$this->db->where('dt_id', $district_id);	
		$this->db->where('season_id', $season_id);
		$this->db->where('member_group !=', '');
		$this->db->order_by('member_group', 'asc');	
		return $this->db->get()->result();
	}*/

}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_country_model.php */