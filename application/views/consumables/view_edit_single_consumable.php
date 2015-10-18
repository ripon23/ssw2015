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
	    
    
    
    <form class="form-horizontal" id="social-goods-form" action="" method="post" enctype="multipart/form-data">        
              	
                <div class="control-group">
                    <label class="control-label" for="consumable_name">Consumable Name *:</label>        
                    <div class="controls">
                    <input class="input-large"  name="consumable_name" id="consumable_name" placeholder="Consumable Name"  value="<?php echo $single_consumable->consumable_name;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="consumable_category">Consumable Category *:</label>        
                    <div class="controls">
                    <select name="consumable_category" class="input-large" id="consumable_category">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($consumable_categorys as $categorys) : ?>
                        <option value="<?php echo $categorys->consumable_category_id; ?>" <?php if($single_consumable->consumable_category_id==$categorys->consumable_category_id) echo 'selected'; ?>><?php echo $this->session->userdata('site_lang')=='bangla'? $categorys->site_name_bn:$categorys->consumable_category_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="expense_site"><?=lang('site')?> *:</label>        
                    <div class="controls">
                    <select name="consumable_site" class="input-large" id="consumable_site">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($site as $site1) : ?>
                        <option value="<?php echo $site1->site_id; ?>" <?php if($single_consumable->consumable_site_id==$site1->site_id) echo 'selected';?> ><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_description">Per consumable price : </label>        
                    <div class="controls">
                    <input type="text" name="consumable_per_price"  placeholder="Per consumable price" value="<?php echo $single_consumable->consumable_per_price;?>" class="input-large" id="consumable_per_price"/>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_description">Consumable Stock *: </label>        
                    <div class="controls">
                    <input type="text" name="consumable_stock"  placeholder="Consumable Stock" value="<?php echo $single_consumable->consumable_stock;?>" class="input-large" id="consumable_stock"/>
                    </div>
                </div>                               
                
                <div class="control-group">
                    <label class="control-label" for="consumable_note"><?=lang('note')?> :</label>        
                    <div class="controls">
                    <textarea rows="3" placeholder="Consumable note" name="consumable_note" id="consumable_note"><?php echo $single_consumable->consumable_note;?></textarea>        
                    </div>
                </div>
                
                
    
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_update'); ?>" />
            </div>
        </div>
        
    </div><!-- /end span11 -->
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>