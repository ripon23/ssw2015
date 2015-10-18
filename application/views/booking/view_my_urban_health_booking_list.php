<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
	function deleteclick_id(booking_id)
	{
		
		var agree=confirm("Are you sure you want to cancel this booking?");
		if(agree)
		{
	
		$.ajax({
			   type: "POST",
			   url: "booking/urban_health_booking/cancel_booking",
			   data: "booking_id="+booking_id,
			   success: function(msg)
			   {               	
					var result = $.trim(msg);    				
					if(result==="Booking successfully cancel")
					{
					location.reload(); 
					}
				alert(msg); // show response from the php script.			      	
			   }
			 });
	
		return false; // avoid to execute the actual submit of the form.
		}// END IF
		else
		{
			return false; // avoid to execute the actual submit of the form.
		}
				
	}// END deleteclick_id
</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> <?=lang('my')?> <?=lang('urban_health_booking_list')?></div>
    <div class="panel-body">            	
    
  <table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th>Booking Id</th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('schedule_date')?></th>
                <th><?=lang('schedule_slot')?></th>
                <th><?=lang('calculated_checkup_start_time')?></th>
                <th><?=lang('status')?></th>  
				<th><?=lang('action')?></th>
            </tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($booking_info) ) {
			foreach ($booking_info as $booking) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $booking->health_booking_id;?></td>
                <td><?php echo $booking->reg_no; ?></td>
                <td><?php echo $booking->booking_date;?></td>
                <td>
				<?php 
				if($booking->booking_slot==1)
				echo '10AM - 12PM';
				elseif($booking->booking_slot==2)
				echo '12PM - 13PM';
				elseif($booking->booking_slot==3)
				echo '13PM - 14PM';
				elseif($booking->booking_slot==4)
				echo '14PM - 15PM';
				elseif($booking->booking_slot==5)
				echo '15PM - 17PM';
				?>
                </td>
                <td><?php echo $booking->calculated_checkup_time;?></td>
                <td>
				<?php 
				if($booking->booking_status==0)
				echo '<span class="label label-warning">Placed</span>';
				elseif($booking->booking_status==1)
				echo '<span class="label label-success">Availed</span>';
				elseif($booking->booking_status==2)
				echo '<span class="label label-important">Cancel</span>';
				?>
                </td>  
                <td>
                <!-- Cancel Booking -->
			<?php if ($this->authorization->is_permitted('cancel_health_booking')) : 
			if($booking->booking_status==0)
			{
			?> 	            
            <input type="button" name="delete" id="delete" value="<?=lang('website_cancel')?>" onClick="deleteclick_id(<?=$booking->health_booking_id?>)" class="btn btn-mini btn-danger" />
            <?php 
			}
			endif; 
			?>
                </td>              
            </tr>
            <?php 
			$i=$i+1;
			endforeach; 
			}//end if
			?> 
             
   	  </table>                
		<div style="text-align:left"><?php echo $links; ?></div>
    
     
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>