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
           success: function(msg)
           {               	
			  var cart_string1='<i class="icon-shopping-cart icon-white"></i> Cart (';
			  var cart_string2=')';
			  $('#cart-button').html(cart_string1+msg+cart_string2);		      	
           }
         });
}
</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?> (<?=lang('menu_view_product')?> <?=lang('menu_list')?>)<a href="social_goods/social_goods/show_cart" id="cart-button" class="btn btn-medium btn-success pull-right" ><i class="icon-shopping-cart icon-white"></i> Cart (<?=$this->cart->total_items()?>)</a></div>
    <div class="panel-body">
       
    <?php echo form_open('social_goods/social_goods/view_product_list_search') ?>
        
       <table class="table table-bordered">
        <tr align="center" class="warning">
          <td>Product Name</td>
          <td>Category</td>
          <td>Type</td>
          <td>Brand</td>
          <td><?=lang('website_search')?></td>          
        </tr>
        <tr align="center" class="success">
            <td><input class="input-large"  name="sproduct_name" id="sproduct_name"  value="<?php echo isset($sproduct_name)?$sproduct_name:'';?>" type="text" /></td>
            <td>
            <select name="sproduct_categories" id="sproduct_categories">
              	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($all_categories as $category) : ?>
                <option value="<?php echo $category->product_category_id;?>"><?php echo $this->session->userdata('site_lang')=='english'? $category->category_name:$category->category_name_bn; ?></option>
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
    
   	
    <table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><abbr title="Product ID">P.ID</abbr></th>
                <th>Product Name</th>
                <th>Category</th>
				<th>Type</th>
                <th>Price</th>
                <th>Brand</th>
                <th>Image</th>              
                <?php if ($this->authorization->is_permitted('add_information_services')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>                				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php foreach ($all_product as $product) : ?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $product->product_id;?></td>
              	<td><?php echo $this->session->userdata('site_lang')=='english'? $product->product_name:$product->product_name_bn; ?></td>
              	<td align="center"><?php if($product->product_category_id) echo $this->social_goods_model->get_product_category_name_by_id($product->product_category_id); ?></td>
              	<td align="center"><?php if($product->product_type_id) echo $this->social_goods_model->get_product_type_name_by_id($product->product_type_id); ?></td>
              	<td align="right"><?php echo $product->product_price." ".lang('taka'); ?></td>
              	<td align="center"><?php if($product->product_brand) echo $this->social_goods_model->get_product_brand_name_by_id($product->product_brand); ?></td>
                <td align="center"><img src="<?php echo base_url().RES_DIR; ?>/img/products/thumbnils/<?php echo $product->thumbnil_image; ?>" ></td>                              
                <td>
                 <a href="<?php echo base_url().'social_goods/social_goods/view_single_product/'.$product->product_id;?>" class="btn btn-small btn-success"><?=lang('website_details')?></a> 
            	<a id="<?=$product->product_id?>" onClick="add_to_cart(this.id)" class="btn btn-small btn-warning"><i class="icon-shopping-cart icon-white"></i> <?=lang('website_order')?></a>
                <?php 
				if ($this->authorization->is_permitted('edit_product')) : 		
				?>                
                <a href="<?php echo base_url().'learning/learning/view_single_learning/'.$product->product_id;?>" class="btn btn-small btn-info"><?=lang('website_edit')?></a>
                <?php
				endif; 
				?>
                </td>                                                
</tr>
            <?php 
			$i=$i+1;
			endforeach; 
			//}
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