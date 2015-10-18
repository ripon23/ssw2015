<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="<?php echo base_url().RES_DIR; ?>/css/jquery.simple-dtpicker.css" rel="stylesheet" />
<script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });

$(function(){
			$('*[name=services_receive_date]').appendDtpicker();
		});

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
                    <label class="control-label" for="services_receive_date">Services receive date: </label>        
                    <div class="controls">
                    <input class="input-medium"  name="services_receive_date" id="services_receive_date"  value="<?php echo $college_bus_info->services_receive_date;?>" type="text" />
                    </div>

                </div>
                
                <div class="control-group">
                    <label class="control-label" for="height">Note: </label>        
                    <div class="controls">
                    <textarea rows="5" name="note" id="note" class="input-xlarge"><?php echo $college_bus_info->note;?></textarea>  
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