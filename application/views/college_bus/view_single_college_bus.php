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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/school_bus_48.png" width="48" height="41"> <?=lang('services_college_bus')?></div>
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
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: <?php echo $registration_info->gender=="M" ? "Male" : "Female"; ?>
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>: <?php if($registration_info->dob) { echo $registration_info->dob;}?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: 
            <?php 
			echo $registration_info->phone;			
			?>            
            </td>
        </tr>
	</table>     
    
 	<div class="span8">
    <table class="table table-bordered">    	
        <tr>
           <td>Services receive date</td>
           <td><?=$college_bus_info->services_receive_date?></td>
        </tr>
        <tr>
           <td>Note</td>
           <td><?=$college_bus_info->note?></td>
        </tr> 
    </table>           
    </div>            
    
    <div class="span3">
    <table class="table table-striped">    	              
        <tr>
        	<td>Last Update: </td>
            <td><?=$college_bus_info->last_edit_date?> </td>
        </tr>
        <tr>
        	<td>Entry/Update user: </td>
            <td><?=$college_bus_info->edit_user_id?> </td>
        </tr>
        </table> 
        
        <?php
        if($this->authorization->is_permitted('edit_college_bus_services'))
        {
        ?>
        <a href="<?php echo base_url().'college_bus/college_bus/edit_single_college_bus/'.$college_bus_info->reg_for_service_id ;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
        <?php
        }
        ?>
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