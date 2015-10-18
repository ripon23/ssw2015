<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>
$(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });
jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?> (<?=lang('menu_add_product')?>)</div>
    <div class="panel-body">
       
    <!--<div class="alert alert-info">Fields with <strong></strong><span class="required">*</span></strong> are required.</div>-->
    <?php 
	//var_dump($error);
	//echo "------";
	
	if($error) 
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$error?>
    </div>
	<?php
	}
	?>
    
    <?php
	/*if(isset($upload_data))
	{
	?>
    <ul>
	<?php foreach ($upload_data as $item => $value):?>
    <li><?php echo $item;?>: <?php echo $value;?></li>
    <?php endforeach; 
	?>
    </ul>
    <?php
	}*/
	?>
    
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
                    <label class="control-label" for="product_name">Product Name(English) *:</label>        
                    <div class="controls">
                    <input class="input-large"  name="product_name" id="product_name"  value="<?php echo $product_info->product_name;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_name_bn">Product Name(Bangla) *: </label>        
                    <div class="controls">
                    <input class="input-large"  name="product_name_bn" id="product_name_bn"  value="<?php echo $product_info->product_name_bn;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_description">Product Description *: </label>        
                    <div class="controls">
                    <textarea rows="7" name="product_description" id="product_description"  class="input-xxlarge"><?php echo $product_info->product_description;?></textarea>  
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_price">Price (Taka) *: </label>        
                    <div class="controls">
                    <input class="input-large"  name="product_price" id="product_price"  value="<?php echo $product_info->product_price;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_categories">Product Categories *: </label>        
                    <div class="controls">                   
                    <select name="product_categories" id="product_categories">
                    <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($all_categories as $category) : ?>
                        <option value="<?php echo $category->product_category_id; ?>" <?php if($category->product_category_id==$product_info->product_category_id) echo ' selected="selected"'; ?>><?php echo $this->session->userdata('site_lang')=='english'? $category->category_name:$category->category_name_bn; ?></option>
                        <?php endforeach; ?>
	                </select>	
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_type">Product Type *: </label>        
                    <div class="controls">                   
                    <select name="product_type" id="product_type">
                    <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($all_product_type as $product_type) : ?>
                        <option value="<?php echo $product_type->product_type_id; ?>" <?php if($product_type->product_type_id==$product_info->product_type_id) echo ' selected="selected"'; ?>><?php echo $this->session->userdata('site_lang')=='english'? $product_type->type_name:$product_type->type_name_bn; ?></option>
                        <?php endforeach; ?>
	                </select>	
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_weight">Weight: </label>        
                    <div class="controls">
                    <input class="input-large"  name="product_weight" id="product_weight"  value="<?php echo $product_info->product_weight;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_weight_unit">Weight Unit: </label>        
                    <div class="controls">                   
                    <select name="product_weight_unit" id="product_weight_unit">
                    <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($all_weight_unit as $weight_unit) : ?>
                        <option value="<?php echo $weight_unit->unit_id; ?>" <?php if($weight_unit->unit_id==$product_info->product_weight_unit) echo ' selected="selected"'; ?>><?php echo $this->session->userdata('site_lang')=='english'? $weight_unit->unit_name:$weight_unit->unit_name_bn; ?></option>
                        <?php endforeach; ?>
	                </select>	
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_size">Size: </label>        
                    <div class="controls">
                    <input class="input-large"  name="product_size" id="product_size"  value="<?php echo $product_info->product_size?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_weight">Color: </label>        
                    <div class="controls">
                    <input class="input-large"  name="product_color" id="product_color"  value="<?php echo $product_info->product_color;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_weight">Brand: </label>        
                    <div class="controls">
                    <select name="product_brand" id="product_brand">
                    <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($all_product_brand as $product_brand) : ?>
                        <option value="<?php echo $product_brand->brand_id; ?>" <?php if($product_brand->brand_id==$product_info->product_brand) echo ' selected="selected"'; ?>><?php echo $this->session->userdata('site_lang')=='english'? $product_brand->brand_name:$product_brand->brand_name_bn; ?></option>
                        <?php endforeach; ?>
	                </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_image">Product Image: *</label>        
                    <div class="controls">
                   <a href="<?php echo base_url().RES_DIR; ?>/img/products/<?php echo $product_info->thumbnil_image; ?>" title="Click to enlarge">
                   <img src="<?php echo base_url().RES_DIR; ?>/img/products/medium/<?php echo $product_info->thumbnil_image; ?>" class="img-rounded">
                   </a>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_image">Change Product image: </label>        
                    <div class="controls">                   
                    <input type="file" class="input-large" name="product_image" />
                    </div>
                </div>
    			
                <div class="control-group">
                    <label class="control-label" for="product_image">Product Status: </label>        
                    <div class="controls">                   
                    <select name="product_status_option" id="product_status_option" class="selectpicker span1.5" data-style="btn">
                    <option value="0" <?php if($product_info->status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                    <option value="1" <?php if($product_info->status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Active</span>">Active</option>            
       				</select>
                    </div>
                </div>
                
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_save'); ?>" />
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