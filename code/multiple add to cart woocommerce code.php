<?php /* this file goes to variation-add-to-cart-button.php file */
$cf_currncy = get_woocommerce_currency_symbol();
$args = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => 10,
    'tax_query'             => array(
										array(
											'taxonomy'      => 'product_cat',
											'field' => 'term_id', 
											'terms'         => 30,
											'operator'      => 'IN' 
										)
        
                                     )
     );
$addOnProdObj = new WP_Query($args);
//echo "<pre>";print_r($addOnProdObj);

?>
<?php if($addOnProdObj->have_posts()){ ?>
<div class="cf_product_product_addon_wrap">
	<ul class="product_addon_list">
	<?php while($addOnProdObj->have_posts()){  $addOnProdObj->the_post(); 
	      $regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
		  $sale_price = get_post_meta( get_the_ID(), '_price', true);
		  $productObj = wc_get_product(get_the_ID());
		  $addonProdstatus = $productObj->is_in_stock() ? "enabled" :  "disabled";

	?>
		<li class="cf_addon_<?php echo $addonProdstatus; ?>">
			<input type="checkbox" class="cf_product_addon" name="cf_product_addon[]" value="<?php echo get_the_id(); ?>" <?php echo $addonProdstatus; ?>><?php echo get_the_title(); ?>
			<div class="cf_addon_price_wrap">
				<?php if($sale_price){ ?>
					<span class="cf_regular_price crossed"><del><?php echo $cf_currncy.$regular_price; ?></del></span>
					<span class="cf_sale_price"><?php echo $cf_currncy.$sale_price; ?></span>
				<?php }else{ ?>
					<span class="cf_regular_price"><?php echo $cf_currncy.$regular_price; ?></span>
                  
				<?php } ?>
				
			</div>
			<div class="cf_addon_qty_wrap"><input type="number" class="cf_addon_qty" name="cf_addon_qty[]" value="1" min="1" <?php if($productObj->managing_stock() && $productObj->get_stock_quantity() > 0){ ?>max="<?php echo $productObj->get_stock_quantity(); ?>" <?php } ?>></div>
	   </li>
	<?php } ?>	
    </ul>	
</div>
<?php } ?>
<?php 
/********************** Multiple add to cart with checkbox this file goes to function.php *******************/
function woocommerce_maybe_add_multiple_products_to_cart() {
  
  //echo "<pre>";print_r($_REQUEST['cf_product_addon']);die('hhhhhhhhhh');
  //echo $_REQUEST['add-to-cart'];die;
  if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['cf_product_addon'] ) ) {
      return;
  }

  remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

  $product_ids =  $_REQUEST['cf_product_addon'];
  $product_ids[] = $_REQUEST['add-to-cart'];
  $count       = count( $product_ids );
  $number      = 0;
  $quantities = $_REQUEST['cf_addon_qty'];
  $quantities[] = $_REQUEST['quantity'];
    //print_r($product_ids);
    //print_r($quantities);die;
  //print_r($_REQUEST['quantity']);die;

  foreach ( $product_ids as $product_id ) {
      if ( ++$number === $count ) {
          // Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
          $_REQUEST['add-to-cart'] = $product_id;

          return WC_Form_Handler::add_to_cart_action();
      }

      $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
      $was_added_to_cart = false;

      $adding_to_cart    = wc_get_product( $product_id );

      if ( ! $adding_to_cart ) {
          continue;
      }

      if ( $adding_to_cart->is_type( 'simple' ) ) {

          // quantity applies to all products atm
          //$quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] ); 
		  $_REQUEST['quantity'] = ! empty( $quantities[$number] ) ? absint( $quantities[$number] ) : 1;
          echo $quantity          = empty( $quantities[$number - 1] ) ? 1 : wc_stock_amount(  $quantities[$number - 1] );
          $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

          if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
              wc_add_to_cart_message( array( $product_id => $quantity ), true );
          }

      } else {

          $variation_id       = empty( $_REQUEST['variation_id'] ) ? '' : absint( wp_unslash( $_REQUEST['variation_id'] ) );
          $quantity           = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ); // WPCS: sanitization ok.
          $missing_attributes = array();
          $variations         = array();
          $adding_to_cart     = wc_get_product( $product_id );

          if ( ! $adding_to_cart ) {
            continue;
          }

          // If the $product_id was in fact a variation ID, update the variables.
          if ( $adding_to_cart->is_type( 'variation' ) ) {
            $variation_id   = $product_id;
            $product_id     = $adding_to_cart->get_parent_id();
            $adding_to_cart = wc_get_product( $product_id );

            if ( ! $adding_to_cart ) {
              continue;
            }
          }

          // Gather posted attributes.
          $posted_attributes = array();

          foreach ( $adding_to_cart->get_attributes() as $attribute ) {
            if ( ! $attribute['is_variation'] ) {
              continue;
            }
            $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );

            if ( isset( $_REQUEST[ $attribute_key ] ) ) {
              if ( $attribute['is_taxonomy'] ) {
                // Don't use wc_clean as it destroys sanitized characters.
                $value = sanitize_title( wp_unslash( $_REQUEST[ $attribute_key ] ) );
              } else {
                $value = html_entity_decode( wc_clean( wp_unslash( $_REQUEST[ $attribute_key ] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) ); // WPCS: sanitization ok.
              }

              $posted_attributes[ $attribute_key ] = $value;
            }
          }

          // If no variation ID is set, attempt to get a variation ID from posted attributes.
          if ( empty( $variation_id ) ) {
            $data_store   = WC_Data_Store::load( 'product' );
            $variation_id = $data_store->find_matching_product_variation( $adding_to_cart, $posted_attributes );
          }

          // Do we have a variation ID?
          if ( empty( $variation_id ) ) {
            throw new Exception( __( 'Please choose product options&hellip;', 'woocommerce' ) );
          }

          // Check the data we have is valid.
          $variation_data = wc_get_product_variation_attributes( $variation_id );

          foreach ( $adding_to_cart->get_attributes() as $attribute ) {
            if ( ! $attribute['is_variation'] ) {
              continue;
            }

            // Get valid value from variation data.
            $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );
            $valid_value   = isset( $variation_data[ $attribute_key ] ) ? $variation_data[ $attribute_key ]: '';

            /**
             * If the attribute value was posted, check if it's valid.
             *
             * If no attribute was posted, only error if the variation has an 'any' attribute which requires a value.
             */
            if ( isset( $posted_attributes[ $attribute_key ] ) ) {
              $value = $posted_attributes[ $attribute_key ];

              // Allow if valid or show error.
              if ( $valid_value === $value ) {
                $variations[ $attribute_key ] = $value;
              } elseif ( '' === $valid_value && in_array( $value, $attribute->get_slugs() ) ) {
                // If valid values are empty, this is an 'any' variation so get all possible values.
                $variations[ $attribute_key ] = $value;
              } else {
                throw new Exception( sprintf( __( 'Invalid value posted for %s', 'woocommerce' ), wc_attribute_label( $attribute['name'] ) ) );
              }
            } elseif ( '' === $valid_value ) {
              $missing_attributes[] = wc_attribute_label( $attribute['name'] );
            }
          }
          if ( ! empty( $missing_attributes ) ) {
            throw new Exception( sprintf( _n( '%s is a required field', '%s are required fields', count( $missing_attributes ), 'woocommerce' ), wc_format_list_of_items( $missing_attributes ) ) );
          }

        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

        if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
          wc_add_to_cart_message( array( $product_id => $quantity ), true );
        }
      }
  }
}
add_action( 'wp_loaded', 'woocommerce_maybe_add_multiple_products_to_cart', 15 );