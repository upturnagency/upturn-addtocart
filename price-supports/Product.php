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

  public function rendurHTML($cart){
    //TODO: implement HTML for product
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->product_id ), 'thumbnail' );
    $class = $this->condition < $cart ? '' : 'canNotBeUsed';
    $active = $this->buttonIsActive ? 'active' : '';
    $product = wc_get_product( $this->product_id );

    if($this->condition > $cart){
      $have_enought_text = 'Du mangler ' . ($this->condition - $cart) . 'kr for å motta dette produktet.';
    } else {
      $have_enought_text = 'Klikk for å aktivere';
    }

    $HTML = '<li class="' . $active .  $class . '"><a href="#">' .
              'Få en ' . $product->get_title() . ' gratis.' .
              '<img src="' . $image[0] . '" data-id="' . $this->product_id . '">' .
              '<strong>Original pris: ' . $product->get_price_html() . '.</strong>' .
              '<span>' . $have_enought_text . '</span>' .
            '</a></li>';

    return $HTML;
  }

  public function getCondition(){
    return $this->condition;
  }

  public function getProductId(){
    return $this->product_id;
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
    update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', 1 );
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
