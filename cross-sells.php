<?php
    ?>
    <main class="cross-sell archive">
        <div class="cross-sell-wrap clearfix hpad wrap">
            <?php
            do_action('before_sold_product');
            global $woocommerce;

            $id = $_GET['id'];

            if (isset($id) && is_numeric($_GET['id'])): ?>
            <div class="cross-sell-header">
                <?php
                $args = array(
                    'p'         => $id,
                    'post_type' => 'product',
                );
                $loop = new WP_Query( $args );

                $i = 0;

                while ( $loop->have_posts() && $i < 1) : $loop->the_post(); global $product;

                    $i++;
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'thumbnail' );?>
                    <h1><?php echo $loop->post->post_title; ?></h1>
                    <img src="<?php  echo $image[0]; ?>" data-id="<?php echo $loop->post->ID; ?>" style="padding-top: 20px;">

                <?php endwhile; ?>

                <div class="item-info">

                </div>
                <div class="item-prossed">
                    <span>Handlekurv-sum er <?php echo $woocommerce->cart->get_cart_total(); ?></span>
                    <a href="<?php echo get_home_url(); ?>">Handle mer</a>
                    <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button">Handlekurven</a>
                    <a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button">Gå til kassen</a>
                </div>
            </div>
            <?php else: ?>
                <h1>This isn't the page for you.</h1>
            <?php endif;

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
    </main>