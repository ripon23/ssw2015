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
	
	public function save_booking()
	{
	$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
	
	
	
	
	$pickup_node = $this->input->post('node_id', TRUE);
	$pickup_point = $this->input->post('pick_up', TRUE);
	// Drop node information
	$drop_node = $this->input->post('drop_node', TRUE);
	$drop_point = $this->input->post('drop_point', TRUE);
	$route_id= $this->input->post('route_id', TRUE);
	$booking_date=$this->input->post('booking_date', TRUE);
	$accepted_time_delay = $this->input->post('time_delay', TRUE);
	$arrival_time= $this->input->post('int_araival_itme', TRUE);	
	$fare_cost= $this->input->post('fare_cost', TRUE);	
	
	$estimated_pickup_time=$this->input->post('estimated_pickup_time', TRUE);
	$int_arrival_time=$this->input->post('int_arrival_time', TRUE);
	$total_kilomiter=$this->input->post('total_kilomiter', TRUE);
	
	$schedules = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $estimated_pickup_time, $int_arrival_time);				
	
	$data = array(
					'user_id' => $data['account']->id, 
					'schedule_id' => $schedules->schedule_id,
					'car_id'=>$schedules->car_id,
					'date_of_booking' => $this->input->post('booking_date', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'start_node' => $this->input->post('node_id', TRUE),
					'start_pickup_point' => $this->input->post('pick_up', TRUE),
					'end_node' => $this->input->post('drop_node', TRUE),
					'end_pickup_point' => $this->input->post('drop_point', TRUE),
					'pickup_time' => date('H:i:s',$estimated_pickup_time),
					'arrival_time' => date('H:i:s',$int_arrival_time),
					'distance_in_km' => $total_kilomiter,
					'accepted_time_delay' => $this->input->post('time_delay', TRUE),
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
	   'booking_date'=> date("l, d F, Y", strtotime($this->input->post('booking_date', TRUE))), 
	   'pickup_point' => $pickup_point_name, 
	   'pickup_time' => date('H:i:s',$estimated_pickup_time), 
	   'drop_point' => $drop_point_name, 
	   'arrival_time' => date('H:i:s',$int_arrival_time), 
	   'no_of_set' => $this->input->post('no_of_set', TRUE),
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
			  // Load reset password sent view
			  echo '<div class="alert alert-success">Successfully book your seat. Thank you for using our services.</div>';
		  }
		  else
		  {
			  //if the email could not be sent it will display the error
			  //should not happen if you have email configured correctly
			  echo $this->email->print_debugger();
			  echo '<div class="alert alert-error">Error! something goes wrong.</div>';
		  }
	  
	 	}		
		else
		{
		echo '<div class="alert alert-error">Error! something goes wrong.</div>';
		}
	
	
	}
	
	
	
	public function check_booking()
	{
	$pickup_node = $this->input->post('node_id', TRUE);
	$pickup_point = $this->input->post('pick_up', TRUE);
	// Drop node information
	$drop_node = $this->input->post('drop_node', TRUE);
	$drop_point = $this->input->post('drop_point', TRUE);
	$route_id= $this->input->post('route_id', TRUE);
	$booking_date=$this->input->post('booking_date', TRUE);
	$accepted_time_delay = $this->input->post('time_delay', TRUE);
	$arrival_time= $this->input->post('int_araival_itme', TRUE);
	$no_of_set =$this->input->post('no_of_set', TRUE);
	
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
			$advance_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))+(60*$accepted_time_delay));
			$late_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))-(60*$accepted_time_delay));

			$int_arrival_time = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE)));
			$estimated_pickup_time = date('H:i:s', strtotime($int_arrival_time)-((60*$time_duration)+(60*$pickup_point_details->time_to_node)+(60*$drop_point_details->time_to_node)));
			
			//echo "estimated_pickup_time=".$estimated_pickup_time."<br>";
			if ($this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $estimated_pickup_time, $int_arrival_time)) 
			{
			$query="Select * from car_booking where date_of_booking='".$this->input->post('booking_date', TRUE)."'AND status=3 AND route_id=".$route_id;
			$nth_customer= $this->general->total_count_query_string($query);
			//echo "===".$nth_customer;
				if($nth_customer>0)
				{												
				// might be nth customer

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
				
								
				
				$arrival_node_array_key=$this->searchForId($this->input->post('drop_node', TRUE), $all_node_arrival_time_array);
				$pickup_node_array_key=$this->searchForId($this->input->post('node_id', TRUE), $all_node_arrival_time_array);				
				
				
				
				// How may available seat? 
				$schedules = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time'], $all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time']);
				
				//echo "car id=".$schedules->car_id;
				
				$number_of_passenger_in_a_car=$this->booking_model->get_number_of_passenger_in_a_car($schedules->car_id,$this->input->post('booking_date', TRUE));
				$car_capacity=$this->booking_model->get_car_capacity($schedules->car_id);
				
				$per_km_cost=$this->config->item('per_km_cost');
				$route_info= $this->general->get_all_table_info_by_id('car_route', 'route_id', $route_id);
				$route_fixed_cost= $route_info->fixed_cost;
				$passenger_cost_per_seat=round(($per_km_cost*$total_kilomiter)+($route_fixed_cost/($number_of_passenger_in_a_car+$no_of_set)),2);
				$passenger_total_cost=$passenger_cost_per_seat*$no_of_set;
				
				if(($number_of_passenger_in_a_car+$no_of_set)<=$car_capacity )
				{
				//echo "We will able to reach in your destination <strong>".$this->booking_model->get_node_name_from_id($this->input->post('drop_node', TRUE))."</strong> approximately at <strong>".$all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time']."</strong> And your pickup time will be <strong>".$all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time']."</strong> approximately from <strong>".$this->booking_model->get_node_name_from_id($this->input->post('node_id', TRUE))."</strong>. Your Fare cost:<strong>".$passenger_total_cost."TK</strong>. If you agree click confirm <button id='btnconfirm' onClick='confirm_booking(".strtotime($all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time']).",".strtotime($all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time']).",".$total_kilomiter.",".$passenger_total_cost.")' name='btnconfirm' class='btn-mini btn-success'>Confirm</button>";
				
				echo '<table width="100%" class="table table-bordered">
				  <tr class="success">
					<td colspan="2">Booking Date: <strong>'.$this->input->post('booking_date', TRUE).'</strong></td>
					<td>Fare Cost: <strong>'.$passenger_total_cost.' TK </strong></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap">Pickup Point: <strong>'.$this->booking_model->get_node_name_from_id($this->input->post('node_id', TRUE)).'</strong></td>
					<td nowrap="nowrap">Drop Point: <strong>'.$this->booking_model->get_node_name_from_id($this->input->post('drop_node', TRUE)).'</strong></td>
					<td rowspan="2" align="left" valign="top">Total distance : '.$total_kilomiter.' km<br />
					  Total passenger (so far + this) : '.($number_of_passenger_in_a_car+$no_of_set).'<br />
					  <br />
					  <small><em>Per km cost : '.$per_km_cost.' TK, Fixed cost : '.$route_fixed_cost.' TK, Total Cost = ((Total distance * Per km cost)+ (Fixed cost / Total passenger)) * No of seat</em></small></td>
				  </tr>
				  <tr>
					<td>Pickup Time: <strong>'.$all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time'].'</strong></td>
					<td>Drop Time: <strong>'.$all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time'].'</strong></td>
				  </tr>
				  <tr>
					<td colspan="3">If you agree click confirm <button id="btnconfirm" onClick="confirm_booking('.strtotime($all_node_arrival_time_array[$pickup_node_array_key]['node_arrival_time']).','.strtotime($all_node_arrival_time_array[$arrival_node_array_key]['node_arrival_time']).','.$total_kilomiter.','.$passenger_total_cost.')" name="btnconfirm" class="btn-mini btn-success">Confirm</button></td>					
				  </tr>
				</table>';
				}
				else
				{
				echo '<div class="alert alert-error">Sorry! there is only '.($car_capacity-$number_of_passenger_in_a_car).' seat available</div>';	
				}
				
				
				//echo "<pre>";
				//print_r($all_node_arrival_time_array);
				//echo "</pre>";
			
			
			
				}
				else
				{
				// definitly 1st customer
				$schedules = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $estimated_pickup_time, $int_arrival_time);
				$number_of_passenger_in_a_car=$this->booking_model->get_number_of_passenger_in_a_car($schedules->car_id,$this->input->post('booking_date', TRUE));				
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
					//echo "Pickup Time:<strong>".$estimated_pickup_time."</strong> from pick-up point:<strong>".$this->booking_model->get_pickup_point_name_from_id($this->input->post('pick_up', TRUE))."</strong>, Arrival time:<strong>".$int_arrival_time."</strong> drop point:<strong>".$this->booking_model->get_pickup_point_name_from_id($this->input->post('drop_point', TRUE))."</strong>.Your Fare cost:<strong>".$passenger_total_cost."TK</strong>. If you agree click confirm <button id='btnconfirm' onClick='confirm_booking(".strtotime($estimated_pickup_time).",".strtotime($int_arrival_time).",".$total_kilomiter.",".$passenger_total_cost.")' name='btnconfirm' class='btn-mini btn-success'>Confirm</button>";
					echo '<table width="100%" class="table table-bordered">
				  <tr class="success">
					<td colspan="2">Booking Date: <strong>'.$this->input->post('booking_date', TRUE).'</strong></td>
					<td>Fare Cost: <strong>'.$passenger_total_cost.' TK </strong></td>
				  </tr>
				  <tr>
					<td nowrap="nowrap">Pickup Point: <strong>'.$this->booking_model->get_pickup_point_name_from_id($this->input->post('pick_up', TRUE)).'</strong></td>
					<td nowrap="nowrap">Drop Point: <strong>'.$this->booking_model->get_pickup_point_name_from_id($this->input->post('drop_point', TRUE)).'</strong></td>
					<td rowspan="2" align="left" valign="top">Total distance : '.$total_kilomiter.' km<br />
					  Total passenger (so far + this) : '.($number_of_passenger_in_a_car+$no_of_set).'<br />
					  <br />
					  <small><em>Per km cost : '.$per_km_cost.' TK, Fixed cost : '.$route_fixed_cost.' TK, Total Cost = ((Total distance * Per km cost)+ (Fixed cost / Total passenger)) * No of seat</em></small></td>
				  </tr>
				  <tr>
					<td>Pickup Time: <strong>'.$estimated_pickup_time.'</strong></td>
					<td>Drop Time: <strong>'.$int_arrival_time.'</strong></td>
				  </tr>
				  <tr>
					<td colspan="3">If you agree click confirm <button id="btnconfirm" onClick="confirm_booking('.strtotime($estimated_pickup_time).','.strtotime($int_arrival_time).','.$total_kilomiter.','.$passenger_total_cost.')" name="btnconfirm" class="btn-mini btn-success">Confirm</button></td>					
				  </tr>
				</table>';
					
					
					}
					else
					{
					echo '<div class="alert alert-error">Sorry! there is only '.($car_capacity-$number_of_passenger_in_a_car).' seat available</div>';	
					}
				
				}
			
			
			//echo "estimated_pickup_time=".$estimated_pickup_time;
			}
			else
			{			
			echo '<div class="alert alert-error">Sorry! Our vehicle is not available in this time </div>';	
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
	
	
	public function check_booking2()
	{
	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access car_booking');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Booking Car';	
		$data['action'] = 'create';
	
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
	          'rules' => 'trim|required|is_natural_no_zero|min_length[1]|min_length[1]|xss_clean')
	      ));
	    // Run form validation
	    if ($this->form_validation->run())
	    {	
		
		// Pickup Informations
			$pickup_node = $this->input->post('node_id', TRUE);
			$pickup_point = $this->input->post('pick_up', TRUE);
			// Drop node information
			$drop_node = $this->input->post('drop_node', TRUE);
			$drop_point = $this->input->post('drop_point', TRUE);
			$route_id= $this->input->post('route_id', TRUE);
			$booking_date=$this->input->post('booking_date', TRUE);
			$accepted_time_delay = $this->input->post('time_delay', TRUE);
			$arrival_time= $this->input->post('int_araival_itme', TRUE);
			
			//echo "int araival time:".$arrival_time;
			
			// Check the start time of the car and arrival time is in the car schedule
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
			$advance_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))+(60*$accepted_time_delay));
			$late_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))-(60*$accepted_time_delay));

			$int_arrival_time = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE)));
			$estimated_pickup_time = date('H:i:s', strtotime($int_arrival_time)-((60*$time_duration)+(60*$pickup_point_details->time_to_node)+(60*$drop_point_details->time_to_node)));
			
			if ($this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $estimated_pickup_time, $int_arrival_time)) 
			{
			$query="Select * from car_booking where date_of_booking='".$booking_date."' AND route_id=".$route_id;
			$nth_customer= $this->general->total_count_query_string($query);
				if($nth_customer>0)
				{
				// might be nth customer
				
				
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
				echo "<pre>";
				print_r($all_node_arrival_time_array);
				echo "</pre>";
				}
				else
				{
				// definitly 1st customer	
				echo "1st customer";	
				}
			
			
			echo "estimated_pickup_time=".$estimated_pickup_time;
			}
			else
			{
			echo "Not in our schedule time.";	
			}
			
			
		
		
		}
		
		$select = "route_id, route_name_en";
		$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select, 'route_id', 'desc', NULL, NULL);		
		$this->load->view('car/booking_form', isset($data) ? $data : NULL);	
		
	}
	
	
	public function index()  
	{	
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access car_booking');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Booking Car';	
		$data['action'] = 'create';
	
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
	          'rules' => 'trim|required|is_natural_no_zero|min_length[1]|min_length[1]|xss_clean')
	      ));
	    // Run form validation
	    if ($this->form_validation->run())
	    {			
			// Pickup Informations
			$pickup_node = $this->input->post('node_id', TRUE);
			$pickup_point = $this->input->post('pick_up', TRUE);
			// Drop node information
			$drop_node = $this->input->post('drop_node', TRUE);
			$drop_point = $this->input->post('drop_point', TRUE);

			$time_dureation = 0;
			$kilomiter = 0;
			do {
				$sche_select = 'node_id, previous_node, distance_previous, time_previous';
			    $previous_nodes_details = $this->general->get_all_table_info_by_id_custom('car_node',$sche_select, 'node_id', $drop_node);
			    $des_previous_node = $previous_nodes_details->previous_node;
			    $drop_node = $des_previous_node;
		    	$time_dureation += $previous_nodes_details->time_previous;
		    	$kilomiter += $previous_nodes_details->distance_previous;

			} while ($des_previous_node != $pickup_node);

			$sche_select = 'distance_to_node, time_to_node';
			$pickup_point_details = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select, 'pickup_point_id', $pickup_point);

			$drop_point_details = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select, 'pickup_point_id', $drop_point);

			// Total Kilomiter
			$total_kilomiter = $kilomiter+$drop_point_details->distance_to_node+$pickup_point_details->distance_to_node;
			
			$accepted_time_delay = $this->input->post('time_delay', TRUE);
			$advance_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))+(60*$accepted_time_delay));
			$late_time_with_delay = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE))-(60*$accepted_time_delay));

			$int_araival_itme = date('H:i:s', strtotime($this->input->post('int_araival_itme', TRUE)));
			$stimated_pickup_time = date('H:i:s', strtotime($int_araival_itme)-((60*$time_dureation)+(60*$pickup_point_details->time_to_node)+(60*$drop_point_details->time_to_node)));
			
			if ($this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $stimated_pickup_time, $int_araival_itme)) {

				$schedules = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), $stimated_pickup_time, $int_araival_itme);
				
				$get_booking = $this->booking_model->get_booking($schedules->schedule_id);
				if ($get_booking) {
					# In current booking
					$next_node =  NULL;
					$time_summery = 0;
					while ( $node_details = $this->booking_model->get_node_route('car_node',NULL, array('previous_node'=>$next_node, 'route_id'=>$this->input->post('route_id', TRUE)))) {
						//echo "<pre>"; print_r($node_details); echo "</pre><hr>"; 
						$next_node = $node_details->node_id;
						$time_summery += $node_details->time_previous;
						$current_node = $get_booking->start_node;
						$totla_minute = 0;
						do {
							$current_details = $this->booking_model->get_node_route('car_node',NULL, array('previous_node'=>$current_node, 'route_id'=>$this->input->post('route_id', TRUE)));
							$totla_minute += $current_details->time_previous;
							$current_node = $current_details->node_id;
						} while ( $current_node != $this->input->post('drop_node', TRUE));
						 // echo "Arrival Time ".date('h:i:s', strtotime($get_booking->pickup_time)+(60*$totla_minute));
						 // echo "<pre>"; print_r($current_details); echo "</pre>"; 
						 $back_time = 0;
						 $current_node = $get_booking->start_node;
						do {
							$current_details = $this->booking_model->get_node_route('car_node',NULL, array('node_id'=>$current_node, 'route_id'=>$this->input->post('route_id', TRUE)));
							$back_time += $current_details->time_previous;
							$current_node = $current_details->previous_node;
						} while ( $pickup_node != $current_node);						
						$stimated_pickup_time = date('H:i:s', strtotime($get_booking->pickup_time)-(60*$back_time));
						$int_araival_itme = date('H:i:s', strtotime($get_booking->pickup_time)+(60*$totla_minute));
					}				
				}

				$data = array(
					'user_id' => $data['account']->id, 
					'schedule_id' => $schedules->schedule_id,
					'date_of_booking' => $this->input->post('booking_date', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'start_node' => $this->input->post('node_id', TRUE),
					'start_pickup_point' => $this->input->post('pick_up', TRUE),
					'end_node' => $this->input->post('drop_node', TRUE),
					'end_pickup_point' => $this->input->post('drop_point', TRUE),
					'pickup_time' => $stimated_pickup_time,
					'arrival_time' => $int_araival_itme,
					'distance_in_km' => $total_kilomiter,
					'accepted_time_delay' => $this->input->post('time_delay', TRUE),
					'create_user_id' => $data['account']->username,
					'status' => 3,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', now())					
				);
				$booking_id = $this->general->save_into_table_and_return_insert_id('car_booking', $data);
				
				$this->session->set_flashdata('message_success', lang('success_booking'));
				redirect(uri_string());
			}
			elseif ($this->booking_model->get_schedule($this->input->post('booking_date', TRUE), date('H:i:s', strtotime($advance_time_with_delay)-(60*$time_dureation)), $advance_time_with_delay)) {
				$schedule_advan = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), date('H:i:s', strtotime($advance_time_with_delay)-(60*$time_dureation)), $advance_time_with_delay);
				
				$adv_arrivel_time = date('H:i:s', strtotime($schedule_advan->start_time) + (60*$time_dureation));
				$data = array(
					'user_id' => $data['account']->id, 
					'schedule_id' => $schedule_advan->schedule_id,
					'date_of_booking' => $this->input->post('booking_date', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'start_node' => $this->input->post('node_id', TRUE),
					'start_pickup_point' => $this->input->post('pick_up', TRUE),
					'end_node' => $this->input->post('drop_node', TRUE),
					'end_pickup_point' => $this->input->post('drop_point', TRUE),
					'pickup_time' => $schedule_advan->start_time,
					'arrival_time' => $adv_arrivel_time,
					'distance_in_km' => $kilomiter,
					'accepted_time_delay' => $this->input->post('time_delay', TRUE),
					'create_user_id' => $data['account']->username,
					'status' => 3,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
				$booking_id = $this->general->save_into_table_and_return_insert_id('car_booking', $data);
				
				$this->session->set_flashdata('message_success', lang('success_booking'));
				redirect(uri_string());
			}
			elseif ($this->booking_model->get_schedule($this->input->post('booking_date', TRUE), date('H:i:s', strtotime($late_time_with_delay)-(60*$time_dureation)), $late_time_with_delay)) {
				$schedule_late = $this->booking_model->get_schedule($this->input->post('booking_date', TRUE), date('H:i:s', strtotime($late_time_with_delay)-(60*$time_dureation)), $late_time_with_delay);
				
				$late_pickup_time = date('H:i:s', strtotime($schedule_late->end_time) - (60*$time_dureation));
				
				$data = array(
					'user_id' => $data['account']->id, 
					'schedule_id' => $schedule_late->schedule_id,
					'date_of_booking' => $this->input->post('booking_date', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'start_node' => $this->input->post('node_id', TRUE),
					'start_pickup_point' => $this->input->post('pick_up', TRUE),
					'end_node' => $this->input->post('drop_node', TRUE),
					'end_pickup_point' => $this->input->post('drop_point', TRUE),
					'pickup_time' => $late_pickup_time,
					'arrival_time' => $schedule_late->end_time,
					'distance_in_km' => $kilomiter,
					'accepted_time_delay' => $this->input->post('time_delay', TRUE),
					'create_user_id' => $data['account']->username,
					'status' => 3,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
				$booking_id = $this->general->save_into_table_and_return_insert_id('car_booking', $data);
				
				$this->session->set_flashdata('message_success', lang('success_booking'));
				redirect(uri_string());
			}
			else {
				$this->session->set_flashdata('message_error', "Schedule not avaiable on your dimand");
				redirect(uri_string());
			}    
		}
		
		$select = "route_id, route_name_en, route_name_bn";
		$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select, 'route_id', 'desc', NULL, NULL);
		
		$this->load->view('car/booking_form', isset($data) ? $data : NULL);		
					
	}


	function schedule_booking()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access car_booking');
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));		
		$data['title'] = 'Schedule Booking';
		
		$select = "route_id, route_name_en, route_name_bn";
		$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select, 'route_id', 'desc', NULL, NULL);	
		$this->load->view('car/schedule_booking_form', isset($data) ? $data : NULL);
		
	}

	function sdrt_booking_save()	
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access car_booking');
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		
		$pickup_point_id=$this->input->post('pickup_point_id', TRUE);
		$drop_point_id=$this->input->post('drop_point_id', TRUE);
		$num_of_seat=$this->input->post('no_of_seat', TRUE);
		$schedule_id=$this->input->post('schedule_id', TRUE);
				
			$data = array(
					'user_id' => $data['account']->id,
					'schedule_id' => $schedule_id, 
					'pickup_point' => $pickup_point_id,					
					'destination_point' => $drop_point_id,					
					'no_of_seat' => $num_of_seat,
					'create_user_id' => $data['account']->username,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', now()),
					'booking_status' => 3					
				);
			
			$sbooking_id = $this->general->save_into_table_and_return_insert_id('car_schedule_booking', $data);
			
			if($sbooking_id)
			{
				$sdrt_schedule_info=$this->general->get_all_table_info_by_id('car_sdrt_schedule', 'schedule_id', $schedule_id);
				$fare_cost=$num_of_seat*$sdrt_schedule_info->per_seat_fare;
				
				$cost_array=array(
					'sbooking_id' => $sbooking_id,
					'fare_cost' => $fare_cost,
					'modification_time' =>mdate('%Y-%m-%d %H:%i:%s', now()),
					'status'=> 1
				);
	
				$this->general->save_into_table('car_sdrt_cost', $cost_array);
				
				
				$reference_id="SSW".$sbooking_id;
				$table_data=array(
					'reference_id' => $reference_id
				);
				$this->general->update_table('car_schedule_booking', $table_data,'sbooking_id', $sbooking_id);
				echo "<span class='label label-success'>Successfully create your booking. You booking reference id is <strong>".$reference_id."</strong></span>";
			}
			else
			{
			echo "<span class='label label-success'>Error! please try again later</span>";	
			}
			
		
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
		//echo "table_name=".$table_name.",field_name=".$where_field.",where_field_value=".$where_field_value;
		
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