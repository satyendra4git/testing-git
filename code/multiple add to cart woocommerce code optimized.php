<?php /*************** this code goes to simple.php or/and variation-add-to-cart-button.php *******************/
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
			<div class="cf_addon_qty_wrap"><input type="number" class="cf_addon_qty" name="cf_addon_qty[<?php echo get_the_id(); ?>]" value="1" min="1" <?php if($productObj->managing_stock() && $productObj->get_stock_quantity() > 0){ ?>max="<?php echo $productObj->get_stock_quantity(); ?>" <?php } ?>></div>
	   </li>
	<?php } ?>	
    </ul>	
</div>
<?php } ?>
<?php 
/********************** Multiple add to cart with checkbox - this code goes to functions.php *******************/
function woocommerce_maybe_add_multiple_products_to_cart($cart_item_key,$prod_id) {
  
  //echo "<pre>";print_r($_REQUEST['cf_product_addon']);die('hhhhhhhhhh');
  //echo $_REQUEST['add-to-cart'];die;
  if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['cf_product_addon'] ) ) {
      return;
  }
  //echo $product_id;
  
  /** get  ids of addon products */

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

 $addOnProdObj = get_posts($args); 
 //echo "<pre>";print_r($addOnProdObj);die;
 $addOnProdIds = [];

 if(count($addOnProdObj)){
	foreach($addOnProdObj as $addonprod){ 
		$addOnProdIds[] = $addonprod->ID;
	}
 }else{
	return;
 }
  
  $product_ids =  $_REQUEST['cf_product_addon'];

  $quantities = $_REQUEST['cf_addon_qty']; 

   if(!in_array($prod_id,$addOnProdIds) && count( $product_ids ) > 0){
	foreach ( $product_ids as $product_id ) {
		 ++$number;
		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$was_added_to_cart = false;

		$adding_to_cart    = wc_get_product( $product_id );

		if ( ! $adding_to_cart ) {
			continue;
		}

		if ( $adding_to_cart->is_type( 'simple' ) ) {

			// quantity applies to all products atm
		
			$quantity          = isset( $quantities[$product_id] ) ?  wc_stock_amount(  $quantities[$product_id] ) : 1;
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

			if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

		}
	}
}
}
add_action( 'woocommerce_add_to_cart', 'woocommerce_maybe_add_multiple_products_to_cart', 15, 2); 