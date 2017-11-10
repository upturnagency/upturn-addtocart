<?php

class Product implements Coupon {
  private $buttonIsActive = false;
  private $condition;
  //private $discount = null;
  private $product_id;
  private $coupon_code;

  function __construct($condition, $product_id) {
    $this->condition = $condition;
    //$this->discount = $discount;
    $this->product_id = $product_id;
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

  public function setCouponCode(){
    $this->coupon_code = "Hello";
  }

  public function setCoupon(){
    $coupon = array(
    	'post_title' => $this->coupon_code,
    	'post_content' => '',
    	'post_status' => 'publish',
    	'post_author' => 1,
    	'post_type'		=> 'shop_coupon'
    );

    $new_coupon_id = wp_insert_post( $coupon );

    update_post_meta( $new_coupon_id, 'discount_type', 'percent_product' );
    update_post_meta( $new_coupon_id, 'coupon_amount', 100 );
    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
    update_post_meta( $new_coupon_id, 'product_ids', $this->product_id );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', '' ); // set to an hour!
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
  }
}
?>
