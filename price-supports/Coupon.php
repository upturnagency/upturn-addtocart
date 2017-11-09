<?php

interface Coupon {

  // Used to disable and able buttons when others are pressed
  public function getButtonState();
  public function setButtonState();

  //Used to rendur HTML to the page for every item.
  public function rendurHTML();

  //Used to initiate type of coupon.
  public function getType();

  //Used to set price condition of cart.
  public function setCondition();
  public function getCondition();
}
