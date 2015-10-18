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
    		<?php echo $this->load->view('car/customer_sidebar',array('active'=>'my_booking'));?>
        </div>
        </div>
    </div><!-- /end span3 -->
    
	<div class="span9">
    
    <div class="panel panel-info">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('car_my_booking')?> </div>
    <div class="panel-body">
       
   
    <?php echo form_open('car/my_booking/search') ?>
       
            <table class="table table-bordered">
                <tr class="warning">
                    <td>
                        <input type="text" class="input-medium" id="date1" value="<?php echo set_value('sdate')?>"  name="sdate" placeholder="Date for booking">
                    </td>
                    
                    <td>
                        <input type="text" id="booking_id" class="input-small" value="<?php echo set_value('booking_id')?>"  name="booking_id" placeholder="Booking id">
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
                <th>B.ID</th>
                <th><?=lang('route_name')?></th>
                <th>Date for booking</th> 
                <th>Pickup point</th>      
                <th>Drop point</th>   
                <th>Seat</th>  
                <th>Status</th>
                <th>
                <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                <a href="car/booking" class="btn btn-mini btn-primary"> <i class="icon-plus-sign icon-white"></i> New booking </a> 
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
                <td>
				<?php echo ($language=='bangla')? ($latest_book['route_details']->route_name_bn==NULL || $latest_book['route_details']->route_name_bn=='')?$latest_book['route_details']->route_name_en:$latest_book['route_details']->route_name_bn : $latest_book['route_details']->route_name_en?>
                </td>
                <td style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><?php echo date("d F, Y", strtotime($latest_book['date_of_booking']));  ?>
                </td>
                <td style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
                    <?php echo $latest_book['pickup_point'].'<br>['.date("g:i A", strtotime($latest_book['pickup_time']));  ?>]
                </td>
                <td><?php echo $latest_book['drop_point'].'<br>['.date("g:i A", strtotime($latest_book['arrival_time']));  ?>]</td>
                
                
                <td><?php echo $latest_book['no_of_set']." [".$latest_book['drt_cost']." ৳"; ?>]</td>
                <td>
                <?php  
                    if($latest_book['status']==3) echo '<span class="badge badge-warning">'.lang('processing').'</span>';
                    if($latest_book['status']==2) echo '<span class="label label-important">'.lang('cancel').'</span>';
                    if($latest_book['status']==1) echo '<span class="label label-success">'.lang('availed').'</span>';
                    if($latest_book['status']==0) echo '<span class="label label-info">'.lang('received').'</span>';                    
                ?>
                </td>
                <td width="150">
                    
                	<?php if($this->authorization->is_permitted('car_my_booking')):                     
                    
                    $timeDiff = (strtotime($latest_book['date_of_booking']." ".$latest_book['pickup_time'])-now())/60;
                
                    if ($timeDiff>$this->config->item("cancel_before_time")) {
                         if ($latest_book['status']!=2) {?>                    
                            <a href="car/my_booking/booking_cancelled/<?php echo $latest_book['booking_id']; ?>" class="btn btn-small btn-warning btn-mini"><i class="icon-remove icon-white"></i> <?php echo lang('cancel'); ?></a>
                        <?php
                        }
                    }
                    else
                    {
                        echo "Can not be cancel";
                    }
                   
                    endif;
                    ?>
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
    
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>