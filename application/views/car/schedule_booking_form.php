<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>

<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-datepicker.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap-datepicker.min.css"/>
    
<script type="text/javascript">
function book_my_seat(button_id)
{
var schedule_id = button_id.replace('booked_','');	
//alert(schedule_id);
var pickup_point_id = $('#pickup_point_id_'+schedule_id).val();
var drop_point_id = $('#drop_point_id_'+schedule_id).val();
var no_of_seat = $('#no_of_seat_'+schedule_id).val();

if(!pickup_point_id)
{
alert("Please select pickup point");	
return;
}

if(!drop_point_id)
{
alert("Please select drop point");	
return;
}

var postData = { //Fetch form data
            'pickup_point_id'     : pickup_point_id, 
			'drop_point_id'     : drop_point_id,
			'schedule_id'     : schedule_id,
			'no_of_seat'     : no_of_seat
        };

$.ajax({
           type: "POST",
           url: "car/booking/sdrt_booking_save",
		   data: postData,
		   beforeSend: function() {
    		$('#sdrt_booking_form_'+schedule_id).html("<img src='<?php echo base_url().RES_DIR; ?>/img/ajax-loader.gif' />");
  			},
           success: function(response)
           {               			  			
			$("#sdrt_booking_form_"+schedule_id).html(response);			      	
           }
         });

//alert("Pickup Point="+pickup_point_id+", Drop Point="+drop_point_id+", Seat="+no_of_seat);
}


function load_sdrt_schedule()
{

	var route_id= document.getElementById('route_id').value;	
	
    $.ajax({
           type: "POST",
           url: "car/sdrt_schedules/ajax_get_sdrt_schedule",
		   data: "route_id="+route_id,
		   beforeSend: function() {
    		$('#ajax_schedule_div').html("<img src='<?php echo base_url().RES_DIR; ?>/img/ajax-loader.gif' />");
  			},
           success: function(response)
           {               			  			
			$("#ajax_schedule_div").html(response);			      	
           }
         });

    return false; // avoid to execute the actual submit of the form.
	
			
}// END deleteclick_id


  
  
  
        // Code for Date Picker
        $(document).ready(function () {   
            var today = new Date();
            var lastDate = new Date(today.getFullYear(), today.getMonth(0)-1, 31);      
            $('#date').datepicker({
                format: "yyyy-mm-dd",
                autoclose:true,
                todayHighlight:true,
                startDate: '1m',
                endDate: '+1m'
            });
											    
        });
    </script>
    
    
<script type="text/javascript">
function confirm_booking(estimated_pickup_time,int_arrival_time,total_kilomiter,fare_cost)
{

var postForm = { //Fetch form data
            'route_id'     : $('#route_id').val(), 
			'node_id'     : $('#node_id').val(),
			'pick_up'     : $('#pick_up').val(),
			
			'drop_node'     : $('#drop_node').val(),
			'drop_point'     : $('#drop_point').val(),
			'booking_date'     : $('#date').val(),
			'time_delay'     : $('#time_delay').val(),
			'int_araival_itme'     : $('#int_araival_itme').val(),
			'no_of_set'		: $('#no_of_set').val(),
			'estimated_pickup_time':estimated_pickup_time,
			'int_arrival_time':int_arrival_time,
			'total_kilomiter':total_kilomiter,
			'fare_cost':fare_cost
        };



$.ajax({ //Process the form using $.ajax()
				type      : 'POST', //Method type
				url       : 'car/booking/save_booking', //Your form processing file URL
				data      : postForm, //Forms name
				success   : function(response) {
								//$('#success').fadeIn(1000).append('<p>' + response + '</p>');
								$("#success").html(response);
								//alert(response);
								//if (!data.success) { //If fails
//									if (data.errors.name) { //Returned if any error from process.php
//										$('.throw_error').fadeIn(1000).html(data.errors.name); //Throw relevant error
//									}
//								}
//								else {
//										$('#success').fadeIn(1000).append('<p>' + response + '</p>'); //If successful, than throw a success message
//									}
								}
			});


//alert(node_id);
}


