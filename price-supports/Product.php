<?php

class Product implements Coupon {
  private $buttonIsActive = false;
  private $condition;
  private $product_id;

  function __construct($condition, $product_id) {
    $this->condition = $condition;
    $this->product_id = $product_id;
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
    return 'Product';
  }

  public function getCondition(){
    return $this->condition;
  }

  public function getProductId(){
    return $this->product_id;
  }

  public function generateCouponCode(){
    return 'heeloThisisAdiscountCode';
  }

  public function setCoupon($name){
    $coupon = array(
    	'post_title' => $name,
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
    update_post_meta( $new_coupon_id, 'usage_limit', 1 );
    update_post_meta( $new_coupon_id, 'expiry_date', '' ); // set to an hour!
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

    $coupon_post = get_post($new_coupon_id);

    if(!empty($coupon_post)) :
      return true;
    else :
      return false;
    endif;
  }
}
?>
