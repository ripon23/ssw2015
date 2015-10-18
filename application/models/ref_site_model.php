<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_site_model extends CI_Model {

	// --------------------------------------------------------------------

	/**
	 * Get all ref location by type
	 *
	 * @access public
	 * @return object
	 */
	 
	function get_all_site_info()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_site');								
		$this->db->order_by('site_type', 'asc');
		$this->db->order_by('site_name', 'asc');
		return $this->db->get()->result();		
	}
	
	function get_all_site()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_site');		
		$this->db->where('site_type', 'ST');	
		$this->db->where('site_status',1);
		$this->db->order_by('site_name', 'asc');			
		return $this->db->get()->result();		
	}
	
	function get_all_services_point()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_site');		
		$this->db->where('site_type', 'SP');	
		$this->db->where('site_status',1);
		$this->db->order_by('site_name', 'asc');			
		return $this->db->get()->result();		
	}

	function get_all_services_point_by_id($site_id)	
	{
		$this->db->where('site_parent_id', $site_id);
		$this->db->where('site_status', 1);
		$this->db->order_by('site_name', 'asc');		
		return $this->db->get('gramcar_site')->result();
	}
	
	
	function get_site_id_by_sp_id($sp_id)
	{
		$this->db->select('site_parent_id as site_id') ;
		$this->db->from('gramcar_site');		
		$this->db->where('site_type', 'SP');	
		$this->db->where('site_id',$sp_id);			
		$result = $this->db->get();
		return $result->row()->site_id;
	}
	
	function get_site_name_by_sp_id($sp_id)
	{
		$this->db->select('site_parent_id as site_parentid') ;
		$this->db->from('gramcar_site');		
		$this->db->where('site_type', 'SP');	
		$this->db->where('site_id',$sp_id);			
		$result = $this->db->get();
		$site_parent_id=$result->row()->site_parentid;
		
		$this->db->select('*') ;
		$this->db->from('gramcar_site');
		$this->db->where('site_type', 'ST');	
		$this->db->where('site_id',$site_parent_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->site_name;
			
			if($language=='bangla')
			return $result_set->row()->site_name_bn;
		}
		else
		{
		return $result_set->row()->site_name;
		}
		
	}
	
	
	
	
	function get_site_name_by_id($s_id)
	{
		$this->db->select('*') ;
		$this->db->from('gramcar_site');	
		$this->db->where('site_id',$s_id);
		$result_set = $this->db->get();
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->site_name;
			
			if($language=='bangla')
			return $result_set->row()->site_name_bn;
		}
		else
		{
		return $result_set->row()->site_name;
		}	
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