$(window).on('load', function () {
	$('.selectpicker').selectpicker({
		'selectedText': 'cat'
	});
});



</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>
	<div class="span3">
    	<div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title">
                <i class="icon-user"></i>
                <?php 
                if ( ! $this->authentication->is_signed_in())
                {
                 echo lang('sign_in_page_name');
				}
				else
				{
					echo lang('car_my_menu');
				}
				 ?></h2>                
              </div>
              <div class="panel-body" >
    		<?php echo $this->load->view('car/customer_sidebar',array('active'=>'booking'));?>
        </div>
        </div>
    </div><!-- /end span3 -->
    
	<div class="span9">
    
    <div class="panel panel-info">  		
        <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> Schedule Car booking </div>
            <div class="panel-body">
        		<!--<form method="post" action="car/booking/check_booking" class="form-horizontal" id="booking_form">-->
                <form  class="form-horizontal" id="booking_form" name="booking_form">
                    <fieldset>

                    <!-- Form Name -->
                    <legend>Schedule Car booking</legend>                    
                    <!-- Select Basic -->
                    

						<?php 
						$language = $this->session->userdata('site_lang');
						foreach ($all_routes as $key => $route) {
						?>
                        <?php 
						$all_node=$this->booking_model->get_all_node_of_a_route($route->route_id);							
						echo '<div style="overflow:hidden">';	
						$route_name=$language=='bangla'? $route->route_name_bn:$route->route_name_en;
						?>
						
                        <ul class="thumbnails" style="float:left;">
							  <li class="span">
								<div class="thumbnail">
                                	<?php echo "<img src='".base_url().RES_DIR."/img/road_sign_16.png' width='32' height='32'>"; ?>
								  	<p><?php echo "<span class='label label-warning'>".$route_name."</span> ";?></p>								  
								</div>
							  </li>
							  <?php //echo "<i class='icon-arrow-right'></i> ";?>
						</ul>
						
						
						<?php
						$i = 0;
						$len = count($all_node);
						
						foreach ($all_node as $key => $value)
						{	
						$node_name=$language=='bangla'? $value['node_name_bn']:$value['node_name_en'];
						//echo "<div class='media'>  <img src='".base_url().RES_DIR."/img/car_icon.png' width='32' height='32'> <br>".$node_name."</div>";
						?> 
						<ul class="thumbnails" style="float:left">
							  <li class="span">
								<div class="thumbnail">
								  <?php echo "<img src='".base_url().RES_DIR."/img/car_icon.png' width='32' height='32'>"; ?>
								  <p><?=$node_name?></p>
								</div>
							  </li>
							  <?php //echo "<i class='icon-arrow-right'></i> ";?>
						</ul>
						<?php	
						
							//if ($i != $len - 1) {
        					// not last
							//echo "<i class='icon-arrow-right'></i> ";
							//echo '<img src="'.base_url().RES_DIR.'/img/car_icon.png" width="32" height="32">';
    						//}
						$i++;
						}
						echo '</div>';
						
						?>
                        
                        <?php } ?>

                    
                    
                    <div class="control-group">
                      <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                      <label class="control-label" for="route_id">Select Route *</label>
                      <div class="controls">
                        <select id="route_id" name="route_id" class="input-large">
                          <option value="">Select Route </option>
                          <?php foreach ($all_routes as $key => $route) {?>
                            <option <?php echo set_select('route_id')==$route->route_id? "selected":""; ?> value="<?php echo $route->route_id; ?>"><?php echo $route->route_name_en; ?></option>
                            <?php
                          }?>                          
                        </select>
                        <input type="button" id="submitButton" class="btn btn-info" name="submitButton" value="Show Schedule" onClick="load_sdrt_schedule()">
                        <p class="help-block">
                        <?php echo form_error('route_id') ?>
                        </p>
                      </div>
                    </div>                                      

                    </fieldset>
                </form>
                
                <div id="ajax_schedule_div"></div>
                <div class="throw_error"> </div>
                
            </div><!-- /end panel-body -->
    	</div><!-- /end panel -->        
    
    </div> <!-- /end span9 -->    
    
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>