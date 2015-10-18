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
       
 
   	<div class="offset span8">
        <div class="panel panel-warning">
          	<div class="panel-heading">
			<h2 class="panel-title"><?php echo $this->session->userdata('site_lang')=='english'? $product_info->product_name:$product_info->product_name_bn; ?></h2>
          	</div>
          	<div class="panel-body productimg" style="text-align:center">

            <a href="<?php echo base_url().RES_DIR; ?>/img/products/<?php echo $product_info->thumbnil_image; ?>" title="Click to enlarge">
            <img src="<?php echo base_url().RES_DIR; ?>/img/products/medium/<?php echo $product_info->thumbnil_image; ?>" class="img-rounded">
            </a>

             <br>
             <br>
             <table class="table table-striped">
                <tr>
                  <td colspan="2" align="left"><?php echo $product_info->product_description; ?></td>
                </tr>
                <tr>
                    <td>Price </td>
                    <td>
                    <?php echo $product_info->product_price." ".lang('taka'); ?>
                    </td>
                </tr>
				
                
                <tr>
                    <td>Caterory </td>
                    <td>
                    <?php if($product_info->product_category_id) echo $this->social_goods_model->get_product_category_name_by_id($product_info->product_category_id); ?>
                    </td>
                </tr>
                <tr>
                    <td>Brand</td>
                    <td><?php if($product_info->product_brand) echo $this->social_goods_model->get_product_brand_name_by_id($product_info->product_brand); ?></td>
                </tr>
                <tr>
                    <td>Type </td>
                    <td><?php if($product_info->product_type_id) echo $this->social_goods_model->get_product_type_name_by_id($product_info->product_type_id); ?></td>
                </tr> 
                <tr>
                    <td>Weight (Unit) </td>
                    <td><?php if($product_info->product_weight) echo $product_info->product_weight." (".$this->social_goods_model->get_product_weight_unit_by_id($product_info->product_weight_unit).")"; ?></td>
                </tr>
                <tr>
                    <td>Size </td>
                    <td><?php if($product_info->product_size) echo $product_info->product_size; ?></td>
                </tr>
                <tr>
                    <td>Color </td>
                    <td><?php if($product_info->product_color) echo $product_info->product_color; ?></td>
                </tr>
                <tr>
                  <td></td>
                    <td align="right"><a id="<?=$product_info->product_id?>" onClick="add_to_cart(this.id)" class="btn btn-small btn-warning"><i class="icon-shopping-cart icon-white"></i> <?=lang('website_order')?></a></td>
                </tr>       
            </table>
             
          	
			
            
          	</div>
        </div>
   	</div> 
    
    <div class="span3">
    <table class="table table-striped">
    	<tr>
        	<td>Caterory </td>
            <td>
			<?php if($product_info->product_category_id) echo $this->social_goods_model->get_product_category_name_by_id($product_info->product_category_id); ?>
            </td>
        </tr>
        <tr>
        	<td>Brand</td>
            <td><?php if($product_info->product_brand) echo $this->social_goods_model->get_product_brand_name_by_id($product_info->product_brand); ?></td>
        </tr>
        <tr>
        	<td>Type </td>
            <td><?php if($product_info->product_type_id) echo $this->social_goods_model->get_product_type_name_by_id($product_info->product_type_id); ?></td>
        </tr> 
        <tr>
        	<td>Last edit (Date/Time)</td>
            <td><?php echo $product_info->last_edit_data; ?></td>
        </tr>       
    </table>                        	        
            <?php 
            if ($this->authorization->is_permitted('edit_product')) : 		
            ?>                
            <a href="<?php echo base_url().'social_goods/social_goods/edit_single_product/'.$product_info->product_id;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
            <?php
            endif; 
            ?>
    </div> 
    
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>