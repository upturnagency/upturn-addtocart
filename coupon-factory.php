<?php
  // Woocommerce variables
  global $woocommerce;
  $cart_total = $woocommerce->cart->subtotal;

  // Interface for coupon
  require 'price-supports/Coupon.php';

  // Classes for coupons
  //require 'price-supports/Price.php';
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
  /*have_rows('cf_price_reduction', 'options') || vv under*/
  /*while ( have_rows('cf_price_reduction', 'options') ) : the_row();
    $condition = get_sub_field('cf_price_cart_total');
    $discount = get_sub_field('cf_price_cart_reduction');
    $discount_coupons[] = new Price($condition, $discount);
  endwhile;*/

  if( have_rows('cf_product_reduction', 'options')):
    while ( have_rows('cf_product_reduction', 'options') ) : the_row();
      $condition = get_sub_field('cf_product_cart_total');
      $product_id = get_sub_field('cf_product_selector');
      $product_coupons[] = new Product($condition, $product_id);
    endwhile;
  endif;

  $product_coupons = bubble_sort_coupons($product_coupons);
  ?>

  <div class="coupon-factory"><?php
    $coupons_in_cart = $woocommerce->cart->get_coupons();
    $coupons_to_handle = array();

    foreach($coupons_in_cart as $cartCoup){
      if(substr( $cartCoup->code, 0, 3) == "cf_"){
        $coupons_to_handle[] = $cartCoup;
      }
    }

    if(count($coupons_to_handle) > 1){
      do {
    		$swapped = false;
    		for( $i = 0; $i < count($coupons_to_handle) - 1; $i++) {
          $tempProduct = wc_get_product( $coupons_to_handle[$i]->product_ids[0] );
          $tempProductTwo = wc_get_product( $coupons_to_handle[$i + 1]->product_ids[0] );

    			if($tempProduct->get_price() > $tempProductTwo->get_price()) {
    				list($coupons_to_handle[$i + 1], $coupons_to_handle[$i]) = array($coupons_to_handle[$i], $coupons_to_handle[$i + 1]);
    				$swapped = true;
    			}
    		}
    	} while($swapped);
    }

    $couponIsHigher = true;

    if(isset($_GET['id']) && !empty($_GET['id'])) {
      $cf_id = $_GET['id'];
      foreach($product_coupons as $product){
        if($product->getProductId() == $cf_id){
          if($cart_total >= $product->getCondition()){
            if(count($coupons_to_handle) > 0){
              $coupon_product = wc_get_product( $coupons_to_handle[0]->product_ids[0] );
              $current_product = wc_get_product( $product->getProductId() );
              if($current_product->get_price() >= $coupon_product->get_price() || count($coupons_to_handle) == 0){
                $couponIsHigher = false;
                $code = generateCouponCode();
                $isset = $product->setCoupon( $code );

                if($isset){
                  $woocommerce->cart->add_discount( $code );
                  $product->setButtonState(true);
                }
              }
            } else {
              $couponIsHigher = false;
              $code = generateCouponCode();
              $isset = $product->setCoupon( $code );

              if($isset){
                $woocommerce->cart->add_discount( $code );
                $product->setButtonState(true);
              }
            }
          }
        }
      }
    }

    if($couponIsHigher){
      $coupon = $coupons_to_handle[0];
      foreach($product_coupons as $product){
        if($product->getProductId() == $coupon->product_ids[0]){
          $product->setButtonState(true);
          break;
        }
      }
    } 

    if(count($coupons_to_handle) > 1){
      for($i = 1; $i < count($coupons_to_handle) - 1; $i++) {
        $woocommerce->cart->remove_coupon( $coupons_to_handle[$i]->code );
      }
    }

    if(count($product_coupons) > 0){
      echo '<div class="coupon-factory-products">';
        echo '<h4>Kjøp for litt til - få gratis produkt!</h4>';
        echo '<ul class="coupon-factory-products-list">';
          foreach($product_coupons as $coupon){
            $brand = $coupon->getBrand();
            echo $coupon->rendurHTML($cart_total, $brand);
          }
      echo '</ul></div>';
    }

    /*if(count($discount_coupons) > 0){
      echo '<div class="coupon-factory-discount">';
        echo '<h4>Kasse kuponger</h4>';
        echo '<ul class="coupon-factory-discount-list">';
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
        echo '</ul></div>';
    }*/?>
  </div>
<?php ?>
