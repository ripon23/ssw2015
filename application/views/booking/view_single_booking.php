<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> Booking Info.</div>
    <div class="panel-body">
       
          
   	<div class="span8">
    <table class="table table-bordered">
    	<tr>
        	<td>Booking Id</td>
            <td><?='<span class=badge>'.$single_booking->booking_id.'</span>'?> </td>
        </tr>
        <tr>
        	<td><?=lang('settings_fullname')?> </td>
            <td><?php echo $single_booking->first_name." ".$single_booking->middle_name." ".$single_booking->last_name;?> </td>
        </tr>
        <tr>
        	<td><?=lang('guardian_name')?> </td>
            <td><?=$single_booking->guardian_name?> </td>
        </tr>
        <tr>
        	<td><?=lang('settings_dateofbirth')?> </td>
            <td><?=$single_booking->dob?> </td>
        </tr>
        <tr>
        	<td><?=lang('settings_gender')?> </td>
            <td><?=$single_booking->gender?> </td>
        </tr>
        <tr>
        	<td><?=lang('division')?> </td>
            <td></td>
        </tr>
        <tr>
        	<td><?=lang('district')?> </td>
            <td><?php if($single_booking->district_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$single_booking->district_id,NULL,NULL,NULL,NULL,$ltype='DT');?></td>
        </tr>
        <tr>
        	<td><?=lang('upazila')?> </td>
            <td><?php if($single_booking->upazila_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$single_booking->district_id,$single_booking->upazila_id,NULL,NULL,NULL,$ltype='UP');?></td>
        </tr>
        <tr>
        	<td><?=lang('union')?> </td>
            <td><?php if($single_booking->union_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$single_booking->district_id,$single_booking->upazila_id,$single_booking->union_id,NULL,NULL,$ltype='UN');?></td>
        </tr>
        <tr>
        	<td><?=lang('mouza')?> </td>
            <td><?php if($single_booking->mouza_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$single_booking->district_id,$single_booking->upazila_id,$single_booking->union_id,$single_booking->mouza_id,NULL,$ltype='MA');?></td>
        </tr>
        <tr>
        	<td><?=lang('village')?> </td>
            <td><?php if($single_booking->village_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$single_booking->district_id,$single_booking->upazila_id,$single_booking->union_id,$single_booking->mouza_id,$single_booking->village_id,$ltype='VI');?></td>
        </tr>
        <tr>
        	<td><?=lang('landmark')?> </td>
            <td><?=$single_booking->landmark?> </td>
        </tr>
        <tr>
        	<td><?=lang('phone')?> </td>
            <td><?=$single_booking->phone?> </td>
        </tr>
        <tr>
        	<td><?=lang('national_id')?></td>
            <td><?=$single_booking->national_id?> </td>
        </tr>
        <tr>
        	<td><?=lang('note')?></td>
            <td><?=$single_booking->note?> </td>
        </tr>
    </table>
            

	
    </div><!-- /end span6 -->
    
    
    <div class="span3">
    	
        <table class="table table-striped">
    	<tr>
        	<td>Status </td>
            <td>
			<?php 
			if($single_booking->status==0)
			{
				echo '<span class="label label-warning">Unregistered</span>';				
			}
			else if($single_booking->status==1)
				{
				echo '<span class="label label-success">Registered</span>';				
				}
			else if	($single_booking->status==2)
				{
				echo '<span class="label label-important">Deleted</span>';
				}
				
			?>
            </td>
        </tr>
        <tr>
        	<td>Create Date</td>
            <td><?=$single_booking->create_date?> </td>
        </tr>
        <tr>
        	<td>Create User </td>
            <td><?=$single_booking->create_user_id?> </td>
        </tr>
        <tr>
        	<td>Last Update</td>
            <td><?=$single_booking->update_date?> </td>
        </tr>
        <tr>
        	<td>Update user </td>
            <td><?=$single_booking->update_user_id?> </td>
        </tr>
        </table>                        	
        <?php
        if($this->authorization->is_permitted('edit_booking'))
        {
        ?>
        <a href="<?php echo base_url().'booking/booking/edit_single_booking/'.$single_booking->booking_id ;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
        <?php
        }
		
		if($this->authorization->is_permitted('create_registration'))
        {
        ?>
        <a href="<?php echo base_url().'booking/booking/create_registration_from_booking/'.$single_booking->booking_id ;?>" class="btn btn-block btn-large btn-info"><?=lang('website_create')?> Registration</a>
        <?php
        }
        ?>
    </div><!-- /end span3 -->
    
    
    <div class="span11">
    
        <table class="table table-striped table-bordered">
        <thead>
    	<tr>
        	<th>#</th>
            <th><?=lang('site')?></th>
            <th><?=lang('services_point')?></th>      
        	<th><?=lang('services')?> </th>
            <th><?=lang('package')?></th>
            <th><?=lang('service_datetime')?></th>
            <th><?=lang('status')?></th>
        </tr>
        <thead>
        <tbody>
        <?php
		$i=1;
		foreach ($single_services_list as $services) :
		?>
        <tr align="center">
        	<td><?=$i++?></td>
            <td><?php if($services->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($services->services_point_id);?></td>
            <td><?php if($services->services_point_id) echo $this->ref_site_model->get_site_name_by_id($services->services_point_id);?></td>      
        	<td><?php if($services->services_id) echo $this->ref_services_model->get_services_name_by_id($services->services_id);?> </td>
            <td><?php if($services->services_package_id) echo $this->ref_services_model->get_package_name_by_id($services->services_package_id);?></td>
            <td><?=$services->services_date?></td>
            <td>
            <?php 
			if($services->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($services->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($services->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($services->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}	
				
			?>
            </td>
        </tr>
        <?php endforeach; ?>
        <tbody>
        </table>
        
       
                         	 
    </div> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>