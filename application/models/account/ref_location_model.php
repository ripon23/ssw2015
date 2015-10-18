<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_location_model extends CI_Model {

	// --------------------------------------------------------------------

	/**
	 * Get all ref location by type
	 *
	 * @access public
	 * @return object
	 */
	 
	function get_all_location_by_type($type)	
	{
		$this->db->where('l_type', $type);	
		$this->db->where('status', 1);
		$this->db->order_by('l_name', 'asc');		
		return $this->db->get('location')->result();
	}


	function get_all_child_location_by_id($lid)	
	{
		$this->db->where('l_sub_main', $lid);	
		$this->db->order_by('l_name', 'asc');		
		return $this->db->get('location')->result();
	}
	
	function get_parent_location_id($lid)
	{	
		$this->db->select('l_sub_main');
		$this->db->from(' location');
		$this->db->where('l_id', $lid);	
		$result_set = $this->db->get();
		return $result_set->row()->l_sub_main;
	}

}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_country_model.php */