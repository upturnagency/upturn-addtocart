<?php

interface Coupon {
  public function getButtonState();
  public function setButtonState();
  public function rendurHTML();
  public function getType();
}
