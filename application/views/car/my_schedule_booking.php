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
    		<?php echo $this->load->view('car/customer_sidebar',array('active'=>'my_schedule_booking'));?>
        </div>
        </div>
    </div><!-- /end span3 -->
    
	<div class="span9">
    
    <div class="panel panel-info">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('car_my_booking')?> </div>
    <div class="panel-body">
		<table class="table table-bordered table-striped" style="font-size:11px">
			<tr>
                <th>B.ID</th>
                <th>Reference ID</th>
                <th><?=lang('route_name')?></th>
                <th>Date for booking</th> 
                <th>Start - Arrival</th> 
                <th>Pickup point</th>      
                <th>Drop point</th>   
                <th>Seat</th>
                <th>Cost</th>  
                <th>Status</th>
                <th>
                <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                <a href="car/booking/schedule_booking" class="btn btn-mini btn-primary"> <i class="icon-plus-sign icon-white"></i> New booking </a> 
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
                <td><?php echo $latest_book->sbooking_id; ?></td>
                <td><?php echo $latest_book->reference_id; ?></td>
                <td>
				<?php //echo ($language=='bangla')? ($latest_book['route_details']->route_name_bn==NULL || $latest_book['route_details']->route_name_bn=='')?$latest_book['route_details']->route_name_en:$latest_book['route_details']->route_name_bn : $latest_book['route_details']->route_name_en
				echo $this->booking_model->get_route_name_from_id($latest_book->route_id);
				?>
                </td>
                <td><?php echo date("d F, Y", strtotime($latest_book->schedule_date));  ?>
                </td>
                <td><?php echo substr($latest_book->start_time,0,5)." - ".substr($latest_book->arrival_time,0,5);?> </td>
                <td>
                    <?php echo $this->booking_model->get_pickup_point_name_from_id($latest_book->pickup_point);?>
                </td>
                <td><?php echo $this->booking_model->get_pickup_point_name_from_id($latest_book->destination_point);?></td>
                
                
                <td><?php echo $latest_book->no_of_seat; ?></td>
                <td><?php echo $latest_book->fare_cost." TK"; ?></td>
                <td>
                <?php  
                    if($latest_book->booking_status==3) echo '<span class="badge badge-warning">'.lang('processing').'</span>';
                    if($latest_book->booking_status==2) echo '<span class="label label-important">'.lang('cancel').'</span>';
                    if($latest_book->booking_status==1) echo '<span class="label label-success">'.lang('availed').'</span>';
                    if($latest_book->booking_status==0) echo '<span class="label label-info">'.lang('received').'</span>';                    
                ?>
                </td>
                <td width="150">
                    
                	<?php if($this->authorization->is_permitted('car_my_booking')):                     
                    
                    $timeDiff = (strtotime($latest_book->schedule_date." ".$latest_book->start_time)-now())/60;
                
                    if ($timeDiff>$this->config->item("cancel_before_time")) {
                         if ($latest_book->booking_status!=2) {?>                    
                            <a href="car/my_booking/schedule_booking_cancelled/<?php echo $latest_book->sbooking_id; ?>" class="btn btn-small btn-warning btn-mini"><i class="icon-remove icon-white"></i> <?php echo lang('cancel'); ?></a>
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