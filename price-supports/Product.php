<?php

class Product implements Coupon {
  private $buttonIsActive = true;
  private $condition = null;

  function __construct($condition) {
    $this->condition = $condition;
  }

  public function getButtonState(){
    return $this->buttonIsActive;
  }

  public function setButtonState($buttonIsActive){
    $this->buttonIsActive = $buttonIsActive;
  }

  public function rendurHTML(){
    return '<h2>' . $this->condition . '</h2>';
  }

  public function getType(){
    return 'Product';
  }

  public function setCondition($condition){
    $this->condition = $condition;
  }

  public function getCondition(){
    return $this->condition;
  }

  public function setCoupon(){
    
  }
}
?>
