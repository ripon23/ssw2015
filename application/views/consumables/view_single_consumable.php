<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>

jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/consumables.png" width="48" height="41"> <?=lang('consumables')?></div>
    <div class="panel-body">
       
    <!--<div class="alert alert-info">Fields with <strong></strong><span class="required">*</span></strong> are required.</div>-->
    
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
	    
    
    
    <form class="form-horizontal" id="health-checkup-form" action="" method="post">    
    
       
 	<div class="span8">
    <table class="table table-bordered">
    	<tr>
           <td>Consumable id</td>
           <td><?php echo $single_consumable->consumable_id; ?></td>
        </tr>
        <tr>
           <td>Consumable name</td>
           <td><?php echo $single_consumable->consumable_name ;?></td>
        </tr>
        <tr>
           <td>Consumable category</td>
           <td><?php echo $this->consumables_model->get_consumable_category_name_by_id($single_consumable->consumable_category_id); ?></td>
        </tr>
        <tr>
           <td>Consumable site</td>
           <td><?php echo $this->ref_site_model->get_site_name_by_id($single_consumable->consumable_site_id); ?></td>
        </tr>
        <tr>
           <td>Per consumable price</td>
           <td><?=$single_consumable->consumable_per_price?></td>
        </tr> 
        <tr>
           <td>Consumable stock</td>
           <td><?=$single_consumable->consumable_stock?></td>
        </tr>
        <tr>
           <td>Consumable note</td>
           <td><?=$single_consumable->consumable_note?></td>
        </tr> 
        
    </table>           
    </div>            
    
    <div class="span3">
    <table class="table table-striped">    	              
        <tr>
        	<td>Last Update: </td>
            <td><?=$single_consumable->update_date?> </td>
        </tr>
        <tr>
        	<td>Entry/Update user: </td>
            <td><?=$single_consumable->update_user_id?> </td>
        </tr>
        </table> 
        
        <?php
        if($this->authorization->is_permitted('add_consumables'))
        {
        ?>
        <a href="<?php echo base_url().'consumables/consumables/edit_single_consumable/'.$single_consumable->consumable_id ;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
        <?php
        }
        ?>
    </div><!-- /end span3 -->
     
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>