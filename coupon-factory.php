<?php
  // Interface for coupon
  require 'price-supports/Coupon.php';
  // Classes for coupons
  require 'price-supports/Price.php';
  require 'price-supports/Product.php';


  $price = new Price("test");
  echo $price->rendurHTML();

  $product = new Product("Hello");
  echo $product->rendurHTML();
?>
