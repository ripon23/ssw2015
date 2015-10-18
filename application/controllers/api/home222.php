<?php

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		$this->load->model(array('account/account_model','car/general'));
		//$this->load->language(array('mainmenu'));
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
			$this->lang->load('general', $this->config->item("default_language"));
			$this->lang->load('mainmenu', $this->config->item("default_language"));
			$this->lang->load('car', $this->config->item("default_language"));
		}
		else
		{
			$this->lang->load('general', $language);
			$this->lang->load('mainmenu', $language);
			$this->lang->load('car', $language);
		}
		
	}

	function index()
	{
		$select = '`schedule_id`, `route_id`, `car_id`, `schedule_type`, `price`, `start_node`, `end_node`, `start_time`, `end_time`';
		$schedules = $this->general->get_list_view('car_schedule', $field_name=NULL, $id=NULL, $select, 'schedule_id', 'asc', NULL, NULL);
		$route_mappings = $this->general->get_all('car_route_node_mapping');
		// Combine all these elements for display
		
		$data['schedules'] = array();
		foreach( $schedules as $schedule )
		{
			
			$current_schedule = array();
			$current_schedule['schedule_id'] = $schedule->schedule_id;
			$car_select = 'hot_line';
			$current_schedule['hot_line'] = $this->general->get_all_table_info_by_id_custom('car_info', $car_select, 'car_id', $schedule->car_id)->hot_line;
			$select_route = '`route_name_en`, `route_name_bn`';
			
			$routes = $this->general->get_all_table_info_by_id_custom('car_route', $select_route, 'route_id', $schedule->route_id);
			$current_schedule['route_name_en'] = $routes->route_name_en;
			$current_schedule['price'] = $schedule->price;
			$current_schedule['route_name_bn'] = $routes->route_name_bn;
			
			$current_schedule['start_time'] = date("g:i A", strtotime($schedule->start_time));
			
			$node_select = '`node_name_en`, `node_name_bn`, `latitude`, `longitude`';
			foreach( $route_mappings as $route_mapping )
			{
				
				if( $route_mapping->route_id == $schedule->route_id )
				{
					$current_schedule['node_list'][] = array(
						'node_id' => $route_mapping->node_id, 					
						'node_details' => $this->general->get_all_table_info_by_id_custom('car_node', $node_select, 'node_id', $route_mapping->node_id),
					);					
				}
			}
			$data['schedules'][] = $current_schedule;
		}
		echo json_encode($data['schedules']);
		//print_r($data['schedules']);
	}
}


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */