<?php

class Product implements Coupon {
  private $buttonIsActive = true;
  private $condition = null;
  private $discount = null;
  private $coupon_code = null;

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

  public function getCondition(){
    return $this->condition;
  }

  public function setCouponCode($coupon_code){
    $this->coupon_code = $coupon_code;
  }

  public function setCoupon(){
    $coupon_code = 'hello-this-is-a-test';
    $amount = '10';
    $discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product

    $coupon = array(
    	'post_title' => $coupon_code,
    	'post_content' => '',
    	'post_status' => 'publish',
    	'post_author' => 1,
    	'post_type'		=> 'shop_coupon'
    );

    $new_coupon_id = wp_insert_post( $coupon );

    update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
    update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', '' );
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
  }
}
?>
