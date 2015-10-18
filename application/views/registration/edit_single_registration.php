
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

function toggle() {
		var ele = document.getElementById("toggleText");
		var text = document.getElementById("displayText");
		if(ele.style.display == "block") {
				ele.style.display = "none";
			text.innerHTML = "<i class='icon-plus icon-white'></i>Add new services";
		}
		else {
			ele.style.display = "block";
			text.innerHTML = "<i class='icon-minus icon-white'></i>Hide add new services";
		}
	} 

function reg_site_change(button_id)
{
var numeric = button_id.replace('reg_site_','');

var id=$.trim($("#reg_site_"+numeric+" option:selected").val());   //$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_servicespoint/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_point_"+numeric).html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});	

}

function reg_services_change(button_id)
{
var numeric = button_id.replace('reg_services_','');
var id=$.trim($("#reg_services_"+numeric+" option:selected").val());   //$(this).val();
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
			$("#reg_services_package_"+numeric).html(html);			
			}
		});
}

function updateclick_id(button_id)
{
var numeric = button_id.replace('update_services_','');
//alert("hi "+numeric);	

var reg_no=document.getElementById('services_registration_no_'+numeric).value;
	var reg_for_service_id=document.getElementById('reg_for_service_id_'+numeric).value;
	var reg_services_point = $.trim($("#reg_services_point_"+numeric+" option:selected").val());   	// Services point
	var reg_services = $.trim($("#reg_services_"+numeric+" option:selected").val());   				// Services id
	var reg_services_package = $.trim($("#reg_services_package_"+numeric+" option:selected").val());// Services package id
	var services_date=document.getElementById('services_date_'+numeric).value;					// Services date
	
	var statusvalue = $.trim($("#services_status_"+numeric+" option:selected").val());
	
	
	var dataString='reg_for_service_id='+reg_for_service_id+'&reg_no='+ reg_no + '&reg_services_point=' + reg_services_point + '&reg_services=' + reg_services + '&reg_services_package=' + reg_services_package +'&services_date='+services_date+'&statusvalue='+statusvalue;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/update_services",
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Update "+msg+" for: "+reg_no);
			location.reload(); 			
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
	var reg_no=document.getElementById('registration_no').value;
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/set_registration_status/"+reg_no+"/"+statusvalue,
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Status change for:"+reg_no+" is "+statusvalue);
			location.reload(); 			
			}
		});
	
	});
	<!-- End -->
	

	
	<!-- Start -->
	$("#add_services").click(function() 
	{
	var reg_no=document.getElementById('registration_no_add_services').value;				// registration no
	var reg_services_point = $.trim($("#reg_services_point2 option:selected").val());   	// Services point
	var reg_services = $.trim($("#reg_services2 option:selected").val());   				// Services id
	var reg_services_package = $.trim($("#reg_services_package2 option:selected").val());	// Services package id
	var services_date=document.getElementById('services_date2').value;						// Services date			
	var statusvalue = $.trim($("#services_status2 option:selected").val());
	
	var dataString='reg_no='+ reg_no + '&reg_services_point=' + reg_services_point + '&reg_services=' + reg_services + '&reg_services_package=' + reg_services_package +'&services_date='+services_date+'&statusvalue='+statusvalue;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/add_new_services",
			data: dataString,
			cache: false,
			success: function(msg)
			{			
			alert("Add new services "+msg+" for: "+reg_no);			
			location.reload(true); 		
			
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> <?=lang('view_registration_info')?></div>
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
        	<td><?=lang('registration_no')?> </td>
            <td>
			<input type="hidden"  name="registration_no" id="registration_no" value="<?php echo $single_registration->registration_no;?>" type="text" />
			<?='<span class=badge>'.$single_registration->registration_no.'</span>'?> 
            
            </td>
        </tr>
        <tr>
        	<td><?=lang('settings_fullname')?> <span class="required">*</span></td>
            <td>
            <input class="input-small" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $single_registration->first_name;?>" type="text" />        
        	<input class="input-small" placeholder="Middle Name"  name="middlename" id="middlename" value="<?php echo $single_registration->middle_name;?>" type="text" />
        	<input class="input-small" placeholder="Last Name"  name="lastname" id="lastname" value="<?php echo $single_registration->last_name;?>" type="text" />
            
            </td>
        </tr>
        <tr>
        	<td><?=lang('guardian_name')?> </td>
            <td><input class="form-control" placeholder="Guardian Name" name="guardian_name" value="<?php echo $single_registration->guardian_name;?>" id="guardian_name" type="text" /></td>
        </tr>
        <tr>
        	<td><?=lang('settings_dateofbirth')?> </td>
            <td>
            <?php
			if($single_registration->dob)
            $dob=explode('-',$single_registration->dob); 
			//var_dump($dob);
			?>
            <select name="settings_dob_month" class="input-small">
                <option value=""><?php echo lang('dateofbirth_month'); ?></option>
                <option value="1" <?php if($single_registration->dob){if($dob[1]=='01') echo ' selected="selected"'; }?>><?php echo lang('month_jan'); ?></option>
                <option value="2" <?php if($single_registration->dob){if($dob[1]=='02') echo ' selected="selected"'; }?>><?php echo lang('month_feb'); ?></option>
                <option value="3" <?php if($single_registration->dob){if($dob[1]=='03') echo ' selected="selected"'; }?>><?php echo lang('month_mar'); ?></option>
                <option value="4" <?php if($single_registration->dob){if($dob[1]=='04') echo ' selected="selected"'; }?>><?php echo lang('month_apr'); ?></option>
                <option value="5" <?php if($single_registration->dob){if($dob[1]=='05') echo ' selected="selected"'; }?>><?php echo lang('month_may'); ?></option>
                <option value="6" <?php if($single_registration->dob){if($dob[1]=='06') echo ' selected="selected"'; }?>><?php echo lang('month_jun'); ?></option>
                <option value="7" <?php if($single_registration->dob){if($dob[1]=='07') echo ' selected="selected"'; }?>><?php echo lang('month_jul'); ?></option>
                <option value="8" <?php if($single_registration->dob){if($dob[1]=='08') echo ' selected="selected"'; }?>><?php echo lang('month_aug'); ?></option>
                <option value="9" <?php if($single_registration->dob){if($dob[1]=='09') echo ' selected="selected"'; }?>><?php echo lang('month_sep'); ?></option>
                <option value="10" <?php if($single_registration->dob){if($dob[1]=='10') echo ' selected="selected"'; }?>><?php echo lang('month_oct'); ?></option>
                <option value="11" <?php if($single_registration->dob){if($dob[1]=='11') echo ' selected="selected"'; }?>><?php echo lang('month_nov'); ?></option>
                <option value="12" <?php if($single_registration->dob){if($dob[1]=='12') echo ' selected="selected"'; }?>><?php echo lang('month_dec'); ?></option>
            </select>
            <select name="settings_dob_day" class="input-small">
                <option value=""><?php echo lang('dateofbirth_day'); ?></option>
                <?php for ($i = 1; $i < 32; $i ++) : ?>
                <option value="<?php echo $i; ?>" <?php if($single_registration->dob){if($dob[2]==$i) echo ' selected="selected"';} ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <select name="settings_dob_year" class="input-small">
                <option value=""><?php echo lang('dateofbirth_year'); ?></option>
                <?php $year = mdate('%Y', now()); for ($i = $year; $i > 1900; $i --) : ?>
                <option value="<?php echo $i; ?>" <?php if($single_registration->dob){if($dob[0]==$i) echo ' selected="selected"'; }?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
			</td>
        </tr>
        <tr>
        	<td><?=lang('settings_gender')?> <span class="required">*</span></td>
            <td><label class="radio inline">
        <input type="radio" name="gender" id="gender" value="M" <?php if($single_registration->gender=='M') echo 'checked';?> ><?=lang('gender_male')?></label>
        <label class="radio inline">
        <input type="radio" name="gender" id="gender" value="F" <?php if($single_registration->gender=='F') echo 'checked';?> ><?=lang('gender_female')?></label> </td>
        </tr>
        
        <tr>
        	<td><?=lang('site')?> <span class="required">*</span></td>
            <td>       
			<select name="registration_site"  id="registration_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if($site1->site_id==$single_registration->site_id) echo ' selected="selected"';?>><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>               
                                
				<?php endforeach; ?>
        	</select>
       
        	</td>
        </tr>
        
        
        <tr>
        	<td><?=lang('division')?> <span class="required">*</span></td>
            <td>
             <?php $division_list=$this->ref_location_model->get_location_list_by_id(NULL,NULL,NULL,NULL,NULL,NULL,'DV'); ?>
            <select name="site_division" id="site_division">
          		<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($division_list as $division) : ?>
            	<option value="<?php echo $division->division; ?>" <?php if($division->division==$single_registration->division_id) echo ' selected="selected"'; ?>><?php echo $division->loc_name_en?></option>
				<?php endforeach; ?>
        	</select>	
        </td>
        </tr>
        
        <tr>
        	<td><?=lang('district')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_registration->division_id)
			$district_list=$this->ref_location_model->get_location_list_by_id($single_registration->division_id,NULL,NULL,NULL,NULL,NULL,'DT'); ?>
            <select name="site_district" id="site_district">
          		<?php 
				if($single_registration->division_id)
				{
				foreach ($district_list as $district) : ?>
            	<option value="<?php echo $district->district; ?>" <?php if($district->district==$single_registration->district_id) echo ' selected="selected"'; ?>><?php echo $district->loc_name_en?></option>
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
			if($single_registration->district_id)
			$upazila_list=$this->ref_location_model->get_location_list_by_id($single_registration->division_id,$single_registration->district_id,NULL,NULL,NULL,NULL,'UP'); ?>
            <select name="site_upazila" id="site_upazila">
          		<?php foreach ($upazila_list as $upazila) : ?>
            	<option value="<?php echo $upazila->upazila; ?>" <?php if($upazila->upazila==$single_registration->upazila_id) echo ' selected="selected"'; ?>><?php echo $upazila->loc_name_en?></option>
				<?php endforeach; ?>                             
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('union')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_registration->upazila_id)
			$union_list=$this->ref_location_model->get_location_list_by_id($single_registration->division_id,$single_registration->district_id,$single_registration->upazila_id,NULL,NULL,NULL,'UN'); ?>
            <select name="site_union" id="site_union">
          		<?php foreach ($union_list as $union) : ?>
            	<option value="<?php echo $union->unionid; ?>" <?php if($union->unionid==$single_registration->union_id) echo ' selected="selected"'; ?>><?php echo $union->loc_name_en?></option>
				<?php endforeach; ?>                           
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('mouza')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_registration->union_id)
			$mouza_list=$this->ref_location_model->get_location_list_by_id($single_registration->division_id,$single_registration->district_id,$single_registration->upazila_id,$single_registration->union_id,NULL,NULL,'MA'); ?>
            <select name="site_mouza" id="site_mouza">
            	<option value=""><?php echo lang('settings_select'); ?></option>
          		<?php foreach ($mouza_list as $mouza) : ?>
            	<option value="<?php echo $mouza->mouza; ?>" <?php if($mouza->mouza==$single_registration->mouza_id) echo ' selected="selected"'; ?>><?php echo $mouza->loc_name_en?></option>
				<?php endforeach; ?>                            
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('village')?> <span class="required">*</span></td>
            <td>
            <?php 
			if($single_registration->mouza_id)
			$village_list=$this->ref_location_model->get_location_list_by_id($single_registration->division_id,$single_registration->district_id,$single_registration->upazila_id,$single_registration->union_id,$single_registration->mouza_id,NULL,'VI'); ?>
            <select name="site_village" id="site_village">
          		<option value=""><?php echo lang('settings_select'); ?></option>
          		<?php foreach ($village_list as $village) : ?>
            	<option value="<?php echo $village->village; ?>" <?php if($village->village==$single_registration->village_id) echo ' selected="selected"'; ?>><?php echo $village->loc_name_en?></option>
				<?php endforeach; ?>
        	</select>
            </td>
        </tr>
        <tr>
        	<td><?=lang('landmark')?> </td>
            <td><input class="form-control" placeholder="Landmark" name="reg_landmark" value="<?php echo $single_registration->landmark;?>" id="reg_landmark" type="text" /> </td>
        </tr>
        <tr>
        	<td><?=lang('phone')?> </td>
            <td>
            <?php 
			$phone_country_code=substr($single_registration->phone,0,3);
			$phone_part1=substr($single_registration->phone,3,5);
			$phone_part2=substr($single_registration->phone,8,6);
			?>
            <input class="span1" placeholder="+88" name="phone_country_code" id="phone_country_code" value="<?=$phone_country_code?>" type="text" />
	        <input class="input-mini" placeholder="01XXX" name="phone_part1" id="phone_part1" value="<?php echo $phone_part1;?>" type="text" />
    	    <input class="input-mini" placeholder="XXXXXX" name="phone_part2" id="phone_part2" value="<?php echo $phone_part2;?>" type="text" />
            </td>
        </tr>
        <tr>
        	<td><?=lang('national_id')?></td>
            <td><input class="form-control" placeholder="National ID" name="reg_national_id" id="reg_national_id" value="<?php echo $single_registration->national_id;?>" type="text" /></td>
        </tr>
        <tr>
        	<td><?=lang('note')?></td>
            <td><textarea rows="3" placeholder="Special note" name="reg_note" id="reg_note"><?php echo $single_registration->note;?></textarea> </td>
        </tr>
        <tr>
          <td colspan="2"><input class="btn btn-primary pull-right" type="submit" name="update" value="<?=lang('website_update')?>" /></td>
        </tr>
        
    </table>
    </form>        

	
    </div><!-- /end span6 -->
    
    
    <div class="span3">
    	
        <table class="table table-striped">
    	<tr>
        	<td>Status </td>
            <td>
            <form class="form-horizontal" id="registration-form-status" action="" method="post">                         
            
            <select name="reg_status" id="reg_status" class="selectpicker span1.5" data-style="btn">
                <option value="1" <?php if($single_registration->status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Active</span>">Active</option>
                <option value="2" <?php if($single_registration->status==2) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Deleted</span>">Deleted</option>
            </select>
            
            <input type="hidden" name="registration_no" id="registration_no" value="<?php echo $single_registration->registration_no;?>" type="text" />			
			<input class="btn btn-primary pull-right" type="button" name="set_status" id="set_status" value="Set" />
            </form>
            </td>
        </tr>
        <tr>
        	<td>Create Date</td>
            <td><?=$single_registration->create_date?> </td>
        </tr>
        <tr>
        	<td>Create User </td>
            <td><?=$single_registration->create_user_id?> </td>
        </tr>
        <tr>
        	<td>Last Update</td>
            <td><?=$single_registration->update_date?> </td>
        </tr>
        <tr>
        	<td>Update user </td>
            <td><?=$single_registration->update_user_id?> </td>
        </tr>
        </table>                        	
        
    </div><!-- /end span3 -->
    
    <div class="span11" style="margin-bottom:20px;">
    	<a id="displayText" href="javascript:toggle();" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i>Add new services</a>
	   			<div id="toggleText" style="display: none">                                              
                    <form class="form-horizontal" name="frm_add_services">
                        <table class="table table-striped table-bordered">
                        <thead>
                        <tr>                            
                            <th><?=lang('site')?></th>
                            <th><?=lang('services_point')?></th>      
                            <th><?=lang('services')?> </th>
                            <th><?=lang('package')?></th>
                            <th><?=lang('service_datetime')?></th> 
                            <th><?=lang('status')?></th>                           
                            <th><?=lang('website_save')?></th>
                        </tr>
                        <thead>
                        <tbody>
                        	<tr>                            	
                                <td>
                                <select name="reg_site2" class="input-medium" id="reg_site2">
                            	<option value=""><?php echo lang('settings_select'); ?></option>            
                            	<?php foreach ($site as $site1) : ?>
                            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $site1->site_name:$site1->site_name_bn; ?></option>
                            <?php endforeach; ?>
                        </select>
                        		</td>
                                <td>
								<select name="reg_services_point2" class="input-medium" id="reg_services_point2">
                        		<option value=""><?php echo lang('settings_select'); ?></option>            
                        		</select> 
                                </td>      
                                <td>
                                <select name="reg_services2" class="input-medium" id="reg_services2">
                                <option value=""><?php echo lang('settings_select'); ?></option>   
                                <?php foreach ($gramcar_services as $services2) : ?>
                                    <option value="<?php echo $services2->services_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $services2->services_name:$services2->services_name_bn; ?></option>
                                    <?php endforeach; ?>         
                                </select>
                                </td>
                                <td>
                                <select name="reg_services_package2" class="input-medium" id="reg_services_package2">
                        		<option value=""><?php echo lang('settings_select'); ?></option>            
                        		</select>  
                        		</td>
                                <td>
                                <input type="text" name="services_date2" id="services_date2"  placeholder="Services Datetime" value="<?php echo set_value('services_date');?>" class="input-medium" id="services_date"/>
                                </td>
                                <td>
                                <select name="services_status2" id="services_status2" class="selectpicker span1.5" data-style="btn">
                                <option value="0" data-content="<span class='label label-warning'>Pending</span>">Pending</option>
                                <option value="1" data-content="<span class='label label-info'>Process</span>">Process</option>
                                <option value="2" data-content="<span class='label label-success'>Taken</span>">Taken</option>
                                <option value="3" data-content="<span class='label label-important'>Cancel</span>">Cancel</option>
                            	</select>
                                </td>
                                <td>
								<input type="hidden" name="registration_no_add_services" id="registration_no_add_services" value="<?php echo $single_registration->registration_no;?>" type="text" />			
								<input class="btn btn-primary pull-right" type="button" name="add_services" id="add_services" value="<?=lang('website_save')?>" />								                                
                                </td>
                            </tr>
                        </tbody>
                        </table>             
                	</form> 
    	</div> <!-- toggleText END -->
    </div>
    
    
    <div class="span11" style="margin-bottom:80px;">
    
        <form class="form-horizontal" id="registration-services-edit" name="registration-services-edit" action="" method="post">
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
            <th><?=lang('website_edit')?></th>
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
            
			<select name="reg_site_<?=$i?>" class="input-medium" id="reg_site_<?=$i?>" onChange="reg_site_change(this.id)">
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
			<select name="reg_services_point_<?=$i?>" class="input-medium" id="reg_services_point_<?=$i?>">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php if($services->services_point_id){ ?><option value="<?=$services->services_point_id?>" selected><?php echo $this->ref_site_model->get_site_name_by_id($services->services_point_id); ?></option>  <?php }?>       
	        </select> 
			<?php 
			
			?>
            </td>      
        	<td>
			<select name="reg_services_<?=$i?>" class="input-medium" id="reg_services_<?=$i?>" onChange="reg_services_change(this.id)">
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
            
            <select name="reg_services_package_<?=$i?>" class="input-medium" id="reg_services_package_<?=$i?>">
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
			<input type="text" name="services_date_<?=$i?>"  placeholder="Services Datetime" value="<?php echo $services->services_date;?>" class="input-medium" id="services_date_<?=$i?>"/>
            </td>
            <td>
            <select name="services_status_<?=$i?>" id="services_status_<?=$i?>" class="selectpicker span1.5" data-style="btn">
                <option value="0" <?php if($services->services_status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-warning'>Pending</span>">Pending</option>
                <option value="1" <?php if($services->services_status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-info'>Process</span>">Process</option>
                <option value="2" <?php if($services->services_status==2) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Taken</span>">Taken</option>
                <option value="3" <?php if($services->services_status==3) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Cancel</span>">Cancel</option>
            </select>
<script>
$(function(){
			$('*[name=services_date_<?=$i?>]').appendDtpicker();
		});
</script>
            <?php 
			/*if($services->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($services->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($services->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($services->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}*/	
				
			?>
            </td>
            <td>
            <input type="hidden" name="reg_for_service_id_<?=$i?>" id="reg_for_service_id_<?=$i?>" value="<?php echo $services->reg_for_service_id;?>" type="text" />	            
            <input type="hidden" name="services_registration_no_<?=$i?>" id="services_registration_no_<?=$i?>" value="<?php echo $services->registration_no;?>" type="text" />			
			<input class="btn btn-small btn-primary" type="button" name="update_services_<?=$i?>" onClick="updateclick_id(this.id)" id="update_services_<?=$i?>" value="<?=lang('website_update')?>" />
            </td>
        </tr>
        <?php endforeach; ?>
        <tbody>
        </table>               
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