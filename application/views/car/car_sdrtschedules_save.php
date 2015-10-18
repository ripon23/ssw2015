<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-datepicker.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap-datepicker.min.css"/>
    
    <script type="text/javascript">
        $(document).ready(function () {   
            var today = new Date();
            var lastDate = new Date(today.getFullYear(), today.getMonth(0)-1, 31);      
            $('#date1').datepicker({
                format: "yyyy-mm-dd",
                autoclose:true,
                todayHighlight:true,
                startDate: '1m',
                endDate: '+1m'
            }); 
            $('#date2').datepicker({
                format: "yyyy-mm-dd",
                autoclose:true,
                todayHighlight:true,
                startDate: '1m',
                endDate: '+1m'
            });  
            
        });
		
	$(window).on('load', function () {
	$('.selectpicker').selectpicker({
		'selectedText': 'cat'
		});
	});
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

 
});
    </script>
</head>
<body>

<?php echo $this->load->view('header'); ?>
    <div class="span9">
    	<div class="panel panel-info">  		
        <div class="panel-heading"><i class="icon-road"></i> <?=lang('schedule_add')?> 
            <?php if ($this->authorization->is_permitted('car_schedule_manage')) : ?> 
            <div class="pull-right">
                <a href="car/sdrt_schedules" class="btn btn-primary"> 
                <i class="icon-th icon-white"></i> sDRT Schedule List</a> 
                <br>
            </div>
            <div style="clear:both;"></div>
            <?php endif; ?>
        </div>
        <div class="panel-body">
        <?php echo form_open(uri_string(),'class="form-horizontal"') ?>
            
                <table class="table table-bordered">                    
                    
                    <tr>
                        <td align="right"><label for="route_id"><strong><?php echo  lang('select_route')?> <span style="color:red;">*</span></strong></label></td>
                        <td>
                            <select name="route_id" id="route_id">
                                <option value=""><?php echo  lang('select_route')?></option>
                                <?php foreach ($all_routes as $route) {?>
                                <option <?php if(isset($sroute)) echo ($route->route_id==$sroute)?"selected":"" ?> value="<?php echo $route->route_id?>"><?php echo $route->route_name_en?></option>
                                 <?php
                                }
                                ?>
                            </select>
                            <span class="help-inline" style="color:#EF030A">
                                <?php echo form_error('route_id')?>
                            </span>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td align="right"><label for="start_node"><strong><?php echo  "Select start node";?> <span style="color:red;">*</span></strong></label></td>
                        <td>                        
                            <select id="start_node" name="start_node" class="input-large node_list">
                              <option value="">Select start node</option>
                            </select>
                            <span class="help-inline" style="color:#EF030A">
                            <?php echo form_error('start_node') ?>
                            </span>                          
                    	</td>
                    </tr>
                    
                    <tr>
                    	<td align="right"><label for="route_id"><strong><?php echo  "Select destination node";?> <span style="color:red;">*</span></strong></label></td>
                        <td>                        
                            <select id="drop_node" name="drop_node" class="input-large node_list">
                          		<option value="">Select destination node</option>
                        	</select>
                            <span class="help-inline" style="color:#EF030A">
                            <?php echo form_error('drop_node') ?>
                            </span>                          
                    	</td>
                    </tr>
                    
                    
                    <tr>
                        <td align="right"><label for="car_id"><strong><?php echo  lang('select_car')?> <span style="color:red;">*</span></strong></label></td>
                        <td>
                            <select name="car_id" id="car_id">
                                <option value=""><?php echo  lang('select_car')?></option>
                                <?php foreach($all_car as $car): ?>
                                <option <?php echo isset($update_details->car_id) && $update_details->car_id == $car->car_id ? "selected":""; ?> value="<?php echo $car->car_id?>">
                                    <?php echo $car->licence_no ?>
                                 </option>
                                <?php endforeach;?>
                            </select>
                            <span class="help-inline" style="color:#EF030A">
                                <?php echo form_error('car_id')?>
                            </span>
                        </td>
                    </tr>
					
                    
                    
                    
                    <tr>
                        <td align="right"><label for="date1"><strong><?php echo  lang('schedule_date')?> <span style="color:red;">*</span></strong></label></td>
                        <td>
                            <input type="text" value="<?php echo set_value('sdate'); ?>" class="input-medium" id="date1" name="sdate" placeholder="Start Date"> 
                           
                            <!--To 
                            <input type="text" value="<?php //echo isset($update_details->schedule_date)? $update_details->schedule_date : set_value('edate'); ?>" class="input-medium" id="date2" name="edate" placeholder="End Date">-->

                            <span class="help-inline" style="color:#EF030A">
                                <?php echo form_error('sdate')?>
                                <?php //echo form_error('edate')?>
                            </span>
                        </td>
                    </tr>
                                        
                    <tr>

                        <td align="right"><label for="stime"><strong><?php echo  lang('schedule_time')?> <span style="color:red;">*</span></strong></label></td>
                        <td>
                            <div class="input-append">
                                <input id="stime"  type="time" value="<?php echo isset($update_details->start_time)? $update_details->start_time : set_value('sime'); ?>" style="width:100px" class="input-mini timemask timepicker" name="stime" placeholder="hh:mm" value=""> <span class="add-on clearpicker"><i class="icon-time"></i></span>
                            </div> To 
                            <div class="input-append">
                                <input id="etime"  type="time" style="width:100px" value="<?php echo isset($update_details->end_time)? $update_details->end_time : set_value('etime'); ?>" class="input-mini timemask timepicker" name="etime" placeholder="hh:mm" value=""> <span class="add-on clearpicker"><i class="icon-time"></i></span>
                            </div>
                            <span class="help-inline">(HH:mm)24hr format ex:18:30</span>
                            <span class="help-inline" style="color:#EF030A">
                                
                                <?php echo form_error('stime')?>
                                <?php echo form_error('etime')?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><label for="status"><strong><?php echo  lang('car_fare')?> <span style="color:red;">*</span></strong></label></td>                
                        <td>
                        <input type="text" value="<?php echo set_value('per_seat_fare'); ?>" class="input-mini" id="per_seat_fare" name="per_seat_fare" placeholder="Fare"> TK
                        </td>  
                    </tr>
                    
                    <tr>
                        <td align="right"><label for="status"><strong><?php echo  lang('status')?> <span style="color:red;">*</span></strong></label></td>                
                        <td>
                        <select name="status" id="status" class="selectpicker span2" data-style="btn">
                            <option value=""><?php echo lang('settings_select'); ?></option>
                            <option value="1" <?php if(isset($status)) echo $status == 1 ? 'selected' : '' ?> data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
                            ><?php echo lang('active')?></option>
                            <option value="2" <?php if(isset($status)) echo $status == 2 ? 'selected' : '' ?> data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                        </select>
                        <span class="help-inline" style="color:#EF030A">
                            <?php echo form_error('status')?>
                        </span>
                        </td>  
                    </tr>
                    
                    <tr>
                        <td align="right"></td>
                        <td><button type="submit" class="btn btn-primary" name="submit"><i class="icon-check icon-white"></i> <?php echo lang('website_save') ?></button></td>
                    </tr>
                </table>
                
            <?php echo form_close();?>
        
            </div><!-- /end panel-body -->
        </div><!-- /end panel -->
    </div>
    
    <div class="span3">
    	<?php echo $this->load->view('car/car_sidebar');?>
    </div><!-- /end row -->
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>