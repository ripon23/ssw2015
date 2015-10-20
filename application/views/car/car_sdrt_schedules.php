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
                todayHighlight:true
            }); 
            $('#date2').datepicker({
                format: "yyyy-mm-dd",
                autoclose:true,
                todayHighlight:true
            });  
            
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
    
    <div class="span12">
    	<div class="panel panel-info">        
            <div class="panel-heading">
                <img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <strong style="font-size:18px;"> sDRT <?=lang('schedule_list')?></strong> 
                <?php if ($this->authorization->is_permitted('car_schedule_manage')) : ?> 
                <div class="pull-right">
                    <a href="car/sdrt_schedules/add_sdrtschedule" class="btn btn-primary"> 
                    <i class="icon-plus-sign icon-white"></i> Add sDRT Schedule</a> 
                </div>
                <?php endif; ?>
            </div>
                
            <div class="panel-body"> 
                <?php echo form_open('car/sdrt_schedules/search_sdrtschedule') ?>       
                <table class="table table-bordered">
                    
                    <tr class="warning">
                        <td>
                            <input type="text" class="input-medium" id="sschedule_date" value="<?php if(isset($sschedule_date)) echo $sschedule_date; ?>"  name="sschedule_date" placeholder="Schedule Date">
                        </td>
                        <td>
                            <select name="sroute" id="sroute" class="selectpicker span2" data-style="btn">
                                <option value="">Select Route</option>
                                <?php foreach ($all_routes as $route) {?>
                                <option <?php if(isset($sroute)) echo ($route->route_id==$sroute)?"selected":"" ?> value="<?php echo $route->route_id?>"><?php echo $route->route_name_en?></option>
                                 <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="scar_id" id="scar_id" class="selectpicker span2" data-style="btn">
                                <option value="">Select Car</option>
                                <?php foreach ($all_car as $car) {?>
                                <option <?php if(isset($scar_id)) echo ($car->car_id==$scar_id)?"selected":"" ?> value="<?php echo $car->car_id?>"><?php echo $car->licence_no?></option>
                                 <?php
                                }
                                ?>
                            </select>
                        </td>
                        <?php
                          $selected = isset( $_POST['sstatus'] ) ? $_POST['sstatus'] : '' ;
                        ?>                    
                        <td>
                        <select name="sstatus" id="sstatus" class="selectpicker span2" data-style="btn">
                            <option value=""><?php echo lang('settings_select'); ?></option>
                            <option value="1" <?php if(isset($sstatus)) echo $sstatus == 1 ? 'selected' : '' ?> data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
                            ><?php echo lang('active')?></option>
                            <option value="2" <?php if(isset($sstatus)) echo $sstatus == 2 ? 'selected' : '' ?> data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                        </select>
                        </td>                    
                        <td>
                        <input type="submit" name="search_submit" id="search_submit" value="<?=lang('website_search')?>" class="btn-small btn-primary" />
                        </td>
                    </tr>
                </table>
                    
                <?php echo form_close();?> 
                <table class="table table-bordered"> 
                    
                    <tr class="warning">
                        <th width="40"><?=lang('sl')?></th>
                        <th>Schedule Date</th>
                        <th>Route</th>
                        <th>Car</th>                        
                        <th>No. of Seat</th>
                        <th>Booked Seat</th>
                        <th>Per seat fare</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Start Node</th>
                        <th>Destination Node</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr> 
                    <?php 
                    $page = (isset($page))? $page:0;
                    $i=$page+1;
                    ?>
                    <?php if($schedules) : 
                    $language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
                    foreach ($schedules as $schedule) :
                    //echo "<pre>"; print_r($schedule); echo "</pre>";              
                    ?>                        
                    <tr <?php if(strtotime($schedule->schedule_date." ".$schedule->arrival_time) >= strtotime(mdate('%Y-%m-%d %H:%i:%s', now()))) echo 'class="success"'; else echo 'class="warning"';?> >
                        <td align="center"><?php echo $i; 
						
						//echo $schedule->schedule_date." ".$schedule->arrival_time;
						?></td>
                        <td>
                        <?php echo $schedule->schedule_date?>
                        </td>
                        <td><?php echo $this->booking_model->get_route_name_from_id($schedule->route_id);?></td>
                        <td>                    
                        <?php 
                        $car_results = $this->general->get_all_table_info_by_id_custom("car_info", "licence_no, no_of_set", 'car_id', $schedule->car_id);
                        echo $car_results->licence_no;?>
                        </td>
                        <td align="center">
                        <?php 
                            echo $car_results->no_of_set;
                        ?>
                        </td>
                        
                        <td align="center">
                        <?php 
                            $available_seat=$this->booking_model->get_available_seat_in_a_car($schedule->schedule_id,$schedule->car_id);
							echo (($car_results->no_of_set)-$available_seat);
                        ?>
                        </td>
                        
                        <td align="center">
                        <?php 
                            echo $schedule->per_seat_fare." TK";
                        ?>
                        </td>
                        <td>
                        <?php echo date("g:i A", strtotime($schedule->start_time));  ?>
                        </td>
                        <td>
                        <?php echo date("g:i A", strtotime($schedule->arrival_time));  ?>
                        </td>
                        
                        <td><?php echo $this->booking_model->get_node_name_from_id($schedule->start_node);?></td>
                        <td><?php echo $this->booking_model->get_node_name_from_id($schedule->destination_node);?></td>
                        <td>
                        <?php
						if($schedule->schedule_status==1)
						echo '<span class="label label-success">Active</span>';
						
						if($schedule->schedule_status==2)
						echo '<span class="label label-important">Inactive</span>';
						
						?>
                        </td>
                        <td>                           
                           <?php if($this->authorization->is_permitted('car_schedule_manage')): ?>
                            <a href="car/sdrt_schedules/edit_sdrt_schedule/<?php echo $schedule->schedule_id?>" class="btn btn-small btn-warning" title="<?php echo lang('website_edit'); ?>"><i class="icon-edit icon-white"></i> </a>
                            <?php endif; ?>
                            <?php if($this->authorization->is_permitted('car_schedule_manage')): ?>
                            <a href="car/sdrt_schedules/delete/<?php echo $schedule->schedule_id?>" class="btn btn-small btn-danger delete" title="<?php echo lang('website_delete'); ?>"><i class="icon-trash icon-white"></i> </a>
                            <?php endif ?> 
                        </td>                        
                    </tr>   
                
                <?php 
                $i=$i+1;
                endforeach;
                ?>  
               
                  
                 <div style="text-align:left"><?php if(isset($links)) echo $links; ?></div>
                <?php 
                else:?>
                     <h4> No avaliable data</h4> 
                <?php 
                endif;
                ?> 
                </table> 
            </div><!-- /end panel-body -->
        </div><!-- /end panel -->
    </div> <!-- /span9 panel -->
    
    
    <!--<div class="span3">
    	<?php //echo $this->load->view('car/car_sidebar');?>
    </div>-->


</div><!-- /end row -->
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>