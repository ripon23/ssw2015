<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>

function resendclick_id(reg_services_id)
{
	
	//alert("hi");

    $.ajax({
           type: "POST",
           url: "health_checkup/health_checkup/resend_health_checkup_api_data",
		   data: "reg_services_id="+reg_services_id,
           success: function(msg)
           {               	
			   	if(msg=="Success")
				{
				//removeTableRow(button_id);
			   	//$('#row_' + numeric).removeClass('class-fail');
				//$('#row_' + numeric).addClass('error');	
				$('.resCheck').removeClass('label-important');
				$(this).parent().addClass('label-success');
				//$( "class-fail" ).html('<span class="label label-success">Success</span>');
				}
			alert(msg); // show response from the php script.			      	
			location.reload();
           }
         });   
	
			
}// END deleteclick_id



jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/health_checkup_48.png" width="48" height="41"> <?=lang('services_health-checkpup')?></div>
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
		  
          <input type="hidden" name="sex" id="sex" value="<?=$sex?>"/>
          </td>          
        </tr>
        <tr class="success">
        	<td>Age: <?=$health_checkup_info->age?></td>
            <td>Guardian: <?=$registration_info->guardian_name?></td>
            <td>Phone: <?=$registration_info->phone?></td>
        </tr>
	</table>     
    <div class="span8">
    <table class="table table-bordered">
    	<tr>
        	<td>Overall Health Status</td>
            <td colspan="2">
            <?php
			if($health_checkup_info->color_status==1)
            $image_name="dot_green.png";
            else if($health_checkup_info->color_status==2)
            $image_name="dot_yellow.png";
            else if($health_checkup_info->color_status==3)
            $image_name="dot_orange.png";
            else if($health_checkup_info->color_status==4)
            $image_name="dot_red.png";
            ?>
            <img src="<?php echo base_url().RES_DIR; ?>/img/<?=$image_name?>" width="14" height="13"><?php ?>			
            
            </td>
            </tr>
        <tr>
        	<td>Checkup Date/Time</td>
            <td colspan="2"><?=$health_checkup_info->checkup_date?></td>            
        </tr>
        <tr>
        	<td>Height</td>
            <td><?=$health_checkup_info->height?></td>
            <td></td>
        </tr>
        <tr>
        	<td>Weight</td>
            <td><?=$health_checkup_info->weight?></td>
            <td></td>
        </tr>
        <tr>
        	<td>BMI</td>
            <td><?=$health_checkup_info->bmi?></td>
            <td><?php if($health_checkup_info->bmi){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("BMI",$health_checkup_info->bmi,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        <tr>
        	<td>Waist (cm)</td>
            <td><?=$health_checkup_info->waist?></td>
            <td><?php if($health_checkup_info->waist){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("waist",$health_checkup_info->waist,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        <tr>
        	<td>Hip (cm)</td>
            <td><?=$health_checkup_info->hip?></td>
            <td></td>
        </tr>
        <tr>
        	<td>Waist Hip Ratio</td>
            <td><?=$health_checkup_info->waist_hip_ratio?></td>
            <td><?php if($health_checkup_info->waist_hip_ratio){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("waist_hip_ratio",$health_checkup_info->waist_hip_ratio,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        <tr>
        	<td>Temperature (&deg;F) </td>
            <td><?=$health_checkup_info->temperature?></td>
            <td><?php if($health_checkup_info->temperature){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("temperature",$health_checkup_info->temperature,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Urine Sugar </td>
            <td><?=$health_checkup_info->urinary_glucose?></td>
            <td><?php if($health_checkup_info->urinary_glucose){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("urine_sugar",$health_checkup_info->urinary_glucose,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        
        <tr>
        	<td>Urine Protein </td>
            <td><?=$health_checkup_info->urinary_protein?></td>
            <td><?php if($health_checkup_info->urinary_protein){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("urine_protein",$health_checkup_info->urinary_protein,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Urinary Urobilinogen </td>
            <td><?=$health_checkup_info->urinary_urobilinogen?></td>
            <td><?php if($health_checkup_info->urinary_urobilinogen){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("urinary_urobilinogen",$health_checkup_info->urinary_urobilinogen,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Urinary pH </td>
            <td><?=$health_checkup_info->urinary_ph?></td>
            <td><?php if($health_checkup_info->urinary_ph){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("urinary_ph",$health_checkup_info->urinary_ph,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Blood Pressure(Systolic/Diastolic) </td>
            <td><?=$health_checkup_info->bp_sys?> / <?=$health_checkup_info->bp_dia?></td>
            <td><?php if($health_checkup_info->bp_sys){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("bp_sys",$health_checkup_info->bp_sys,$sex)?>.png" width="14" height="13"><?php } if($health_checkup_info->bp_dia){?> / <img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("bp_dia",$health_checkup_info->bp_dia,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Blood Sugar (mg/dl) </td>
            <td><?=$health_checkup_info->blood_glucose?> (<?=$health_checkup_info->blood_glucose_type?>)</td>
            <td><?php if($health_checkup_info->blood_glucose) {?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("blood_gluckose",$health_checkup_info->blood_glucose,$health_checkup_info->blood_glucose_type)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Blood Hemoglobin </td>
            <td><?=$health_checkup_info->blood_hemoglobin?></td>
            <td><?php if($health_checkup_info->blood_hemoglobin){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("blood_hemoglobin",$health_checkup_info->blood_hemoglobin,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Pulse Ratio </td>
            <td><?=$health_checkup_info->pulse_rate?></td>
            <td><?php if($health_checkup_info->pulse_rate){?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("pulse_ratio",$health_checkup_info->pulse_rate,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Arrhythmia </td>
            <td><?=$health_checkup_info->arrhythmia?></td>
            <td><?php if($health_checkup_info->arrhythmia) {?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("rhythm",$health_checkup_info->arrhythmia,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Blood cholesterol  </td>
            <td><?=$health_checkup_info->cholesterol?></td>
            <td><?php if($health_checkup_info->cholesterol) {?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("cholesterol",$health_checkup_info->cholesterol,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>Blood uric acid </td>
            <td><?=$health_checkup_info->uric_acid?></td>
            <td><?php if($health_checkup_info->uric_acid) {?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("uric_acid",$health_checkup_info->uric_acid,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>
        
        <tr>
        	<td>HBsAg  </td>
            <td><?=$health_checkup_info->hbsag?></td>
            <td><?php if($health_checkup_info->hbsag) {?><img src="<?php echo base_url().RES_DIR; ?>/img/dot_<?=$this->health_checkup_model->get_result_status("hbsag",$health_checkup_info->hbsag,$sex)?>.png" width="14" height="13"><?php }?></td>
        </tr>        
        
    </table>    
   </div>     
        
      
    </form> 
    
    <div class="span3">
    <table class="table table-striped">    	       
        <tr>
        	<td>PHC API status: </td>
            <td><?php 
			if($health_checkup_info->upload_status==0)
			echo '<span class="label label-warning">Not Sent</span>';
			else if($health_checkup_info->upload_status==1)
			echo '<span class="label label-success">Success</span>';			
			else if($health_checkup_info->upload_status==2)
				{
					echo '<div class="class-fail"> <span class="resCheck label label-important">Fail</span></div>';
				?>
                <input type="button" name="resend" id="resend" value="Resend" onClick="resendclick_id(<?=$health_checkup_info->reg_for_service_id?>)" class="btn-mini btn-warning" />	
                <?php
				}
			?> 
            </td>
        </tr>
        <tr>
        	<td>Last Update: </td>
            <td><?=$health_checkup_info->last_edit_date?> </td>
        </tr>
        <tr>
        	<td>Entry/Update user: </td>
            <td><?=$health_checkup_info->edit_user_id?> </td>
        </tr>
        </table> 
        
        <?php
        if($this->authorization->is_permitted('edit_health_checkup'))
        {
        ?>
        <a href="<?php echo base_url().'health_checkup/health_checkup/edit_single_health_checkup/'.$health_checkup_info->reg_for_service_id ;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
        <?php
        }
        ?>
    </div><!-- /end span3 -->
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>