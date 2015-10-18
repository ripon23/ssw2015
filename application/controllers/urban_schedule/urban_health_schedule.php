<?php
class Urban_health_schedule extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('general_model','services_point_schedule_model','basic_setting_model','account/account_model', 'ref_site_model','urban_schedule_model'));	
		//$this->load->language(array( 'account/account_settings'));
		
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

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_services_point_schedule'))
			{
				
				$data['title'] = 'GramCar add services point schedule';
				$data['site'] = $this->ref_site_model->get_all_site();
				$this->load->view('services_point_schedule/view_add_services_point_schedule', isset($data) ? $data : NULL);
				
			}
			else
			{
			redirect('');  // if not permitted "create_registration" redirect to home page
			}
			
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	public function create_urban_schedule()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('create_urban_health_schedule'))
			{
												
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('schedule_date', 'Schedule date', 'required');
				$this->form_validation->set_rules('schedule_type', 'Schedule type', 'required');
				
				
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar urban health schedule';
					$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');	
					$this->load->view('urban_schedule/view_create_urban_health_schedule', isset($data) ? $data : NULL);
				}
				else
				{
					$schedule_date=$this->input->post('schedule_date');
					$schedule_type=$this->input->post('schedule_type');						
					
					if($this->general_model->is_exist_in_a_table(' gramcar_urban_health_schedule','schedule_date',$schedule_date))
					{
					$data['error_msg']="Schedule already created";	
					}
					else
					{
						if($schedule_type==1)
						{
							$schedule_type_10am_12pm=$this->input->post('schedule_type_10am_12pm');
							$schedule_type_12pm_13pm=$this->input->post('schedule_type_12pm_13pm');
							$schedule_type_13pm_14pm=$this->input->post('schedule_type_13pm_14pm');
							$schedule_type_14pm_15pm=$this->input->post('schedule_type_14pm_15pm');
							$schedule_type_15pm_17pm=$this->input->post('schedule_type_15pm_17pm');
							
							if($schedule_type_10am_12pm==1)  $schedule_type_10am_12pm_value="office_day,".$this->input->post('reg_site').",".$this->input->post('reg_services_point');
							elseif($schedule_type_10am_12pm==2) $schedule_type_10am_12pm_value="lunch_break";
							elseif($schedule_type_10am_12pm==3) $schedule_type_10am_12pm_value="off_hour";
							
							if($schedule_type_12pm_13pm==1)  $schedule_type_12pm_13pm_value="office_day,".$this->input->post('reg_site2').",".$this->input->post('reg_services_point2');
							elseif($schedule_type_12pm_13pm==2) $schedule_type_12pm_13pm_value="lunch_break";
							elseif($schedule_type_12pm_13pm==3) $schedule_type_12pm_13pm_value="off_hour";
							
							if($schedule_type_13pm_14pm==1)  $schedule_type_13pm_14pm_value="office_day,".$this->input->post('reg_site3').",".$this->input->post('reg_services_point3');
							elseif($schedule_type_13pm_14pm==2) $schedule_type_13pm_14pm_value="lunch_break";
							elseif($schedule_type_13pm_14pm==3) $schedule_type_13pm_14pm_value="off_hour";
							
							if($schedule_type_14pm_15pm==1)  $schedule_type_14pm_15pm_value="office_day,".$this->input->post('reg_site4').",".$this->input->post('reg_services_point4');
							elseif($schedule_type_14pm_15pm==2) $schedule_type_14pm_15pm_value="lunch_break";
							elseif($schedule_type_14pm_15pm==3) $schedule_type_14pm_15pm_value="off_hour";
							
							if($schedule_type_15pm_17pm==1)  $schedule_type_15pm_17pm_value="office_day,".$this->input->post('reg_site5').",".$this->input->post('reg_services_point5');
							elseif($schedule_type_15pm_17pm==2) $schedule_type_15pm_17pm_value="lunch_break";
							elseif($schedule_type_15pm_17pm==3) $schedule_type_15pm_17pm_value="off_hour";
							
							$schedule_data=array(
							'schedule_date'=>$schedule_date,
							'schedule_type'=>$schedule_type,
							'10am_12pm'=>$schedule_type_10am_12pm_value,
							'12pm_13pm'=>$schedule_type_12pm_13pm_value,
							'13pm_14pm'=>$schedule_type_13pm_14pm_value,
							'14pm_15pm'=>$schedule_type_14pm_15pm_value,
							'15pm_17pm'=>$schedule_type_15pm_17pm_value,
							'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
							'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
							);	
														
							
							
						}
						elseif($schedule_type==2)
						{
							$schedule_data=array(
							'schedule_date'=>$schedule_date,
							'schedule_type'=>$schedule_type,											
							'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
							'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
							);	
						}
						elseif($schedule_type==3)
						{
							$schedule_data=array(
							'schedule_date'=>$schedule_date,
							'schedule_type'=>$schedule_type,											
							'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
							'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
							);	
						}
											
					
					$success_or_fail=$this->general_model->save_into_table('gramcar_urban_health_schedule',$schedule_data);										
					
					if($success_or_fail)
					$data['success_msg']="Schedule created";
					else
					$data['success_msg']="Error! Schedule can not created";	

					}
					
					$data['title'] = 'GramCar urban health schedule';	
					$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');	
					$this->load->view('urban_schedule/view_create_urban_health_schedule', isset($data) ? $data : NULL);
					
				}
				
				
			}
			else
			{
			redirect('');  // if not permitted "create_registration" redirect to home page
			}
			
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	public function showMonth($month = null, $year = null)
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
		$calendar .= "<div class='panel panel-warning'><div class='panel-heading'><p class='text-center'><a class='ajax-navigation btn btn-default btn-sm pull-left' href='urban_schedule/urban_health_schedule/showMonth/".$prev_month."/".$prev_year."'><i class='icon-arrow-left'></i></a><strong>" . date("F Y", $date) . "</strong>";
		$calendar .= "<a class='ajax-navigation btn btn-default btn-sm pull-right' href='urban_schedule/urban_health_schedule/showMonth/".$next_month."/".$next_year."'><i class='icon-arrow-right'></i></a></p></div>";
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
			
			
			if($this->urban_schedule_model->have_urban_health_schedule($event_date))
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
									<td>'.$i.'-'.$month."-".$year.' <br/><strong>'.$day.'</strong> <a class="btn btn-mini btn-danger"  title="Delete Schedule" onClick="deleteclick_id(\''.$event_date.'\')">X</a></td>';
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
			else
				{
				$calendar .= "<tr align='center'>
									<td>$i-".$month."-".$year." <br/><strong>".$day."</strong></td>";	
				$calendar .= "<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>";	
				}
			}
		$calendar .= "</table></div></div>";
		echo $calendar;
	}	
		

	public function remove_schedule($event_schedule)
	{
	//$this->input->post('event_date');
	//$this->services_point_schedule_model->remove_services_point_schedule($services_point_id, $event_schedule);
	$this->general_model->delete_from_table('gramcar_urban_health_schedule', 'schedule_date', $event_schedule);
	//echo $event_schedule;
	}
	

}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>