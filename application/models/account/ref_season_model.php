<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_season_model extends CI_Model {

	/**
	 * Get ref country
	 *
	 * @access public
	 * @param string $country
	 * @return object
	 */
	function get($season)
	{
		$this->db->where('current_season', 1);		
		$query = $this->db->get('season');
		if ($query->num_rows()) return $query->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Get all ref countries
	 *
	 * @access public
	 * @return object
	 */
	function get_all_season()
	{
		$this->db->order_by('seasonid', 'asc');
		return $this->db->get('season')->result();
	}

}


/* End of file ref_country_model.php */
/* Location: ./application/account/models/ref_country_model.php */