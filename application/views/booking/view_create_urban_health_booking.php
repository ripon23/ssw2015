<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>



<script type="text/javascript">

		function callCalendar(url) {
			$.get(url,function(data){
				$('.calendar').html(data);
			});
		}



jQuery(document).ready(function(){
	var current_month, current_year;
	
	
	callCalendar('booking/urban_health_booking/showMonth/<?=date('m')?>/<?=date('Y')?>/<?=$family_info->site_id?>/<?=$family_info->sp_id?>');
			$('body').delegate('.ajax-navigation', 'click', function(e){
				e.preventDefault();
				callCalendar($(this).attr('href'));
			});							
	
		
	
	<!-- Start -->
	$("#schedule_date").change(function()
	{
	var id=$(this).val();
	
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "booking/urban_health_booking/load_schedule_slot/"+id+"/"+<?=$user_info->site_id?>+"/"+<?=$family_info->sp_id?>+"/"+<?=$user_info->user_id?>,
			data: dataString,
			cache: false,
			success: function(html)
			{
				if(html=='There is already a booking for you in this day . Please select another day')
				{
				$("#schedule_date_msg_div").html("<span class='label label-important'>"+html+"</span>");	
				}
				else
				{
				$("#schedule_slot").html(html);
				$("#schedule_date_msg_div").html("");
				}
			
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#schedule_slot").change(function()
	{
	var id=$(this).val();
	var schedule_date=$("#schedule_date").val();	
	
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "booking/urban_health_booking/calculated_checkup_start_time/"+schedule_date+"/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			
			//$("#calculated_checkup_start_time").val()=""+html;
			document.getElementById("calculated_checkup_start_time").value = html;
				
				if(html=='<span class="label label-important">No availavle time slot, Check another time slot or day</span>')
				{
				document.getElementById("save_button").disabled=true;
				$("#calculated_checkup_start_time_div").html(html);
				}
				else
				{
				document.getElementById("save_button").disabled=false;	
				$("#calculated_checkup_start_time_div").html("<span class='label label-success'>"+html+"</span>");
				}
			//$("#schedule_slot").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	
	
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><?=lang('menu_create_urban_health_schedule')?></div>
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
   
    <?php 
	if(isset($error_msg))
	{					 
	?>
    <div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$error_msg?>
    </div>
	<?php
	}
	?>
    
    <form class="form-horizontal" id="registration-form" action="" method="post">                 	
    
    	
		<div class="control-group">
			<label class="control-label" for="schedule_date"><?php echo lang('site'); ?></label>        
        	<div class="controls">
			<?php  echo '<span class="label label-info">'.$this->ref_site_model->get_site_name_by_id($user_info->site_id).'</span>';?>
        	</div>
		</div>
        
        
        <div class="control-group">
			<label class="control-label" for="schedule_date"><?php echo lang('services_point'); ?></label>        
        	<div class="controls">
			<?php  echo '<span class="label label-info">'.$this->ref_site_model->get_site_name_by_id($family_info->sp_id).'</span>';?>
        	</div>
		</div>
        
        
        <div class="control-group">
			<label class="control-label" for="family"><?php echo lang('family'); ?></label>        
        	<div class="controls">
			<?php  echo '<span class="label label-success">'.$family_info->household_name.'</span>';?>
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_date"><?=lang('schedule_date')?></label>        
        	<div class="controls">
			<select name="schedule_date" class="input-large" id="schedule_date">
            <option value=""><?php echo lang('settings_select'); ?></option>             
            <?php
			foreach($schedule_date as $schedule_dates)
			echo '<option value="'.$schedule_dates->schedule_date.'">'.date('d-m-Y',strtotime($schedule_dates->schedule_date)).'</option>';			
			?>                       
	        </select>  
            <div id="schedule_date_msg_div" style="width:400px; display:inline" > </div>
        	</div>
		</div>
    	
        <div class="control-group">
			<label class="control-label" for="schedule_slot"><?=lang('schedule_slot')?></label>        
        	<div class="controls">
			<select name="schedule_slot" class="input-large" id="schedule_slot">            
	        </select> 
                  
        	</div>
		</div>
        
       <div class="control-group">
			<label class="control-label" for="schedule_type"><?=lang('calculated_checkup_start_time')?></label>        
        	<div class="controls">
            <input type="hidden"  name="calculated_checkup_start_time" id="calculated_checkup_start_time"/>
			<div id="calculated_checkup_start_time_div" style="width:300px;" > </div>                   
        	</div>
		</div>
       
        
        <div class="control-group">
                <div class="controls">
                <input class="btn btn-primary pull-right" type="submit" name="save" id="save_button" value="Save Booking" disabled />
                </div>
        </div>
    
    </form>
    <?php
    //$last_day_this_month  = date('m-t-Y');
	//echo $last_day_this_month;
    ?>
    <div class="calendar"></div>
    
    
    
  <?php  
    
   
 ?>

    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>