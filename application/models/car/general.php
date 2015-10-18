<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Model {		
	
	function save_into_table($table_name, $table_data)
	{
	$this->db->insert($table_name,$table_data);
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function save_into_table_and_return_insert_id($table_name, $table_data)
	{
	$this->db->insert($table_name,$table_data);
	return $this->db->insert_id();
	//return mysql_insert_id();
	}
		
	function update_table($table_name, $table_data,$where_field, $id)
	{
	$this->db->where($where_field, $id);	
	$this->db->update($table_name,$table_data);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}			
	
	
	function delete_from_table($table_name, $field_name, $id)
	{
		$this->db->where($field_name,$id);
		$this->db->delete($table_name);
		return true;
	}
	
	function number_of_total_rows_in_a_table($table_name)
	{
	return $this->db->count_all($table_name);
	}
	
	function number_of_total_rows_in_a_table_where($table_name,$field_name,$field_value)
	{
	$this->db->where($field_name,$field_value);
	$query = $this->db->get($table_name);	
	return $query->num_rows();	
	}
	
	function get_all_table_info_by_id($table_name, $field_name, $id)
	{
		$this->db->where($field_name,$id);
		$query = $this->db->get($table_name);
		return $query->row();		
	}
	
	function get_all_table_info_by_id_custom($table_name, $select = NULL, $field_name, $id)
	{
		if($select!=NULL):
		$this->db->select($select);
		endif;
		$this->db->where($field_name,$id);
		$query = $this->db->get($table_name);
		return $query->row();		
	}
	
	function total_sum($table, $sum_feald, $whare_parms)
	{
		$this->db->select_sum($sum_feald);
		$this->db->where($whare_parms);
		$query = $this->db->get($table);
		return $query->row();
	}
	
	
	function get_all_table_info_by_id_asc_desc($table_name, $field_name, $id, $order_by, $asc_or_desc)
	{	
	$this->db->order_by($order_by, $asc_or_desc);
	$this->db->where($field_name,$id);
	$query = $this->db->get($table_name);
	return $query->result();		
	}
	
	function get_all($table_name)
	{	
		//$this->db->order_by($order_by, $asc_or_desc);
		//$this->db->where($field_name,$id);
		$query = $this->db->get($table_name);
		return $query->result();		
	}
	
	function get_all_table_info_by_id_asc_desc_with_join($table_name, $field_name, $id, $order_by, $asc_or_desc)
	{
		$this->db->select('*'); 	
		$this->db->from($table_name);
		$this->db->join('car_node', 'car_node.node_id=car_route_node_mapping.node_id', 'left'); 
		$this->db->order_by($order_by, $asc_or_desc);
		$this->db->where($field_name,$id);
		$query = $this->db->get();
		return $query->result();		
	}
	
	function get_all_nodes_by_note_join($table_name, $field_name, $id, $order_by, $asc_or_desc)
	{
		$this->db->select('*'); 	
		$this->db->from($table_name);
		$this->db->join('car_node', 'car_node.node_id=car_route_node_mapping.node_id', 'left'); 
		$this->db->order_by($order_by, $asc_or_desc);
		$this->db->where($field_name,$id);
		$query = $this->db->get();
		return $query->result();		
	}
	
	/*function get_row_table_info_by_id($table_name, $field_name, $field_value)
	{
	//$this->db->select('*');	
	$this->db->where($field_name,$field_value);
	$query = $this->db->get($table_name);
	return $query->row();	
	}*/
	
	function get_all_result_by_limit($table_name, $order_by, $asc_or_desc,$limit, $start) {	
		$this->db->order_by($order_by, $asc_or_desc);
        $this->db->limit($limit, $start);		
        $query = $this->db->get($table_name);
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
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
	
	function total_count_query_string($searchterm)
	{
		$result = $this->db->query($searchterm);
		return $result->num_rows();	
	}
	
	function total_count($table,$where=NULL,$id=NULL)
	{
		$result = $this->db->query($searchterm);
		return $result->num_rows();	
	}
	
	
	function get_all_result_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	function get_all_single_row_querystring($searchterm)
	{	
	$resultSet = $this->db->query($searchterm);
	return $resultSet->row();		
	}
	
	function is_exist_in_a_table($table_name,$field_name,$field_value)
	{
	$this->db->where($field_name,$field_value);
	$query = $this->db->get($table_name); 
	if($query->num_rows() > 0)
	return true;
	else
	return false;	
	}
	
	function have_access($current_user_id,$want_to_see_user_id)
	{
		if($current_user_id==$want_to_see_user_id)	//if same patient user
		return true;
		else
		{
		$all_user_role=$this->site_model->get_all_user_role($current_user_id);
		foreach ($all_user_role as $role)
			{
				if($role->role_id==$this->config->item("admin_role_id"))
				{
					return true;
					break;
				}
				else if($role->role_id==$this->config->item("operator_role_id"))
				{
					// Need to check same site or not
					if($this->site_model->is_same_site($current_user_id,$want_to_see_user_id))
					return true;
				}
				else if($role->role_id==$this->config->item("site_owner_role_id"))
				{
					// Need to check same site or not
					if($this->site_model->is_same_site($current_user_id,$want_to_see_user_id))
					return true;
				}
				else if($role->role_id==$this->config->item("doctor_role_id"))
				{
					// Need to check same site or not
					if($this->site_model->is_same_site($current_user_id,$want_to_see_user_id))
					return true;
				}
			}
			
		}
	
	}	
	// End Function
	/* Start By Ahsan */
	/* Start By Ahsan */
	// Field Name and Id for where clouse
	function get_list_view($table_name=NULL, $field_name=NULL, $id=NULL, $select=NULL, $order_by=NULL, $asc_or_desc=NULL, $start=NULL, $limit=NULL)
	{
		
		if ($select!=NULL):
		$this->db->select($select);
		endif;
		if($order_by!=NULL && $asc_or_desc!=NULL):
			$this->db->order_by($order_by, $asc_or_desc);
		endif;
		if($field_name!=NULL && $id!=NULL):
			$this->db->where($field_name,$id);
		endif;
		if($limit!=NULL):
			$this->db->limit($limit, $start);
		endif;
		$query = $this->db->get($table_name);
		//if($query->num_rows()>0):
		return $query->result();
	}
	
	function get_list_search_view($table_name=NULL, $field_name=NULL, $select=NULL)
	{
		if ($select!=NULL):
		$this->db->select($select);
		endif;
		
		if($field_name!=NULL):
			$this->db->like($field_name);
		endif;
		$query = $this->db->get($table_name);
		//if($query->num_rows()>0):
		return $query->result();			
	}

	function get_list_search_date_range($table_name=NULL, $field_name=NULL, $select=NULL, $sdate = NULL, $edate = NULL)
	{
		if ($select!=NULL):
		$this->db->select($select);
		endif;
		
		if($field_name!=NULL):
			$this->db->like($field_name);
		endif;
		if ($sdate!==NULL) {
			$this->db->where('schedule_date >=',$sdate);
			$this->db->where('schedule_date <=',$edate);
		}
		$query = $this->db->get($table_name);
		//if($query->num_rows()>0):
		return $query->result();			
	}
	
	function get_list_multi_clause($table_name=NULL, $field_array=NULL, $select=NULL, $order_by=NULL, $asc_or_desc=NULL, $start=NULL, $limit=NULL)
	{
		if ($select!=NULL):
		$this->db->select($select);
		endif;
		if($order_by!=NULL && $asc_or_desc!=NULL):
			$this->db->order_by($order_by, $asc_or_desc);
		endif;
		if(is_array($field_array) && count($field_array)>0):
			$this->db->where($field_array);
		endif;
		if($start!=NULL && $limit!=NULL):
			$this->db->limit($limit, $start);
		endif;
		$query = $this->db->get($table_name);
		//if($query->num_rows()>0):
		return $query->result();			
	}

	function get_driver(){
		$this->db->select('a3m_account.id, a3m_account_details.fullname');
		$this->db->from('a3m_account');
		$this->db->join('a3m_account_details', 'a3m_account.id=a3m_account_details.account_id', 'left'); 
		$this->db->join('a3m_rel_account_role', 'a3m_account.id=a3m_rel_account_role.account_id'); 
		//$this->db->order_by('a3m_rel_account_role.role_id', 4);
		$this->db->where('a3m_rel_account_role.role_id', 4);
		$query = $this->db->get();
		return $query->result();
	}
}


/* End of file account_model.php */
/* Location: ./application/models/general_model.php */