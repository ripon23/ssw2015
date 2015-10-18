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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('services_emergency')?></div>
    <div class="panel-body">          
    
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
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: <?=$registration_info->gender?>
          <?php $sex  = $registration_info->gender=="M" ? "Male" : "Female"; ?>		  
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>: <?php echo $registration_info->dob;?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: <?=$registration_info->phone?></td>
        </tr>
	</table>     
    
 
              	
                <div class="control-group">
                    <label class="control-label" for="height">Total travel distance:* </label>        
                    <div class="controls">
                  	<input class="input-small"  name="total_travel_distance" id="total_travel_distance"  value="<?php echo $emergency_info->total_travel_distance;?>" type="text" />
                    <span class="help-inline">Km</span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="height">Total travel time:* </label>        
                    <div class="controls">
                    <input class="input-small"  name="total_travel_time" id="total_travel_time"  value="<?php echo $emergency_info->total_travel_time;?>" type="text" /> 
                    <span class="help-inline">Minute</span>
                    </div>
                </div>
                
                
                <div class="control-group">
                    <label class="control-label" for="height">Total estimated bill:* </label>        
                    <div class="controls">
                    <input class="input-small"  name="total_bill" id="total_bill"  value="<?php echo $emergency_info->total_bill;?>" type="text" />
                    <span class="help-inline"><?=lang('taka')?></span>
                    </div>
                </div>
                                
                
                <div class="control-group">
                    <label class="control-label" for="height">Note: </label>        
                    <div class="controls">
                    <textarea rows="5" name="note" id="note" class="input-xlarge"><?php echo $emergency_info->note;?></textarea>  
                    </div>
                </div>
                
    
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_update'); ?>" />
            </div>
        </div>
        
    </div><!-- /end span11 -->
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>