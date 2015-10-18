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
    
    <div class="span9">
    	<div class="panel panel-info">        
            <div class="panel-heading">
                <img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <strong style="font-size:18px;"> <?=lang('schedule_list')?></strong> 
                <?php if ($this->authorization->is_permitted('car_schedule_manage')) : ?> 
                <div class="pull-right">
                    <a href="car/schedules/save" class="btn btn-primary"> 
                    <i class="icon-plus-sign icon-white"></i> Add Schedule</a> 
                </div>
                <?php endif; ?>
            </div>
                
            <div class="panel-body"> 
                <?php echo form_open('car/schedules/search_schedule') ?>       
                <table class="table table-bordered">
                    
                    <tr class="warning">
                        <td>
                            <input type="text" class="input-medium" id="date1" value="<?php echo set_value('sdate')?>"  name="sdate" placeholder="Date From">
                        </td>
                        <td>
                            <input type="text" id="date2" class="input-medium" value="<?php echo set_value('edate')?>"  name="edate" placeholder="Date To">
                        </td>
                        <td>
                            <select name="car_id" id="car_id" class="selectpicker span2" data-style="btn">
                                <option value="">Select Car</option>
                                <?php foreach ($all_car as $car) {?>
                                <option <?php echo ($car->car_id==$this->input->post('car_id'))?"selected":"" ?> value="<?php echo $car->car_id?>"><?php echo $car->licence_no?></option>
                                 <?php
                                }
                                ?>
                            </select>
                        </td>
                        <?php
                          $selected = isset( $_POST['status'] ) ? $_POST['status'] : '' ;
                        ?>                    
                        <td>
                        <select name="status" id="sservices_status" class="selectpicker span2" data-style="btn">
                            <option value=""><?php echo lang('settings_select'); ?></option>
                            <option value="1" <?php echo $selected == 1 ? 'selected' : '' ?> data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
                            ><?php echo lang('active')?></option>
                            <option value="0" <?php echo $selected == '0' ? 'selected' : '' ?> data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                        </select>
                        </td>                    
                        <td>
                        <button class="btn-small btn-primary" type="submit" name="search_submit"><i class="icon-search icon-white"></i> <?=lang('website_search')?></button>
                        </td>
                    </tr>
                </table>
                    
                <?php echo form_close();?> 
                <table class="table table-bordered"> 
                    
                    <tr class="warning">
                        <th width="40"><?=lang('sl')?></th>
                        <th>Schedule Date</th>
                        <th>Scheduled Car</th>
                        <th>No. Of Set</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr> 
                    <?php 
                    $page = (isset($page))? $page:0;
                    $i=$page+1;
                    ?>
                    <?php if(!empty($schedules) ) : 
                    $language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
                    foreach ($schedules as $schedule) :
                    //echo "<pre>"; print_r($schedule); echo "</pre>";              
                    ?>                        
                    <tr>
                        <td align="center"><?php echo $i?></td>
                        <td>
                        <?php echo $schedule->schedule_date?>
                        </td>
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
                        <td>
                        <?php echo date("g:i A", strtotime($schedule->start_time));  ?>
                        </td>
                        <td>
                        <?php echo date("g:i A", strtotime($schedule->end_time));  ?>
                        </td>
                        <td width="150">                           
                           <?php if($this->authorization->is_permitted('car_delete_node')): ?>
                            <a href="car/schedules/save/<?php echo $schedule->schedule_id?>" class="btn btn-small btn-warning"><i class="icon-edit icon-white"></i> <?php echo lang('website_edit'); ?></a>
                            <?php endif; ?>
                            <?php if($this->authorization->is_permitted('car_delete_route')): ?>
                            <a href="car/schedules/delete/<?php echo $schedule->schedule_id?>" class="btn btn-small btn-danger delete"><i class="icon-trash icon-white"></i> <?php echo lang('website_delete'); ?></a>
                            <?php endif ?> 
                        </td>                        
                    </tr>   
                
                <?php 
                $i=$i+1;
                endforeach;
                ?>  
                <div style="text-align:left"><?php if(isset($links)) echo $links; ?></div>
                </table>   
                
                <?php 
                else:?>
                    <!-- <div> No avaliable data</div> -->
                <?php 
                endif
                ?> 
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