<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>


<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="<?php echo base_url().RES_DIR; ?>/css/jquery.simple-dtpicker.css" rel="stylesheet" />
    
<script>

jQuery(document).ready(function(){
	
	//Start
	$("#site_division").change(function()
	{
	var dvid=$(this).val();
	var ltype='DT';
	var dataString = 'dvid='+ dvid+'&ltype='+ltype;
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_district").html(html);	

			}
		});
	
	});
	//End
	
	//Start
	$("#site_district").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$(this).val();
	var ltype='UP';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&ltype='+ltype;	
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_upazila").html(html);			

			}
		});		
		
	
	});
	//End
	
	//Start
	$("#site_upazila").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$(this).val();
	var ltype='UN';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&ltype='+ltype;			
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_union").html(html);
	
			}
		});
		
	
	});
	//End
	
	//Start
	$("#site_union").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$("#site_upazila").val();
	var unid=$(this).val();
	var ltype='MA';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&unid='+unid+'&ltype='+ltype;				
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_mouza").html(html);
	
			}
		});
	
	
	});
	//End
	
	//Start
	$("#site_mouza").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$("#site_upazila").val();
	var unid=$("#site_union").val();
	var maid=$(this).val();
	var ltype='VI';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&unid='+unid+'&maid='+maid+'&ltype='+ltype;	
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_village").html(html);
			//$('#site_union_show').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	
	});
	//End		
	
	
	<!-- Start -->
	$("#reg_site").change(function()
	{
	var id=$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_servicespoint/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{			
			$("#reg_services_point").html(html);			
			}
		});
	
	});
	<!-- End -->
	
	
	<!-- Start -->
	$("#reg_services_point").change(function()
	{
	var id=$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "booking/booking/load_services_point_schedule_date/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			if(html.match('No schedule found'))	
				{
					$("#no_schedule").html('<span class="label label-important">'+html+'</span>');
					$("#schedule_date").hide();
					$("#schedule_time").hide();
				}
			else
				{
					$("#schedule_date").show();
					$("#schedule_time").show();
					$("#schedule_date").html(html);
					$("#no_schedule").empty();			
				}
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_services").change(function()
	{
	var id=$(this).val();
	var dataString;	
	//var dataString = 'id='+ id;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_services_pacakge/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_package").html(html);			
			}
		});
	
	});
	<!-- End -->
	
});

