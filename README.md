# Add To Cart plugin for WooCommerce by Upturn Agency
This plugin redirects the user to a page that shows cross sell products, bestsellers, news and products on sale.

## Features
### Display a countdown
You can display a reservation countdown below the product name to rush the customers to place their order. You can set the number of minutes to count down from in the plugin settings.

## Install instructions
1. Upload the plugin zip to your WordPress installation
2. Set your preferred settings under WooCommerce -> Settings -> Products -> Cross sells
3. Deactivate "Enable AJAX add to cart buttons on archives" under WooCommerce -> Settings -> Products -> Display

## How to add content to the cross sell page
We have added many actions you can hook into in the plugin.

- before_cross_sell_actions
- before_cross_sell_page
- before_cross_sell_location_1
- before_cross_sell_location_2
- before_cross_sell_location_3
- before_cross_sell_location_4

```php
add_action( 'before_cross_sell_page', 'custom_function' );
function custom_function() {
    // DO SOMETHING HERE
}
```

## Requirements
- WooCommerce 3.0.0 or higher
- PHP 7 or higher

## Contribute
Styling of this plugin is done with .scss, and compiled with grunt.
To install grunt and needed modules run `npm install` in the `/assets` folder. Then run `grunt`.

To contribute make a fork of this repo, make your changes, and make a pull request