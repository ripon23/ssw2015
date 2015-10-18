<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>
    <script type="text/javascript">
jQuery(document).ready(function(){
	
	<!-- Start -->
	$("#reg_site").change(function()
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
			$("#reg_services_point").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41">  <?=lang('menu_family_list')?></div>
    <div class="panel-body">
       
   
    <form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="./registration/registration/family_list_search" method="post">   
    <table class="table table-bordered">
      <tr class="warning">
        <td><?=lang('household_name')?></td>
        <td><?=lang('primary_contact_person')?></td> 
        <td><?=lang('site')?> &nbsp;&raquo; &nbsp; <?=lang('services_point')?></td>  
        <td><?=lang('apartment_name')?></td>
        <td><?=lang('apartment_number')?></td>    
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input id="family_title" name="family_title" type="text" placeholder="<?=lang('household_name')?>" value="<?php echo isset($family_title)?$family_title:'';?>" class="form-control input-medium" ></td>

        <td>
         <input id="primary_contact_person" name="primary_contact_person" type="text" value="<?=set_value('primary_contact_person')?>" placeholder="<?=lang('barcode_id')?>" class="form-control input-small"/>
        </td>                
        <td>

        <select name="reg_site" class="input-medium" id="reg_site">
            <option value=""><?php echo lang('settings_select'); ?></option>            
            <?php foreach ($site as $site1) : ?>
            <option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $site1->site_name:$site1->site_name_bn; ?></option>
            <?php endforeach; ?>
        </select>
        
        <select name="reg_services_point" class="input-medium" id="reg_services_point">
        <option value=""><?php echo lang('settings_select'); ?></option>            
        </select> 
        </td>  
        <td>
        <input id="apartment_name" name="apartment_name" type="text" value="<?=set_value('apartment_name')?>" placeholder="<?=lang('apartment_name')?>" class="form-control input-small"/> 
        </td>
        <td>
        <input id="apartment_number" name="apartment_number" type="text" value="<?=set_value('apartment_number')?>" placeholder="<?=lang('apartment_number')?>" class="form-control input-small"/>  
        </td>      
        <td>
        <input type="submit" name="search_submit" id="search_submit" value="<?=lang('website_search')?>" class="btn btn-small btn-primary" />
        </td>
      </tr>
    </table>                   
    </form>
	
    
    
    
    
  <table class="table table-bordered table-striped">
    	<tr class="warning">
        	<td>ID</td>
            <td><?=lang('household_name')?></td>
            <td><?=lang('primary_contact_person')?></td>
            <td><?=lang('site')?> &nbsp;&raquo; &nbsp; <?=lang('services_point')?></td>            
            <td><?=lang('apartment_name')?> (<?=lang('apartment_number')?>)</td>
            <td><?=lang('family_member')?></td>
            <td align="center"><?=lang('action')?></td>
      	</tr>
     	<?php 
		if( !empty($all_family) ) {
			foreach ($all_family as $family_info) : 
		?>
        <tr>
        	<td><?=$family_info->family_id?></td>
            <td><?=$family_info->household_name?></td>
            <td>
            <?php
			if($family_info->primary_contact_person)
			{
				
				$user_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $family_info->primary_contact_person );
				if($user_info)
				{
			?>
            <a href="./registration/registration/view_single_registration/<?=$user_info->registration_no?>"><?php echo $family_info->primary_contact_person;?></a>
            <?php
				}
				else
				{
				echo $family_info->primary_contact_person;
				}
			}
			?>
            </td>
            <td>
            <?php	
				echo '<span class="label label-success">'.$this->ref_site_model->get_site_name_by_id($family_info->site_id).'</span> &nbsp;&raquo; &nbsp;';	
				echo '<span class="label label-success">'.$this->ref_site_model->get_site_name_by_id($family_info->sp_id).'</span>';	
			?>
            </td>
            <td><?=$family_info->apartment_name?> (<?=$family_info->apartment_number?>)</td>
            <td>
            <?php
			$all_family_member=$this->general_model->get_all_table_info_by_id_asc_desc('gramcar_registration', 'family_id', $family_info->family_id, 'registration_no', 'ASC');
			foreach($all_family_member as $family_member) :
			?>
			<a href="./registration/registration/view_single_registration/<?=$family_member->registration_no?>" title="<?=$family_member->registration_no;?>"><?php echo '<span class="label label-info">'.$family_member->first_name.' '.$family_member->middle_name.' '.$family_member->last_name.'</span>';?></a>
            <?php
			endforeach; 
			?>
            </td>
            <td align="center">
           
            <!-- View Button -->
            
			<?php if ($this->authorization->is_permitted('create_registration')) : ?>
            <a href="<?php echo base_url().'registration/registration/new_registration/0/'.$family_info->family_id;?>" target="_blank" class="btn btn-info btn-mini"><?=lang('website_add')?> <?=lang('family_member')?></a>
            <?php endif; ?>
            
            <!-- Edit Button -->
			<?php if ($this->authorization->is_permitted('edit_family')) : ?>
            <a href="<?php echo base_url().'registration/registration/edit_family_registration/'.$family_info->family_id;?>" class="btn btn-warning btn-mini"><?=lang('website_edit')?></a>
            <?php endif; ?>
            
            </td>
        </tr>
        <?php 			
			endforeach; 
		}	//end if
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