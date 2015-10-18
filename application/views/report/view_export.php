<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GramCar_data.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
 	<tr>    
          <th>S.ID</th>
          <th><?=lang('registration_no')?></th>
          <th><?=lang('settings_fullname')?></th>
          <th><?=lang('site')?></th>
          <th><?=lang('services_point')?></th>
          <th><?=lang('services')?> </th>
          <th><?=lang('package')?></th>
          <th><?=lang('services')?> <?=lang('date_time')?></th>
          <th>Received</th>  
          <th><?=lang('status')?></th> 
	</tr>
    		<?php 
			foreach ($all_reg_services as $report) : 
			?>
            <tr>				
                <td>
				<?php 				
				echo $report->reg_for_service_id;                
				?>                                
                </td>
                <td><?php echo $report->registration_no; ?></td>
                <td>
				<?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($report->registration_no); 
				echo $reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name;				
				?>
				</td>
                <td><?php if($report->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($report->services_point_id); ?></td>
                <td><?php if($report->services_point_id) echo $this->ref_site_model->get_site_name_by_id($report->services_point_id); ?></td>
                <td><?php if($report->services_id) echo $this->ref_services_model->get_services_name_by_id($report->services_id);?></td>
                <td><?php if($report->services_package_id) echo $this->ref_services_model->get_package_name_by_id($report->services_package_id); ?></td>
                <td><?php echo $report->services_date; ?></td>
                <td><?php if($report->received_amount) echo $report->received_amount;?></td>
                <td>
				<?php 
				if($report->services_status==0)
				{
				echo 'Pending';
				}
			else if	($report->services_status==1)
				{
				echo 'Process';
				}
			else if	($report->services_status==2)
				{
				echo 'Taken';
				}
			else if	($report->services_status==3)
				{
				echo 'Cancel';
				}
			else if	($report->services_status==4)
				{
				echo 'Deleted';
				}	
				?>                
              </td>                              
                                
			</tr>
            <?php 
			endforeach; 
			?> 
  	</tr>
</table>