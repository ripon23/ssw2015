<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-datepicker.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap-datepicker.min.css"/>
    
  <script type="text/javascript">
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
			
			
			
		// Ajax form submit start	
			
		$('form').submit(function(event) { //Trigger on form submit
        $('#name + .throw_error').empty(); //Clear the messages first
        $('#success').empty();

        //Validate fields if required using jQuery

        var postForm = { //Fetch form data
            'route_id'     : $('#route_id').val(), //Store name fields value var base_url = $('#base_url').val();
			'node_id'     : $('#node_id').val(),
			'pick_up'     : $('#pick_up').val(),
			
			'drop_node'     : $('#drop_node').val(),
			'drop_point'     : $('#drop_point').val(),
			'booking_date'     : $('#date').val(),
			'time_delay'     : $('#time_delay').val(),
			'no_of_set'		: $('#no_of_set').val(),
			'int_araival_itme'     : $('#int_araival_itme').val()						
        };

			$.ajax({ //Process the form using $.ajax()
				type      : 'POST', //Method type
				url       : 'car/booking/check_booking', //Your form processing file URL
				data      : postForm, //Forms name
				//dataType  : 'json',
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
			event.preventDefault(); //Prevent the default submit
    	});
		// Ajax form submit End		
			
			
			
            
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

</script>
    
<script>
$(function() 
{
  $("#route_id").change(function()
  {
    var route_id=$(this).val();
    var field_name = 'route_id';
    var table_name = 'car_node';
    var base_url = $('#base_url').val();

    // get_chile($table_name, $where_field, $where_field_value, $select)
    var url = "car/booking/get_node/";
    // var url = "car/booking/test";
	
    $.ajax
    ({
      type: "POST",
      url: url,
	  //data: "table_name="+table_name+",field_name="+field_name+",route_id="+route_id,
	  data: { table_name:table_name, field_name:field_name, route_id: route_id},
      beforeSend : function (){
          //$(".dis_load").html('<img src="'+base_url+'images/ajax/ajax-1.gif">');
          $(".node_list").html('<option>Loading...</option>');
      },
      //data: dataString,
      cache: false,               
      success: function(response)
      {
        //alert(response);
		$(".node_list").html(response);
        $(".pick_up").html('<option value="">Parent Option Not Seleted</option>');
          //$(".dis_load").html('');
      }
    });
  }) 


		
	

  $("#node_id").change(function()
  {
    var node_id=$(this).val();
    var field_name = 'node_id';
    var table_name = 'car_pickup_point';
    var base_url = $('#base_url').val();

    // get_chile($table_name, $where_field, $where_field_value, $select)
    var url = "car/booking/get_pickup_point/"+table_name+"/"+field_name+"/"+node_id;
    // var url = "car/booking/test";
    $.ajax
    ({
      type: "GET",
      url: url,
      beforeSend : function (){
          //$(".dis_load").html('<img src="'+base_url+'images/ajax/ajax-1.gif">');
          $("#pick_up").html('<option>Loading...</option>');
      },
      //data: dataString,
      cache: false,               
      success: function(response)
      {
        $("#pick_up").html(response);
          //$(".dis_load").html('');
      }
    });
  }) 

  $("#drop_node").change(function()
  {
    var node_id=$(this).val();
    var field_name = 'node_id';
    var table_name = 'car_pickup_point';
    var base_url = $('#base_url').val();

    // get_chile($table_name, $where_field, $where_field_value, $select)
    var url = "car/booking/get_pickup_point/"+table_name+"/"+field_name+"/"+node_id;
    // var url = "car/booking/test";
    $.ajax
    ({
      type: "GET",
      url: url,
      beforeSend : function (){
          //$(".dis_load").html('<img src="'+base_url+'images/ajax/ajax-1.gif">');
          $("#drop_point").html('<option>Loading...</option>');
      },
      //data: dataString,
      cache: false,               
      success: function(response)
      {
        $("#drop_point").html(response);
          //$(".dis_load").html('');
      }
    });
  }) 
});
    </script>
<script>
$(document).ready(function(){
	$(".delete").click(function(e){
		e.preventDefault(); 
		var href = $(this).attr("href");
		var btn=this;
			
		if(confirm("Sure you want to delete it? There is NO undo!"))
		{
			window.location.assign(href);		
		}
		return false;
	});
});

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
        <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> Checking for car booking </div>
            <div class="panel-body">
        		<!--<form method="post" action="car/booking/check_booking" class="form-horizontal" id="booking_form">-->
                <form  class="form-horizontal" id="booking_form" name="booking_form">
                    <fieldset>

                    <!-- Form Name -->
                    <legend>Checking for booking</legend>                    
                    <!-- Select Basic -->
                    <div class="control-group">
                      <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                      <label class="control-label" for="route_id">Select Route</label>
                      <div class="controls">
                        <select id="route_id" name="route_id" class="input-large">
                          <option value="">Select Route *</option>
                          <?php foreach ($all_routes as $key => $route) {?>
                            <option <?php echo set_select('route_id')==$route->route_id? "selected":""; ?> value="<?php echo $route->route_id; ?>"><?php echo $route->route_name_en; ?></option>
                            <?php
                          }?>                          
                        </select>
                        <p class="help-block">
                        <?php echo form_error('route_id') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                      <label class="control-label" for="node_id">Select Node  *</label>
                      <div class="controls">
                        <select id="node_id" name="node_id" class="input-large node_list">
                          <option value="">Select Node</option>
                        </select>
                        <p class="help-block">
                        <?php echo form_error('node_id') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                      <label class="control-label" for="pick_up">Select Pickup Point *</label>
                      <div class="controls">
                        <select id="pick_up" class="input-large pick_up" name="pick_up">
                          <option value="">Select Pickup Point</option>
                        </select>
                        <p class="help-block">
                        <?php echo form_error('pick_up') ?>
                        </p>
                      </div>
                    </div>                    
                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="date">Booking Date *</label>
                      <div class="controls">
                        <input id="date" name="booking_date"  value="<?php echo set_value('booking_date'); ?>" type="text" placeholder="YYYY-MM-DD" class="input-large" required="">
                        <p class="help-block">
                        <?php echo form_error('booking_date') ?>
                        </p>
                      </div>
                    </div>
                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="int_araival_itme">Intended Arrival Time *</label>
                      <div class="controls">
                        <input id="int_araival_itme" name="int_araival_itme" value="<?php echo set_value('int_araival_itme');?>" type="time" placeholder="Intended Arrival Time" class="input-large" required="">
                        <p class="help-block">
                        <?php echo form_error('int_araival_itme') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                      <label class="control-label" for="drop_node">Select Drop Node *</label>
                      <div class="controls">
                        <select id="drop_node" name="drop_node" class="input-large node_list">
                          <option value="">Select Drop Point</option>
                        </select>
                        <p class="help-block">
                        <?php echo form_error('drop_node') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                      <label class="control-label" for="drop_point">Select Drop Point  *</label>
                      <div class="controls">
                        <select id="drop_point" class="input-large pick_up" name="drop_point">
                          <option value="">Select Drop Point</option>
                        </select>
                        <p class="help-block">
                        <?php echo form_error('drop_point') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Appended Input-->
                    <div class="control-group">
                      <label class="control-label" for="time_delay">Accepted Time Delay  *</label>
                      <div class="controls">
                        <div class="input-append">
                          <input  placeholder="Time Delay" type="number" required="required" id="time_delay" name="time_delay" class="input-small" value="<?php echo set_value('time_delay'); ?>">
                          <span class="add-on">Minute</span>
                        </div>
                        <p class="help-block">
                        <?php echo form_error('time_delay') ?>
                        </p>
                        
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">No. of seat *</label>
                      <div class="controls">
                        <input id="no_of_set" name="no_of_set" value="1" lenght="1" type="number" placeholder="placeholder" class="input-large">
                        <p class="help-block">
                        <?php echo form_error('no_of_set') ?>
                        </p>
                      </div>
                    </div>

                    <!-- Button -->
                    <div class="control-group">
                      <label class="control-label" for=""></label>
                      <div class="controls">
                        <!--<button id="" name="" class="btn btn-info">Checking</button>-->
                        <input type="submit" id="submitButton" class="btn btn-info" name="submitButton" value="Checking">                        
                      </div>
                    </div>

                    </fieldset>
                </form>
                
                <div id="success"></div>
                <div class="throw_error"> </div>
                
            </div><!-- /end panel-body -->
    	</div><!-- /end panel -->        
    
    </div> <!-- /end span9 -->    
    
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>