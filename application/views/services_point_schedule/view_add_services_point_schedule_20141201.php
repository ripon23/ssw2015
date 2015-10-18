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


function deleteclick_id(services_point_id,event_date)
{
	var dataString;	
    $.ajax({
           type: "POST",
           url: "services_point_schedule/services_point_schedule/remove_schedule/"+services_point_id+"/"+event_date,
		   data: dataString,
           success: function(msg)
           {               	
			   	
			//alert(msg); // show response from the php script.			      	
           }
         });

   
			
}// END deleteclick_id




jQuery(document).ready(function(){
	
	
	callCalendar('services_point_schedule/services_point_schedule/showMonth');
			$('body').delegate('.ajax-navigation', 'click', function(e){
				e.preventDefault();
				callCalendar($(this).attr('href'));
			});
			
			
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
		
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><?=lang('menu_add_services_point_schedule')?></div>
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
    
    <form class="form-horizontal" id="registration-form" action="" method="post">    
         
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
			<label class="control-label" for="schedule_date"><?=lang('schedule_date')?></label>        
        	<div class="controls">
			<select name="schedule_date" class="input-large" id="schedule_date">
            <option value=""><?php echo lang('settings_select'); ?></option> 
            <?php $date=strtotime(date('Y-m-d', time() - 2592000));?>
            <!--<option value="<?=date('Y-m-d')?>"><?php echo date('Y-m-d'); ?></option> -->
            <?php
			for($i=1;$i<35;$i++)
			{
			$newDate = date('Y-m-d',strtotime('+'.$i.' days',$date));
			echo '<option value="'.$newDate.'">'.$newDate.'</option>';
			}
			?>                       
	        </select>        
        	</div>
		</div>
    	
        <div class="control-group">
                <div class="controls">
                <input class="btn btn-primary pull-right" type="submit" name="save" value="Save Scheduled" />
                </div>
        </div>
    
    </form>
    
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