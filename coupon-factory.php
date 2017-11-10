<?php
  // Woocommerce variables
  global $woocommerce;
  $cart_total = $woocommerce->cart->total;

  // Interface for coupon
  require 'price-supports/Coupon.php';
  // Classes for coupons
  require 'price-supports/Price.php';
  require 'price-supports/Product.php';

  function bubble_sort_coupons(array $array){
  	do {
  		$swapped = false;
  		for( $i = 0; $i < count($array) - 1; $i++) {
  			if($array[$i]->getCondition() > $array[$i + 1]->getCondition()) {
  				list($array[$i + 1], $array[$i]) = array($array[$i], $array[$i + 1]);
  				$swapped = true;
  			}
  		}
  	} while($swapped);
  	return $array;
  }

  // Object arrays for price and product
  $product_coupons = $discount_coupons = array();

  // Checks if there are any product or discount coupons intitated
  if( have_rows('cf_price_reduction', 'options') || have_rows('cf_product_reduction', 'options')):
    while ( have_rows('cf_price_reduction', 'options') ) : the_row();
      $condition = get_sub_field('cf_price_cart_total');
      $discount = get_sub_field('cf_price_cart_reduction');

      $discount_coupons[] = new Price($condition, $discount);
    endwhile;

    while ( have_rows('cf_product_reduction', 'options') ) : the_row();
      $condition = get_sub_field('cf_product_cart_total');
      $product_id = get_sub_field('cf_product_selector');

      $product_coupons[] = new Product($condition, $product_id);
    endwhile;
  endif;


?>
