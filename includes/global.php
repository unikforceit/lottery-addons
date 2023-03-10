<?php
/**
 * Enqueue JavaScript and CSS files for the plugin
 */
function lottery_addons_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-slider');
    // Enqueue JavaScript file
    wp_enqueue_script('lottery-addons-plugin', LotteryAddons_PLUG_URL . 'includes/assets/global/global.js', array('jquery'), '1.0', true);

    // Enqueue CSS file
    wp_enqueue_style('lottery-addons-plugin', LotteryAddons_PLUG_URL . 'includes/assets/global/global.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'lottery_addons_enqueue_scripts');

remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_date_ranges_template', 10);
add_action('lty_after_add_to_cart_form', 'LTY_Lottery_Single_Product_Templates::render_date_ranges_template', 10);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_progress_bar_template', 30);
add_action('lty_after_add_to_cart_form', 'LTY_Lottery_Single_Product_Templates::render_progress_bar_template', 10);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_tickets_status_template', 5);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_minimum_tickets_notice_template', 15);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_maximum_tickets_notice_template', 15);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_maximum_tickets_per_user_notice_template', 15);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_minimum_tickets_per_user_notice_template', 15);
remove_action('lty_lottery_single_product_content', 'LTY_Lottery_Single_Product_Templates::render_gift_product_notice_template', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 10);
function remove_product_meta() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}
add_action( 'woocommerce_single_product_summary', 'remove_product_meta', 5 );

function remove_product_title_from_structure( $structure ) {
    $key = array_search( 'title', $structure );
    if ( false !== $key ) {
        unset( $structure[ $key ] );
    }
    return $structure;
}

add_filter( 'astra_woo_single_product_structure', 'remove_product_title_from_structure', 15 );
function remove_product_meta_from_structure( $structure ) {
    $key = array_search( 'meta', $structure );
    if ( false !== $key ) {
        unset( $structure[ $key ] );
    }
    return $structure;
}

add_filter( 'astra_woo_single_product_structure', 'remove_product_meta_from_structure', 15 );
function remove_product_des_from_structure( $structure ) {
    $key = array_search( 'short_desc', $structure );
    if ( false !== $key ) {
        unset( $structure[ $key ] );
    }
    return $structure;
}

add_filter( 'astra_woo_single_product_structure', 'remove_product_des_from_structure', 15 );
function remove_product_category_from_structure( $structure ) {
    $key = array_search( 'category', $structure );
    if ( false !== $key ) {
        unset( $structure[ $key ] );
    }
    return $structure;
}

add_filter( 'astra_woo_single_product_structure', 'remove_product_category_from_structure', 15 );
//remove woocommere breadcrumb
remove_action('woocommerce_before_main_content' , 'woocommerce_breadcrumb', 20);

//remove woocommere page Title
add_filter( 'woocommerce_show_page_title', '__return_false');

//remove woocommere result count
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

//remove woocommere sale badge
add_filter('woocommerce_before_shop_loop_item_title', '__return_false');

// Change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_add_to_cart_button_text_single' );
function woocommerce_add_to_cart_button_text_single() {
    return __( 'Enter Now', 'woocommerce' );
}

// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives' );
function woocommerce_add_to_cart_button_text_archives() {
    return __( 'Enter Now', 'woocommerce' );
}
function add_quantity_slider() {
    global $product;

    $min_value = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
    $max_value = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
    $step      = apply_filters( 'woocommerce_quantity_input_step', 1, $product );
    $value     = wc_stock_amount( $product->get_min_purchase_quantity() );

    ?>
    <div class="quantity-range-slider">
        <input type="button" value="-" class="minus">
        <span class="min-qty"><?php echo esc_attr( $min_value ); ?></span>
        <input type="range" id="quantity" class="" name="quantity" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" step="<?php echo esc_attr( $step ); ?>" value="<?php echo esc_attr( $value ); ?>">
        <span class="max-qty"><?php echo esc_attr( $max_value ); ?></span>
        <input type="button" value="+" class="plus">
        <span class="quantity_slide"><?php echo esc_html( $value ); ?></span>
    </div>
    <?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_quantity_slider' );
function add_hidden_quantity_field() {
    global $product;
    echo '<input type="hidden" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '">';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_hidden_quantity_field' );
//shop page title ordering
function title_ordering_astra_woo_shop_product_structure($field){
    $title = $field[1];
    $add_cart = $field[4];
    unset($field[1]);
    unset($field[4]);
    $field[1] = $title;
    $field[4] = $add_cart;
    return $field;
}
add_filter('astra_woo_shop_product_structure', 'title_ordering_astra_woo_shop_product_structure');
add_filter( 'lty_lottery_product_participate_now_text', 'lty_single_product_page_add_to_cart_button_text_change' );
function lty_single_product_page_add_to_cart_button_text_change() {
    return __( 'Add To Basket', 'lotteryaddons' );
}
