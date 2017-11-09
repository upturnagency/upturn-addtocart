<?php
  // Interface for coupon
  require 'price-supports/Coupon.php';
  // Classes for coupons
  require 'price-supports/Price.php';
  require 'price-supports/Product.php';

  $price = new Price(23);
  $product = new Product(20);
  $product2 = new Product(10);

  $items = array($price, $product);

  $items[] = $product2;

  

  foreach($items as $item){
    echo $item->rendurHTML();
  }
?>
