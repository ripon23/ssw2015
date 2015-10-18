
<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="<?php echo base_url().RES_DIR; ?>/css/jquery.simple-dtpicker.css" rel="stylesheet" />
<script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });




$(function(){
			$('*[name=services_date2]').appendDtpicker();
		});



function reg_site_change()
{
var id=$.trim($("#reg_site option:selected").val());   //$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_servicespoint/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_point").html(html);			
			}
		});	

}

function reg_services_change()
{
var id=$.trim($("#reg_services option:selected").val());   //$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_services_pacakge/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_package").html(html);			
			}
		});
}




jQuery(document).ready(function(){
	
	//Start
	$("#site_division").change(function()
	{
	var dvid=$(this).val();
	var ltype='DT';
	var dataString = 'dvid='+ dvid+'&ltype='+ltype;
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_district").html(html);	
			}
		});
	
	});
	//End
	
	//Start
	$("#site_district").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$(this).val();
	var ltype='UP';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&ltype='+ltype;	
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_upazila").html(html);			
			}
		});		
		
	
	});
	//End
	
	//Start
	$("#site_upazila").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$(this).val();
	var ltype='UN';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&ltype='+ltype;			
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_union").html(html);
			}
		});
		
	
	});
	//End
	
	//Start
	$("#site_union").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$("#site_upazila").val();
	var unid=$(this).val();
	var ltype='MA';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&unid='+unid+'&ltype='+ltype;				
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_mouza").html(html);
			}
		});
	
	
	});
	//End
	
	//Start
	$("#site_mouza").change(function()
	{
	var dvid=$("#site_division").val();	
	var dtid=$("#site_district").val();	
	var upid=$("#site_upazila").val();
	var unid=$("#site_union").val();
	var maid=$(this).val();
	var ltype='VI';
	var dataString = 'dvid='+ dvid+'&dtid='+ dtid+'&upid='+upid+'&unid='+unid+'&maid='+maid+'&ltype='+ltype;	
	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/get_all_child_location",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#site_village").html(html);			
			}
		});
	
	
	});
	//End
	
	
	<!-- Start -->
	$("#set_status").click(function() 
	{
	var statusvalue = $.trim($("#reg_status option:selected").val());
	var booking_id=document.getElementById('booking_id').value;
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "booking/booking/set_booking_status/"+booking_id+"/"+statusvalue,
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Status change for:"+booking_id+" is "+statusvalue);
			location.reload(); 			
			}
		});
	
	});
	<!-- End -->
	

	
	
		
	
	<!-- Start -->
	$("#reg_site2").change(function()
	{
	var id=$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_servicespoint/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_point2").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_services2").change(function()
	{
	var id=$(this).val();
	var dataString;	
	//var dataString = 'id='+ id;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_services_pacakge/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_package2").html(html);			
			}
		});
	
	});
	<!-- End -->
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> Registration from booking info.</div>
    <div class="panel-body">
       
          
   	<div class="span8">
    <?php 
	if(validation_errors())
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=validation_errors()?>
    </div>
	<?php
	}
	?>
    
    <?php 
	if(isset($success_msg))
	{					 
	?>
    <div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$success_msg?>
    </div>
	<?php
	}
	?>
    
    <form class="form-horizontal" id="registration-form" action="" method="post">  
    
    <table class="table table-bordered table-striped">
    	<tr>
        	<td><?=lang('registration_no')?><span class="required">*</span> </td>
            <td>
			<input class="input-small"  name="registration_no" id="registration_no" type="text" value="<?php echo set_value('registration_no');?>"/> 
            
            </td>
        </tr>
        <tr>
        	<td>Booking Id </td>
            <td>
			<input type="hidden"  name="booking_id" id="booking_id" value="<?php echo $single_booking->booking_id;?>" type="text" />
			<?='<span class=badge>'.$single_booking->booking_id.'</span>'?> 
            
            </td>
        </tr>
        <tr>
        	<td><?=lang('settings_fullname')?> <span class="required">*</span></td>
            <td>
            <input class="input-small" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $single_booking->first_name;?>" type="text" />        
        	<input class="input-small" placeholder="Middle Name"  name="middlename" id="middlename" value="<?php echo $single_booking->middle_name;?>" type="text" />
        	<input class="input-small" placeholder="Last Name"  name="lastname" id="lastname" value="<?php echo $single_booking->last_name;?>" type="text" />
            
            </td>
        </tr>
        <tr>
        	<td><?=lang('guardian_name')?> </td>
            <td><input class="form-control" placeholder="Guardian Name" name="guardian_name" value="<?php echo $single_booking->guardian_name;?>" id="guardian_name" type="text" /></td>
        </tr>
        <tr>
        	<td><?=lang('settings_dateofbirth')?> </td>
            <td>
            <?php
			if($single_booking->dob)
            $dob=explode('-',$single_booking->dob); 
			//var_dump($dob);
			?>
            <select name="settings_dob_month" class="input-small">
                <option value=""><?php echo lang('dateofbirth_month'); ?></option>
                <option value="1" <?php if($single_booking->dob){if($dob[1]=='01') echo ' selected="selected"'; }?>><?php echo lang('month_jan'); ?></option>
                <option value="2" <?php if($single_booking->dob){if($dob[1]=='02') echo ' selected="selected"'; }?>><?php echo lang('month_feb'); ?></option>
                <option value="3" <?php if($single_booking->dob){if($dob[1]=='03') echo ' selected="selected"'; }?>><?php echo lang('month_mar'); ?></option>
                <option value="4" <?php if($single_booking->dob){if($dob[1]=='04') echo ' selected="selected"'; }?>><?php echo lang('month_apr'); ?></option>
                <option value="5" <?php if($single_booking->dob){if($dob[1]=='05') echo ' selected="selected"'; }?>><?php echo lang('month_may'); ?></option>
                <option value="6" <?php if($single_booking->dob){if($dob[1]=='06') echo ' selected="selected"'; }?>><?php echo lang('month_jun'); ?></option>
                <option value="7" <?php if($single_booking->dob){if($dob[1]=='07') echo ' selected="selected"'; }?>><?php echo lang('month_jul'); ?></option>
                <option value="8" <?php if($single_booking->dob){if($dob[1]=='08') echo ' selected="selected"'; }?>><?php echo lang('month_aug'); ?></option>
                <option value="9" <?php if($single_booking->dob){if($dob[1]=='09') echo ' selected="selected"'; }?>><?php echo lang('month_sep'); ?></option>
                <option value="10" <?php if($single_booking->dob){if($dob[1]=='10') echo ' selected="selected"'; }?>><?php echo lang('month_oct'); ?></option>
                <option value="11" <?php if($single_booking->dob){if($dob[1]=='11') echo ' selected="selected"'; }?>><?php echo lang('month_nov'); ?></option>
                <option value="12" <?php if($single_booking->dob){if($dob[1]=='12') echo ' selected="selected"'; }?>><?php echo lang('month_dec'); ?></option>
            </select>
            <select name="settings_dob_day" class="input-small">
                <option value=""><?php echo lang('dateofbirth_day'); ?></option>
                <?php for ($i = 1; $i < 32; $i ++) : ?>
                <option value="<?php echo $i; ?>" <?php if($single_booking->dob){if($dob[2]==$i) echo ' selected="selected"';} ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <select name="settings_dob_year" class="input-small">
                <option value=""><?php echo lang('dateofbirth_year'); ?></option>
                <?php $year = mdate('%Y', now()); for ($i = $year; $i > 1900; $i --) : ?>
                <option value="<?php echo $i; ?>" <?php if($single_booking->dob){if($dob[0]==$i) echo ' selected="selected"'; }?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
			</td>
        </tr>
        <tr>
        	<td><?=lang('settings_gender')?> <span class="required">*</span></td>
            <td><label class="radio inline">
        <input type="radio" name="gender" id="gender" value="M" <?php if($single_booking->gender=='M') echo 'checked';?> ><?=lang('gender_male')?></label>
        <label class="radio inline">
        <input type="radio" name="gender" id="gender" value="F" <?php if($single_booking->gender=='F') echo 'checked';?> ><?=lang('gender_female')?></label> </td>
        </tr>
        <tr>
        	<td><?=lang('division')?> <span class="required">*</span></td>
            <td>
             <?php $division_list=$this->ref_location_model->get_location_list_by_id(NULL,NULL,NULL,NULL,NULL,NULL,'DV'); ?>
            <select name="site_division" id="site_division">
          		<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($division_list as $division) : ?>
            	<option value="<?php echo $division->division; ?>" <?php if($division->division==$single_booking->division_id) echo ' selected="selected"'; ?>><?php echo $division->loc_name_en?></option>
				<?php endforeach; ?>
        	</select>	
        </td>
        </tr>
        <tr>
        	<td><?=lang('district')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_booking->division_id)
			$district_list=$this->ref_location_model->get_location_list_by_id($single_booking->division_id,NULL,NULL,NULL,NULL,NULL,'DT'); ?>
            <select name="site_district" id="site_district">
          		<?php 
				if($single_booking->division_id)
				{
				foreach ($district_list as $district) : ?>
            	<option value="<?php echo $district->district; ?>" <?php if($district->district==$single_booking->district_id) echo ' selected="selected"'; ?>><?php echo $district->loc_name_en?></option>
				<?php endforeach; 
				}
				?>
        	</select>	 
            </td>
        </tr>
        <tr>
        	<td><?=lang('upazila')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_booking->district_id)
			$upazila_list=$this->ref_location_model->get_location_list_by_id($single_booking->division_id,$single_booking->district_id,NULL,NULL,NULL,NULL,'UP'); ?>
            <select name="site_upazila" id="site_upazila">
          		<?php foreach ($upazila_list as $upazila) : ?>
            	<option value="<?php echo $upazila->upazila; ?>" <?php if($upazila->upazila==$single_booking->upazila_id) echo ' selected="selected"'; ?>><?php echo $upazila->loc_name_en?></option>
				<?php endforeach; ?>                             
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('union')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_booking->upazila_id)
			$union_list=$this->ref_location_model->get_location_list_by_id($single_booking->division_id,$single_booking->district_id,$single_booking->upazila_id,NULL,NULL,NULL,'UN'); ?>
            <select name="site_union" id="site_union">
          		<?php foreach ($union_list as $union) : ?>
            	<option value="<?php echo $union->unionid; ?>" <?php if($union->unionid==$single_booking->union_id) echo ' selected="selected"'; ?>><?php echo $union->loc_name_en?></option>
				<?php endforeach; ?>                           
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('mouza')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_booking->union_id)
			$mouza_list=$this->ref_location_model->get_location_list_by_id($single_booking->division_id,$single_booking->district_id,$single_booking->upazila_id,$single_booking->union_id,NULL,NULL,'MA'); ?>
            <select name="site_mouza" id="site_mouza">
            	<option value=""><?php echo lang('settings_select'); ?></option>
          		<?php foreach ($mouza_list as $mouza) : ?>
            	<option value="<?php echo $mouza->mouza; ?>" <?php if($mouza->mouza==$single_booking->mouza_id) echo ' selected="selected"'; ?>><?php echo $mouza->loc_name_en?></option>
				<?php endforeach; ?>                            
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('village')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_booking->mouza_id)
			$village_list=$this->ref_location_model->get_location_list_by_id($single_booking->division_id,$single_booking->district_id,$single_booking->upazila_id,$single_booking->union_id,$single_booking->mouza_id,NULL,'VI'); ?>
            <select name="site_village" id="site_village">
          		<option value=""><?php echo lang('settings_select'); ?></option>
          		<?php foreach ($village_list as $village) : ?>
            	<option value="<?php echo $village->village; ?>" <?php if($village->village==$single_booking->village_id) echo ' selected="selected"'; ?>><?php echo $village->loc_name_en?></option>
				<?php endforeach; ?>
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('landmark')?> </td>
            <td><input class="form-control" placeholder="Landmark" name="reg_landmark" value="<?php echo $single_booking->landmark;?>" id="reg_landmark" type="text" /> </td>
        </tr>
        <tr>
        	<td><?=lang('phone')?> </td>
            <td>
            <?php 
			$phone_country_code=substr($single_booking->phone,0,3);
			$phone_part1=substr($single_booking->phone,3,5);
			$phone_part2=substr($single_booking->phone,8,6);
			?>
            <input class="span1" placeholder="+88" name="phone_country_code" id="phone_country_code" value="<?=$phone_country_code?>" type="text" />
	        <input class="input-mini" placeholder="01XXX" name="phone_part1" id="phone_part1" value="<?php echo $phone_part1;?>" type="text" />
    	    <input class="input-mini" placeholder="XXXXXX" name="phone_part2" id="phone_part2" value="<?php echo $phone_part2;?>" type="text" />
            </td>
        </tr>
        <tr>
        	<td><?=lang('national_id')?></td>
            <td><input class="form-control" placeholder="National ID" name="reg_national_id" id="reg_national_id" value="<?php echo $single_booking->national_id;?>" type="text" /></td>
        </tr>
        <tr>
        	<td><?=lang('note')?></td>
            <td><textarea rows="3" placeholder="Special note" name="reg_note" id="reg_note"><?php echo $single_booking->note;?></textarea> </td>
        </tr>
       
        
    </table>
        

	
    </div><!-- /end span6 -->
    
    
    <div class="span3">
    	
        <table class="table table-striped">
    	<tr>
        	<td>Status </td>
            <td>
                       
            <?php 
			if($single_booking->status==0)
			{
				echo '<span class="label label-warning">Unregistered</span>';				
			}
			else if($single_booking->status==1)
			{
				echo '<span class="label label-success">Registered</span>';				
			}
			else if	($single_booking->status==2)
			{
				echo '<span class="label label-important">Deleted</span>';
			}
				
			?>            					
            </td>
        </tr>
        <tr>
        	<td>Create Date</td>
            <td><?=$single_booking->create_date?> </td>
        </tr>
        <tr>
        	<td>Create User </td>
            <td><?=$single_booking->create_user_id?> </td>
        </tr>
        <tr>
        	<td>Last Update</td>
            <td><?=$single_booking->update_date?> </td>
        </tr>
        <tr>
        	<td>Update user </td>
            <td><?=$single_booking->update_user_id?> </td>
        </tr>
        </table>                        	
        
    </div><!-- /end span3 -->
        
    
    
    <div class="span11" style="margin-bottom:80px;">
    

        <table class="table table-striped table-bordered">
        <thead>
    	<tr>
        	<th>#</th>
            <th><?=lang('site')?></th>
            <th><?=lang('services_point')?></th>      
        	<th><?=lang('services')?> </th>
            <th><?=lang('package')?></th>
            <th><?=lang('service_datetime')?></th>
            <th><?=lang('status')?></th>
        </tr>
        <thead>
        <tbody>
        <?php
		$i=1;
		foreach ($single_services_list as $services) :
		?>
        <tr align="center">
        	<td><?=$i++?></td>
            <td>
            
			<select name="reg_site" class="input-medium" id="reg_site" onChange="reg_site_change()">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if($services->services_point_id){if($site1->site_id==$this->ref_site_model->get_site_id_by_sp_id($services->services_point_id)) echo 'selected="selected"';} ?> ><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
			<?php 
			//echo $this->ref_site_model->get_site_name_by_sp_id($services->services_point_id);
			
			?>
            </td>
            <td>
			<select name="reg_services_point" class="input-medium" id="reg_services_point">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php if($services->services_point_id){ ?><option value="<?=$services->services_point_id?>" selected><?php echo $this->ref_site_model->get_site_name_by_id($services->services_point_id); ?></option>  <?php }?>       
	        </select> 
			<?php 
			
			?>
            </td>      
        	<td>
			<select name="reg_services" class="input-medium" id="reg_services" onChange="reg_services_change()">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php foreach ($gramcar_services as $services1) : ?>
            	<option value="<?php echo $services1->services_id; ?>" <?php if($services->services_id){if($services1->services_id==$services->services_id) echo 'selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='bangla'? $services1->services_name_bn:$services1->services_name; ?></option>
				<?php endforeach; ?>         
	        </select> 
			<?php 
			//if($services->services_id) echo $this->ref_services_model->get_services_name_by_id($services->services_id);
			?>
            </td>
            <td>
            
            <select name="reg_services_package" class="input-medium" id="reg_services_package">
            <option value=""><?php echo lang('settings_select'); ?></option>
            <?php if($services->services_package_id){?>
            <option value="<?=$services->services_package_id?>" selected><?php echo $this->ref_services_model->get_package_name_by_id($services->services_package_id); ?></option>
            <?php }?>
	        </select>
            
			<?php 
			//if($services->services_package_id) echo $this->ref_services_model->get_package_name_by_id($services->services_package_id);			
			?>
            </td>
            <td>
			<input type="text" name="services_date"  placeholder="Services Datetime" value="<?php echo $services->services_date;?>" class="input-medium" id="services_date"/>
            </td>
            <td>
            <select name="services_status" id="services_status" class="selectpicker span1.5" data-style="btn">
                <option value="0" <?php if($services->services_status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-warning'>Pending</span>">Pending</option>
                
            </select>
<script>
$(function(){
			$('*[name=services_date]').appendDtpicker();
		});
</script>


            </td>
        </tr>
        <?php endforeach; ?>
        <tbody>
        </table>   
        <input class="btn btn-large btn-info pull-right" type="submit" name="update" value="Create Registration" />            
    </form>                     	 
    </div> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>