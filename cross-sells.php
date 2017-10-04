 <div class="cross-sell archive">
	    <?php

	    global $woocommerce;

	    $id = $_GET['id'];
	    $time = $_GET['t'];
	    $expire_time = get_option('upturn_expire_time') * 60;
	    $timeleft = $time + $expire_time;
	    $current = time();

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

                        display.textContent = minutes + " <?php echo __('minutes', 'cross-sells'); ?> " + seconds + " <?php echo __('seconds', 'cross-sells'); ?>" + ".";

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
                <div class="added-to-cart">
				    <?php
				    while ( $loop->have_posts() && $i < 1) : $loop->the_post(); global $product;
					    $i++;
					    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'thumbnail' );?>
                        <i class="check"></i><h3><?php echo $loop->post->post_title;?> <?php echo __('added to cart', 'cross-sell'); ?></h3>
				    <?php endwhile; ?>
                </div>
                <div class="cart clearfix">
		            <?php
		            echo '<strong>' . __('Cart total:', 'cross-sell') . '</strong>&nbsp;' . $woocommerce->cart->get_cart_total();
		            echo '&nbsp;('.sprintf ( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ).')';
		            do_action('before_cross_sell_actions');
		            ?>
                </div>
            </div>
            <div class="cross-sell-header bottom clearfix cf">
			    <?php

			    $args = array(
				    'p'         => $id,
				    'post_type' => 'product',
			    );
			    $loop = new WP_Query( $args );

			    $i = 0;
			    ?>
                <div class="item-info">
				    <?php
				    while ( $loop->have_posts() && $i < 1) : $loop->the_post(); global $product;

					    $i++;
					    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'thumbnail' );?>
                        <img src="<?php  echo $image[0]; ?>" data-id="<?php echo $loop->post->ID; ?>" class="product-image">
                        <div class="item-info--text">
                            <span class="product clearfix"><strong><?php echo $loop->post->post_title;?></strong>
                                <?php
                                if(!empty($expire_time)):
                                    echo '<br/><span id="cart-countdown">' . __("We'll reserve it for ", "cross-sells"); ?>
                                    <span id="countdown"><?php echo __('checking...', 'cross-sells'); ?></span></span>
                                <?php
                                endif;
                                ?>
                            </span>
                            </span>
                        </div>
				    <?php endwhile; ?>



                </div>
                <div class="actions clearfix">
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
		                    $added_text = esc_html__('Get free shipping if you order ', 'woocommerce' ) . wc_price( $min_amount - $current ) . esc_html__(' more!', 'woocommerce' );

	                    }else{
		                    $added_text = esc_html__('You get free shipping on your order! ', 'woocommerce' );
                        }
	                    print_r($added_text);

                    }

                    ?>
                    <div class="buttons">
                        <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button btn cart"><?php echo __('Go to cart', 'cross-sell'); ?></a>
                        <a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button btn alt checkout"><?php echo __('Checkout', 'cross-sell'); ?></a>
                    </div>
                </div>
            </div>
	    <?php else: ?>
            <h1>This isn't the page for you.</h1>
	    <?php endif; ?>
        <div class="cross-sell-wrap">
            <?php

            do_action('before_cross_sell_page');

            for($i = 1; $i <= 4; $i++){

                $location = "before_location_" . $i;
                do_action($location);

                if ( $i == get_option( 'cross-sell-products' ) ){
                    upturn_cross_sell();
                } else if ( $i == get_option( 'best-sellers-site-wide' ) ){
                    upturn_best_sellers_site_wide();
                } else if ( $i == get_option( 'new-products' ) ){
                    upturn_new_products();
                } else if ( $i == get_option( 'sales-items' ) ){
                    upturn_sales_items();
                }
            }

            do_action('after_cross_sell_page');

            ?>
        </div>
    </div>