</script>
<style>
/*#registration_no_part1{
width:60px;	
}*/
</style>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> Services Booking</div>
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
	    
    
    
    <form class="form-horizontal" id="registration-form" action="" method="post">    
    
    <div class="span6">
    <h4>Booking info.</h4>           

	<div class="control-group">
		<label class="control-label" for="Registration_name"><?=lang('settings_fullname')?><span class="required">*</span></label>        
        <div class="controls">
		<input class="input-small" placeholder="First Name" name="firstname" id="firstname" value="<?php echo set_value('firstname');?>" type="text" />        
        <input class="input-small" placeholder="Middle Name"  name="middlename" id="middlename" value="<?php echo set_value('middlename');?>" type="text" />
        <input class="input-small" placeholder="Last Name"  name="lastname" id="lastname" value="<?php echo set_value('lastname');?>" type="text" />
        </div>
	</div>

	<div class="control-group">
		<label class="control-label" for="Registration_gaurdian_name"><?=lang('guardian_name')?></label>        
        <div class="controls">
		<input class="form-control" placeholder="Guardian Name" name="guardian_name" value="<?php echo set_value('guardian_name');?>" id="guardian_name" type="text" />
        </div>
	</div>

	
    <div class="control-group <?php echo isset($settings_dob_error) ? 'error' : ''; ?>">
    	<label class="control-label" for="settings_dateofbirth"><?=lang('settings_dateofbirth')?></label>
    	<div class="controls">
        <select name="settings_dob_month" class="input-small">
            <option value=""><?php echo lang('dateofbirth_month'); ?></option>
            <option value="1"><?php echo lang('month_jan'); ?></option>
            <option value="2"><?php echo lang('month_feb'); ?></option>
            <option value="3"><?php echo lang('month_mar'); ?></option>
            <option value="4"><?php echo lang('month_apr'); ?></option>
            <option value="5"><?php echo lang('month_may'); ?></option>
            <option value="6"><?php echo lang('month_jun'); ?></option>
            <option value="7"><?php echo lang('month_jul'); ?></option>
            <option value="8"><?php echo lang('month_aug'); ?></option>
            <option value="9"><?php echo lang('month_sep'); ?></option>
            <option value="10"><?php echo lang('month_oct'); ?></option>
            <option value="11"><?php echo lang('month_nov'); ?></option>
            <option value="12"><?php echo lang('month_dec'); ?></option>
        </select>
		
        <select name="settings_dob_day" class="input-small">
            <option value="" selected="selected"><?php echo lang('dateofbirth_day'); ?></option>
			<?php for ($i = 1; $i < 32; $i ++) : ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
        </select>
		
        <select name="settings_dob_year" class="input-small">
            <option value=""><?php echo lang('dateofbirth_year'); ?></option>
			<?php $year = mdate('%Y', now()); for ($i = $year; $i > 1900; $i --) : ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
        </select>
		<?php if (isset($settings_dob_error))
		{
		?>
        <span class="help-inline ">
		<?php echo $settings_dob_error; ?>
		</span>
		<?php } ?>
    	</div>
	</div>        	
	
    <div class="control-group">
		<label class="control-label" for="site_division"><?=lang('division')?> <span class="required">*</span></label>        
        <div class="controls">
		<select name="site_division" id="site_division">
          	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($all_division as $division) : ?>
            	<option value="<?php echo $division->division; ?>"><?php echo $division->loc_name_en?></option>
				<?php endforeach; ?>
        </select>	
        </div>
	</div>
    
    <div class="control-group">
		<label class="control-label" for="site_district"><?=lang('district')?> <span class="required">*</span></label>        
        <div class="controls">
		<select name="site_district" id="site_district">
          	<option value=""><?php echo lang('settings_select'); ?></option>                            
        </select>	
        </div>
	</div>
    
    <div class="control-group">
		<label class="control-label" for="site_upazila"><?=lang('upazila')?> <span class="required">*</span></label>        
        <div class="controls">
		<select name="site_upazila" id="site_upazila">
          	<option value=""><?php echo lang('settings_select'); ?></option>                            
        </select>	
        </div>
	</div>
    
    <div class="control-group">
		<label class="control-label" for="site_union"><?=lang('union')?> <span class="required">*</span></label>        
        <div class="controls">
		<select name="site_union" id="site_union">
          	<option value=""><?php echo lang('settings_select'); ?></option>                            
        </select>	
        </div>
	</div>
    
    <div class="control-group">
		<label class="control-label" for="site_mouza"><?=lang('mouza')?> </label>        
        <div class="controls">
		<select name="site_mouza" id="site_mouza">
          	<option value=""><?php echo lang('settings_select'); ?></option>                            
        </select>	
        </div>
	</div>
    
	<div class="control-group">
		<label class="control-label" for="site_village"><?=lang('village')?> </label>        
        <div class="controls">
		<select name="site_village" id="site_village">
          	<option value=""><?php echo lang('settings_select'); ?></option>                            
        </select>
        </div>
	</div>
	
    <div class="control-group">
		<label class="control-label " for="registration_gender"><?=lang('settings_gender')?> <span class="required">*</span></label>        
        <div class="controls">                      
		<label class="radio inline">
        <input type="radio" name="gender" id="gender_male" value="M" ><?=lang('gender_male')?></label>
        <label class="radio inline">
        <input type="radio" name="gender" id="gender_female" value="F"><?=lang('gender_female')?></label>
        </div>
	</div>
    
	<div class="control-group">
		<label class="control-label" for="Registration_landmark"><?=lang('landmark')?></label>
        <div class="controls">
		<input class="form-control" placeholder="Landmark" name="reg_landmark" value="<?php echo set_value('reg_landmark');?>" id="reg_landmark" type="text" />
        </div>
	</div>

	<div class="control-group">
		<label class="control-label" for="Registration_phone"><?=lang('phone')?><span class="required">*</span></label>        
        <div class="controls">
		<input class="span1" name="phone_country_code" id="phone_country_code" value="+88" type="text" />
        <input class="input-mini" placeholder="01XXX" name="phone_part1" id="phone_part1" value="<?php echo set_value('phone_part1');?>" type="text" />
        <input class="input-mini" placeholder="XXXXXX" name="phone_part2" id="phone_part2" value="<?php echo set_value('phone_part2');?>" type="text" />
       	</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="Registration_national_id"><?=lang('national_id')?></label>        
        <div class="controls">
        <input class="form-control" placeholder="National ID" name="reg_national_id" id="reg_national_id" value="<?php echo set_value('reg_national_id');?>" type="text" />
        </div>
	</div>	   

	
    </div><!-- /end span6 -->
    
    
    <div class="span5">
    <h4>Booking services info.</h4>	
        <div class="control-group">
			<label class="control-label" for="reg_site"><?=lang('site')?></label>        
        	<div class="controls">
			<select name="reg_site" class="input-large" id="reg_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $site1->site_name:$site1->site_name_bn; ?></option>
				<?php endforeach; ?>
        	</select>
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="reg_services_point"><?=lang('services_point')?></label>        
        	<div class="controls">
			<select name="reg_services_point" class="input-large" id="reg_services_point">
            <option value=""><?php echo lang('settings_select'); ?></option>            
	        </select>        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="reg_services"><?=lang('services')?></label>        
        	<div class="controls">
			<select name="reg_services" class="input-large" id="reg_services">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php foreach ($services as $services1) : ?>
            	<option value="<?php echo $services1->services_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $services1->services_name:$services1->services_name_bn; ?></option>
				<?php endforeach; ?>         
	        </select>        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="reg_services_package"><?=lang('package')?></label>        
        	<div class="controls">
			<select name="reg_services_package" class="input-large" id="reg_services_package">
            <option value=""><?php echo lang('settings_select'); ?></option>            
	        </select>        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_date"><?=lang('service_date')?></label>        
        	<div class="controls">
            <div id="no_schedule"> </div>
            <select name="schedule_date" class="input-medium" id="schedule_date">
            <option value=""><?php echo lang('settings_select'); ?></option>                              
	        </select>  
            
            <select name="schedule_time" class="input-small" id="schedule_time">
            <option value=""><?php echo lang('settings_select'); ?></option> 
  			<?php
			$start=strtotime('10:00');
			$end=strtotime('18:00');
			for ($halfhour=$start;$halfhour<=$end;$halfhour=$halfhour+30*60) {
    		printf('<option value="%s">%s</option>',date('H:i',$halfhour),date('g:i a',$halfhour));
			}
			?>                             
	        </select> 
            
        	</div>
		</div>
        
         
         
        <div class="control-group">
			<label class="control-label" for="Registration_national_id"><?=lang('note')?></label>        
        	<div class="controls">
        	<textarea rows="3" placeholder="Special note" name="reg_note" id="reg_note"><?php echo set_value('reg_note');?></textarea>        
        	</div>
		</div>	
                
		<script type="text/javascript">
		$(function(){
			$('*[name=services_date]').appendDtpicker();
		});
		</script>
        
    </div><!-- /end span6 -->
    
    <div class="span10">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="Save Booking" />
            </div>
        </div>
        
    </div><!-- /end span12 -->
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>