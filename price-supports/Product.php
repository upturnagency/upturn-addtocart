<?php

class Product implements Coupon {
  $button = true;

  public function getButtonState(){
    require $button;
  }

  public function setButtonState($bool){
    $this.button = $bool;
  }

  public function rendurHTML(){
    echo '<h1> hello world! </h1>';
  }

  public function getType(){
    return 'Product';
  }
}
