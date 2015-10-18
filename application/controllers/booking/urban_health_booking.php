<?php
class Urban_health_booking extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','booking_model','account/account_model', 'health_checkup_model','ref_site_model','ref_location_model','urban_schedule_model', 'ref_services_model','general_model' ));	
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('mainmenu', 'english');
		$this->lang->load('registration_form', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);	
		$this->lang->load('registration_form', $language);
		}
		
	}
	
	public function index()  
	{
	
		maintain_ssl();
				
	}
	
	
	
	
	public function create_urban_health_booking($booking_for=NULL)
	{
		
		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		if($this->authorization->is_permitted('create_health_booking'))
			{
				
			$highest_role=100;
			$all_user_role=$this->general_model->get_all_table_info_by_id_asc_desc('a3m_rel_account_role', 'account_id', $data['account']->id, 'account_id', 'ASC');
			foreach ($all_user_role as $user_role) :
				if($user_role->role_id<$highest_role)
				$highest_role=$user_role->role_id;
			endforeach; 												
			
				if($highest_role<=3) //1=Admin 2= Manager 3= Operator 6= Customer
				{
				//echo $highest_role;						
					if($booking_for)
					{
						$data['user_info']=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_for);
						$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $data['user_info']->family_id);
						$searchterm='Select * from gramcar_urban_health_schedule Where schedule_type=1 AND schedule_date >="'.date('Y-m-d').'" Order By schedule_date ASC';
						$data['schedule_date']=$this->general_model->get_all_querystring_result($searchterm);
						
						$this->load->helper(array('form', 'url'));
						$this->load->library('form_validation');
						$this->form_validation->set_rules('schedule_date', 'Schedule date', 'required');
						$this->form_validation->set_rules('schedule_slot', 'Schedule slot', 'required');
							if ($this->form_validation->run() == FALSE)
							{
								$data['title'] = 'GramCar Health Booking';					
								$this->load->view('booking/view_create_urban_health_booking', isset($data) ? $data : NULL);
							}
							else
							{	
								$health_booking_data=array(
								'reg_no'=>$data['user_info']->registration_no,
								'user_id'=>$data['user_info']->user_id,
								'booking_date'=>$this->input->post('schedule_date'),
								'booking_slot'=>$this->input->post('schedule_slot'),
								'calculated_checkup_time'=>$this->input->post('calculated_checkup_start_time'),
								'booking_status'=>0,
								'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
								'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),	
								);						
								
								$success_or_fail=$this->general_model->save_into_table('urban_health_booking', $health_booking_data);
								
								if($success_or_fail)
								$data['success_msg']="Booking Successfull for ".$data['user_info']->registration_no;
								else
								$data['success_msg']="Booking Unsuccessfull! Please try again";
								
								$data['title'] = 'GramCar Health Booking';	
								$this->load->view('booking/view_create_urban_health_booking', isset($data) ? $data : NULL);
							}
					}
					else
					{
					$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
					$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(9);	// 2= General health checkup
					$this->load->library('pagination');
					//pagination
					$config = array();
					$config["base_url"] = base_url() . "booking/urban_health_booking/create_urban_health_booking/";
					$config["total_rows"] = $this->health_checkup_model->get_all_urban_health_checkup_registration_count();
					$config["per_page"] = $this->config->item("pagination_perpage");
					$config["uri_segment"] = 4;
					
					$config['full_tag_open'] = '<div class="pagination"><ul>';
					$config['full_tag_close'] = '</ul></div><!--pagination-->';
					
					$config['first_link'] = '&laquo; First';
					$config['first_tag_open'] = '<li class="prev page">';
					$config['first_tag_close'] = '</li>';
					
					$config['last_link'] = 'Last &raquo;';
					$config['last_tag_open'] = '<li class="next page">';
					$config['last_tag_close'] = '</li>';
					
					$config['next_link'] = 'Next &rarr;';
					$config['next_tag_open'] = '<li class="next page">';
					$config['next_tag_close'] = '</li>';
					
					$config['prev_link'] = '&larr; Previous';
					$config['prev_tag_open'] = '<li class="prev page">';
					$config['prev_tag_close'] = '</li>';
					
					$config['cur_tag_open'] = '<li class="active"><a href="">';
					$config['cur_tag_close'] = '</a></li>';
					
					$config['num_tag_open'] = '<li class="page">';
					$config['num_tag_close'] = '</li>';
					
					//$config['anchor_class'] = 'follow_link';
					
					$choice = $config['total_rows']/$config['per_page'];
					//$config['num_links'] = round($choice);
					$config['num_links'] = 5;
					
					$this->pagination->initialize($config);
					
					$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
					$data['all_health_checkup'] = $this->health_checkup_model->get_all_urban_health_checkup_registration_by_limit($config["per_page"], $page);				
					$data["links"] = $this->pagination->create_links();
					$data["page"]=$page;			
					
					$this->load->view('booking/view_urban_health_checkup_member_list', isset($data) ? $data : NULL);
					}
				
				
				}// END highest_role
				elseif($highest_role==6) //6= Customer
				{
				
				$data['user_info']=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'user_id', $data['account']->id);
				$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $data['user_info']->family_id);
				$searchterm='Select * from gramcar_urban_health_schedule Where schedule_type=1 AND schedule_date >="'.date('Y-m-d').'" Order By schedule_date ASC';
				$data['schedule_date']=$this->general_model->get_all_querystring_result($searchterm);
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->form_validation->set_rules('schedule_date', 'Schedule date', 'required');
				$this->form_validation->set_rules('schedule_slot', 'Schedule slot', 'required');
					if ($this->form_validation->run() == FALSE)
					{
						$data['title'] = 'GramCar Health Booking';					
						$this->load->view('booking/view_create_urban_health_booking', isset($data) ? $data : NULL);
					}
					else
					{	
						$health_booking_data=array(
						'reg_no'=>$data['user_info']->registration_no,
						'user_id'=>$data['account']->id,
						'booking_date'=>$this->input->post('schedule_date'),
						'booking_slot'=>$this->input->post('schedule_slot'),
						'calculated_checkup_time'=>$this->input->post('calculated_checkup_start_time'),
						'booking_status'=>0,
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),	
						);						
						
						$success_or_fail=$this->general_model->save_into_table('urban_health_booking', $health_booking_data);
						
						if($success_or_fail)
						$data['success_msg']="Booking Successfull for ".$data['user_info']->registration_no;
						else
						$data['success_msg']="Booking Unsuccessfull! Please try again";
						
						$data['title'] = 'GramCar Health Booking';	
						$this->load->view('booking/view_create_urban_health_booking', isset($data) ? $data : NULL);
					}
					
				}
			
				
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function search_urban_health_checkup_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_health_checkup'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['sservices_point']     	= $this->input->post('sservices_point');
				$data['spackage']     			= $this->input->post('spackage');
				$data['sservices_status']     	= $this->input->post('sservices_status');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_registration_for_services";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=9)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration_for_services.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("sservices_point"))	
				{
					$sservices_point=$this->input->post("sservices_point");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_point_id = $sservices_point)";
				}
				
				if($this->input->post("spackage"))	
				{
					$spackage=$this->input->post("spackage");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_package_id = $spackage)";				
				}								
				
				if($this->input->post("sservices_status"))	
				{
					if($this->input->post("sservices_status")=="zero")
					$sservices_status=0;	
					else
					$sservices_status=$this->input->post("sservices_status");
					
					$query_string=$query_string." AND(gramcar_registration_for_services.services_status = $sservices_status)";				
				}								
				
				
				if($this->input->post("sdate1"))
				{
				$sdate1=$this->input->post("sdate1"); $sdate2=$this->input->post("sdate2");
	
				if(($sdate1!='')&& ($sdate2==''))
				$sdate2=$sdate1;
				
				if(strlen($sdate1)<12) 
				$sdate1=$sdate1." 00:00:00";	
				
				if(strlen($sdate2)<12) 
				$sdate2=$sdate2." 23:59:59";
			
				$query_string= $query_string."  AND services_date BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				$query_string=$query_string." ORDER BY gramcar_registration_for_services.services_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar General Health Check-up List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(9);	// 2= General health checkup	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "booking/urban_health_booking/search_health_checkup_list/";
			$config["total_rows"] = $this->health_checkup_model->all_health_checkup_count_query_string($searchterm);
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config["uri_segment"] = 4;
				
				$config['full_tag_open'] = '<div class="pagination"><ul>';
				$config['full_tag_close'] = '</ul></div><!--pagination-->';
				
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev page">';
				$config['first_tag_close'] = '</li>';
				
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next page">';
				$config['last_tag_close'] = '</li>';
				
				$config['next_link'] = 'Next &rarr;';
				$config['next_tag_open'] = '<li class="next page">';
				$config['next_tag_close'] = '</li>';
				
				$config['prev_link'] = '&larr; Previous';
				$config['prev_tag_open'] = '<li class="prev page">';
				$config['prev_tag_close'] = '</li>';
				
				$config['cur_tag_open'] = '<li class="active"><a href="">';
				$config['cur_tag_close'] = '</a></li>';
				
				$config['num_tag_open'] = '<li class="page">';
				$config['num_tag_close'] = '</li>';
				
				//$config['anchor_class'] = 'follow_link';
				
				$choice = $config['total_rows']/$config['per_page'];
				//$config['num_links'] = round($choice);
				$config['num_links'] = 5;
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_health_checkup'] = $this->health_checkup_model->get_all_health_checkup_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('booking/view_urban_health_checkup_member_list', isset($data) ? $data : NULL);
				
			
			
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}	
		
		
		}
		else
		{
			redirect('account/sign_in');
		}	
	}
	
	
	public function urban_health_booking_list()
	{
		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('create_health_booking'))
			{
			
			$data['title'] = 'GramCar Urban Health Booking List';	
			$this->load->view('booking/view_urban_health_booking_list', isset($data) ? $data : NULL);
			}
			else
			{
			redirect('');  // if not permitted "create_health_booking" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	public function calender_export_to_excel($month = null, $year = null)
	{
	$calendar = '';
		if($month == null || $year == null) {
			$month = date('m');
			$year = date('Y');
		}
		$date = mktime(12, 0, 0, $month, 1, $year);
		$daysInMonth = date("t", $date);
		$offset = date("w", $date);
		$rows = 1;
		$prev_month = $month - 1;
		$prev_year = $year;
		if ($month == 1) {
			$prev_month = 12;
			$prev_year = $year-1;
		}
	 
		$next_month = $month + 1;
		$next_year = $year;
		if ($month == 12) {
			$next_month = 1;
			$next_year = $year + 1;
		}
		
	$calendar .= "<table class='table table-bordered'>";
		$calendar .= "<tr align='center'>
						<td><strong>Time</strong></td>
						<td><strong>10AM - 12PM</strong></td>
						<td><strong>12PM - 13PM</strong></td>
						<td><strong>13PM - 14PM</strong></td>
						<td><strong>14PM - 15PM</strong></td>
						<td><strong>15PM - 17PM</strong></td>
					  </tr>";
		
		 for($i=1;$i<=$daysInMonth;$i++)
  			{
			$timestamp = strtotime($year.'-'.$month.'-'.$i);
            $day = date('l', $timestamp);
			if($i<10) $i="0".$i;
			//if($month<10) $month="0".$month;
			$event_date=$year."-".$month."-".$i;						
			
			
			//echo "event_date=".$event_date;
			
			
			if($this->urban_schedule_model->have_urban_health_schedule($event_date))
				{				
					$booking_by_list1="";
					$booking_by_list2="";
					$booking_by_list3="";
					$booking_by_list4="";
					$booking_by_list5="";
					$booking_by1='';
					$booking_by2='';
					$booking_by3='';
					$booking_by4='';
					$booking_by5='';
					
					$return_result=$this->urban_schedule_model->has_a_urban_health_schedule_in_the_date($event_date);
					foreach($return_result as $schedule_info)
					{
					if($schedule_info->schedule_type==1)
					{
					
						$pieces1 = explode(",",$schedule_info->{'10am_12pm'});						
						if($pieces1[0]=='office_day')
						{	
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=1 AND booking_status=0 ORDER BY health_booking_id DESC";
						$booking_by1=$this->general_model->get_all_querystring_result($searchterm);
							//print_r($booking_by1);
							if($booking_by1)
							{
								foreach($booking_by1 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list1='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list1;
								}
							//$booking_by_list1='<span class="badge badge-inverse">'.$booking_by_list1.'</span>';
							}
							else
							{$booking_by_list1='';}
							
						$services_point1=$this->ref_site_model->get_site_name_by_id($pieces1[2]);
						$services_point1='<span class="label label-success">'.$services_point1.'&nbsp;&nbsp;'.$booking_by_list1.'</span>';
						}
						elseif($pieces1[0]=='lunch_break')
						{
						$services_point1='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces1[0]=='off_hour')
						{
						$services_point1='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces2 = explode(",",$schedule_info->{'12pm_13pm'});						
						if($pieces2[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=2 AND booking_status=0";
						$booking_by2=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by2)
							{
								foreach($booking_by2 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list2='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list2;
								}
							//$booking_by_list2='<span class="badge badge-inverse">'.$booking_by_list2.'</span>';
							}
							else
							{$booking_by_list2='';}	
						$services_point2=$this->ref_site_model->get_site_name_by_id($pieces2[2]);
						$services_point2='<span class="label label-success">'.$services_point2.'&nbsp;&nbsp;'.$booking_by_list2.'</span>';
						}
						elseif($pieces2[0]=='lunch_break')
						{
						$services_point2='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces2[0]=='off_hour')
						{
						$services_point2='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces3 = explode(",",$schedule_info->{'13pm_14pm'});						
						if($pieces3[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=3 AND booking_status=0";
						$booking_by3=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by3)
							{
								foreach($booking_by3 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list3='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list3;
								}
							//$booking_by_list3='<span class="badge badge-inverse">'.$booking_by_list3.'</span>';
							}
							else
							{$booking_by_list3='';}		
						$services_point3=$this->ref_site_model->get_site_name_by_id($pieces3[2]);
						$services_point3='<span class="label label-success">'.$services_point3.'&nbsp;&nbsp;'.$booking_by_list3.'</span>';
						}
						elseif($pieces3[0]=='lunch_break')
						{
						$services_point3='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces3[0]=='off_hour')
						{
						$services_point3='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces4 = explode(",",$schedule_info->{'14pm_15pm'});						
						if($pieces4[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=4 AND booking_status=0";
						$booking_by4=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by4)
							{
								foreach($booking_by4 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list4='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list4;
								}
							//$booking_by_list4='<span class="badge badge-inverse">'.$booking_by_list4.'</span>';
							}
							else
							{$booking_by_list4='';}		
						$services_point4=$this->ref_site_model->get_site_name_by_id($pieces4[2]);
						$services_point4='<span class="label label-success">'.$services_point4.'&nbsp;&nbsp;'.$booking_by_list4.'</span>';
						}
						elseif($pieces4[0]=='lunch_break')
						{
						$services_point4='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces4[0]=='off_hour')
						{
						$services_point4='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces5 = explode(",",$schedule_info->{'15pm_17pm'});	
						
						if($pieces5[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=5 AND booking_status=0 ORDER BY health_booking_id DESC";
						$booking_by5=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by5)
							{
								foreach($booking_by5 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list5='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list5;
								}
							//$booking_by_list5='<span class="badge badge-inverse">'.$booking_by_list5.'</span>';
							}
							else
							{$booking_by_list5='';}		
						$services_point5=$this->ref_site_model->get_site_name_by_id($pieces5[2]);
						$services_point5='<span class="label label-success">'.$services_point5.'&nbsp;&nbsp;'.$booking_by_list5.'</span>';						
						}
						elseif($pieces5[0]=='lunch_break')
						{
						$services_point5='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces5[0]=='off_hour')
						{
						$services_point5='<span class="label label-warning">Off Hour</span>';
						}
				
						
					$calendar .= '<tr align="center">
									<td>'.$i.'-'.$month."-".$year.' &nbsp;&nbsp;<strong>'.$day.'</strong> </td>';
					$calendar .= "<td>".$services_point1."</td>
						<td>".$services_point2."</td>
						<td>".$services_point3."</td>
						<td>".$services_point4."</td>
						<td>".$services_point5."</td>
					</tr>";	
					
					
					unset($pieces1);
					unset($pieces2);
					unset($pieces3);
					unset($pieces4);
					unset($pieces5);
					$services_point1="";
					$services_point2="";
					$services_point3="";
					$services_point4="";
					$services_point5="";
					
					
					
					}
					elseif($schedule_info->schedule_type==2)
					{					
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' &nbsp;&nbsp;<strong>'.$day.'</strong> </td>';
									
					$calendar .=  '<td colspan="5"><span class="label label-important">Off Day</span></td></tr>';	
					}
					elseif($schedule_info->schedule_type==3)
					{
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' &nbsp;&nbsp;<strong>'.$day.'</strong> </td>';
					$calendar .= '<td colspan="5"><span class="label label-important">National Holiday</span></td></tr>';	
					}
					
					}
			
				}	
			}
		$calendar .= "</table>";
		$data['calendar']=$calendar;	
		$this->load->view('booking/export_to_excel_booking_list', isset($data) ? $data : NULL);
	}
	
	
	
	
	
	public function showMonth_booking_list($month = null, $year = null)
	{
		$calendar = '';
		if($month == null || $year == null) {
			$month = date('m');
			$year = date('Y');
		}
		$date = mktime(12, 0, 0, $month, 1, $year);
		$daysInMonth = date("t", $date);
		$offset = date("w", $date);
		$rows = 1;
		$prev_month = $month - 1;
		$prev_year = $year;
		if ($month == 1) {
			$prev_month = 12;
			$prev_year = $year-1;
		}
	 
		$next_month = $month + 1;
		$next_year = $year;
		if ($month == 12) {
			$next_month = 1;
			$next_year = $year + 1;
		}
		$calendar .= "<div class='panel panel-warning'><div class='panel-heading'><p class='text-center'><a class='ajax-navigation btn btn-default btn-sm pull-left' href='booking/urban_health_booking/showMonth_booking_list/".$prev_month."/".$prev_year."'><i class='icon-arrow-left'></i></a><strong>" . date("F Y", $date) . "</strong>";
		$calendar .= "<a class='ajax-navigation btn btn-default btn-sm pull-right' href='booking/urban_health_booking/showMonth_booking_list/".$next_month."/".$next_year."'><i class='icon-arrow-right'></i></a></p></div>";
		$calendar .= "<div class='panel-body'><a href='booking/urban_health_booking/calender_export_to_excel/".$month."/".$year."' class='pull-right'>Export to excel</a> <table class='table table-bordered'>";
		$calendar .= "<tr align='center'>
						<td><strong>Time</strong></td>
						<td><strong>10AM - 12PM</strong></td>
						<td><strong>12PM - 13PM</strong></td>
						<td><strong>13PM - 14PM</strong></td>
						<td><strong>14PM - 15PM</strong></td>
						<td><strong>15PM - 17PM</strong></td>
					  </tr>";
		
		 for($i=1;$i<=$daysInMonth;$i++)
  			{
			$timestamp = strtotime($year.'-'.$month.'-'.$i);
            $day = date('l', $timestamp);
			if($i<10) $i="0".$i;
			//if($month<10) $month="0".$month;
			$event_date=$year."-".$month."-".$i;						
			
			
			//echo "event_date=".$event_date;
			
			
			if($this->urban_schedule_model->have_urban_health_schedule($event_date))
				{				
					$booking_by_list1="";
					$booking_by_list2="";
					$booking_by_list3="";
					$booking_by_list4="";
					$booking_by_list5="";
					$booking_by1='';
					$booking_by2='';
					$booking_by3='';
					$booking_by4='';
					$booking_by5='';
					
					$return_result=$this->urban_schedule_model->has_a_urban_health_schedule_in_the_date($event_date);
					foreach($return_result as $schedule_info)
					{
					if($schedule_info->schedule_type==1)
					{
					
						$pieces1 = explode(",",$schedule_info->{'10am_12pm'});						
						if($pieces1[0]=='office_day')
						{	
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=1 AND booking_status=0 ORDER BY health_booking_id DESC";
						$booking_by1=$this->general_model->get_all_querystring_result($searchterm);
							//print_r($booking_by1);
							if($booking_by1)
							{
								foreach($booking_by1 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list1='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list1;
								}
							//$booking_by_list1='<span class="badge badge-inverse">'.$booking_by_list1.'</span>';
							}
							else
							{$booking_by_list1='';}
							
						$services_point1=$this->ref_site_model->get_site_name_by_id($pieces1[2]);
						$services_point1='<span class="label label-success">'.$services_point1.'<br/>'.$booking_by_list1.'</span>';
						}
						elseif($pieces1[0]=='lunch_break')
						{
						$services_point1='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces1[0]=='off_hour')
						{
						$services_point1='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces2 = explode(",",$schedule_info->{'12pm_13pm'});						
						if($pieces2[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=2 AND booking_status=0";
						$booking_by2=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by2)
							{
								foreach($booking_by2 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list2='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list2;
								}
							//$booking_by_list2='<span class="badge badge-inverse">'.$booking_by_list2.'</span>';
							}
							else
							{$booking_by_list2='';}	
						$services_point2=$this->ref_site_model->get_site_name_by_id($pieces2[2]);
						$services_point2='<span class="label label-success">'.$services_point2.'<br/>'.$booking_by_list2.'</span>';
						}
						elseif($pieces2[0]=='lunch_break')
						{
						$services_point2='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces2[0]=='off_hour')
						{
						$services_point2='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces3 = explode(",",$schedule_info->{'13pm_14pm'});						
						if($pieces3[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=3 AND booking_status=0";
						$booking_by3=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by3)
							{
								foreach($booking_by3 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list3='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list3;
								}
							//$booking_by_list3='<span class="badge badge-inverse">'.$booking_by_list3.'</span>';
							}
							else
							{$booking_by_list3='';}		
						$services_point3=$this->ref_site_model->get_site_name_by_id($pieces3[2]);
						$services_point3='<span class="label label-success">'.$services_point3.'<br/>'.$booking_by_list3.'</span>';
						}
						elseif($pieces3[0]=='lunch_break')
						{
						$services_point3='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces3[0]=='off_hour')
						{
						$services_point3='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces4 = explode(",",$schedule_info->{'14pm_15pm'});						
						if($pieces4[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=4 AND booking_status=0";
						$booking_by4=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by4)
							{
								foreach($booking_by4 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list4='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list4;
								}
							//$booking_by_list4='<span class="badge badge-inverse">'.$booking_by_list4.'</span>';
							}
							else
							{$booking_by_list4='';}		
						$services_point4=$this->ref_site_model->get_site_name_by_id($pieces4[2]);
						$services_point4='<span class="label label-success">'.$services_point4.'<br/>'.$booking_by_list4.'</span>';
						}
						elseif($pieces4[0]=='lunch_break')
						{
						$services_point4='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces4[0]=='off_hour')
						{
						$services_point4='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces5 = explode(",",$schedule_info->{'15pm_17pm'});	
						
						if($pieces5[0]=='office_day')
						{
						$searchterm="SELECT * FROM  urban_health_booking WHERE  booking_date='".$event_date."' AND booking_slot=5 AND booking_status=0 ORDER BY health_booking_id DESC";
						$booking_by5=$this->general_model->get_all_querystring_result($searchterm);
							if($booking_by5)
							{
								foreach($booking_by5 as $booking_by_person_barcode)
								{
								$reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $booking_by_person_barcode->reg_no);	
								$family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $reg_info->family_id);
								$booking_by_list5='<span class="badge badge-inverse"><a href=./registration/registration/view_single_registration/'.$reg_info->registration_no.'>'.$reg_info->first_name.' '.$reg_info->middle_name.' ('.$family_info->apartment_number.') ('.$booking_by_person_barcode->calculated_checkup_time.')'.'</a></span> <br/>'.$booking_by_list5;
								}
							//$booking_by_list5='<span class="badge badge-inverse">'.$booking_by_list5.'</span>';
							}
							else
							{$booking_by_list5='';}		
						$services_point5=$this->ref_site_model->get_site_name_by_id($pieces5[2]);
						$services_point5='<span class="label label-success">'.$services_point5.'<br/>'.$booking_by_list5.'</span>';						
						}
						elseif($pieces5[0]=='lunch_break')
						{
						$services_point5='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces5[0]=='off_hour')
						{
						$services_point5='<span class="label label-warning">Off Hour</span>';
						}
				
						
					$calendar .= '<tr align="center">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> </td>';
					$calendar .= "<td>".$services_point1."</td>
						<td>".$services_point2."</td>
						<td>".$services_point3."</td>
						<td>".$services_point4."</td>
						<td>".$services_point5."</td>
					</tr>";	
					
					
					unset($pieces1);
					unset($pieces2);
					unset($pieces3);
					unset($pieces4);
					unset($pieces5);
					$services_point1="";
					$services_point2="";
					$services_point3="";
					$services_point4="";
					$services_point5="";
					
					
					
					}
					elseif($schedule_info->schedule_type==2)
					{					
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> </td>';
									
					$calendar .=  '<td colspan="5"><span class="label label-important">Off Day</span></td></tr>';	
					}
					elseif($schedule_info->schedule_type==3)
					{
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> </td>';
					$calendar .= '<td colspan="5"><span class="label label-important">National Holiday</span></td></tr>';	
					}
					
					}
			
				}
			/*else
				{
				$calendar .= "<tr align='center'>
									<td>$i-".$month."-".$year." <br/><strong>".$day."</strong></td>";	
				$calendar .= "<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>";	
				}*/
			}
		$calendar .= "</table></div></div>";
		echo $calendar;
	}
	
	public function my_urban_health_booking_list()
	{
		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		if($this->authorization->is_permitted('create_health_booking'))
			{
				
			$this->load->library('pagination');
						
 		  	$searchterm='SELECT * FROM urban_health_booking Where user_id = '.$data['account']->id.' ORDER BY booking_status ASC, booking_date ASC';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "urban_health_booking/urban_health_booking/my_urban_health_booking_list/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['booking_info'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
			$data['title'] = 'GramCar My Health Booking';
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$this->load->view('booking/view_my_urban_health_booking_list', isset($data) ? $data : NULL);												
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	//ajax function
	public function cancel_booking()
	{
	$booking_id=$this->input->post('booking_id');
	/********* Calculate the date difference *********/	
	$booking_details=$this->general_model->get_all_table_info_by_id('urban_health_booking', 'health_booking_id', $booking_id);
	
	$t1 = strtotime( $booking_details->booking_date );
	$t2 = strtotime( date('Y-m-d') );
	$diff = $t1 - $t2;
	$hours = $diff / ( 60 * 60 );
		
		if($hours>24)
		{
		$table_data=array(	'booking_status'=>2,
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),	
						);	
		$success_or_fail=$this->general_model->update_table('urban_health_booking', $table_data,'health_booking_id', $booking_id);
	
		if($success_or_fail)
		echo "Booking successfully cancel";							
		}
		else
		{
		echo "You can not cancel it now. Only ".$hours." hour remain";
		}
		
	}
	
	
	
	// Ajax function
	public function load_schedule_slot($event_date,$site_id,$sp_id,$user_id)
	{
	$schedule_slot=array();
	$i=0;
	
	$query='Select * FROM urban_health_booking WHERE user_id ='.$user_id.' AND booking_date="'.$event_date.'" AND booking_status != 2';
	if($this->general_model->total_count_query_string($query)>0)
		{
		echo "There is already a booking for you in this day . Please select another day";
		}
	else
		{
		// 10am_12pm
		$query='Select * FROM  gramcar_urban_health_schedule Where schedule_date="'.$event_date.'" AND 10am_12pm="office_day,'.$site_id.','.$sp_id.'"';
			if($this->general_model->urban_schedule_model->have_in_the_slot($query))
			{
			$schedule_slot[$i++]='10am_12pm';	
			}
			
		// 12pm_13pm
		$query='Select * FROM  gramcar_urban_health_schedule Where schedule_date="'.$event_date.'" AND 12pm_13pm="office_day,'.$site_id.','.$sp_id.'"';
			if($this->general_model->urban_schedule_model->have_in_the_slot($query))
			{
			$schedule_slot[$i++]='12pm_13pm';	
			}
			
		// 13pm_14pm
		$query='Select * FROM  gramcar_urban_health_schedule Where schedule_date="'.$event_date.'" AND 13pm_14pm="office_day,'.$site_id.','.$sp_id.'"';
			if($this->general_model->urban_schedule_model->have_in_the_slot($query))
			{
			$schedule_slot[$i++]='13pm_14pm';	
			}
		
		// 14pm_15pm
		$query='Select * FROM  gramcar_urban_health_schedule Where schedule_date="'.$event_date.'" AND 14pm_15pm="office_day,'.$site_id.','.$sp_id.'"';
			if($this->general_model->urban_schedule_model->have_in_the_slot($query))
			{
			$schedule_slot[$i++]='14pm_15pm';	
			}
		
		// 15pm_17pm
		$query='Select * FROM  gramcar_urban_health_schedule Where schedule_date="'.$event_date.'" AND 15pm_17pm="office_day,'.$site_id.','.$sp_id.'"';
			if($this->general_model->urban_schedule_model->have_in_the_slot($query))
			{
			$schedule_slot[$i++]='15pm_17pm';	
			}
		
			echo '<option value="">'.lang('settings_select').'</option>';
			for($j=0;$j<count($schedule_slot);$j++)
			{
				if($schedule_slot[$j]=='10am_12pm')
				$slot_val=1;
				elseif($schedule_slot[$j]=='12pm_13pm')
				$slot_val=2;
				elseif($schedule_slot[$j]=='13pm_14pm')
				$slot_val=3;
				elseif($schedule_slot[$j]=='14pm_15pm')
				$slot_val=4;
				elseif($schedule_slot[$j]=='15pm_17pm')
				$slot_val=5;
			?>
			<option value="<?php echo $slot_val; ?>"><?php echo $schedule_slot[$j]; ?></option>
			<?php	
			}
		}
	}
	
	public function calculated_checkup_start_time($booking_date,$booking_slot)
	{
	//echo $booking_date." --- ".$booking_slot;
	$query="Select * FROM urban_health_booking WHERE booking_date='".$booking_date."' AND booking_slot=".$booking_slot;
	$previous_booking_count=$this->general_model->total_count_query_string($query);
	//$previous_booking_count=3;
		if(($booking_slot==1)||($booking_slot==5))
		{
			switch ($previous_booking_count) {
				case 0:
					if($booking_slot==1)
					$calculated_checkup_start_time='10:10 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='5:10 PM';
					break;
				case 1:
					if($booking_slot==1)
					$calculated_checkup_start_time='10:30 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='5:30 PM';
					break;
				case 2:
					if($booking_slot==1)
					$calculated_checkup_start_time='10:50 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='5:50 PM';
					break;
				case 3:
					if($booking_slot==1)
					$calculated_checkup_start_time='11:10 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='6:10 PM';
					break;	
				case 4:
					if($booking_slot==1)
					$calculated_checkup_start_time='11:30 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='6:30 PM';
					break;	
				case 5:
					if($booking_slot==1)
					$calculated_checkup_start_time='11:50 AM';
					elseif($booking_slot==5)
					$calculated_checkup_start_time='6:50 PM';
					break;	
				case 6:
					$calculated_checkup_start_time= '<span class="label label-important">No availavle time slot, Check another time slot or day</span>';
					break;											
			}
	
			
		}
		
		if(($booking_slot==2)||($booking_slot==3)||($booking_slot==4))
		{
			switch ($previous_booking_count) {
				case 0:
					if($booking_slot==2)
					$calculated_checkup_start_time='12:10 AM';
					elseif($booking_slot==3)
					$calculated_checkup_start_time='1:10 PM';
					elseif($booking_slot==4)
					$calculated_checkup_start_time='2:10 PM';
					break;
				case 1:
					if($booking_slot==2)
					$calculated_checkup_start_time='12:30 AM';
					elseif($booking_slot==3)
					$calculated_checkup_start_time='1:30 PM';
					elseif($booking_slot==4)
					$calculated_checkup_start_time='2:30 PM';
					break;
				case 2:
					if($booking_slot==2)
					$calculated_checkup_start_time='12:50 AM';
					elseif($booking_slot==3)
					$calculated_checkup_start_time='1:50 PM';
					elseif($booking_slot==4)
					$calculated_checkup_start_time='2:50 PM';
					break;
				case 3:
					$calculated_checkup_start_time= '<span class="label label-important">No availavle time slot, Check another time slot or day</span>';
					break;	
															
			}
			
		}
	
		echo $calculated_checkup_start_time;
	
	}
	
	public function showMonth($month = NULL, $year = NULL, $site_id=NULL, $sp_id=NULL)
	{	
		$calendar = '';
		if($month == NULL || $year == NULL) {
			$month = date('m');
			$year = date('Y');
		}
		$date = mktime(12, 0, 0, $month, 1, $year);
		$daysInMonth = date("t", $date);
		$offset = date("w", $date);
		$rows = 1;
		$prev_month = $month - 1;
		

		
		$prev_year = $year;
		if ($month == 1) {
			$prev_month = 12;
			$prev_year = $year-1;
		}
	 
		$next_month = $month + 1;
		

		
		$next_year = $year;
		if ($month == 12) {
			$next_month = 1;
			$next_year = $year + 1;
		}
		$calendar .= "<div class='panel panel-warning'><div class='panel-heading'><p class='text-center'><a class='ajax-navigation btn btn-default btn-sm pull-left' href='booking/urban_health_booking/showMonth/".$prev_month."/".$prev_year."/".$site_id."/".$sp_id."'><i class='icon-arrow-left'></i></a><strong>" . date("F Y", $date) . "</strong>";
		$calendar .= "<a class='ajax-navigation btn btn-default btn-sm pull-right' href='booking/urban_health_booking/showMonth/".$next_month."/".$next_year."/".$site_id."/".$sp_id."'><i class='icon-arrow-right'></i></a></p></div>";
		$calendar .= "<div class='panel-body'><table class='table table-bordered'>";
		$calendar .= "<tr align='center'>
						<td><strong>Time</strong></td>
						<td><strong>10AM - 12PM</strong></td>
						<td><strong>12PM - 13PM</strong></td>
						<td><strong>13PM - 14PM</strong></td>
						<td><strong>14PM - 15PM</strong></td>
						<td><strong>15PM - 17PM</strong></td>
					  </tr>";
		
		for($i=1;$i<=$daysInMonth;$i++)
  			{
			$timestamp = strtotime($year.'-'.$month.'-'.$i);
            $day = date('l', $timestamp);
			if($i<10) $i="0".$i;
			
			$event_date=$year."-".$month."-".$i;						
			
			
			//echo "event_date=".$event_date;
			
			
			if($this->urban_schedule_model->have_urban_health_schedule_sp_id($event_date,$site_id,$sp_id))
				{				
					$return_result=$this->urban_schedule_model->has_a_urban_health_schedule_in_the_date($event_date);
					foreach($return_result as $schedule_info)
					{
					if($schedule_info->schedule_type==1)
					{
					
						$pieces1 = explode(",",$schedule_info->{'10am_12pm'});						
						if($pieces1[0]=='office_day')
						{					
						$services_point1=$this->ref_site_model->get_site_name_by_id($pieces1[2]);
						$services_point1='<span class="label label-success">'.$services_point1.'</span>';
						}
						elseif($pieces1[0]=='lunch_break')
						{
						$services_point1='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces1[0]=='off_hour')
						{
						$services_point1='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces2 = explode(",",$schedule_info->{'12pm_13pm'});						
						if($pieces2[0]=='office_day')
						{					
						$services_point2=$this->ref_site_model->get_site_name_by_id($pieces2[2]);
						$services_point2='<span class="label label-success">'.$services_point2.'</span>';
						}
						elseif($pieces2[0]=='lunch_break')
						{
						$services_point2='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces2[0]=='off_hour')
						{
						$services_point2='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces3 = explode(",",$schedule_info->{'13pm_14pm'});						
						if($pieces3[0]=='office_day')
						{					
						$services_point3=$this->ref_site_model->get_site_name_by_id($pieces3[2]);
						$services_point3='<span class="label label-success">'.$services_point3.'</span>';
						}
						elseif($pieces3[0]=='lunch_break')
						{
						$services_point3='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces3[0]=='off_hour')
						{
						$services_point3='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces4 = explode(",",$schedule_info->{'14pm_15pm'});						
						if($pieces4[0]=='office_day')
						{					
						$services_point4=$this->ref_site_model->get_site_name_by_id($pieces4[2]);
						$services_point4='<span class="label label-success">'.$services_point4.'</span>';
						}
						elseif($pieces4[0]=='lunch_break')
						{
						$services_point4='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces4[0]=='off_hour')
						{
						$services_point4='<span class="label label-warning">Off Hour</span>';
						}
						
						$pieces5 = explode(",",$schedule_info->{'15pm_17pm'});	
						
						if($pieces5[0]=='office_day')
						{					
						$services_point5=$this->ref_site_model->get_site_name_by_id($pieces5[2]);
						$services_point5='<span class="label label-success">'.$services_point5.'</span>';						
						}
						elseif($pieces5[0]=='lunch_break')
						{
						$services_point5='<span class="label label-warning">Lunch Break</span>';
						}
						elseif($pieces5[0]=='off_hour')
						{
						$services_point5='<span class="label label-warning">Off Hour</span>';
						}
				
						
					$calendar .= '<tr align="center">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> </td>';
					$calendar .= "<td>".$services_point1."</td>
						<td>".$services_point2."</td>
						<td>".$services_point3."</td>
						<td>".$services_point4."</td>
						<td>".$services_point5."</td>
					</tr>";	
					
					
					unset($pieces1);
					unset($pieces2);
					unset($pieces3);
					unset($pieces4);
					unset($pieces5);
					$services_point1="";
					$services_point2="";
					$services_point3="";
					$services_point4="";
					$services_point5="";
					}
					elseif($schedule_info->schedule_type==2)
					{					
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> <a class="btn btn-mini btn-danger"  title="Delete Schedule" onClick="deleteclick_id(\''.$event_date.'\')">X</a></td>';
									
					$calendar .=  '<td colspan="5"><span class="label label-important">Off Day</span></td></tr>';	
					}
					elseif($schedule_info->schedule_type==3)
					{
					$calendar .= '<tr align="center" class="error">
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> <a class="btn btn-mini btn-danger"  title="Delete Schedule" onClick="deleteclick_id(\''.$event_date.'\')">X</a></td>';
					$calendar .= '<td colspan="5"><span class="label label-important">National Holiday</span></td></tr>';	
					}
					
					}
			
				}
			/*else
				{
				$calendar .= "<tr align='center'>
									<td>$i-".date('m-Y')." <br/><strong>".$day."</strong></td>";	
				$calendar .= "<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>";	
				}*/
			}
		$calendar .= "</table></div></div>";
		echo $calendar;
	}
	
	
	
	
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>