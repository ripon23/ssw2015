
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


function toggle() {
		var ele = document.getElementById("toggleText");
		var text = document.getElementById("displayText");
		if(ele.style.display == "block") {
				ele.style.display = "none";
			text.innerHTML = "<i class='icon-plus icon-white'></i>Add new site";
		}
		else {
			ele.style.display = "block";
			text.innerHTML = "<i class='icon-minus icon-white'></i>Hide add new site";
		}
	} 


function site_or_services_onchange(field_id)
{
var numeric = field_id.replace('site_or_services_point_','');
var site_or_services_value = $.trim($("#site_or_services_point_"+numeric+" option:selected").val());
if(site_or_services_value=='ST')
document.getElementById('site_'+numeric).disabled=true;
else
document.getElementById('site_'+numeric).disabled=false;
}


function updateclick_id(button_id)
{
var numeric = button_id.replace('update_package_','');

	var site_id=document.getElementById('site_id_'+numeric).value;         // From Hidden field    
	var site_or_services_value = $.trim($("#site_or_services_point_"+numeric+" option:selected").val());
	
	if(site_or_services_value=='ST')
		var site_value =0;
	else
		var site_value = $.trim($("#site_"+numeric+" option:selected").val());
	
	var site_name_english=document.getElementById('name_english_'+numeric).value;
	var site_name_bangla = document.getElementById('name_bangla_'+numeric).value;
	var site_description = document.getElementById('description_'+numeric).value;
	var statusvalue = $.trim($("#site_status_"+numeric+" option:selected").val());	
	
	var dataString='site_id='+site_id+'&site_or_services_point='+ site_or_services_value + '&site='+site_value +'&site_name_english='+ site_name_english + '&site_name_bangla=' + site_name_bangla + '&site_description=' + site_description +'&statusvalue='+statusvalue;	
	$.ajax
		({
			type: "POST",
			url: "basic_setting/basic_setting/update_gramcar_site",
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Update "+msg+" for: "+site_id);
			location.reload(true); 			
			}
		});	
	
}


