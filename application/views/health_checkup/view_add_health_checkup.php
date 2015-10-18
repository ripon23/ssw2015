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
        	<td><?=lang('age')?>: <input type="text" name="age" id="age" class="input-mini" value="<?php if($registration_info->dob) { echo floor( (strtotime(date('Y-m-d')) - strtotime($registration_info->dob)) / 31556926);}?>" /></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: <?=$registration_info->phone?></td>
        </tr>
	</table>     
    
   <div class="offset span3">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/ruler1.png" width="128" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group">
                    <label class="control-label" for="height">Height(cm): </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="height" id="height"  onkeyup="bmi_calculation();" tabindex="1" value="<?php echo set_value('height');?>"/>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="weight">Weight (kg): </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="weight" id="weight"  onkeyup="bmi_calculation();" tabindex="2" value="<?php echo set_value('weight');?>"/>
                    </div>
                </div>
                
				<div class="control-group">
                    <label class="control-label" for="bmi">BMI (kg/m2): </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="bmi" id="bmi" onFocus="bmi_calculation();" readonly="readonly" value="<?php echo set_value('bmi');?>"/>
                    </div>
                </div>
                
              </div>
            </div>
   </div> 
   
   <div class="offset span5">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/hip.png" width="170" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group">
                    <label class="control-label" for="waist">Waist (cm): </label>        
                    <div class="controls">
                    <input type="text" class="form-control input-mini" name="waist_circumference" id="waist_circumference" onKeyUp="waist_hip_ratio_calculation();" tabindex="3" value="<?php echo set_value('waist_circumference');?>"/>
                    </div>
                </div>                
                <div class="control-group">
                    <label class="control-label" for="hip">Hip (cm): </label>        
                    <div class="controls">
                    <input type="text" class="form-control input-mini" name="hip" id="hip" onKeyUp="waist_hip_ratio_calculation();" tabindex="4" value="<?php echo set_value('hip');?>"/>
                    </div>
                </div>
				<div class="control-group">
                    <label class="control-label" for="waist_hip_ratio">Waist Hip Ratio: </label>        
                    <div class="controls">
                    <input type="text" class=" form-control input-mini" name="waist_hip_ratio" id="waist_hip_ratio"  onfocus="waist_hip_ratio_calculation();" readonly="readonly" value="<?php echo set_value('waist_hip_ratio');?>"/>
                    </div>
                </div>
                
                
              </div>
            </div>
   </div>
   
   <div class="offset span3">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/thermometer.png" width="128" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group">
                    <label class="control-label" for="temperature">Temperature (&deg;C): </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="temperature" id="temperature" onKeyUp="changeColour('temperature');" tabindex="5" value="<?php echo set_value('temperature');?>"/>
                    </div>
                </div>  
                
                
                <div class="control-group">
                    <label class="control-label" for="HBsAg">HBsAg </label>        
                    <div class="controls">
                    <select name="hbsag" id="hbsag" onchange="changeColour('hbsag');" class="input-mini" tabindex="19">        
                        <option value="" selected="selected" style="background-color:#FFF;border-bottom:#FFF 1px solid;">select</option> 
                        <option value="negative" style="background-color:#00FF33;border-bottom:#FFF 1px solid;" <?php if(set_value('hbsag')=="negative") echo 'selected="selected"';?>>Negative</option>                    
                        <option value="positive" style="background-color:#F00;border-bottom:#FFF 1px solid;" <?php if(set_value('hbsag')=="positive") echo 'selected="selected"';?>>Positive</option>                    
                    </select>   
                    
                                                       
                    </div>
                </div>
                
				<div class="control-group">
                    <label class="control-label" for="bmi">&nbsp; </label>        
                    <div class="controls">
                    &nbsp;
                    </div>
                </div>
                
              </div>
            </div>
   </div>  
   
   
   
   <div class="offset span3">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/urine_test.png" width="128" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group" >
                    <label class="control-label" for="urine_sugar">Urine Sugar: </label>        
                    <div class="controls">
                    <select name="unine_sugar" id="unine_sugar" onChange="changeColour('unine_sugar');" class="form-control input-mini" tabindex="12">        
                    <option value="" style="background-color:#FFF;border-bottom:#FFF 1px solid;" selected="selected">select</option> 
                    <option value="-" style="background-color:#00FF33;border-bottom:#FFF 1px solid;" <?php if(set_value('unine_sugar')=="-") echo 'selected="selected"';?>>-</option>        
                    <option value="+-" style="background-color:#FF0;border-bottom:#FFF 1px solid;" <?php if(set_value('unine_sugar')=="+-") echo 'selected="selected"';?>>&plusmn;</option>
                    <option value="+" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('unine_sugar')=="+") echo 'selected="selected"';?>>+</option>
                    <option value="++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('unine_sugar')=="++") echo 'selected="selected"';?>>++</option>
                    <option value="+++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('unine_sugar')=="+++") echo 'selected="selected"';?>>+++</option>            
                    </select>
                    </div>
                </div>                
                <div class="control-group">
                    <label class="control-label" for="urine_protein">Urine Protein: </label>        
                    <div class="controls">
                    <select name="urine_protein" id="urine_protein" onChange="changeColour('urine_protein');" class="form-control input-mini" tabindex="13">        
                    <option value="" style="background-color:#FFF;border-bottom:#FFF 1px solid;" selected="selected">select</option> 
                    <option value="-" style="background-color:#00FF33;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="-") echo 'selected="selected"';?>>-</option>        
                    <option value="+-" style="background-color:#FF0;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="+-") echo 'selected="selected"';?>>&plusmn;</option>
                    <option value="+" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="+") echo 'selected="selected"';?>>+</option>
                    <option value="++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="++") echo 'selected="selected"';?>>++</option>
                    <option value="+++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="+++") echo 'selected="selected"';?>>+++</option>            
                    <option value="++++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urine_protein')=="++++") echo 'selected="selected"';?>>++++</option>            
                    </select>
                    </div>
                </div>
				<div class="control-group">
                    <label class="control-label" for="urine_urobilinogen">Urinary Urobilinogen: </label>        
                    <div class="controls">
                    <select name="urinary_urobilinogen" id="urinary_urobilinogen" onChange="changeColour('urinary_urobilinogen');" class="form-control input-mini" tabindex="14">
                        <option value="" selected="selected">select</option> 
                        <option value="+-" style="background-color:#00FF33;border-bottom:#FFF 1px solid;" <?php if(set_value('urinary_urobilinogen')=="+-") echo 'selected="selected"';?>>&plusmn;</option>
                        <option value="+" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urinary_urobilinogen')=="+") echo 'selected="selected"';?>>+</option>
                        <option value="++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urinary_urobilinogen')=="++") echo 'selected="selected"';?>>++</option>
                        <option value="+++" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('urinary_urobilinogen')=="+++") echo 'selected="selected"';?>>+++</option>                               
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="urinary_ph">Urinary pH: </label>        
                    <div class="controls">
                   	<input name="urinary_ph" id="urinary_ph"  type="text" class="form-control input-mini" onKeyUp="changeColour('urinary_ph');" tabindex="16" value="<?php echo set_value('urinary_ph');?>"/>
                    </div>
                </div>
                
              </div>
            </div>
   </div> 
   
   <div class="offset span5">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/blood.png" width="128" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group">
                    <label class="control-label" for="spo2">Oxygenation of Blood: </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="oxigen_blood_hemoglobin" id="oxigen_blood_hemoglobin" onKeyUp="changeColour('oxygenation');" tabindex="11" value="<?php echo set_value('oxigen_blood_hemoglobin');?>"/>
                    </div>
                </div>                
                <div class="control-group">
                    <label class="control-label" for="bp_sys">Blood Pressure: </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="blood_sys" id="blood_sys" onKeyUp="changeColour('systolic');" placeholder="Systolic" tabindex="6" value="<?php echo set_value('blood_sys');?>"/> / <input type="text" class="input-mini" name="blood_dia" id="blood_dia" placeholder="Diastolic" onKeyUp="changeColour('diastolic');" tabindex="7" value="<?php echo set_value('blood_dia');?>"/>
                    </div>
                </div>
				<div class="control-group">
                    <label class="control-label" for="blood_sugar">Blood Sugar (mg/dl): </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="blood_sugar" id="blood_sugar" onKeyUp="changeColour('blood_sugar');" tabindex="10" value="<?php echo set_value('blood_sugar');?>"/> 
        
                    <select name="blood_glucose_unit" id="blood_glucose_unit"  onchange="changeColour('blood_sugar');" class="input-mini">        
	                    <option value="mg/dL" <?php if(set_value('blood_glucose_unit')=="mg/dL") echo 'selected="selected"';?>>mg/dL</option>        
    	                <option value="mmol/L"  <?php if(set_value('blood_glucose_unit')=="mmol/L") echo 'selected="selected"'; if(!set_value('blood_glucose_unit') ) echo 'selected="selected"';?>>mmol/L</option>        
                    </select> 
        
                    <select name="blood_glucose_status" id="blood_glucose_status" onChange="changeColour('blood_sugar');" class="form-control input-mini">
                    	<option value="FBS" <?php if(set_value('blood_glucose_status')=="FBS") echo 'selected="selected"';?>>FBS</option>
                    	<option value="PBS" <?php if(set_value('blood_glucose_status')=="PBS") echo 'selected="selected"'; if(!set_value('blood_glucose_status') ) echo 'selected="selected"';?>>PBS</option>
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="blood_hemoglobin">Blood Hemoglobin: </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="blood_hemoglobin" id="blood_hemoglobin" onKeyUp="changeColour('blood_hemoglobin');" tabindex="15" value="<?php echo set_value('blood_hemoglobin');?>"/>
                    </div>
                </div>
                
              </div>
            </div>
   </div> 
      
   
   <div class="offset span3">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/arrhythmia.png" width="128" height="128"></h2>                
              </div>
              <div class="panel-body">
              	
                <div class="control-group">
                    <label class="control-label" for="pulse_ratio">Pulse Ratio: </label>        
                    <div class="controls">
                    <input type="text" class="input-mini" name="pulse_ratio" id="pulse_ratio" onKeyUp="changeColour('pulse_ratio');" tabindex="8" value="<?php echo set_value('pulse_ratio');?>"/>
                    </div>
                </div>                
                <div class="control-group">
                    <label class="control-label" for="arrhythmia">Arrhythmia: </label>        
                    <div class="controls">
                    <select name="rhythm" id="rhythm" onChange="changeColour('rhythm');" class="input-mini" tabindex="9">        
                        <option value="" selected="selected">select</option> 
                        <option value="Normal" style="background-color:#00FF33;border-bottom:#FFF 1px solid;" <?php if(set_value('rhythm')=="Normal") echo 'selected="selected"';?>>Normal</option>                    
                        <option value="Abnormal" style="background-color:#F90;border-bottom:#FFF 1px solid;" <?php if(set_value('rhythm')=="Abnormal") echo 'selected="selected"';?>>Abnormal</option>                    
                    </select>
                    </div>
                </div>
                
				<div class="control-group">
                    <label class="control-label" for="nothing">Blood cholesterol </label>        
                    <div class="controls">
                    <input name="cholesterol" id="cholesterol" type="text" onKeyUp="changeColour('cholesterol');" tabindex="17" class="input-mini"/>
                    </div>
                </div>
                
				<div class="control-group">
                    <label class="control-label" for="nothing">Blood uric acid  </label>        
                    <div class="controls">
                    <input name="uric_acid" id="uric_acid" type="text" onKeyUp="changeColour('uric_acid');" tabindex="18" class="input-mini"/>
                    </div>
                </div>
                
              </div>
            </div>
   </div>   
    
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="Save" tabindex="20"/>
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