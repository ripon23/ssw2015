<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_farmer_model extends CI_Model {

	// --------------------------------------------------------------------

	/**
	 * Get all ref location by type
	 *
	 * @access public
	 * @return object
	 */
	 
	function get_all_area_under_district_season($district_id,$season_id)	
	{
		
		$this->db->distinct();
		$this->db->select('member_area');
		//$this->db->distinct('member_area');
		//$this->db->select('*');
		$this->db->from('season_wise_member_info');
		$this->db->where('dt_id', $district_id);	
		$this->db->where('season_id', $season_id);
		$this->db->where('member_area !=', '');
		$this->db->order_by('member_area', 'asc');	
		//return $this->db->get('season_wise_member_info')->result();
		return $this->db->get()->result();
		//return $result_set->result();
	}

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
	}

}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_country_model.php */