<?php

interface Coupon {
  // Used to disable and able buttons when others are pressed
  public function setButtonState($condition);
  public function getButtonState();

  //Used to rendur HTML to the page for every item.
  public function rendurHTML($cart, $itemInfo);

  //Used to get price condition of cart.
  public function getCondition();

  //Initiating coupon in woocommerce
  public function setCoupon($name);
}
?>
