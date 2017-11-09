<?php
class Price implements Coupon {
  $button = true;
  $condition;

  public function getButtonState(){
    return $this.button;
  }

  public function setButtonState($bool){
    $this.button = $bool;
  }

  public function rendurHTML(){
    echo '<h1> hello world! </h1>';
  }

  public function getType(){
    return 'Price';
  }

  public function setCondition($var){
    $this.condition = $var;
  }

  public function getCondition(){
    return $this.condition;
  }
}
