<?php

interface Coupon {
  // Used to disable and able buttons when others are pressed
  public function setButtonState($condition);
  public function getButtonState();

  //Used to rendur HTML to the page for every item.
  public function rendurHTML();

  //Used to initiate type of coupon.
  public function getType();

  //Used to set price condition of cart.
  public function getCondition();

  //Initiating coupon in woocommerce
  public function generateCouponCode();
  public function setCoupon($name);
}
?>
