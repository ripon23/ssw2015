<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social_goods_model extends CI_Model {	
		

	function get_all_product_categories()
	{
	$this->db->where('status',1); 	
	$this->db->order_by('category_name', 'asc');		
	return $this->db->get('gramcar_product_categories')->result();	
	}
	
	function get_all_product_type()
	{
	$this->db->where('status',1); 	
	$this->db->order_by('type_name', 'asc');		
	return $this->db->get('gramcar_product_type')->result();		
	}
	
	function get_all_weight_unit()
	{
	$this->db->where('status',1); 	
	$this->db->order_by('unit_name', 'asc');		
	return $this->db->get('gramcar_product_weight_unit')->result();		
	}
	
	function get_all_product_brand()
	{
	$this->db->where('status',1); 	
	$this->db->order_by('brand_name', 'asc');		
	return $this->db->get('gramcar_product_brand')->result();		
	}
	
	function save_product_info($product_data)
	{
	$this->db->insert('gramcar_product',$product_data);
	return mysql_insert_id();
	//return ($this->db->affected_rows() != 1) ? false : true;
	}
		
	function get_product_thumbnil_by_id($product_id)
	{
	$this->db->where('product_id', $product_id);
	$result_set = $this->db->get('gramcar_product');
	return $result_set->row()->thumbnil_image ;
	}
	
	function update_product_info($product_data_with_image,$product_id)
	{
	$this->db->where('product_id', $product_id);	
	$this->db->update('gramcar_product',$product_data_with_image);	
	return ($this->db->affected_rows() != 1) ? false : true;	
	}
	
	function all_product_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();	
	}
	
	function get_all_product_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	function get_all_product_count()
	{
	$this->db->where('status',1); 	 // 1= Active product	
	$query = $this->db->get('gramcar_product');
	return $query->num_rows();	
	}
	
	function get_all_product_count_with_inactive()
	{
	$query = $this->db->get('gramcar_product');
	return $query->num_rows();	
	}
	
	function get_all_product_by_limit_with_inactive($limit, $start) {		
		$this->db->order_by('last_edit_data', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_product");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function get_all_product_by_limit($limit, $start) {
		$this->db->where('status',1); 	// 1= Active product	
		$this->db->order_by('last_edit_data', 'desc');
        $this->db->limit($limit, $start);		
        $query = $this->db->get("gramcar_product");
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}
	
	function get_product_weight_unit_by_id($unit_id)
	{
	$this->db->where('unit_id',$unit_id);
		$result_set = $this->db->get('gramcar_product_weight_unit');
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->unit_name;
			
			if($language=='bangla')
			return $result_set->row()->unit_name_bn;
		}
		else
		{
		return $result_set->row()->unit_name;
		}	
	
	}
	
	function get_product_brand_name_by_id($brand_id)
	{
		$this->db->where('brand_id',$brand_id);
		$result_set = $this->db->get('gramcar_product_brand');
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->brand_name;
			
			if($language=='bangla')
			return $result_set->row()->brand_name_bn;
		}
		else
		{
		return $result_set->row()->brand_name;
		}
	}
	
	
	function get_product_type_name_by_id($type_id)
	{
		$this->db->where('product_type_id',$type_id);
		$result_set = $this->db->get('gramcar_product_type');
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->type_name;
			
			if($language=='bangla')
			return $result_set->row()->type_name_bn;
		}
		else
		{
		return $result_set->row()->type_name;
		}
	}
	
	function get_product_category_name_by_id($category_id)
	{
		$this->db->where('product_category_id',$category_id);
		$result_set = $this->db->get('gramcar_product_categories');
		
		$language = $this->session->userdata('site_lang');
		if($language)
		{
			if($language=='english')
			return $result_set->row()->category_name;
			
			if($language=='bangla')
			return $result_set->row()->category_name_bn;
		}
		else
		{
		return $result_set->row()->category_name;
		}
	}
	
	function get_product_info_by_id($product_id)
	{
	$this->db->where('product_id',$product_id);	
	$result_set=$this->db->get('gramcar_product');
	return $result_set->row();
	}
	
	function get_all_social_goods_registration_count()
	{
	$this->db->where('services_id',4); 	 // 4= Social Goods
	$this->db->where('services_status <',3); //3= cancle 4=deleted
	$query = $this->db->get('gramcar_registration_for_services');
	return $query->num_rows();	
	}
	
	function get_all_social_goods_registration_by_limit($limit, $start) {
		$this->db->where('services_id',4); 	 // 4= Social Goods
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
	
	function all_social_goods_count_query_string($searchterm)
	{
	$result = $this->db->query($searchterm);
	return $result->num_rows();		
	}
	
	function get_all_social_goods_by_limit_querystring($searchterm, $limit, $start)
	{
	$query_string=$searchterm." LIMIT $start, $limit"; 
	//echo $query_string;
	$resultSet = $this->db->query($query_string);
	return $resultSet->result();		
	}
	
	function get_registration_info_by_reg_services_id($reg_services_id)
	{
	$query = $this->db->query('SELECT registration_no FROM gramcar_registration_for_services WHERE reg_for_service_id='.$reg_services_id);
	$row = $query->row();	
	
	$this->db->where('registration_no',$row->registration_no);
	$query = $this->db->get('gramcar_registration');
	return $query->row();
	}
	
	function insert_order_info($gramcar_social_goods_order)
	{
	$this->db->insert('gramcar_social_goods_order',$gramcar_social_goods_order);
 	return $this->db->insert_id();	 // return the order id
	}
	
	function insert_order_cart_info($cart_data)
	{
	$this->db->insert('gramcar_social_goods_order_details',$cart_data);
	return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	function check_reg_services_id_is_exits($reg_services_id)
	{
	$this->db->where('reg_services_id ',$reg_services_id);
	$query = $this->db->get('gramcar_social_goods_order');
	 if ($query->num_rows() > 0) {
		return false;
	 }
	 else
	 	return true;
	}
	
	function update_social_goods_services_status($reg_data_table1,$reg_services_id)
	{
	$this->db->where('reg_for_service_id', $reg_services_id);	
	$this->db->update('gramcar_registration_for_services',$reg_data_table1);	
	return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	function social_goods_new_order_count()
	{
	$this->db->where('order_status ',0);
	$query = $this->db->get('gramcar_social_goods_order');	
	return $query->num_rows();
	}
	
	function get_all_social_goods_order_count()
	{
	$sql="SELECT count(*) as total_order
  FROM    gramcar_social_goods_order gramcar_social_goods_order
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_social_goods_order.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)";
	$query = $this->db->query($sql);	
	$row = $query->row();	
	return $row->total_order;
	}
	
	function get_all_social_goods_order_by_limit($limit, $start) {
		
		$query_string ='SELECT gramcar_registration_for_services.services_point_id,
       gramcar_registration_for_services.services_id,
       gramcar_registration_for_services.services_date,
       gramcar_registration_for_services.services_status,
       gramcar_social_goods_order.*
  FROM    gramcar_social_goods_order gramcar_social_goods_order
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_social_goods_order.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)
ORDER BY gramcar_social_goods_order.order_status ASC,
         gramcar_social_goods_order.order_time DESC LIMIT '.$start.','.$limit;
		$query = $this->db->query($query_string); 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   	}	
	
	function get_registration_info_by_order_id($order_id)
		{
		$query = $this->db->query('SELECT registration_no FROM gramcar_social_goods_order WHERE order_id='.$order_id);
		$row = $query->row();	
		
		$this->db->where('registration_no',$row->registration_no);
		$query = $this->db->get('gramcar_registration');
		return $query->row();
		}	
		
	
	function get_order_info_by_id($order_id)
	{
		$this->db->where('order_id',$order_id);
		$query = $this->db->get('gramcar_social_goods_order');
		return $query->row();	
	}
		
		
	function get_order_details_info_by_id($order_id)
	{
		$this->db->where('order_id',$order_id);
		return $this->db->get('gramcar_social_goods_order_details')->result();	
	}

	function set_social_goods_order_status($order_id,$status)
	{
	$this->db->where('order_id', $order_id);	
	$this->db->update('gramcar_social_goods_order',array('order_status' => $status,'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())));	
	return ($this->db->affected_rows() != 1) ? false : true;
	}	
	
	function product_exits_in_order_list($product_id)
	{
	$this->db->where('product_id',$product_id);
	$query = $this->db->get('gramcar_social_goods_order_details');
	if($query->num_rows()>0)
	return true;
	else
	return false;
	}
	
	function delete_product_by_id($product_id)
	{
	$this->db->where('product_id',$product_id);
	$this->db->delete('gramcar_product');
	return true;
	}
	
	
	
	function get_order_id_from_reg_service_id($reg_services_id)
	{
	$this->db->where('reg_services_id',$reg_services_id);
	$query = $this->db->get('gramcar_social_goods_order');
	return $query->row()->order_id; 
	}
	
	
	

}


/* End of file account_model.php */
/* Location: ./application/account/models/social_goods_model.php */