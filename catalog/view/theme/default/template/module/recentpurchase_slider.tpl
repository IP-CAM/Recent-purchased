<!--
/*
 * @support
 * http://www.opensourcetechnologies.com/contactus.html
 * sales@opensourcetechnologies.com
* */
-->
<link href='catalog/view/javascript/jquery/owl-carousel/owl.carousel.css' rel="stylesheet" type="text/css" >
<script src='catalog/view/javascript/jquery/owl-carousel/owl.carousel.js' type="text/javascript"></script>
<h3><?php echo $heading_title; ?></h3>
<div id="recent_purchase" class="owl-carousel">
  <?php foreach ($products as $product) {  ?>
    <div class="item text-center" style='margin:1px;'>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
      
      </div>
      
      <div class="caption" style='min-height:50px;'>
        <p><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></b></p>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  <?php } ?>
</div>
<script type="text/javascript"><!--
$('#recent_purchase').owlCarousel({
	center:true,
	loop:true,
	items:<?php echo $no_item_slider;?>,
	autoPlay: '3000',
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: false,
	lazyLoad:false,
	stopOnHover:true,
	lazyEffect : "fade",
	rewindNav : true,
	navigation:true,
        
});
$( document ).ready
(
	
	function()
	{
		var width=$('.owl-item').css('width');
		var widths=width.split('px');
        	var no_of_items=<?php echo $no_item_slider;?>;
                var width=Number(widths[0])/Number(no_of_items);
                var dimension=<?php echo $width;?>;
                if(Number(dimension)<Number(width))
		{  
			var left_width=Number(width)-Number(dimension);
			left_width=Number(left_width)/2;
			left_width=parseInt(left_width);
			//alert(left_width);
                     	$('.owl-item').css({'padding-left':left_width+'px','padding-right':left_width+'px'});
		}
	}
);
--></script>
