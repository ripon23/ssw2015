<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basic_setting_model extends CI_Model {	
		
	
	function update_services_by_id($reg_data_table1,$services_id)
	{
	$this->db->where('services_id', $services_id);	
	$this->db->update('gramcar_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function update_package_by_id($reg_data_table1,$package_id)
	{
	$this->db->where('package_id', $package_id);	
	$this->db->update('gramcar_services_package',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function update_site_by_id($reg_data_table1,$site_id)
	{
	$this->db->where('site_id', $site_id);	
	$this->db->update('gramcar_site',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function get_all_services()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_services');			
		$this->db->order_by('services_id', 'asc');			
		return $this->db->get()->result();		
	}
	
	function get_all_packages()	
	{
		$this->db->select('*');
		$this->db->from('gramcar_services_package');			
		$this->db->order_by('services_id', 'asc');			
		return $this->db->get()->result();		
	}
	
	function add_new_services($reg_data_table1)
	{
	$this->db->insert('gramcar_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function add_new_package($reg_data_table1)
	{
	$this->db->insert('gramcar_services_package',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function add_new_site($reg_data_table1)
	{
	$this->db->insert('gramcar_site',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
}


/* End of file account_model.php */
/* Location: ./application/models/basic_setting/basic_setting.php */