<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model {	
	
	
	// --------------------------------------------------------------------
	/*
	* send_message	
	*/
	
	function send_message($query_string)
	{		
		$this->load->helper('date');
		$edit_user = $this->account_model->get_username_by_id($this->session->userdata('account_id'));		
		$resultSet = $this->db->query($query_string);					
		foreach ($resultSet->result() as $row)
            {
			if($this->message_model->check_farmer_exists($row->user_id) > 0)	
			$this->db->insert('messages_out', array('from_user_id' => $edit_user, 'to_user_id' => $row->user_id, 'title' =>mysql_real_escape_string($this->input->post('message_subject')), 'message' => mysql_real_escape_string($this->input->post('message_body')), 'send_time' => mdate('%Y-%m-%d %H:%i:%s', now()) ));
			}
		
	}
		
	
	function send_message_id_list($id_list)
	{
		$this->load->helper('date');
		$edit_user = $this->account_model->get_username_by_id($this->session->userdata('account_id'));		
		$length=sizeof($id_list);
			for($i=0;$i<$length;$i++)
			{
			if($this->message_model->check_farmer_exists(trim($id_list[$i])) > 0)
			$this->db->insert('messages_out', array('from_user_id' => $edit_user, 'to_user_id' => trim($id_list[$i]), 'title' =>mysql_real_escape_string($this->input->post('message_subject')), 'message' => mysql_real_escape_string($this->input->post('message_body')), 'send_time' => mdate('%Y-%m-%d %H:%i:%s', now()) ));				
			}
	}
	
	function check_farmer_exists($userid)
	{	
	$this->db->select('count(*) as no_of_farmer');
	$this->db->from('season_wise_member_info');
	$this->db->where('user_id',$userid);
	$result = $this->db->get();
	return $result->row()->no_of_farmer;	
	}
	
	function get_all_sent_message($current_user)
	{
	$this->db->like('from_user_id', $current_user);	
	$this->db->order_by('send_time', 'desc');		
	return $this->db->get('messages_out')->result();	
	}
	
	function get_all_sent_message_bylimit($current_user, $limit, $start)
	{
	$this->db->like('from_user_id', $current_user);	
	$this->db->order_by('send_time', 'desc');		
	return $this->db->get('messages_out', $limit, $start)->result();		
	}
	
	function count_all_sent_message($current_user)
	{
	$this->db->select('count(*) as total_sent_msg');
	$this->db->from('messages_out');
	$this->db->like('from_user_id', $current_user);	
	$result = $this->db->get();
	return $result->row()->total_sent_msg;		
	}
	
}


/* End of file message_model.php */
/* Location: ./application/models/message_model.php */