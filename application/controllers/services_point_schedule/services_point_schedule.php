<?php
class Services_point_schedule extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('services_point_schedule_model','basic_setting_model','account/account_model', 'ref_site_model'));	
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
	
	public function add_services_point_schedule()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_services_point_schedule'))
			{
				
				
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('reg_site', 'Site', 'required');
				$this->form_validation->set_rules('reg_services_point', 'Services point', 'required');
				$this->form_validation->set_rules('schedule_date', 'Schedule date', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar add services point schedule';
					$data['site'] = $this->ref_site_model->get_all_site();					
					$this->load->view('services_point_schedule/view_add_services_point_schedule', isset($data) ? $data : NULL);
				}
				else
				{
					$reg_site=$this->input->post('reg_site');
					$reg_services_point=$this->input->post('reg_services_point');	
					$schedule_date=$this->input->post('schedule_date');	
					
					if($this->services_point_schedule_model->check_allready_booked($reg_site,$schedule_date)>0)
					{
					$data['success_msg']="This date is already booked";	
					}
					else
					{
					$reg_data=array(
						'site_id'=>$reg_site,
						'services_point_id'=>$reg_services_point,
						'schedule_date'=>$schedule_date,						
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
					
					$success_or_fail=$this->services_point_schedule_model->save_services_point_schedule($reg_data);										
					
					if($success_or_fail)
					$data['success_msg']="Schedule created";
					else
					$data['success_msg']="Error! Schedule can not created";	

					}
					
					$data['title'] = 'GramCar add services point schedule';
					$data['site'] = $this->ref_site_model->get_all_site();	
					$this->load->view('services_point_schedule/view_add_services_point_schedule', isset($data) ? $data : NULL);
					
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
		$calendar .= "<div class='panel panel-warning'><div class='panel-heading'><p class='text-center'><a class='ajax-navigation btn btn-default btn-sm pull-left' href='services_point_schedule/services_point_schedule/showMonth/".$prev_month."/".$prev_year."'><i class='icon-arrow-left'></i></a><strong>" . date("F Y", $date) . "</strong>";
		$calendar .= "<a class='ajax-navigation btn btn-default btn-sm pull-right' href='services_point_schedule/services_point_schedule/showMonth/".$next_month."/".$next_year."'><i class='icon-arrow-right'></i></a></p></div>";
		$calendar .= "<div class='panel-body'><table class='table table-bordered'>";
		$calendar .= "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
		$calendar .= "<tr>";
		for($i = 1; $i <= $offset; $i++) {
			$calendar .= "<th></th>";
		}
		for($day = 1; $day <= $daysInMonth; $day++) {
			if( ($day + $offset - 1) % 7 == 0 && $day != 1) {
				$calendar .= "</tr><tr>";
				$rows++;
			}
			
			
			$event_date=$year."-".$month."-".$day;
			$services_point_name_list='';
			
			
			if($this->services_point_schedule_model->is_event_date($event_date))
			{
				//echo $event_date." is event date";
				
				$return_result=$this->services_point_schedule_model->has_a_event_in_the_date($event_date);
				foreach($return_result as $services_point)
				{
					//echo $services_point->services_point_name;
					$services_point_name_list='<span class="badge badge-info"><a href="#" class="btn btn-mini close" data-dismiss="alert" onClick="deleteclick_id('.$services_point->services_point_id.',\''.$event_date.'\')">&times;</a> '.$services_point->services_point_name.'</span><br/>'.$services_point_name_list;
				}
			
			}
			
			//services_point_schedule/services_point_schedule/remove_schedule/'.$services_point->services_point_id.'/'.$event_date.'
			
			$calendar .= "<th><strong>" .$day .'</strong><br/> '.$services_point_name_list."</th>";
		}
		while( ($day + $offset) <= $rows * 7)
		{
			$calendar .= "<th></th>";
			$day++;
		}
		$calendar .= "</tr>";
		$calendar .= "</table></div></div>";
		echo $calendar;
	}	
		

	public function remove_schedule($services_point_id, $event_schedule)
	{

	$this->services_point_schedule_model->remove_services_point_schedule($services_point_id, $event_schedule);
	}
	

}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>