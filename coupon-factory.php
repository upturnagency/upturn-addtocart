<?php
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

  $price = new Price(23, 15);
  $product = new Product(20, 15, 3233);
  $product2 = new Product(10, 15, 3423);

  $items = array($product, $price);

  $items[] = $product2;

  $sorted = bubble_sort_coupons($items);

  foreach($sorted as $item){
    echo $item->rendurHTML();
  }
?>
