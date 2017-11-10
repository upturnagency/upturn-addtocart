<?php
class Price implements Coupon {
  private $buttonIsActive = false;
  private $condition;
  private $discount;
  private $coupon_code;
  private $internal_id;

  function __construct($condition, $discount) {
    $this->condition = $condition;
    $this->discount = $discount;
  }

  public function getButtonState(){
    return $this->buttonIsActive;
  }

  public function setButtonState($buttonIsActive){
    $this->buttonIsActive = $buttonIsActive;
  }

  public function rendurHTML(){
    $HTML = '<li>' .
              $this->condition .
            '</li>';
    return $HTML;
  }

  public function getType(){
    return 'Price';
  }

  public function getDiscountAmount(){
    return $this->discount;
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

    update_post_meta( $new_coupon_id, 'discount_type', 'fixed_cart' );
    update_post_meta( $new_coupon_id, 'coupon_amount', $this->discount );
    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', '' ); // set to an hour!
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
  }
}
?>
