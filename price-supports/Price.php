<?php
class Price implements Coupon {
  private $buttonIsActive;
  private $condition;
  private $discount;
  private $internal_id;

  function __construct($condition, $discount) {
    $this->condition = $condition;
    $this->discount = $discount;
    $this->buttonIsActive = false;
  }

  public function getButtonState(){
    return $this->buttonIsActive;
  }

  public function setButtonState($buttonIsActive){
    $this->buttonIsActive = $buttonIsActive;
  }

  public function rendurHTML($cart){
    $class = $this->condition < $cart ? '' : 'canNotBeUsed';
    $active = $this->buttonIsActive ? 'active' : '';

    if($this->condition > $cart){
      $have_enought_text = 'Du mangler ' . ($this->condition - $cart) . 'kr </br>for å få avslaget.';
    } else {
      $have_enought_text = 'Klikk for å aktivere';
    }

    $HTML = '<li class="' . $active . $class . '"><a href="#">' .
              '<strong>' . $this->discount . '%</strong> avslag på ordren din' .
              '<span>' . $have_enought_text . '</span>' .
            '</a></li>';

    return $HTML;
  }

  public function getDiscountAmount(){
    return $this->discount;
  }

  public function getCondition(){
    return $this->condition;
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

    update_post_meta( $new_coupon_id, 'discount_type', 'percent' );
    update_post_meta( $new_coupon_id, 'coupon_amount', $this->discount );
    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
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
