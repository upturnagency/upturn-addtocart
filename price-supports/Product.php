<?php

class Product implements Coupon {
  private $buttonIsActive;
  private $condition;
  private $product_id;

  function __construct($condition, $product_id) {
    $this->condition = $condition;
    $this->product_id = $product_id;
    $this->buttonIsActive = false;
  }

  public function setButtonState($buttonIsActive){
    $this->buttonIsActive = $buttonIsActive;
  }

  public function getButtonState(){
    return $this->buttonIsActive;
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
    update_post_meta( $new_coupon_id, 'minimum_amount', $this->condition );

    $coupon_post = get_post($new_coupon_id);

    return !empty($coupon_post);
  }

  public function getBrand(){
    $terms = get_the_terms( $this->product_id, 'brand');
		if(!empty($terms)):
			foreach ( $terms as $term ) {
				$termID[] = $term->term_id;
				$termName = $term->name;
			}
		endif;
    return empty($termName) ? "" : $termName;
  }

  public function rendurHTML($cart, $itemInfo){
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->product_id ), 'thumbnail' );
    $class = $this->condition < $cart ? '' : 'canNotBeUsed';
    $active = $this->buttonIsActive ? 'active' : '';

    $product = wc_get_product( $this->product_id );

    if($this->condition > $cart){
      $have_enought_text = 'Kjøp for kr. <b>' . ($this->condition - $cart) . '</b> mer';
      $lock_image = '<div class="cf-product-lock"><span class="cf-product-lock-image"></span></div>';
    } else {
      $price_display = '<span class="price"><strike>Før ' . $product->get_price() . 'kr </strike> Nå gratis</span>';
    }

    if($this->buttonIsActive){
      $lock_image = '<div class="cf-product-lock"><span class="cf-product-lock-image"></span></div>';
    }

    $HTML = '<li class="' . $active .  $class . ' cf-product">' .
              '<span>' . $have_enought_text . '</span>' .
              $lock_image .
              '<img src="' . $image[0] . '" data-id="' . $this->product_id . '">' .
              '<div class="cf-product-info">' .
                '<span>' . $itemInfo . '</span>' .
                '<span>' . $product->get_title() . '</span>' .
                $price_display .
                do_shortcode('[add_to_cart id="' . $this->product_id . '" show_price="false"]') .
              '</div>' .
            '</li>';

    return $HTML;
  }
}
?>
