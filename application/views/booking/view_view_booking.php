<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
function deleteclick_id(button_id)
{
	var numeric = button_id.replace('delete_','');
	var agree=confirm("Are you sure you want to delete this booking?");
	if(agree)
	{
	var booking_id= document.getElementById('booking_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "booking/booking/delete_booking",
		   data: "booking_id="+booking_id,
           success: function(msg)
           {               	
			   	//removeTableRow(button_id);
			   	$('#row_' + numeric).addClass('error');			  
				//document.getElementById('row_' + numeric).style.backgroundColor = 'red';
				$('#row_' + numeric).fadeOut(4000, function(){   				
				//$("#row_"+ numeric).remove();
				$('#row_' + numeric).removeClass('error');
				});
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41">  <?=lang('menu_booking_list')?></div>
    <div class="panel-body">
       
   
    <?php echo form_open('booking/booking/search_view_booking') ?>
        
       <table class="table table-bordered">
        <tr class="warning">
          <td>Booking Id</td>
          <td><?=lang('settings_firstname')?></td>
          <td><?=lang('settings_middlename')?></td>
          <td><?=lang('settings_lastname')?></td>
          <td><?=lang('guardian_name')?></td>
          <td><?=lang('settings_gender')?></td>
          
        </tr>
        <tr class="success">
            <td><input type="text" name="sbooking_id" id="sbooking_id" value="<?php echo isset($sbooking_id)?$sbooking_id:'';?>" class="input-medium"/></td>
            <td><input type="text" name="sfirstname" id="sfirstname" value="<?php echo isset($sfirstname)?$sfirstname:'';?>" class="input-medium"/></td>            
            <td><input type="text" name="smiddlename" id="smiddlename" value="<?php echo isset($smiddlename)?$smiddlename:'';?>" class="input-medium"/></td>
            <td><input type="text" name="slastname" id="slastname" value="<?php echo isset($slastname)?$slastname:'';?>" class="input-medium"/></td>
            <td><input type="text" name="sguardian" id="sguardian" value="<?php echo isset($sguardian)?$sguardian:'';?>" class="input-medium"/></td>
            <td>
            <label class="radio inline">
       		<input type="radio" name="sgender" id="sgender" value="M" ><?=lang('gender_male')?></label>
        	<label class="radio inline">
        	<input type="radio" name="sgender" id="sgender" value="F"><?=lang('gender_female')?></label>
          	</td>
          </tr>
          <tr class="warning">
            <td><?=lang('phone')?></td>
          	<td><?=lang('national_id')?></td>           
            <td><?=lang('landmark')?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr class="success">
            <td><input type="text" name="sphone" id="sphone" value="<?php echo isset($sphone)?$sphone:'';?>" class="input-medium"/></td>
            <td><input type="text" name="snationalid" id="snationalid" value="<?php echo isset($snationalid)?$snationalid:'';?>" class="input-medium"/></td>
            <td><input type="text" name="slandmark" id="slandmark" value="<?php echo isset($slandmark)?$slandmark:'';?>" class="input-medium"/></td>
            <td colspan="3" align="right"> <input type="submit" name="search_submit" id="search_submit" value="View Booking" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
	
    
  <table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th>Booking Id</th>
                <th><?=lang('settings_fullname')?></th>
                <th><?=lang('guardian_name')?></th>
                <th><?=lang('settings_dateofbirth')?></th>
                <th><?=lang('settings_gender')?></th>
                <th><?=lang('district')?></th>
                <th><?=lang('upazila')?></th>
                <th><?=lang('union')?></th>
                <!--<th><?=lang('landmark')?></th>-->
                <th><?=lang('phone')?></th> 
                <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <th><?=lang('website_view')?></th> 
                <?php endif; ?>
                
				<?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <th><?=lang('website_delete')?></th>  
                <?php endif; ?>
            </tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_booking) ) {
			foreach ($all_booking as $booking) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $booking->booking_id;?></td>
                <td><?php echo $booking->first_name." ".$booking->middle_name." ".$booking->last_name ; ?></td>
                <td><?php echo $booking->guardian_name;?></td>
                <td><?php echo $booking->dob;?></td>
                <td><?php echo $booking->gender;?></td>
                <td><?php if($booking->district_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$booking->district_id,NULL,NULL,NULL,NULL,$ltype='DT');?></td>
                <td><?php if($booking->upazila_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$booking->district_id,$booking->upazila_id,NULL,NULL,NULL,$ltype='UP');?></td>
                <td><?php if($booking->upazila_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$booking->district_id,$booking->upazila_id,$booking->union_id,NULL,NULL,$ltype='UN');?></td>
                <!--<td><?php //echo $booking->landmark;?></td>-->
                <td><?php echo $booking->phone;?></td>
                 <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <td><a href="<?php echo base_url().'booking/booking/view_single_booking/'.$booking->booking_id ;?>" class="btn btn-small btn-info"><?=lang('website_view')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <td><a href="<?php echo base_url().'booking/booking/edit_single_booking/'.$booking->booking_id ;?>" class="btn btn-small btn-warning"><?=lang('website_edit')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                <td>
                <input type="hidden" name="booking_id_<?=$i?>" id="booking_id_<?=$i?>" value="<?=$booking->booking_id?>"/>
                <input type="button" name="delete_<?=$i?>" id="delete_<?=$i?>" value="<?=lang('website_delete')?>" onClick="deleteclick_id(this.id)" class="btn-small btn-danger" />
                </td>
                <?php endif; ?>
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