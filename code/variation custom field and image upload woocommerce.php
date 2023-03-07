<?php

// 1. Add custom field input @ Product Data > Variations > Single Variation
add_action( 'woocommerce_variation_options_pricing', 'bbloomer_add_custom_field_to_variations', 10, 3 );
function bbloomer_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
   woocommerce_wp_text_input( array(
'id' => 'custom_field[' . $loop . ']',
'class' => 'short',
'label' => __( 'Custom Field for gallery', 'woocommerce' ),
'value' => get_post_meta( $variation->ID, 'custom_field', true )
   ) );
}

// 2. Save custom field on product variation save
add_action( 'woocommerce_save_product_variation', 'bbloomer_save_custom_field_variations', 10, 2 );
function bbloomer_save_custom_field_variations( $variation_id, $i ) {
   $custom_field = $_POST['custom_field'][$i];
   if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'custom_field', esc_attr( $custom_field ) );
}

// 3. Store custom field value into variation data
add_filter( 'woocommerce_available_variation', 'bbloomer_add_custom_field_variation_data' );
function bbloomer_add_custom_field_variation_data( $variations ) {
   $variations['custom_field'] = '<div class="woocommerce_custom_field">Custom Field: <span>' . get_post_meta( $variations[ 'variation_id' ], 'custom_field', true ) . '</span></div>';
   return $variations;
}

//Add extra image upload field for product variations in WooCommerce backend product edit pages
//Product Variations Upload Custom Image Field

add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

function variation_settings_fields( $loop, $variation_data, $variation ) {
    $my_custom_image_field = $variation_data['my_custom_image_field'][0] ?? null;
    ?>
    <p class="form-row form-row-first upload_my_custom_field">
        <a
            href="#"
            class="upload_my_custom_field_button tips <?php echo $my_custom_image_field ? 'remove' : ''; ?>"
            data-tip="<?php echo $my_custom_image_field ? esc_attr__( 'Remove this image', 'woocommerce' ) : esc_attr__( 'Upload an image', 'woocommerce' ); ?>"
            rel="<?php echo esc_attr( $variation->ID ); ?>">
            <img width="80" src="<?php echo $my_custom_image_field ? esc_url( wp_get_attachment_thumb_url( $my_custom_image_field ) ) : esc_url( wc_placeholder_img_src() ); ?>" />
            <input
                type="hidden"
                name="upload_my_custom_image_field[<?php echo esc_attr( $loop ); ?>][0]"
                class="upload_my_custom_image_field" value="<?php echo esc_attr( $my_custom_image_field ); ?>" />
        </a>
    </p>
    <?php
}

function save_variation_settings_fields( $variation_id, $loop ) {
    if (isset( $_POST['upload_my_custom_image_field'][ $loop ] )) {
        $value = wc_clean( wp_unslash( $_POST['upload_my_custom_image_field'][ $loop ] ) );
        update_post_meta( $variation_id, 'my_custom_image_field', esc_attr( $value ));
    }
}

function product_variation_img_script() {
$screen = get_current_screen();
    if ($screen->post_type === 'product') :
        ?>
        <style>
            .upload_my_custom_field_button:focus {
                outline: none !important;
                box-shadow: none !important;
            }
        </style>
        <script>
           (function($) {
        var settings = {
            setting_variation_image: null,
            setting_variation_image_id: null
        }
        function add_my_custom_field(event) {
            var $button = $( this ),
                post_id = $button.attr( 'rel' ),
                $parent = $button.closest( '.upload_my_custom_field' );

            settings.setting_variation_image    = $parent;
            settings.setting_variation_image_id = post_id;

            event.preventDefault();
            
            if ( $button.hasClass('remove')) {
                console.log("remove");
                $( '.upload_my_custom_image_field', settings.setting_variation_image ).val( '' ).trigger( 'change' );
                settings.setting_variation_image.find( 'img' ).eq( 0 )
                    .attr( 'src', woocommerce_admin_meta_boxes_variations.woocommerce_placeholder_img_src );
                settings.setting_variation_image.find( '.upload_my_custom_field_button' ).removeClass( 'remove' );

            } else {
                
                // If the media frame already exists, reopen it.
                if ( settings.variable_image_frame ) {
                    settings.variable_image_frame.uploader.uploader
                        .param( 'post_id', settings.setting_variation_image_id );
                    settings.variable_image_frame.open();
                    return;
                } else {
                    wp.media.model.settings.post.id = settings.setting_variation_image_id;
                }

                // Create the media frame.
                settings.variable_image_frame = wp.media.frames.variable_image = wp.media({
                    // Set the title of the modal.
                    title: woocommerce_admin_meta_boxes_variations.i18n_choose_image,
                    button: {
                        text: woocommerce_admin_meta_boxes_variations.i18n_set_image
                    },
                    states: [
                        new wp.media.controller.Library({
                            title: woocommerce_admin_meta_boxes_variations.i18n_choose_image,
                            filterable: 'all'
                        })
                    ]
                });

                // When an image is selected, run a callback.
                settings.variable_image_frame.on( 'select', function () {

                    var attachment = settings.variable_image_frame.state()
                        .get( 'selection' ).first().toJSON(),
                        url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    $( '.upload_my_custom_image_field', settings.setting_variation_image ).val( attachment.id )
                        .trigger( 'change' );
                    settings.setting_variation_image.find( '.upload_my_custom_field_button' ).addClass( 'remove' );
                    settings.setting_variation_image.find( 'img' ).eq( 0 ).attr( 'src', url );

                    wp.media.model.settings.post.id = settings.wp_media_post_id;
                });

                // Finally, open the modal.
                settings.variable_image_frame.open();
            }
        }

        $(document).on('click', '.upload_my_custom_field_button', add_my_custom_field);
        })(jQuery)
        </script>
<?php
    endif;
}
add_action('admin_footer', 'product_variation_img_script');
