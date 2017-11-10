<?php
  // Woocommerce variables
  global $woocommerce;
  $cart_total = $woocommerce->cart->subtotal;

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

  function generateCouponCode() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $coupongString = 'CF_';
    for ($i = 0; $i < 15; $i++) {
        $coupongString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $coupongString;
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

  //Ajax should start here!
  // TODO: Implement AJAX.
  ?>
  <div class="coupon-factory"><?php

    $coupons_in_cart = $woocommerce->cart->get_coupons();
    $count = 0;
    $coupons_to_handle = array();
    foreach($coupons_in_cart as $cartCoup){
      if(substr( $cartCoup->code, 0, 3) == "cf_"){
        $count++;
        $coupons_to_handle[] = $cartCoup->code;
      }
    }

    if(count($coupons_to_handle) > 1){
      $trace = 0;
      foreach($coupons_to_handle as $code){
        if ($trace == 0){
          $trace++;
          continue;
        } else {
          $woocommerce->cart->remove_coupon( $code );
        }
      }
    }

    $amount = 0;

    if(count($discount_coupons) > 0){
      echo '<h4>Kasse kuponger</h4>';
      echo '<ul class="coupon-factory-discount">';
        $discount_coupons = bubble_sort_coupons($discount_coupons);
        foreach($discount_coupons as $coupon){
          if($coupon->getButtonState() && $amount < 1){
            $code = generateCouponCode();
            $isset = $coupon->setCoupon( $code );

            if($isset){
              $woocommerce->cart->add_discount( $code );
            }
            echo 'innside';
            $amount++;
          }

          echo $coupon->rendurHTML($cart_total);
        }
      echo '</ul>';
    }

    if(count($product_coupons) > 0){
      echo '<h4>Gratis produkter</h4>';
      echo '<ul class="coupon-factory-products">';
        $product_coupons = bubble_sort_coupons($product_coupons);
        foreach($product_coupons as $coupon){
          if($coupon->getButtonState() && $amount < 1){
            $code = generateCouponCode();
            $isset = $coupon->setCoupon( $code );

            if($isset){
              $id = $coupon->getProductId();
              $woocommerce->cart->add_to_cart( $id );
              $woocommerce->cart->add_discount( $code );
            }
            $amount++;
          }

          echo $coupon->rendurHTML($cart_total);
        }
      echo '</ul>';
    }?>
  </div>
<?php ?>
