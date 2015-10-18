<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>
function add_to_cart(product_id)
{
//alert(product_id);	
$.ajax({
           type: "POST",
           url: "social_goods/social_goods/add_to_cart",
		   data: "product_id="+product_id,
           
		   beforeSend: function()
		   {
			$(".loder"+product_id).html('<img src="<?php echo base_url().RES_DIR; ?>/img/ajax-loader.gif">');
		   },
		   
		   success: function(msg)
           {               	
			  var cart_string1='<i class="icon-shopping-cart icon-white"></i> Cart (';
			  var cart_string2=')';
			  $('#cart-button').html(cart_string1+msg+cart_string2);	
			  $(".loder"+product_id).html('');
           }
         });
}

jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?> (<?=lang('menu_view_product')?> <?=lang('menu_list')?>)<a href="social_goods/social_goods/show_cart" id="cart-button" class="btn btn-medium btn-success pull-right" ><i class="icon-shopping-cart icon-white"></i> Cart (<?=$this->cart->total_items()?>)</a></div>
    <div class="panel-body">
       
    <?php echo form_open('social_goods/social_goods/view_product_grid_search') ?>
        
       <table class="table table-bordered">
        <tr align="center" class="warning">
          <td>Product Name</td>
          <td>Category</td>
          <td>Type</td>
          <td>Brand</td>
          <td><?=lang('website_search')?></td>          
        </tr>
        <tr align="center" class="success">
            <td><input class="input-large"  name="sproduct_name" id="sproduct_name"  value="<?php echo set_value('sproduct_name');?>" type="text" /></td>
            <td>
            <select name="sproduct_categories" id="sproduct_categories">
              	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($all_categories as $category) : ?>
                <option value="<?php echo $category->product_category_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $category->category_name:$category->category_name_bn; ?></option>
                <?php endforeach; ?>
	        </select>
            </td>            
            <td>
            <select name="sproduct_type" id="sproduct_type">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($all_product_type as $product_type) : ?>
                <option value="<?php echo $product_type->product_type_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $product_type->type_name:$product_type->type_name_bn; ?></option>
                <?php endforeach; ?>
	        </select> 
            </td>
            <td>
            <select name="sproduct_brand" id="sproduct_brand">
            <option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($all_product_brand as $product_brand) : ?>
                <option value="<?php echo $product_brand->brand_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $product_brand->brand_name:$product_brand->brand_name_bn; ?></option>
                <?php endforeach; ?>
            </select>
            </td>
          	<td><input type="submit" name="search_submit" id="search_submit" value="<?=lang('website_search')?>" class="btn-small btn-primary" /></td>
          </tr>
          
        </table>
        
        </form>     
    
    <?php 			
	$i=$page+0;
	?>
    <?php foreach ($all_product as $product) : ?>
   	<div class="offset span3 <?=$i%4==0? "no_margin":"no_margin10"?>">
        <div class="panel panel-warning">
          	<div class="panel-heading">
			<h2 class="panel-title"><?php echo $this->session->userdata('site_lang')=='english'? $product->product_name:$product->product_name_bn; ?></h2>
          	</div>
          	<div class="panel-body productimg" style="text-align:center">

            <img src="<?php echo base_url().RES_DIR; ?>/img/products/medium/<?php echo $product->thumbnil_image; ?>" class="img-rounded productimage">

             <br>
             <br>
             <span class="badge badge-info">
          	<?php echo $product->product_price." ".lang('taka'); ?> </span><br><br>
			<a href="<?php echo base_url().'social_goods/social_goods/view_single_product/'.$product->product_id;?>" class="btn btn-small btn-success"><?=lang('website_details')?></a> 
            <a id="<?=$product->product_id?>" onClick="add_to_cart(this.id)" class="btn btn-small btn-warning"><i class="icon-shopping-cart icon-white"></i><?=lang('website_order')?></a> 
            <div class="loder<?=$product->product_id?>" style="width:32px !important;height:32px !important; float:right;"></div>
          	</div>
            
            
        </div>
   	</div> 
    <?php 
	$i=$i+1;
	endforeach; 
	?> 
    
    <div class="span12">                
		<div style="text-align:left"><?php echo $links; ?></div>
    </div>
    
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>