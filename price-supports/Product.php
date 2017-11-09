<?php

class Product implements Coupon {
  $button = true;
  $condition;

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

  public function setCondition( $var ){
    $this.condition = $var;
  }

  public function getCondition(){
    return $this.condition;
  }
}