jQuery(document).ready(function(){
	

	
	<!-- Start -->
	$("#add_site").click(function() 
	{	
	var site_or_services_value = $.trim($("#site_or_services_point_999 option:selected").val());
	
	if(site_or_services_value=='ST')
		var site_value =0;
	else
		var site_value = $.trim($("#site_999 option:selected").val());
	
	var site_name_english=document.getElementById('name_english').value;
	var site_name_bangla = document.getElementById('name_bangla').value;
	var site_description = document.getElementById('description').value;
	var statusvalue = $.trim($("#site_status2 option:selected").val());	
	
	
	if(site_name_english!='' && site_name_bangla!='' )
		{
		var dataString='site_or_services_point='+ site_or_services_value + '&site='+site_value +'&site_name_english='+ site_name_english + '&site_name_bangla=' + site_name_bangla + '&site_description=' + site_description +'&statusvalue='+statusvalue;		
		$.ajax
			({
				type: "POST",
				url: "basic_setting/basic_setting/add_new_gramcar_site",
				data: dataString,
				cache: false,
				success: function(msg)
				{			
				alert("Add new GramCar site "+msg);			
				location.reload(true); 					
				}
			});		
		}<!-- End if-->	
		else
		{
			alert("Please fill all field");
		}
	});
	<!-- End -->
		
		
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><?=lang('basic_setting')?></div>
    <div class="panel-body">
       
    
    <div class="span11" style="margin-bottom:20px;">
    	<a id="displayText" href="javascript:toggle();" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i>Add new site</a>
	   			<div id="toggleText" style="display: none">                                              
                    <form class="form-horizontal" name="frm_add_site" method="post">
                        <table class="table table-striped table-bordered">
                        <thead>
                        <tr>                            
                            <th>Site / Services point</th>
                            <th>Select Site</th>
                            <th>Name(English) </th>
                            <th>Name(Bangla)</th>
                            <th>Description</th>  
                            <th>Status</th>                           
                            <th>Action</th>  
                        </tr>
                        <thead>
                        <tbody>
                        	<tr>                            	
                                <td>
								<select name="site_or_services_point_999" class="selectpicker span1.8" data-style="btn" id="site_or_services_point_999" onChange="site_or_services_onchange(this.id)">             
            						<option value="ST" selected data-content="<span class='label label-info'>Site</span>">Site</option>
                					<option value="SP" data-content="<span class='label label-primary'>Services Points</span>">Services Points</option>
	       						</select>
                                </td>
                                <td>      
                                <select name="site_999" class="input-medium" id="site_999" disabled >
									<?php foreach ($gramcar_site as $site1) : ?>
                                    <option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                </td>
                                <td>
								<input type="text" name="name_english" id="name_english" class="input-medium"/>
                                </td>      
                                <td>
                                <input type="text" name="name_bangla" id="name_bangla" class="input-medium"/>
                                </td>
                                <td>
                                <input type="text" name="description" id="description" class="input-medium"/> 
                        		</td>                                                               
                                <td>
                                <select name="site_status2" id="site_status2" class="selectpicker span1.5" data-style="btn">
                                	<option value="1" selected data-content="<span class='label label-success'>Active</span>">Active</option>                              		<option value="0" data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                                </select>
                                </td>
                                <td>										
								<input class="btn btn-primary pull-right" type="button" name="add_site" id="add_site" value="<?=lang('website_save')?>" />						                                </td>
                            </tr>
                        </tbody>
                        </table>             
                	</form> 
    	</div> <!-- toggleText END -->
    </div>
    
    
    <div class="span11" style="margin-bottom:40px;">
    
        <form class="form-horizontal" id="registration-services-edit" name="registration-services-edit" action="" method="post">
        <table class="table table-striped table-bordered">
        <thead>
    	<tr>
        	<th>#</th>
            <th>ID</th>
            <th>Site/Services point</th>
            <th>Select Site</th> 
            <th>Name(English)</th>
            <th>Name(Bangla)</th>
            <th>Description</th>             
            <th>Status</th>
            <?php if ($this->authorization->is_permitted('add_edit_delete_services_point')) : ?> 
            <th>Action</th>
            <?php endif; ?>                        
            
        </tr>
        <thead>
        <tbody>
        <?php
		$i=1;
		foreach ($gramcar_services_points as $site_list) :
		?>
        <tr align="center">
        	<td><?=$i++?></td>
            <td>
            <?=$site_list->site_id?>	
            <input type="hidden" name="site_id_<?=$i?>" id="site_id_<?=$i?>" value="<?=$site_list->site_id?>"  />
            </td>
            <td>
			<select name="site_or_services_point_<?=$i?>" class="selectpicker span1.8" data-style="btn" id="site_or_services_point_<?=$i?>" onChange="site_or_services_onchange(this.id)">             
            	<option value="ST" <?php if($site_list->site_type=="ST") echo 'selected="selected"'; ?> data-content="<span class='label label-info'>Site</span>">Site</option>
                <option value="SP" <?php if($site_list->site_type=="SP") echo 'selected="selected"'; ?> data-content="<span class='label label-primary'>Services Points</span>">Services Points</option>
	        </select>
            </td>
            <td>
			<select name="site_<?=$i?>" class="input-medium" id="site_<?=$i?>" <?php if($site_list->site_type=="ST") echo 'disabled'; ?> >             
            	<?php foreach ($gramcar_site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if($site_list->site_type=="SP"){if($site1->site_id==$site_list->site_parent_id) echo 'selected="selected"'; }?> ><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
	        </select>
            </td>      
        	<td>
			<input type="text" name="name_english_<?=$i?>" id="name_english_<?=$i?>" value="<?=$site_list->site_name?>" class="input-medium"/>
            </td>
            <td>
			<input type="text" name="name_bangla_<?=$i?>" id="name_bangla_<?=$i?>" value="<?=$site_list->site_name_bn?>" class="input-medium"/>
            </td>
            <td>
            <input type="text" name="description_<?=$i?>" id="description_<?=$i?>" value="<?=$site_list->site_description?>" class="input-medium"/>
            </td>      
            <td>
            <select name="site_status_<?=$i?>" id="site_status_<?=$i?>" class="selectpicker span1.5" data-style="btn">
                <option value="0" <?php if($site_list->site_status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                <option value="1" <?php if($site_list->site_status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Active</span>">Active</option>
            </select>
            </td>
             <?php if ($this->authorization->is_permitted('add_edit_delete_services_point')) : ?> 
            <td>          			
			<input class="btn btn-small btn-primary" type="button" name="update_package_<?=$i?>" onClick="updateclick_id(this.id)" id="update_package_<?=$i?>" value="<?=lang('website_update')?>" />
            </td>
            <?php endif; ?>
            
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