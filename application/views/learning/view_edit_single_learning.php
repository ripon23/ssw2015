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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/internet_services_48.png" width="48" height="41"> <?=lang('services_internet')?></div>
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
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: <?=$registration_info->gender?>
          <?php $sex  = $registration_info->gender=="M" ? "Male" : "Female"; ?>		  
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>: <?php echo $registration_info->dob;?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: 
            <?php 
			$phone_country_code=substr($registration_info->phone,0,3);
			$phone_part1=substr($registration_info->phone,3,5);
			$phone_part2=substr($registration_info->phone,8,6);
			?>
            <input class="span1" placeholder="+88" name="phone_country_code" id="phone_country_code" value="<?=$phone_country_code?>" type="text" />
	        <input class="input-mini" placeholder="01XXX" name="phone_part1" id="phone_part1" value="<?php echo $phone_part1;?>" type="text" />
    	    <input class="input-mini" placeholder="XXXXXX" name="phone_part2" id="phone_part2" value="<?php echo $phone_part2;?>" type="text" />
            </td>
        </tr>
	</table>     
    
 
              	
                <div class="control-group">
                    <label class="control-label" for="height">Services Type: </label>        
                    <div class="controls">
                    <select name="learning_type" id="learning_type" class="input-medium">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <?php foreach ($learning_type as $learning_type1) : ?>
                		<option value="<?php echo $learning_type1->learning_type_id;?>" <?php if($learning_type1->learning_type_id==$learning_info->services_type) echo 'selected="selected"'; ?>><?php echo $this->session->userdata('site_lang')=='english'? $learning_type1->type_name:$learning_type1->type_name_bn; ?></option>
                <?php endforeach; ?> 
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="height">Duration (In min): </label>        
                    <div class="controls">
                    <input class="input-mini"  name="duration" id="duration"  value="<?php echo $learning_info->duration;?>" type="text" />
                    </div>

                </div>
                
                <div class="control-group">
                    <label class="control-label" for="height">Description: </label>        
                    <div class="controls">
                    <textarea rows="5" name="description" id="description" class="input-xlarge"><?php echo $learning_info->description;?></textarea>  
                    </div>
                </div>
                
    
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_save'); ?>" />
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