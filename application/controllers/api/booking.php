<?php
class Booking extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model', 'car/schedule_model', 'car/booking_model'));	
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
			$this->lang->load('general', $this->config->item("default_language"));
			$this->lang->load('mainmenu', $this->config->item("default_language"));
			$this->lang->load('registration_form', $this->config->item("default_language"));
			$this->lang->load('car', $this->config->item("default_language"));
		}
		else
		{
			$this->lang->load('general', $language);
			$this->lang->load('mainmenu', $language);	
			$this->lang->load('registration_form', $language);
			$this->lang->load('car', $language);
		}		
	}
		
	public function index()  
	{	
		if ($this->config->item("api_key") === base64_decode($this->input->post('api_key', TRUE)))
		{
			$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		    $this->form_validation->set_rules(
		      array(
			  	array(
		          'field' => 'route_id',
		          'label' => 'Route Name',
		          'rules' => 'trim|required'), 
		        array(
		          'field' => 'node_id',
		          'label' => 'Node Name',
		          'rules' => 'trim|required'),
		        array(
		          'field' => 'pick_up', 
		          'label' => 'Pickup Point', 
		          'rules' => 'trim|required'), 
		        array(
		          'field' => 'booking_date', 
		          'label' => 'Booking Date', 
		          'rules' => 'trim|required|xss_clean'),
				array(
		          'field' => 'int_araival_itme', 
		          'label' => 'Intended Arrival Time', 
		          'rules' => 'trim|required|xss_clean'),
				array(
		          'field' => 'drop_node', 
		          'label' => 'Drop Node', 
		          'rules' => 'trim|required'),		  
				array(
		          'field' => 'drop_point', 
		          'label' => 'Drop Point', 
		          'rules' => 'trim|required'),
				array(
		          'field' => 'no_of_set', 
		          'label' => 'No. Of Set', 
		          'rules' => 'trim|required|min_length[1]|xss_clean')
		      ));
			// Run form validation
		    if ($this->form_validation->run())
		    {
				$pickup_node = base64_decode($this->input->post('node_id', TRUE));
				$pickup_point = base64_decode($this->input->post('pick_up', TRUE));
				// Drop node information
				$drop_node = base64_decode($this->input->post('drop_node', TRUE));
				$drop_point = base64_decode($this->input->post('drop_point', TRUE));
				$route_id = base64_decode($this->input->post('route_id', TRUE));
				$booking_date = base64_decode($this->input->post('booking_date', TRUE));
				$accepted_time_delay = base64_decode($this->input->post('time_delay', TRUE));
				$arrival_time = base64_decode($this->input->post('int_araival_itme', TRUE));
				$no_of_set = base64_decode($this->input->post('no_of_set', TRUE));
				
				$time_duration = 0;
				$kilomiter = 0;
				do {
					$sche_select = 'node_id, previous_node, distance_previous, time_previous';
				    $previous_nodes_details = $this->general->get_all_table_info_by_id_custom('car_node',$sche_select, 'node_id', $drop_node);
				    $des_previous_node = $previous_nodes_details->previous_node;
				    $drop_node = $des_previous_node;
			    	$time_duration += $previous_nodes_details->time_previous;
			    	$kilomiter += $previous_nodes_details->distance_previous;

				} while ($des_previous_node != $pickup_node);

				$sche_select = 'distance_to_node, time_to_node';
						
				$pickup_point_details = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select, 'pickup_point_id', $pickup_point);

				$drop_point_details = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select, 'pickup_point_id', $drop_point);

				// Total Kilomiter
				$total_kilomiter = $kilomiter+$drop_point_details->distance_to_node+$pickup_point_details->distance_to_node;
				
				$accepted_time_delay = $this->input->post('time_delay', TRUE);
				$advance_time_with_delay = date('H:i:s', strtotime($arrival_time)+(60*$accepted_time_delay));
				$late_time_with_delay = date('H:i:s', strtotime($arrival_time)-(60*$accepted_time_delay));

				$int_arrival_time = date('H:i:s', strtotime($arrival_time));
				$estimated_pickup_time = date('H:i:s', strtotime($int_arrival_time)-((60*$time_duration)+(60*$pickup_point_details->time_to_node)+(60*$drop_point_details->time_to_node)));
						
				if ($this->booking_model->get_schedule($booking_date, $estimated_pickup_time, $int_arrival_time)) 
				{
					$query="Select * from car_booking where date_of_booking='".$booking_date."'AND status=3 AND route_id=".$route_id;
					$nth_customer= $this->general->total_count_query_string($query);
					//echo "===".$nth_customer;
					if($nth_customer>0)
					{		
						// Calculate the each node arrival time depending on the pickup time of 1st passenger
						$first_passenger_pickup_time_query="Select * from car_booking where date_of_booking='".$booking_date."' AND route_id=".$route_id." Order By booking_id  LIMIT 1";
						$first_passenger_pickup_time_array=$this->general->get_all_single_row_querystring($first_passenger_pickup_time_query);
										
						$first_passenger_pickup_time=$first_passenger_pickup_time_array->pickup_time; 	// first passenger pick-up time
						$first_passenger_pickup_node=$first_passenger_pickup_time_array->start_node; 	// first passenger start_node
						$first_passenger_pickup_point=$first_passenger_pickup_time_array->start_pickup_point; 	// first passenger pick-up point
						
						//calculate 1st paggenger pickup point to node time distance
						$pickup_point_to_node_time_distance_query="Select * from car_pickup_point WHERE pickup_point_id=".$first_passenger_pickup_point."  AND 	node_id=".$first_passenger_pickup_node;
						$pickup_point_to_node_time_distance_array=$this->general->get_all_single_row_querystring($pickup_point_to_node_time_distance_query);
						$pickup_point_to_node_time_distance=$pickup_point_to_node_time_distance_array->time_to_node;  // pickup point to node time_distance for 1st passenger
						$first_passenger_first_node_arrival_time=strtotime($first_passenger_pickup_time)+($pickup_point_to_node_time_distance*60);
						//echo "Node ID=".$first_passenger_pickup_node." Arrival Time=".date('h:i:s',$first_passenger_first_node_arrival_time)." for 1st passenger";
						
						$all_node_arrival_time_array= $this->booking_model->get_all_node_arrival_time_of_a_route($route_id,$first_passenger_pickup_node,date('H:i:s',$first_passenger_first_node_arrival_time));
						//echo "Route id=".$route_id.",First node id=".$all_node_arrival_time_array;
								
						$arrival_node_array_key=$this->searchForId($drop_node, $all_node_arrival_time_array);
						$pickup_node_array_key=$this->searchForId($pickup_node, $all_node_arrival_time_array);				
						
						
						
						// How may available seat? 
						$schedules = $this->booking_model->get_schedule($booking_date, $all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time'], $all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time']);
						
						//echo "car id=".$schedules->car_id;
						
						$number_of_passenger_in_a_car=$this->booking_model->get_number_of_passenger_in_a_car($schedules->car_id, $booking_date);
						$car_capacity=$this->booking_model->get_car_capacity($schedules->car_id);
						
						$per_km_cost=$this->config->item('per_km_cost');
						$route_info= $this->general->get_all_table_info_by_id('car_route', 'route_id', $route_id);
						$route_fixed_cost= $route_info->fixed_cost;
						$passenger_cost_per_seat=round(($per_km_cost*$total_kilomiter)+($route_fixed_cost/($number_of_passenger_in_a_car+$no_of_set)),2);
						$passenger_total_cost=$passenger_cost_per_seat*$no_of_set;
						
						if(($number_of_passenger_in_a_car+$no_of_set)<=$car_capacity )
						{
							$response["success"] = 1;
							$response['booking_date'] = $booking_date;
							$response['fare_cost'] = $passenger_total_cost;
							$response['pickup_point'] = $this->booking_model->get_pickup_point_name_from_id($pickup_point);
							$response['drop_point'] = $this->booking_model->get_pickup_point_name_from_id($drop_point);
							$response['total_kilomiter'] = $total_kilomiter;
							$response['total_passenger'] = $number_of_passenger_in_a_car+$no_of_set;
							$response['per_km_cost'] = $per_km_cost;
							$response['fixed_cost'] = $route_fixed_cost;
							$response['pickup_time'] = $all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time'];
							$response['drop_time'] = $all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time'];
							echo json_encode($response);							
						}
						else
						{
							$response["success"] = 0;
							$response["message"] = 'Sorry! there is only '.($car_capacity-$number_of_passenger_in_a_car).' seat available';
							echo json_encode($response);
						}
					}
					else
					{
						// definitly 1st customer
						$schedules = $this->booking_model->get_schedule($booking_date, $estimated_pickup_time, $int_arrival_time);
						$number_of_passenger_in_a_car=$this->booking_model->get_number_of_passenger_in_a_car($schedules->car_id, $booking_date);				
						//print_r($schedules);
						
						$per_km_cost=$this->config->item('per_km_cost');
						$route_info= $this->general->get_all_table_info_by_id('car_route', 'route_id', $route_id);
						$route_fixed_cost= $route_info->fixed_cost;
						//$passenger_cost=round(($per_km_cost*$total_kilomiter)+($route_fixed_cost/($number_of_passenger_in_a_car+$no_of_set)),2);				
						
						$passenger_cost_per_seat=round(($per_km_cost*$total_kilomiter)+($route_fixed_cost/($number_of_passenger_in_a_car+$no_of_set)),2);
						$passenger_total_cost=$passenger_cost_per_seat*$no_of_set;
						
						$number_of_passenger_in_a_car=$this->booking_model->get_number_of_passenger_in_a_car($schedules->car_id,$this->input->post('booking_date', TRUE));
						$car_capacity=$this->booking_model->get_car_capacity($schedules->car_id);
						
						if(($number_of_passenger_in_a_car+$no_of_set)<=$car_capacity )
						{
							$response["success"] = 1;
							$response['booking_date'] = $booking_date;
							$response['fare_cost'] = $passenger_total_cost;
							$response['pickup_point'] = $this->booking_model->get_pickup_point_name_from_id($pickup_point);
							$response['drop_point'] = $this->booking_model->get_pickup_point_name_from_id($drop_point);
							$response['total_kilomiter'] = $total_kilomiter;
							$response['total_passenger'] = $number_of_passenger_in_a_car+$no_of_set;
							$response['per_km_cost'] = $per_km_cost;
							$response['fixed_cost'] = $route_fixed_cost;
							$response['pickup_time'] = $estimated_pickup_time;
							$response['drop_time'] = $int_arrival_time;
							echo json_encode($response);
						}
						else
						{
							$response["success"] = 0;
							$response["message"] = 'Sorry! there is only '.($car_capacity-$number_of_passenger_in_a_car).' seat available';
							echo json_encode($response);
							//echo '<div class="alert alert-error"></div>';	
						}						
					}					
				}
				else
				{
					$response["success"] = 0;
					$response["message"] = "Sorry! Our vehical is not available in this time";
					echo json_encode($response);	
				}
			}
			else
			{
				$response["success"] = 0;
				//$response["message"] = validation_errors();
				$response["message"] = "Form validation error";
				echo json_encode($response);
			}
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "API key is wrong";
			echo json_encode($response); 
		}						
	}

	public function save_booking()
	{
		if ($this->config->item("api_key") === base64_decode($this->input->post('api_key', TRUE)))
		{
			$this->form_validation->set_rules(
		      array(
			  	array(
		          'field' => 'route_id',
		          'label' => 'Route Name',
		          'rules' => 'trim|required'), 
		        array(
		          'field' => 'node_id',
		          'label' => 'Node Name',
		          'rules' => 'trim|required'),
		        array(
		          'field' => 'pick_up', 
		          'label' => 'Pickup Point', 
		          'rules' => 'trim|required'), 
		        array(
		          'field' => 'booking_date', 
		          'label' => 'Booking Date', 
		          'rules' => 'trim|required|xss_clean'),
				array(
		          'field' => 'int_araival_itme', 
		          'label' => 'Intended Arrival Time', 
		          'rules' => 'trim|required|xss_clean'),
				array(
		          'field' => 'drop_node', 
		          'label' => 'Drop Node', 
		          'rules' => 'trim|required'),		  
				array(
		          'field' => 'drop_point', 
		          'label' => 'Drop Point', 
		          'rules' => 'trim|required'),
				array(
		          'field' => 'no_of_set', 
		          'label' => 'No. Of Set', 
		          'rules' => 'trim|required|min_length[1]|xss_clean')
		      )
			);
			// Run form validation
		    if ($this->form_validation->run())
		    {
		    	$data['account'] = $this->account_model->get_by_id(base64_decode($this->input->post('user_id', TRUE)));		
				
				$pickup_node = base64_decode($this->input->post('node_id', TRUE));
				$pickup_point = base64_decode($this->input->post('pick_up', TRUE));
				// Drop node information
				$drop_node = base64_decode($this->input->post('drop_node', TRUE));
				$drop_point = base64_decode($this->input->post('drop_point', TRUE));
				$route_id = base64_decode($this->input->post('route_id', TRUE));
				$booking_date = base64_decode($this->input->post('booking_date', TRUE));
				$accepted_time_delay = base64_decode($this->input->post('time_delay', TRUE));
				$arrival_time = base64_decode($this->input->post('int_araival_itme', TRUE));
				$no_of_set = base64_decode($this->input->post('no_of_set', TRUE));


					
				$fare_cost = base64_decode($this->input->post('fare_cost', TRUE));	
				
				$estimated_pickup_time = base64_decode($this->input->post('estimated_pickup_time', TRUE));
				$int_arrival_time = base64_decode($this->input->post('int_arrival_time', TRUE));
				$total_kilomiter = base64_decode($this->input->post('total_kilomiter', TRUE));
				
				$schedules = $this->booking_model->get_schedule($booking_date, $estimated_pickup_time, $int_arrival_time);				
				
				$data = array(
					'user_id' => $data['account']->id, 
					'schedule_id' => $schedules->schedule_id,
					'car_id'=>$schedules->car_id,
					'date_of_booking' => $booking_date,
					'no_of_set' => $no_of_set,
					'route_id' => $route_id,
					'start_node' => $pickup_node,
					'start_pickup_point' => $pickup_point,
					'end_node' => $drop_node,
					'end_pickup_point' => $drop_point,
					'pickup_time' => date('H:i:s',$estimated_pickup_time),
					'arrival_time' => date('H:i:s',$int_arrival_time),
					'distance_in_km' => $total_kilomiter,
					'accepted_time_delay' => $accepted_time_delay,
					'create_user_id' => $data['account']->username,
					'status' => 3,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', now())			
				);
				$booking_id = $this->general->save_into_table_and_return_insert_id('car_booking', $data);
			
				$cost_array=array(
					'booking_id' => $booking_id,
					'fare_cost' => $fare_cost,
					'modification_time' =>mdate('%Y-%m-%d %H:%i:%s', now()),
					'status'=> 1
				);
			
				$this->general->save_into_table('car_drt_cost', $cost_array);
			

				if($booking_id)
			 	{
			  		// Pickup Point details 
			  		$pickup_point_name = $this->booking_model->get_pickup_point_name_from_id($pickup_point);
			
			  		// Drop off points
			  		$drop_point_name= $this->booking_model->get_pickup_point_name_from_id($drop_point);
				
					$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));		
					// Content array for email template
					$email_array = array(
						'username' => $data['account']->username, 
						'booking_date'=> date("l, d F, Y", strtotime($booking_date)), 
						'pickup_point' => $pickup_point_name, 
						'pickup_time' => date('H:i:s',$estimated_pickup_time), 
						'drop_point' => $drop_point_name, 
						'arrival_time' => date('H:i:s',$int_arrival_time), 
						'no_of_set' => $no_of_set,
						'amount_of_cost' => $fare_cost
					);
			  
					//print_r($data['account']);

					//echo "Email=".$data['account']->email;

					// Load email library
					$this->load->library('email');

					// Set up email preferences
					$config['mailtype'] = 'html';

					// Initialise email lib
					$this->email->initialize($config);
					  

					// Send reset password email
					$this->email->from($this->config->item('password_reset_email'), 'GramCar Booking');
					$this->email->to($data['account']->email);
					$this->email->subject('SSW GramCar booking information of '.date("l, d F, Y", strtotime($this->input->post('booking_date', TRUE))));
					$this->email->message($this->load->view('car/booking_details_email', $email_array, TRUE));
					//$this->email->send();
					if($this->email->send())
					{
						$response["success"] = 1;
						$response["message"] = "We have sent an email about yur booking details";
						echo json_encode($response);					  
					}
					else
					{
						$response["success"] = 1;
						$response["message"] = "Email sending failed";
						echo json_encode($response);
					}		  
			 	}		
				else
				{
					$response["success"] = 0;
					$response["message"] = "Error! something goes wrong.";
					echo json_encode($response);
				}
			}
			else
			{
				$response["success"] = 0;
				$response["message"] = "Form validation error";
				echo json_encode($response);
			}
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "API key is wrong";
			echo json_encode($response);
		}
	}
	
	public function get_route_node_pickuppoint(){
		if ($this->config->item("api_key") === base64_decode($this->input->post('api_key', TRUE)))
		{
			$select_route = "route_id, route_name_en";
			$response['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select_route, 'route_id', 'desc', NULL, NULL);
			$select_node = "`node_id`, `route_id`, `previous_node`, `node_name_en`";
			$response['all_nodes'] = $this->general->get_list_view('car_node', 'enable', 1, $select_node, 'node_id', 'desc', NULL, NULL);
			$select_pickup = "`pickup_point_id`, `node_id`, `pickup_point_en`";
			$response['all_pickup_point'] = $this->general->get_list_view('car_pickup_point', 'enable', 1, $select_pickup, 'pickup_point_id', 'desc', NULL, NULL);
			$response["success"] = 1;
			echo json_encode($response);
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "API key is wrong";
			echo json_encode($response);
		}
	}
	function searchForId($id, $array) {
	   foreach ($array as $key => $val) {
		   if ($val['node_id'] === $id) {
			   return $key;
		   }
	   }
	   return null;
	}
	
  function save($id=null)
  {
    // Keep track if this is a new user
   		 $is_new = empty($id);
	
    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().uri_string()));
    }
	
	if (empty($id) )
    {
      $this->session->set_flashdata('parmission', 'This is worng url');
      redirect(base_url());
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('car_booking'))
    {
      $this->session->set_flashdata('parmission', 'You have no permission to booking');
      redirect(base_url());
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
	
    $data['action'] = 'create';
	
	$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
	  	/*array(
          'field' => 'id',
          'label' => 'Schedule',
          'rules' => 'trim|required|numeric'), */
        array(
          'field' => 'booking_date',
          'label' => 'lang:booking_date',
          'rules' => 'trim|required'),
        array(
          'field' => 'rise_station', 
          'label' => 'lang:rise_station', 
          'rules' => 'trim|required'), 
        array(
          'field' => 'off_station', 
          'label' => 'lang:off_station', 
          'rules' => 'trim|required'),
		array(
          'field' => 'no_of_set', 
          'label' => 'lang:no_of_set', 
          'rules' => 'trim|required'),
		array(
          'field' => 'booking_date', 
          'label' => 'lang:booking_date', 
          'rules' => 'trim|required'),		  
		array(
          'field' => 'price', 
          'label' => 'lang:price', 
          'rules' => 'trim|required'),
      ));

    // Run form validation
    if ($this->form_validation->run())
    {
		$sche_select = 'car_id,start_time';
		$schedule_details = $this->general->get_all_table_info_by_id_custom('car_schedule',$sche_select, 'schedule_id', $id);
		
		if ($this->input->post('no_of_set', TRUE)<1)
		{
			$data['no_of_seat_error'] = lang('book_error');			
		}
		
		elseif($this->avaliable_seat($id,$this->input->post('booking_date', TRUE))+$this->input->post('no_of_set', TRUE)>$this->get_car_no_of_seat( $schedule_details->car_id))
		{
			$this->session->set_flashdata('message_error', lang('not_available_seat'));
			redirect(uri_string());
		}
		elseif($this->input->post('booking_date', TRUE)<= date('Y-m-d'))
		{
			$this->session->set_flashdata('message_error', lang('ivalid_date'));
			redirect(uri_string());
		}		
		else
		{	
			$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
				'schedule_id' => $id, 
				'user_id' => $data['account']->id,
				'no_of_set' => $this->input->post('no_of_set', TRUE),
				'per_seat_price' => $this->input->post('price', TRUE),
				'date_of_booking' => $this->input->post('booking_date', TRUE),
				'start_node' => $this->input->post('rise_station', TRUE),
				'end_node' => $this->input->post('off_station', TRUE),
				'create_user_id' => $data['account']->username,
				'status' => 3,
				'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
			);
			$route_id = $this->general->save_into_table_and_return_insert_id('car_booking', $data);
			
			$this->session->set_flashdata('message_success', lang('success_booking'));
			redirect(uri_string());
        }        
	}
	
	//$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
	$data['schedule_details'] = $this->general->get_all_table_info_by_id('car_schedule', 'schedule_id', $id);
		
	$data['route_details'] = $this->general->get_all_table_info_by_id('car_route', 'route_id', $data['schedule_details']->route_id);
	
	$data['route_nodes'] = $this->general->get_all_table_info_by_id_asc_desc_with_join('car_route_node_mapping', 'route_id', $data['schedule_details']->route_id, 'map_id', 'asc');
    
	$this->load->view('car/booking_save', $data);
  }
  
  function avaliable_seat($schedule_id,$date_of_booking)
  {
	  $parm_array = array('schedule_id'=>$schedule_id,'date_of_booking'=>$date_of_booking);	  
	  return $this->general->total_sum('car_booking', 'no_of_set', $parm_array)->no_of_set;
  }
  
  function get_car_no_of_seat($car_id)
  {
	$select = 'no_of_set';
	return $this->general->get_all_table_info_by_id_custom('car_info',$select, 'car_id', $car_id)->no_of_set;
  }
	
  function delete($id)
  {
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}		
		
		// Check if they are allowed to car_delete_route
		if ( !empty($id) && ! $this->authorization->is_permitted('car_delete_route') && empty($id))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete route');
		  redirect(base_url().'car/routes');
		}
		
		if (!empty($id))
		{
			$this->general->delete_from_table('car_route', 'route_id', $id);
			$this->session->set_flashdata('message_success', 'Your data successfully deleted');
		  	redirect(base_url().'car/routes');
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
		  	redirect(base_url().'car/routes');
		}
  }
  
	function add_mapping($id=null)
	{
		// Keep track if this is a new user
    	$is_new = empty($id);
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_route'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage route');
			//redirect(uri_string());
			redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Route Mapping';
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'asc', NULL, NULL);
		$data['all_nodes'] = $this->general->get_list_view('car_node', $field_name=NULL, $id=NULL, $select=NULL, 'node_id', 'desc', NULL, NULL);
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'route_id',
          'label' => 'lang:select_route',
          'rules' => 'trim|required'),
        array(
          'field' => 'node_id', 
          'label' => 'lang:select_node', 
          'rules' => 'trim|required'), 
        array(
          'field' => 'prev_node_id', 
          'label' => 'lang:select_prev_node', 
          'rules' => 'trim|required'),
		array(
          'field' => 'next_node_id', 
          'label' => 'lang:select_next_node', 
          'rules' => 'trim|required')
      ));
	  
	  	// Run form validation
		if ($this->form_validation->run())
		{
			if( empty($id) ) {
				$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'node_id' => $this->input->post('node_id', TRUE), 
						'prv_node_id' => $this->input->post('prev_node_id', TRUE),
						'next_node_id' => $this->input->post('next_node_id', TRUE),
						'duration_to_next' => $this->input->post('duration_to_next', TRUE),
						'create_user_id' => $data['account']->username,
						'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				$mapping_id = $this->general->save_into_table_and_return_insert_id('car_route_node_mapping', $data);
				$this->session->set_flashdata('message_success', lang('success_add'));
				redirect('car/routes/add_mapping');
				
			}
			// Update existing News
			else 
			{
				$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'node_id' => $this->input->post('node_id', TRUE), 
						'prv_node_id' => $this->input->post('prev_node_id', TRUE),
						'next_node_id' => $this->input->post('next_node_id', TRUE),
						'duration_to_next' => $this->input->post('duration_to_next', TRUE),
						'update_user_id' => $data['account']->username,
						'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				
				$this->general->update_table('car_route_node_mapping', $data,'map_id',$id);
				$this->session->set_flashdata('message_success', lang('success_update'));
				redirect('car/routes/add_mapping');
			}
		}
		// Get the account to update
		if( ! $is_new )
		{
		  $data['update_details'] = $this->general->get_all_table_info_by_id('car_route_node_mapping', 'map_id', $id);
		  $data['action'] = 'update';
		}
		$this->load->view('car/mapping_save', $data);	  
	}

	

	function get_chile($table_name, $where_field, $where_field_value)
	{
		$select = "node_id, node_name_en";
		$nodes = $this->general->get_list_view($table_name, $where_field, $where_field_value, $select, NULL, NULL, NULL, NULL);

	  	if (count($nodes)>0)
	  	{
		  echo "<option value=''>Select Node </option>";
		  foreach ($nodes as $node)
		  {
			  $val ="";			  
			  $val.="<option value='".$node->node_id."'>".$node->node_name_en."</option>";
			  echo $val;  
		  }
	  	}
	  	else
	  	{
			echo "<option value=''>Not Found</option>";  
	  	}
	}

	function get_pickup_point($table_name, $where_field, $where_field_value){
		$select = "pickup_point_id, pickup_point_en";
		$pickup_points = $this->general->get_list_view($table_name, $where_field, $where_field_value, $select, NULL, NULL, NULL, NULL);

	  	if (count($pickup_points)>0)
	  	{
		  echo "<option value=''>Select Pickup Point</option>";
		  foreach ($pickup_points as $pickup_point)
		  {
			  $val ="";			  
			  $val.="<option value='".$pickup_point->pickup_point_id."'>".$pickup_point->pickup_point_en."</option>";
			  echo $val;  
		  }
	  	}
	  	else
	  	{
			echo "<option value=''>Not Found</option>";  
	  	}
	}

	function maximumCheck($num)
	{
		if ($num < 10 && $num > 0)
	{
	    $this->form_validation->set_message(
            'maximumCheck',
            'The %s field must be less than 10 and getter than 0'
	    );
	    return FALSE;
	}
	else
	{
	    return TRUE;
	}
	}

	function check_schedule($schedule_date, $stime, $etime){
		return $this->schedule_model->get_car_schedule($schedule_date, $car_id, $stime, $etime)? TRUE:FALSE;
	}

	function get_start_time(){
		return $this->booking_model->get_start_time($schedule_date, $car_id, $stime, $etime);
	}
	
	function get_node()
	{
		
		$table_name=$this->input->post('table_name');
		$where_field=$this->input->post('field_name');
		$where_field_value=$this->input->post('route_id');
		
		//echo "hi----";
		echo "table_name=".$table_name.",field_name=".$where_field.",where_field_value=".$where_field_value;
		
		$select = "node_id, node_name_en";
		$nodes = $this->general->get_list_view($table_name, $where_field, $where_field_value, $select, NULL, NULL, NULL, NULL);

	  	if (count($nodes)>0)
	  	{
		  echo "<option value=''>Select Node </option>";
		  foreach ($nodes as $node)
		  {
			  $val ="";			  
			  $val.="<option value='".$node->node_id."'>".$node->node_name_en."</option>";
			  echo $val;  
		  }
	  	}
	  	else
	  	{
			echo "<option value=''>Not Found</option>";  
	  	}
	}
	
}// END Class

?>