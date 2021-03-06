<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumables_model extends CI_Model {	
	
	function save_consumables($reg_data_table1)
	{
	$this->db->insert('gramcar_consumables',$reg_data_table1);
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function all_consumables_count()
	{	
	$query = $this->db->get('gramcar_consumables');
	return $query->num_rows();	
	}
	
	function get_all_consumables_by_limit($limit, $start) {
		$this->db->order_by('update_date', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_consumables");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function get_all_consumable_info_by_id($consumable_id)
	{
	$this->db->where('consumable_id',$consumable_id);
	$query = $this->db->get('gramcar_consumables');
	return $query->row();	
	}
	
	function update_consumables($reg_data_table1,$consumable_id)
	{
	$this->db->where('consumable_id', $consumable_id);	
	$this->db->update('gramcar_consumables',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function delete_consumable($consumable_id)
	{	
	$this->db->delete('gramcar_consumables', array('consumable_id' => $consumable_id));		
	return ($this->db->affected_rows() > 0) ? true : false;	
	}
	
	
	function all_consumables_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_consumables_by_limit_querystring($searchterm, $limit, $start)
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
	}
	
	function get_all_consumable_categorys()
	{
	$this->db->select('*');
	$this->db->from('gramcar_consumable_category');		
	$this->db->order_by('consumable_category_id', 'asc');			
	return $this->db->get()->result();		
	}
	
	function get_consumable_category_name_by_id($category_id)
	{
	$this->db->select('*') ;
	$this->db->from(' gramcar_consumable_category');	
	$this->db->where('consumable_category_id',$category_id);
	$result_set = $this->db->get();
	
	$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->consumable_category_name;
			
			if($language=='bangla')
			return $result_set->row()->consumable_category_name_bn;
		}
		else
		{
		return $result_set->row()->consumable_category_name;
		}	
	}
	/************* need to delete *//////////////
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
	function get_all_registration()
	{
	$this->db->order_by('create_date', 'desc');		
	return $this->db->get('gramcar_registration')->result();	
	}
	
	
	
	
	
	
	
	function get_all_services_by_regid($reg_id)
	{
	$this->db->select('*');
	$this->db->where('registration_no',$reg_id);	
	$this->db->order_by('services_date', 'desc');		
	return $this->db->get('gramcar_registration_for_services')->result();	
	}
		
	
	
	
	

	
	
	
	
	
	function save_registration_services($reg_data_table2)
	{	
	$this->db->insert('gramcar_registration_for_services',$reg_data_table2);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	
	function add_new_services($reg_data_table1)
	{
	$this->db->insert('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	
	function update_services_by_reg_for_service_id($reg_data_table1,$reg_for_service_id)
	{
	$this->db->where('reg_for_service_id', $reg_for_service_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	
	
	function set_registration_status($reg_no,$status)
	{
	$this->db->where('registration_no', $reg_no);	
	$this->db->update('gramcar_registration',array('status' => $status));	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
		
	
	
	
	
	function registration_no_exits($reg_no)
	{
		$this->db->where('registration_no',$reg_no);
		$query = $this->db->get('gramcar_registration');
		return $query->num_rows();
	}
	*/
	
	


}


/* End of file account_model.php */
/* Location: ./application/account/models/farmer_model.php */