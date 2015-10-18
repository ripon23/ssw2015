<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
function deleteclick_id(button_id)
{
	var numeric = button_id.replace('delete_','');
	var agree=confirm("Are you sure you want to delete this registration?");
	if(agree)
	{
	var reg_no= document.getElementById('registration_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "registration/registration/delete_registration",
		   data: "reg_no="+reg_no,
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41">  <?=lang('view_registration_list')?></div>
    <div class="panel-body">
       
   
    <?php echo form_open('registration/registration/search_view_registration') ?>
        
       <table class="table table-bordered">
        <tr class="warning">
          <td><?=lang('registration_no')?></td>
          <td><?=lang('settings_firstname')?></td>
          <td><?=lang('settings_middlename')?></td>
          <td><?=lang('settings_lastname')?></td>
          <td><?=lang('guardian_name')?></td>
          <td><?=lang('settings_gender')?></td>
          
        </tr>
        <tr class="success">
            <td><input type="text" name="sregistration_no" id="sregistration_no" value="<?php echo isset($sregistration_no)?$sregistration_no:'';?>" class="input-medium"/></td>
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
            <td><?=lang('union')?></td>
          	<td><?=lang('village')?></td>           
            <td><?=lang('phone')?></td>
            <td><?=lang('national_id')?></td>
            <td><?=lang('landmark')?></td>
            <td></td>
          </tr>
          <tr class="success">
            <td><input type="text" name="sunion" id="sunion" value="" class="input-medium"/></td>
            <td><input type="text" name="svillage" id="svillage" value="" class="input-medium"/></td>
            <td><input type="text" name="sphone" id="sphone" value="<?php echo isset($sphone)?$sphone:'';?>" class="input-medium"/></td>
            <td><input type="text" name="snationalid" id="snationalid" value="<?php echo isset($snationalid)?$snationalid:'';?>" class="input-medium"/></td>
            <td><input type="text" name="slandmark" id="slandmark" value="<?php echo isset($slandmark)?$slandmark:'';?>" class="input-medium"/> </td>
            
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
	
    
  <table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('settings_fullname')?></th>
                <th><?=lang('guardian_name')?></th>
                <th><?=lang('settings_dateofbirth')?></th>
                <th><?=lang('settings_gender')?></th>
                <th><?=lang('district')?></th>
                <th><?=lang('upazila')?></th>
                <th><?=lang('union')?></th>
                <!--<th><?=lang('landmark')?></th>-->
                <th><?=lang('phone')?></th> 
                <?php if ($this->authorization->is_permitted('view_registration')) : ?> 
                <th><?=lang('website_view')?></th> 
                <?php endif; ?>
                
				<?php if ($this->authorization->is_permitted('edit_registration')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('delete_registration')) : ?> 
                <th><?=lang('website_delete')?></th>  
                <?php endif; ?>
            </tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_registration) ) {
			foreach ($all_registration as $registration) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $registration->registration_no;?></td>
                <td><?php echo $registration->first_name." ".$registration->middle_name." ".$registration->last_name ; ?></td>
                <td><?php echo $registration->guardian_name;?></td>
                <td><?php echo $registration->dob;?></td>
                <td><?php echo $registration->gender;?></td>
                <td><?php if($registration->district_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$registration->district_id,NULL,NULL,NULL,NULL,$ltype='DT');?></td>
                <td><?php if($registration->upazila_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$registration->district_id,$registration->upazila_id,NULL,NULL,NULL,$ltype='UP');?></td>
                <td><?php if($registration->upazila_id) echo $this->ref_location_model->get_location_name_by_id(NULL,$registration->district_id,$registration->upazila_id,$registration->union_id,NULL,NULL,$ltype='UN');?></td>
                <!--<td><?php //echo $registration->landmark;?></td>-->
                <td><?php echo $registration->phone;?></td>
                 <?php if ($this->authorization->is_permitted('view_registration')) : ?> 
                <td><a href="<?php echo base_url().'registration/registration/view_single_registration/'.$registration->registration_no ;?>" class="btn btn-small btn-info"><?=lang('website_view')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('edit_registration')) : ?> 
                <td><a href="<?php echo base_url().'registration/registration/edit_single_registration/'.$registration->registration_no ;?>" class="btn btn-small btn-warning"><?=lang('website_edit')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('delete_registration')) : ?> 
                <td>
                <input type="hidden" name="registration_id_<?=$i?>" id="registration_id_<?=$i?>" value="<?=$registration->registration_no?>"/>
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