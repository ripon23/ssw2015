<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
$(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });


function cuttentdate()
{
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
//document.write(today);
document.getElementById('sdate1').value=today;
}


</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('services_emergency')?> <?=lang('menu_list')?></div>
    <div class="panel-body">
       
   
    <?php echo form_open('emergency/emergency/emergency_services_list_search') ?>
        
       <table class="table table-bordered">
        <tr align="center" class="warning">
          <td><?=lang('registration_no')?></td>
          <td><?=lang('services_point')?></td>
          <td><?=lang('package')?></td>
          <td><?=lang('status')?></td>
          
          
        </tr>
        <tr align="center" class="success">
            <td><input type="text" name="sregistration_no" id="sregistration_no" value="<?php echo isset($sregistration_no)?$sregistration_no:'';?>" class="input-medium" placeholder="XXXXXXXXXXXXX"/></td>
            <td>
            <select name="sservices_point" class="input-medium" id="sservices_point">            	
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <?php foreach ($all_services_point as $services_point) : ?>
                <option value="<?php echo $services_point->site_id; ?>" <?php if(isset($sservices_point)){ if($services_point->site_id==$sservices_point) echo ' selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='english'? $services_point->site_name:$services_point->site_name_bn; ?></option>
                <?php endforeach; ?> 
            </select> 
            </td>            
            <td>
            <select name="spackage" class="input-medium" id="spackage">            	
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <?php foreach ($all_package as $package) : ?>
                <option value="<?php echo $package->package_id; ?>" <?php if(isset($spackage)){ if($package->package_id==$spackage) echo ' selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='english'? $package->package_name:$package->package_name_bn; ?></option>
                <?php endforeach; ?> 
            </select> 
            </td>
            <td>
            <select name="sservices_status" id="sservices_status" class="selectpicker span1.5" data-style="btn">
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <option value="zero" data-content="<span class='label label-warning'>Pending</span>" <?php if(isset($sservices_status)){ if($sservices_status=="zero") echo ' selected="selected"'; }?>>Pending</option>
                <option value="1" data-content="<span class='label label-info'>Process</span>" <?php if(isset($sservices_status)){ if($sservices_status=="1") echo ' selected="selected"'; }?>>Process</option>
                <option value="2" data-content="<span class='label label-success'>Taken</span>" <?php if(isset($sservices_status)){ if($sservices_status=="2") echo ' selected="selected"'; }?>>Taken</option>
                <option value="3" data-content="<span class='label label-important'>Cancel</span>" <?php if(isset($sservices_status)){ if($sservices_status=="3") echo ' selected="selected"'; }?>>Cancel</option>
            </select>
            </td>
          
          </tr>
          <tr align="center" class="warning">
            <td colspan="2"><?=lang('date_between')?></td>
          	<td><?=lang('today')?></td>       
            <td></td>
          </tr>
          <tr align="center" class="success">
            <td colspan="2"><input type="text" name="sdate1" id="sdate1" value="<?php echo isset($sdate1)?$sdate1:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/> <?=lang('website_and')?> <input type="text" name="sdate2" id="sdate2" value="<?php echo isset($sdate2)?$sdate2:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/></td>
            <td><input type="checkbox" name="Today" id="Today" onClick="cuttentdate()" /></td>
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
	
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><abbr title="Services ID">S.ID</abbr></th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('settings_fullname')?></th>
<th><?=lang('site')?></th>
                <th><?=lang('services_point')?></th>
                <th><?=lang('package')?></th>
                <th><?=lang('services')?> <?=lang('date_time')?></th>
                <th><?=lang('status')?></th>               
                <?php if ($this->authorization->is_permitted('add_information_services')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>                				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_emergency) ) {
			foreach ($all_emergency as $emergency) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $emergency->reg_for_service_id; ?></td>
                <td><?php echo $emergency->registration_no; ?></td>
                <td><?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($emergency->registration_no); 
				echo "<a href=".base_url().'registration/registration/view_single_registration/'.$emergency->registration_no.">".$reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name."</a>";
				
				?>
</td>
                <td><?php if($emergency->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($emergency->services_point_id); ?></td>
                <td><?php if($emergency->services_point_id) echo $this->ref_site_model->get_site_name_by_id($emergency->services_point_id); ?></td>
                <td><?php if($emergency->services_package_id) echo $this->ref_services_model->get_package_name_by_id($emergency->services_package_id); ?></td>
                <td><?php echo $emergency->services_date; ?></td>
                <td>
				<?php 
				if($emergency->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($emergency->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($emergency->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($emergency->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}
			else if	($emergency->services_status==4)
				{
				echo '<span class="label label-important">Deleted</span>';
				}	
				?>                
              </td>                
                
                <?php //if ($this->authorization->is_permitted('add_emergency')) : ?> 
                <td>
                <?php 
				if($emergency->services_status==2)
				{
				if ($this->authorization->is_permitted('view_information_services')) : 		
				?>                
                <a href="<?php echo base_url().'emergency/emergency/view_single_emergency/'.$emergency->reg_for_service_id;?>" class="btn btn-small btn-success"><?=lang('website_view')?></a>
                <?php
				endif; 
				if ($this->authorization->is_permitted('edit_information_services')) : 	
				?>
                <a href="<?php echo base_url().'emergency/emergency/edit_single_emergency/'.$emergency->reg_for_service_id;?>" class="btn btn-small btn-warning"><?=lang('website_edit')?></a>
                <?php
				endif; 
				}
				else
				{
				if ($this->authorization->is_permitted('add_information_services')) : 	
				?>
                <a href="<?php echo base_url().'emergency/emergency/add_emergency/'.$emergency->reg_for_service_id;?>" class="btn btn-small btn-info"><?=lang('website_add')?></a>
                <?php
				endif; 
				}
				?>
                </td>                
                                
</tr>
            <?php 
			$i=$i+1;
			endforeach; 
			}//end if
			?> 
             
    	</table>                
		<div style="text-align:left"><?php echo $links; ?></div>
    
     
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>