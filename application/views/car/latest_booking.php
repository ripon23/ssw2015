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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span9">
    
    <div class="panel panel-info">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('car_booking_last')?> </div>
    <div class="panel-body">
    <?php echo form_open('car/latest_booking/search') ?>
       
            <table class="table table-bordered">
                <tr class="warning">
                	<td>
                        <input type="text" class="input-small" id="date1" value="<?php echo set_value('sdate')?>"  name="sdate" placeholder="Date From">
                    </td>
                    <td>
                        <input type="text" id="date2" class="input-small" value="<?php echo set_value('edate')?>"  name="edate" placeholder="Date To">
                    </td>
                    <td>
                        <input type="text" id="booking_id" class="input-small" value="<?php echo set_value('booking_id')?>"  name="booking_id" placeholder="Booking id">
                    </td>
                    <td>
                        <input type="text" id="username" class="input-small" value="<?php echo set_value('username')?>"  name="username" placeholder="Username">
                    </td>
                    <?php
                      $selected = isset( $_POST['car_id'] ) ? $_POST['car_id'] : '' ;
                    ?>                    
                    <td align="center">
                    <select name="car_id" id="car_id" class="span2">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <?php foreach ($car_list as $key => $car) {?>
                        <option value="<?php echo $car->car_id; ?>" <?php echo $selected == $car->car_id ? 'selected' : '' ?>><?php echo $car->licence_no; ?></option>
                        <?php 
                        }
                        ?>
                        </select>
                    </td> 
                    <?php
					  $selected = isset( $_POST['route_id'] ) ? $_POST['route_id'] : '' ;
					?>                    
                    <td align="center">
                    <select name="route_id" id="route_id" class="span2">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <?php foreach ($route_list as $key => $route) {?>
                        <option value="<?php echo $route->route_id; ?>" <?php echo $selected == $route->route_id ? 'selected' : '' ?> data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
                        ><?php echo $route->route_name_en; ?></option>
                        <?php 
                        }
                        ?>
                        </select>
                    </td>                    
                    <td>
                    <button class="btn-medium btn-primary" type="submit" name="search_submit"><i class="icon-search icon-white"></i></button>
                    </td>
                </tr>
            </table>
            
        <?php echo form_close();?>
	
    
		<table class="table table-bordered table-striped">
			<tr>
                <th>B. ID<?php //echo lang('booking_id')?></th>
                <th>Passenger</th>
                <th>Car</th>
                <th>Date for booking</th>
                <th><?=lang('route_name')?></th>
                 
                <th>Pickup point</th>      
                <th>Drop point</th>   
                <th>Seat</th>    
                <th>Status</th>                    
                <th>
				<?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                <!--
                <a href="car/add_car/save" class="btn btn-mini btn-primary"> <i class="icon-plus-sign icon-white"></i> <?=lang('add_new')?></a> 
                -->
                <?php endif; ?>
                </th>                				
			</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$page = (isset($page))? $page:0;
			$i=$page+1;
			?>
            <?php 
			if(!empty($latest_booking) ) {
			$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
			foreach ($latest_booking as $latest_book) :
			?>            
            <tr id="row_<?=$i?>">
				<th><?php echo $latest_book['booking_id']; ?></th>
                <td><?php echo $latest_book['username']; ?></td>
                <td>
				<?php 
				$car_results = $this->general->get_all_table_info_by_id_custom("car_info", "licence_no, no_of_set", 'car_id', $latest_book['car_id']);
                echo $car_results->licence_no;				
				?>
                
                </td>
                <td style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><?php echo date("d F, Y", strtotime($latest_book['date_of_booking']));  ?>
                </td>
                <td>
				<?php echo ($language=='bangla')? ($latest_book['route_details']->route_name_bn==NULL || $latest_book['route_details']->route_name_bn=='')?$latest_book['route_details']->route_name_en:$latest_book['route_details']->route_name_bn : $latest_book['route_details']->route_name_en?>
                </td>
                
                <td style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
					<?php echo $latest_book['pickup_point'].'<br>['.date("g:i A", strtotime($latest_book['pickup_time']));  ?>]
                </td>
                <td><?php echo $latest_book['drop_point'].'<br>['.date("g:i A", strtotime($latest_book['arrival_time']));  ?>]</td>
                 <td align="center" nowrap><?php echo $latest_book['no_of_set']." [".$latest_book['drt_cost']." ৳"; ?>]</td>
                <td>
				<?php  
					if($latest_book['status']==3) echo '<span class="badge badge-info">'.lang('processing').'</span>';
					if($latest_book['status']==2) echo '<span class="label label-warning">'.lang('cancel').'</span>';
					if($latest_book['status']==1) echo '<span class="label label-success">'.lang('availed').'</span>';
					if($latest_book['status']==0) echo '<span class="label label-info">'.lang('received').'</span>';					
				?>
                </td>               

                <td width="150">
                	<?php if($this->authorization->is_permitted('car_booking_management')): ?>
                	<!--<a href="car/latest_booking/booking_accepted/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-info btn-mini"><i class="icon-ok icon-white"></i> <?php echo lang('received'); ?></a>-->
                    <?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_booking_management')):                    
                    if ($latest_book['status']!=1) {?>
                        <a href="car/latest_booking/booking_availed/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-success btn-mini"><i class="icon-ok icon-white"></i> <?php echo lang('availed'); ?></a>
                    <?php
                    }
                    ?>                	
                    <?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_booking_management')):
                        if ($latest_book['status']!=3) {?>                    
                        <a href="car/latest_booking/booking_processing/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-info btn-mini"><i class="icon-refresh icon-white"></i> <?php echo lang('processing'); ?></a>
                    <?php
                    }
                    ?>

                	<?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_booking_management')): 
                    if ($latest_book['status']!=2) {?>                    
                        <a href="car/latest_booking/booking_cancelled/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-warning btn-mini"><i class="icon-remove icon-white"></i> <?php echo lang('cancel'); ?></a>
                    <?php
                    }
                    ?>
                	<?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_booking_management')): ?>
                    <a title="Delete Booking" href="car/latest_booking/delete_booking/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-danger btn-mini"><i class="icon-trash icon-white"></i> </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php 
			$i=$i+1;
			endforeach; 
			}
			else			
			{
			?> 
            <tr>
            	<th colspan="9" align="left"><?php echo lang('not_found'); ?></th>
            </tr>
            <?php 
			}
			?>
             
    	</table>                
		<div style="text-align:left"><?php if(isset($links)) echo $links; ?></div>
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span9 -->
    
    <div class="span3">
    	<?php echo $this->load->view('car/car_sidebar');?>
    </div><!-- /end row -->
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>