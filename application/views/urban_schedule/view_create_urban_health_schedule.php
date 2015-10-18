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


function deleteclick_id(event_date)
{
	
	var agree=confirm("Are you sure you want to delete this schedule?");
	if(agree)
	{
	var dataString;	
    $.ajax({
           type: "POST",
           url: "urban_schedule/urban_health_schedule/remove_schedule/"+event_date,
		   data: dataString,
           success: function(msg)
           {               				   	
			//alert(msg); // show response from the php script.			      	
			location.reload(); 
           }
         });

	}
			
}// END deleteclick_id




jQuery(document).ready(function(){
	
	
	callCalendar('urban_schedule/urban_health_schedule/showMonth');
			$('body').delegate('.ajax-navigation', 'click', function(e){
				e.preventDefault();
				callCalendar($(this).attr('href'));
			});
			
	
	
	
	<!-- Start -->
	$("#schedule_type").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{
		$( "#schedule_type_10am_12pm" ).prop( "disabled", true );
		$( "#schedule_type_12pm_13pm" ).prop( "disabled", true );
		$( "#schedule_type_13pm_14pm" ).prop( "disabled", true );
		$( "#schedule_type_14pm_15pm" ).prop( "disabled", true );
		$( "#schedule_type_15pm_17pm" ).prop( "disabled", true );
		$( "#reg_site").hide();
		$( "#reg_site2").hide();
		$( "#reg_site3").hide();
		$( "#reg_site4").hide();
		$( "#reg_site5").hide();
		$( "#reg_services_point").hide();
		$( "#reg_services_point2").hide();
		$( "#reg_services_point3").hide();
		$( "#reg_services_point4").hide();
		$( "#reg_services_point5").hide();
		}
		else
		{
		$( "#schedule_type_10am_12pm" ).prop( "disabled", false );
		$( "#schedule_type_12pm_13pm" ).prop( "disabled", false );
		$( "#schedule_type_13pm_14pm" ).prop( "disabled", false );
		$( "#schedule_type_14pm_15pm" ).prop( "disabled", false );
		$( "#schedule_type_15pm_17pm" ).prop( "disabled", false );
		$( "#reg_site" ).show();
		$( "#reg_site2").show();
		$( "#reg_site3").show();
		$( "#reg_site4").show();
		$( "#reg_site5").show();
		$( "#reg_services_point").show();
		$( "#reg_services_point2").show();
		$( "#reg_services_point3").show();
		$( "#reg_services_point4").show();
		$( "#reg_services_point5").show();
		}
	
	});
	<!-- End -->
	
	
	<!-- Start -->
	$("#schedule_type_10am_12pm").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{		
		$( "#reg_site" ).prop( "disabled", true );
		$( "#reg_services_point" ).prop( "disabled", true );
		}
		else
		{
		$( "#reg_site" ).prop( "disabled", false );
		$( "#reg_services_point" ).prop( "disabled", false );	
		}
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#schedule_type_12pm_13pm").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{		
		$( "#reg_site2" ).prop( "disabled", true );
		$( "#reg_services_point2" ).prop( "disabled", true );
		}
		else
		{
		$( "#reg_site2" ).prop( "disabled", false );
		$( "#reg_services_point2" ).prop( "disabled", false );	
		}
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#schedule_type_13pm_14pm").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{		
		$( "#reg_site3" ).prop( "disabled", true );
		$( "#reg_services_point3" ).prop( "disabled", true );
		}
		else
		{
		$( "#reg_site3" ).prop( "disabled", false );
		$( "#reg_services_point3" ).prop( "disabled", false );	
		}
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#schedule_type_14pm_15pm").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{		
		$( "#reg_site4" ).prop( "disabled", true );
		$( "#reg_services_point4" ).prop( "disabled", true );
		}
		else
		{
		$( "#reg_site4" ).prop( "disabled", false );
		$( "#reg_services_point4" ).prop( "disabled", false );	
		}
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#schedule_type_15pm_17pm").change(function()
	{
	var id=$(this).val();
	
	//alert(id);
		if(id>1)
		{		
		$( "#reg_site5" ).prop( "disabled", true );
		$( "#reg_services_point5" ).prop( "disabled", true );
		}
		else
		{
		$( "#reg_site5" ).prop( "disabled", false );
		$( "#reg_services_point5" ).prop( "disabled", false );	
		}
	
	});
	<!-- End -->
	
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
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_site2").change(function()
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
			$("#reg_services_point2").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_site3").change(function()
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
			$("#reg_services_point3").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_site4").change(function()
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
			$("#reg_services_point4").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_site5").change(function()
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
			$("#reg_services_point5").html(html);
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
			<label class="control-label" for="schedule_date"><?=lang('schedule_date')?></label>        
        	<div class="controls">
			<select name="schedule_date" class="input-large" id="schedule_date">
            <option value=""><?php echo lang('settings_select'); ?></option> 
            <?php $date=strtotime(date('Y-m-d'));?>
            <!--<option value="<?=date('Y-m-d')?>"><?php echo date('Y-m-d'); ?></option> -->
            <?php
			for($i=0;$i<31;$i++)
			{
			$newDate = date('Y-m-d',strtotime('+'.$i.' days',$date));
			echo '<option value="'.$newDate.'">'.$newDate.'</option>';
			}
			?>                       
	        </select>        
        	</div>
		</div>
    	
        <div class="control-group">
			<label class="control-label" for="schedule_type"><?=lang('schedule_type')?></label>        
        	<div class="controls">
			<select name="schedule_type" class="input-large" id="schedule_type">
            <option value="1"><?php echo lang('office_day'); ?></option>
            <option value="2"><?php echo lang('off_day'); ?></option>
            <option value="3"><?php echo lang('national_holiday'); ?></option>
	        </select>        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_type">10 AM - 12 PM</label>        
        	<div class="controls">
			<select name="schedule_type_10am_12pm" class="input-large" id="schedule_type_10am_12pm">            
            <option value="1"><?php echo lang('working_hour'); ?></option>
            <option value="2"><?php echo lang('lunch_break'); ?></option>
            <option value="3"><?php echo lang('break'); ?></option>
	        </select>  
            
            <select name="reg_site" class="input-large" id="reg_site">
            	<option value=""><?php echo lang('site'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<select name="reg_services_point" class="input-large" id="reg_services_point"> 
            	<option value=""><?php echo lang('services_point'); ?></option>                                
	        </select>                        
        	</div>
		</div>

        <div class="control-group">
			<label class="control-label" for="schedule_type">12 PM - 13 PM</label>        
        	<div class="controls">
			<select name="schedule_type_12pm_13pm" class="input-large" id="schedule_type_12pm_13pm">
            <option value="1"><?php echo lang('working_hour'); ?></option>
            <option value="2"><?php echo lang('lunch_break'); ?></option>
            <option value="3"><?php echo lang('break'); ?></option>
	        </select>  
            
            <select name="reg_site2" class="input-large" id="reg_site2">
            	<option value=""><?php echo lang('site'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<select name="reg_services_point2" class="input-large" id="reg_services_point2"> 
            	<option value=""><?php echo lang('services_point'); ?></option>                                
	        </select>                        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_type">13 PM - 14 PM</label>        
        	<div class="controls">
			<select name="schedule_type_13pm_14pm" class="input-large" id="schedule_type_13pm_14pm">
            <option value="1"><?php echo lang('working_hour'); ?></option>
            <option value="2"><?php echo lang('lunch_break'); ?></option>
            <option value="3"><?php echo lang('break'); ?></option>
	        </select>  
            
            <select name="reg_site3" class="input-large" id="reg_site3">
            	<option value=""><?php echo lang('site'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<select name="reg_services_point3" class="input-large" id="reg_services_point3"> 
            	<option value=""><?php echo lang('services_point'); ?></option>                                
	        </select>                        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_type">14 PM - 15 PM</label>        
        	<div class="controls">
			<select name="schedule_type_14pm_15pm" class="input-large" id="schedule_type_14pm_15pm">
            <option value="1"><?php echo lang('working_hour'); ?></option>
            <option value="2"><?php echo lang('lunch_break'); ?></option>
            <option value="3"><?php echo lang('break'); ?></option>
	        </select>  
            
            <select name="reg_site4" class="input-large" id="reg_site4">
            	<option value=""><?php echo lang('site'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<select name="reg_services_point4" class="input-large" id="reg_services_point4"> 
            	<option value=""><?php echo lang('services_point'); ?></option>                                
	        </select>                        
        	</div>
		</div>
        
        <div class="control-group">
			<label class="control-label" for="schedule_type">15 PM - 17 PM</label>        
        	<div class="controls">
			<select name="schedule_type_15pm_17pm" class="input-large" id="schedule_type_15pm_17pm">
            <option value="1"><?php echo lang('working_hour'); ?></option>
            <option value="2"><?php echo lang('lunch_break'); ?></option>
            <option value="3"><?php echo lang('break'); ?></option>
	        </select>  
            
            <select name="reg_site5" class="input-large" id="reg_site5">
            	<option value=""><?php echo lang('site'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<select name="reg_services_point5" class="input-large" id="reg_services_point5"> 
            	<option value=""><?php echo lang('services_point'); ?></option>                                
	        </select>                        
        	</div>
		</div>
        
        <div class="control-group">
                <div class="controls">
                <input class="btn btn-primary pull-right" type="submit" name="save" value="Save Scheduled" />
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