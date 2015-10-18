<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>

jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/payment_icon.png" width="48" height="41"> <?=lang('menu_payment')?></div>
    <div class="panel-body">
       
    <!--<div class="alert alert-info">Fields with <strong></strong><span class="required">*</span></strong> are required.</div>-->
    
	<?php 
	if(validation_errors())
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=validation_errors()?>
    </div>
	<?php
	}
	?>
    
    <?php 
	if(isset($success_msg))
	{					 
	?>
    <div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$success_msg?>
    </div>
	<?php
	}
	?>
	    
    
    
    <form class="form-horizontal" id="health-checkup-form" action="" method="post">    
    
    <table class="table table-striped">
        <tr class="success">
          <td><?=lang('registration_no')?>: <?=$registration_info->registration_no?>
          <input type="hidden" name="registration_no" id="registration_no" value="<?=$registration_info->registration_no?>"/>
          </td>
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: 
          <?php $sex  = $registration_info->gender=="M" ? "Male" : "Female"; ?>		  
          <?=$sex?>
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>:<?php  echo $registration_info->dob; ?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: 
            <?php 
			echo $registration_info->phone;			
			?>            
            </td>
        </tr>
        <tr class="success">
        	<td><?=lang('site')?>(<?=lang('services_point')?>):<?php echo $this->ref_site_model->get_site_name_by_sp_id($reg_services_info->services_point_id); echo "(".$this->payment_model->get_site_name_by_id($reg_services_info->services_point_id).")";?></td>
            <td><?=lang('services')?>(<?=lang('package')?>):<?php echo $this->ref_services_model->get_services_name_by_id($reg_services_info->services_id); echo "(".$this->ref_services_model->get_package_name_by_id($reg_services_info->services_package_id).")";?></td>
            <td><?=lang('status')?>:
			<?php 
			if($reg_services_info->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($reg_services_info->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($reg_services_info->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($reg_services_info->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}
			?>
            </td>
        </tr>
	</table>     
    
    <div class="span8">
 	<table class="table table-bordered">
    	<tr>
        	<td>Package Price(In BDT)</td>
            <td>
			<?php 
			$package_info=$this->ref_services_model->get_package_info_by_pacakgeid($reg_services_info->services_package_id); 
			echo $package_info->package_price." ".lang('taka');
			?>
            </td>                       
        </tr>
        <tr>
	        <td>Receive Amount(In BDT)</td>
            <td><input type="text" name="received_amount" id="received_amount" class="input-mini" value="<?php if(set_value('received_amount')) echo set_value('received_amount'); else echo $payment_info->received_amount;?>"/> <?=lang('taka')?></td>
        </tr>
        
        <tr>
	        <td>Payment Date (yyyy-mm-dd hh:mm:ss)</td>
            <td><input type="text" name="payment_received_date" id="payment_received_date" class="input-medium" value="<?php if(set_value('payment_received_date')) echo set_value('payment_date'); else echo $payment_info->payment_received_date;?>"/> Example: 2015-08-16 14:25:30</td>
        </tr>
        <tr>
        	<td>Note</td>
            <td><textarea name="note" id="note" rows="3"><?php if(set_value('note')) echo set_value('note'); else echo $payment_info->note;?></textarea></td>
        </tr>
        <tr>
	        <td colspan="2"><input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_update'); ?>" /></td>            
        </tr>
    </table>
    </div>
    
    <div class="span3">
    <table class="table table-striped">    	              
        <tr>
        	<td>Last Update: </td>
            <td><?=$payment_info->last_edit_date?> </td>
        </tr>
        <tr>
        	<td>Entry/Update user: </td>
            <td><?=$payment_info->edit_user_id?> </td>
        </tr>
        <tr>
        	<td>Approved Status: </td>
            <td>
			<?php 
			if($payment_info->approved_status==0)
				{
				echo '<span class="label label-important">Unapproved</span>';				
				}
			else if	($payment_info->approved_status==1)
				{
				echo '<span class="label label-success">Approved</span>';
				}
			else if	($payment_info->approved_status==2)
				{
				echo '<span class="label label-warning">Need to check</span>';
				}			
			?>			
            </td>
        </tr>
        </table>                 
    </div><!-- /end span3 -->                                
                      
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>