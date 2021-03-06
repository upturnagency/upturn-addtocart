 <div class="cross-sell archive">
	    <?php

	    global $woocommerce;

	    $id = $_GET['id'];
	    $time = $_GET['t'];
      try {
        if (is_numeric(get_option('upturn_expire_time'))):
          $expire_time = get_option('upturn_expire_time') * 60;
        endif;
      } catch (Exception $e){
        // The variable might not be initialized.
      }
	    $timeleft = $time + $expire_time;
	    $current = time();

	    $men = get_option('upturn-parent-category-men');
	    $women = get_option('upturn-parent-category-women');
	    $gender = $GLOBALS['upturn_gender'];

	    if ( $gender == '1' ) {
		    $cat = get_term_by('slug', $men, 'product_cat');
		    $catid = $cat->term_id;
		    $catname = $men;
	    } elseif ( $gender == '2' ) {
		    $cat = get_term_by('slug', $women, 'product_cat');
		    $catid = $cat->term_id;
		    $catname = $women;
	    }

	    if(!empty($expire_time)):
	    ?>
            <script type="text/javascript">
                function startTimer(duration, display) {
                    var timer = duration, minutes, seconds;
                    setInterval(function () {
                        minutes = parseInt(timer / 60, 10)
                        seconds = parseInt(timer % 60, 10);

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        seconds = seconds < 10 ? "0" + seconds : seconds;

                        display.textContent = minutes + " <?php echo __('minutter', 'cross-sell'); ?> " + seconds + " <?php echo __('sekunder', 'cross-sells'); ?>" + ".";

                        if (--timer < 0) {
                            timer = duration;
                        }
                    }, 1000);
                }

                jQuery(function ($) {
                    var timeLeft = <?php echo $timeleft; ?> - <?php echo time(); ?>,
                        display = document.querySelector('#countdown');
                        <?php if($timeleft - $current > 1): ?>
                            startTimer(timeLeft, display);
                        <?php else: ?>
                            var elem = document.getElementById('cart-countdown');
                            elem.parentNode.removeChild(elem);
                        return false;
                        <?php endif; ?>
                });
            </script>
         <?php
         endif;

	    if (isset($id) && is_numeric($_GET['id'])): ?>
            <div class="cross-sell-header top clearfix cf">
			    <?php

			    $args = array(
				    'p'         => $id,
				    'post_type' => 'product',
			    );
			    $loop = new WP_Query( $args );

			    $i = 0;
			    ?>
                <div class="added-to-cart clearfix">
				    <?php
				    while ( $loop->have_posts() && $i < 1) : $loop->the_post(); global $product;
					    $i++;
					    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'thumbnail' );?>
                        <div class="container">
                            <div class="content">
                                <span><?php echo sprintf ( _n( '%d produkt lagt til', '%d produkter lagt til', $i ), $i); ?> </span>
                            </div>
                        </div>
                        <div class="container">
                            <div class="content">
                                <img src="<?php  echo $image[0]; ?>" data-id="<?php echo $loop->post->ID; ?>" class="product-image">
                            </div>
                        </div>
                        <div class="container">
                            <div class="content">
                            <strong><?php echo $loop->post->post_title;?></strong></span>
                            <?php if(!empty($expire_time)):
                                echo '<br/><span id="cart-countdown">' . __("Vi reserverer den til deg i ", "cross-sell"); ?>
                                <span id="countdown"><?php echo __('sjekker...', 'cross-sell'); ?></span>
                            <?php endif; ?>
                            </div>
				    <?php endwhile; ?>
                        </div>
                </div>
                </div>
            <?php
            $class = ( get_option('upturn-useStickyHeader') != 'no' ) ? 'sticky-header' : '';
            ?>
            <div class="cross-sell-header bottom <?php echo $class; ?> clearfix cf">
                <div class="container">
                    <div class="content">
	                <?php echo '<strong>' . __('Total:', 'cross-sell') . '</strong>&nbsp;' . $woocommerce->cart->get_cart_total();
	                echo '&nbsp;('.sprintf ( _n( '%d produkt', '%d produkter', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ).')';
                    echo '<br>';
	                do_action('before_cross_sell_actions');
	                ?>
	                <?php
	                // Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts

	                $default_zone = new WC_Shipping_Zone(0);
	                $default_methods = $default_zone->get_shipping_methods();

	                foreach( $default_methods as $key => $value ) {
		                if ( $value->id === "free_shipping" ) {
			                if ( $value->min_amount > 0 ) $min_amounts[] = $value->min_amount;
		                }
	                }
	                // Get Free Shipping Methods for all other ZONES & populate array $min_amounts

	                $delivery_zones = WC_Shipping_Zones::get_zones();

	                foreach ( $delivery_zones as $key => $delivery_zone ) {
		                foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
			                if ( $value->id === "free_shipping" ) {
				                if ( $value->min_amount > 0 ) $min_amounts[] = $value->min_amount;
			                }
		                }
	                }

	                // Find lowest min_amount

	                if ( is_array($min_amounts) ) {

		                $min_amount = min($min_amounts);

		                // Get Cart Subtotal inc. Tax excl. Shipping

		                $current = WC()->cart->subtotal;

		                // If Subtotal < Min Amount Echo Notice
		                // and add "Continue Shopping" button

		                if ( $current < $min_amount ) {
			                $added_text = esc_html__('Du er ', 'woocommerce' ) . wc_price( $min_amount - $current ) . esc_html__(' unna gratis frakt!', 'woocommerce' );

		                }else{
			                $added_text = esc_html__('Du får fri frakt på din ordre! ', 'woocommerce' );
		                }
		                print_r($added_text);
	                }

	                ?>
                    </div>
                </div>
                <?php if ( get_option( 'upturn-displayGoToShopButton' ) == 'yes' ) : ?>
                    <a href="<?php echo get_home_url(); ?>" class="button btn black alt gotoshop"><?php echo __('Gå til butikken', 'cross-sell'); ?></a>
                <?php endif; ?>
                <?php if ( get_option( 'upturn-displayGoToCartButton' ) == 'yes' ) :
                  $class_cart = get_option( 'upturn-displayCheckoutButton' ) == 'yes' ? 'cart-with-checkout' : 'cart-without-checkout'; ?>
                      <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button btn cart <?php echo $class_cart; ?>"><?php echo __('Til handlekurven', 'cross-sell'); ?></a>
                <?php endif; ?>

                <?php if ( get_option( 'upturn-displayCheckoutButton' ) == 'yes' ) :
                  $class_checkout = get_option( 'upturn-displayGoToCartButton' ) == 'yes' ? 'checkout-with-cart' : 'checkout-without-cart'; ?>
                      <a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button btn alt checkout <?php echo $class_checkout; ?>"><?php echo __('Til kassen', 'cross-sell'); ?></a>
                <?php endif; ?>
            </div>
	    <?php else: ?>
            <h1>This isn't the page for you.</h1>
	    <?php endif; ?>
        <?php
        if(get_option('upturn-couponFactory') != 'no'){
	        require 'coupon-factory.php';
        }
        ?>
        <div class="cross-sell-wrap">
            <?php
            do_action('before_cross_sell_page');

            $get_products_per_row = get_option('upturn-products-per-row');
            $products_per_row = !empty( $get_products_per_row ) ? $get_products_per_row : 3 ;


            for($i = 1; $i <= 4; $i++){

                $location = "before_cross_sell_location_" . $i;
                do_action($location);

                if ( $i == get_option( 'upturn-cross-sell-products' ) ){
                    upturn_cross_sell( $products_per_row, $catname );
                } else if ( $i == get_option( 'upturn-best-sellers-site-wide' ) ){
                    upturn_best_sellers_site_wide( $products_per_row, $catname );
                } else if ( $i == get_option( 'upturn-new-products' ) ){
                    upturn_new_products( $products_per_row, $catname );
                } else if ( $i == get_option( 'upturn-sales-items' ) ){
                    if(!empty(wc_get_product_ids_on_sale())){
                        upturn_sales_items( $products_per_row, $catname );
                    }
                }
            }

            do_action('after_cross_sell_page');

            ?>
        </div>
    </div>
