<?php
// create healthdish settings menu
add_action('admin_menu', 'my_healthdish_create_menu');

function my_healthdish_create_menu() {

	//create new top-level default settings menu page
	add_menu_page('Ad Settings', 'Ad Settings', 'administrator', "healthdish-settings", 'my_healthdish_default_settings_page' , 'dashicons-admin-tools' );
    add_submenu_page( 'healthdish-settings', 'MP3 Layout', 'MP3 Layout', 'manage_options', 'healthdish-settings-mp3-layout', 'my_healthdish_settings_mp3_layout_page' ); 
    add_submenu_page( 'healthdish-settings', 'MP5 Layout', 'MP5 Layout', 'manage_options', 'healthdish-settings-mp5-layout', 'my_healthdish_settings_mp5_layout_page' ); 
    add_submenu_page( 'healthdish-settings', 'MP8 Layout', 'MP8 Layout', 'manage_options', 'healthdish-settings-mp8-layout', 'my_healthdish_settings_mp8_layout_page' ); 
    add_submenu_page( 'healthdish-settings', 'MP9 Layout', 'MP9 Layout', 'manage_options', 'healthdish-settings-mp9-layout', 'my_healthdish_settings_mp9_layout_page' ); 

	//call register settings function
	add_action( 'admin_init', 'register_my_healthdish_settings' );
}

//enable color picker
add_action( 'admin_enqueue_scripts', 'healthdish_enqueue_color_picker', 20);
function healthdish_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'healthdish-color-picker-handle', get_stylesheet_directory_uri().'/assets/js/settings-color-picker-script.js', array( 'wp-color-picker' ), false, true );
}

function register_my_healthdish_settings() {
	//register our default settings
	register_setting( 'healthdish-settings-default-layout', 'default_border_on_ad_placeholder' );
	register_setting( 'healthdish-settings-default-layout', 'default_background_color_of_add_placeholder' );
    register_setting( 'healthdish-settings-default-layout', 'default_make_bg_color_transparent' );
	register_setting( 'healthdish-settings-default-layout', 'default_ads_before_the_title' );
    register_setting( 'healthdish-settings-default-layout', 'default_ads_after_the_image' );
    register_setting( 'healthdish-settings-default-layout', 'default_ads_after_each_n_characters' );
    register_setting( 'healthdish-settings-default-layout', 'default_show_left_rail_ads' );
    register_setting( 'healthdish-settings-default-layout', 'default_show_right_rail_ads' );
    register_setting( 'healthdish-settings-default-layout', 'default_show_anchor_ads' );
    register_setting( 'healthdish-settings-default-layout', 'default_show_anchor_ad_on_the_slide' );
    register_setting( 'healthdish-settings-default-layout', 'default_advertisement_text_placement' );
    //register our mp3 settings
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_border_on_ad_placeholder' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_background_color_of_add_placeholder' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_make_bg_color_transparent' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_ads_before_the_title' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_ads_after_the_image' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_ads_after_each_n_characters' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_show_left_rail_ads' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_show_right_rail_ads' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_show_anchor_ads' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_show_anchor_ad_on_the_slide' );
    register_setting( 'healthdish-settings-mp3-layout', 'mp3_advertisement_text_placement' );
    //register our mp5 settings
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_border_on_ad_placeholder' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_background_color_of_add_placeholder' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_make_bg_color_transparent' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_ads_before_the_title' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_ads_after_the_image' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_ads_after_each_n_characters' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_show_left_rail_ads' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_show_right_rail_ads' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_show_anchor_ads' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_show_anchor_ad_on_the_slide' );
    register_setting( 'healthdish-settings-mp5-layout', 'mp5_advertisement_text_placement' );
    //register our mp8 settings
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_border_on_ad_placeholder' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_background_color_of_add_placeholder' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_make_bg_color_transparent' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_ads_before_the_title' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_ads_after_the_image' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_ads_after_each_n_characters' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_show_left_rail_ads' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_show_right_rail_ads' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_show_anchor_ads' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_show_anchor_ad_on_the_slide' );
    register_setting( 'healthdish-settings-mp8-layout', 'mp8_advertisement_text_placement' );
    //register our mp9 settings
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_border_on_ad_placeholder' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_background_color_of_add_placeholder' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_make_bg_color_transparent' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_ads_before_the_title' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_ads_after_the_image' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_ads_after_each_n_characters' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_show_left_rail_ads' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_show_right_rail_ads' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_show_anchor_ads' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_show_anchor_ad_on_the_slide' );
    register_setting( 'healthdish-settings-mp9-layout', 'mp9_advertisement_text_placement' );
    
}

function my_healthdish_default_settings_page() {
?>
<div class="default_settings_wrap">
<h1>Default Layout Settings</h1>
<?php isset($_POST['submit']){ ?>
<div class="notice notice-success is-dismissible">Setting has been saved successfully</div>
<?php } ?>
<form method="post" action="options.php">
    <?php settings_fields( 'healthdish-settings-default-layout' ); ?>
    <?php do_settings_sections( 'healthdish-settings-default-layout' ); ?>
    <?php //echo get_option('default_make_bg_color_transparent'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Border on Ad place holder</th>
        <td>
            Yes <input type="radio" class="default_border_on_ad_placeholder" id="default_border_on_ad_placeholder_yes" name="default_border_on_ad_placeholder" value="yes" <?php if((get_option('default_border_on_ad_placeholder')=="") || (get_option('default_border_on_ad_placeholder')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_border_on_ad_placeholder" id="default_border_on_ad_placeholder_no" name="default_border_on_ad_placeholder" value="no" <?php if((get_option('default_border_on_ad_placeholder')!="") && (get_option('default_border_on_ad_placeholder')=="no")){ ?>checked<?php } ?>>
        
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Background Color of Ad Place holder</th>
        <td><input type="text" class="default_bg_color_ads healthdish_bg_color_pick" id="default_bg_color_ads" name="default_background_color_of_add_placeholder" value="<?php echo esc_attr( get_option('default_background_color_of_add_placeholder') ); ?>" data-default-color="#effeff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Make background Color transparent?</th>
        <td>
            Yes <input type="radio" class="default_make_bg_color_transparent" id="default_make_bg_color_transparent_yes" name="default_make_bg_color_transparent" value="yes" <?php if((get_option('default_make_bg_color_transparent')=="") || (get_option('default_make_bg_color_transparent')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_make_bg_color_transparent" id="default_make_bg_color_transparent_no" name="default_make_bg_color_transparent" value="no" <?php if((get_option('default_make_bg_color_transparent')!="") && (get_option('default_make_bg_color_transparent')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Ads before the title</th>
        <td>
            Yes <input type="radio" class="default_ads_before_the_title" id="default_ads_before_the_title_yes" name="default_ads_before_the_title" value="yes" <?php if((get_option('default_ads_before_the_title')=="") || (get_option('default_ads_before_the_title')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_ads_before_the_title" id="default_ads_before_the_title_no" name="default_ads_before_the_title" value="no" <?php if((get_option('default_ads_before_the_title')!="") && (get_option('default_ads_before_the_title')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after the image</th>
        <td>
            Yes <input type="radio" class="default_ads_after_the_image" id="default_ads_after_the_image_yes" name="default_ads_after_the_image" value="yes" <?php if((get_option('default_ads_after_the_image')=="") || (get_option('default_ads_after_the_image')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_ads_after_the_image" id="default_ads_after_the_image_no" name="default_ads_after_the_image" value="no" <?php if((get_option('default_ads_after_the_image')!="") && (get_option('default_ads_after_the_image')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after each 'N' charachers</th>
        <td><input type="number" name="default_ads_after_each_n_characters" value="<?php if(get_option('default_ads_after_each_n_characters')==""){ echo '230';}else{ echo esc_attr( get_option('default_ads_after_each_n_characters') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show left rail Ads</th>
        <td>
            Yes <input type="radio" class="default_show_left_rail_ads" id="default_show_left_rail_ads_yes" name="default_show_left_rail_ads" value="yes" <?php if((get_option('default_show_left_rail_ads')=="") || (get_option('default_show_left_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_show_left_rail_ads" id="default_show_left_rail_ads_no" name="default_show_left_rail_ads" value="no" <?php if((get_option('default_show_left_rail_ads')!="") && (get_option('default_show_left_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show right rail Ads</th>
        <td>
            Yes <input type="radio" class="default_show_right_rail_ads" id="default_show_right_rail_ads_yes" name="default_show_right_rail_ads" value="yes" <?php if((get_option('default_show_right_rail_ads')=="") || (get_option('default_show_right_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_show_right_rail_ads" id="default_show_right_rail_ads_no" name="default_show_right_rail_ads" value="no" <?php if((get_option('default_show_right_rail_ads')!="") && (get_option('default_show_right_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ads</th>
        <td>
            Yes <input type="radio" class="default_show_anchor_ads" id="default_show_anchor_ads_yes" name="default_show_anchor_ads" value="yes" <?php if((get_option('default_show_anchor_ads')=="") || (get_option('default_show_anchor_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="default_show_anchor_ads" id="default_show_anchor_ads_no" name="default_show_anchor_ads" value="no" <?php if((get_option('default_show_anchor_ads')!="") && (get_option('default_show_anchor_ads')=="no")){ ?>checked<?php } ?>>
        </td>

        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ad on the slide</th>
        <td><input type="number" name="default_show_anchor_ad_on_the_slide" value="<?php if(get_option('default_show_anchor_ad_on_the_slide')==""){ echo '1';}else{ echo esc_attr( get_option('default_show_anchor_ad_on_the_slide') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Advertisement text placement</th>
        <td>
            Left <input type="radio" class="default_advertisement_text_placement" id="default_advertisement_text_placement_left" name="default_advertisement_text_placement" value="left" <?php if((get_option('default_advertisement_text_placement')=="") || (get_option('default_advertisement_text_placement')=="left")){ ?>checked<?php } ?>><br>
            Center <input type="radio" class="default_advertisement_text_placement" id="default_advertisement_text_placement_center" name="default_advertisement_text_placement" value="center" <?php if((get_option('default_advertisement_text_placement')!="") && (get_option('default_advertisement_text_placement')=="center")){ ?>checked<?php } ?>><br>
            Right <input type="radio" class="default_show_anchor_ads" id="default_advertisement_text_placement_right" name="default_advertisement_text_placement" value="right" <?php if((get_option('default_advertisement_text_placement')!="") && (get_option('default_advertisement_text_placement')=="right")){ ?>checked<?php } ?>>
        </td>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
<?php 
  //Mp3 layout page callback
  function my_healthdish_settings_mp3_layout_page(){
?>
   <div class="mp3_settings_wrap">
    <h1>MP3 Layout Settings</h1>
    <form method="post" action="options.php">
    <?php settings_fields( 'healthdish-settings-mp3-layout' ); ?>
    <?php do_settings_sections( 'healthdish-settings-mp3-layout' ); ?>
    <?php //echo get_option('mp3_make_bg_color_transparent'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Border on Ad place holder</th>
        <td>
            Yes <input type="radio" class="mp3_border_on_ad_placeholder" id="mp3_border_on_ad_placeholder_yes" name="mp3_border_on_ad_placeholder" value="yes" <?php if((get_option('mp3_border_on_ad_placeholder')=="") || (get_option('mp3_border_on_ad_placeholder')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_border_on_ad_placeholder" id="mp3_border_on_ad_placeholder_no" name="mp3_border_on_ad_placeholder" value="no" <?php if((get_option('mp3_border_on_ad_placeholder')!="") && (get_option('mp3_border_on_ad_placeholder')=="no")){ ?>checked<?php } ?>>
        
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Background Color of Ad Place holder</th>
        <td><input type="text" class="mp3_bg_color_ads healthdish_bg_color_pick" id="mp3_bg_color_ads" name="mp3_background_color_of_add_placeholder" value="<?php echo esc_attr( get_option('mp3_background_color_of_add_placeholder') ); ?>" data-default-color="#effeff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Make background Color transparent?</th>
        <td>
            Yes <input type="radio" class="mp3_make_bg_color_transparent" id="mp3_make_bg_color_transparent_yes" name="mp3_make_bg_color_transparent" value="yes" <?php if((get_option('mp3_make_bg_color_transparent')=="") || (get_option('mp3_make_bg_color_transparent')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_make_bg_color_transparent" id="mp3_make_bg_color_transparent_no" name="mp3_make_bg_color_transparent" value="no" <?php if((get_option('mp3_make_bg_color_transparent')!="") && (get_option('mp3_make_bg_color_transparent')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Ads before the title</th>
        <td>
            Yes <input type="radio" class="mp3_ads_before_the_title" id="mp3_ads_before_the_title_yes" name="mp3_ads_before_the_title" value="yes" <?php if((get_option('mp3_ads_before_the_title')=="") || (get_option('mp3_ads_before_the_title')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_ads_before_the_title" id="mp3_ads_before_the_title_no" name="mp3_ads_before_the_title" value="no" <?php if((get_option('mp3_ads_before_the_title')!="") && (get_option('mp3_ads_before_the_title')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after the image</th>
        <td>
            Yes <input type="radio" class="mp3_ads_after_the_image" id="mp3_ads_after_the_image_yes" name="mp3_ads_after_the_image" value="yes" <?php if((get_option('mp3_ads_after_the_image')=="") || (get_option('mp3_ads_after_the_image')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_ads_after_the_image" id="mp3_ads_after_the_image_no" name="mp3_ads_after_the_image" value="no" <?php if((get_option('mp3_ads_after_the_image')!="") && (get_option('mp3_ads_after_the_image')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after each 'N' charachers</th>
        <td><input type="number" name="mp3_ads_after_each_n_characters" value="<?php if(get_option('mp3_ads_after_each_n_characters')==""){ echo '230';}else{ echo esc_attr( get_option('mp3_ads_after_each_n_characters') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show left rail Ads</th>
        <td>
            Yes <input type="radio" class="mp3_show_left_rail_ads" id="mp3_show_left_rail_ads_yes" name="mp3_show_left_rail_ads" value="yes" <?php if((get_option('mp3_show_left_rail_ads')=="") || (get_option('mp3_show_left_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_show_left_rail_ads" id="mp3_show_left_rail_ads_no" name="mp3_show_left_rail_ads" value="no" <?php if((get_option('mp3_show_left_rail_ads')!="") && (get_option('mp3_show_left_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show right rail Ads</th>
        <td>
            Yes <input type="radio" class="mp3_show_right_rail_ads" id="mp3_show_right_rail_ads_yes" name="mp3_show_right_rail_ads" value="yes" <?php if((get_option('mp3_show_right_rail_ads')=="") || (get_option('mp3_show_right_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_show_right_rail_ads" id="mp3_show_right_rail_ads_no" name="mp3_show_right_rail_ads" value="no" <?php if((get_option('mp3_show_right_rail_ads')!="") && (get_option('mp3_show_right_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ads</th>
        <td>
            Yes <input type="radio" class="mp3_show_anchor_ads" id="mp3_show_anchor_ads_yes" name="mp3_show_anchor_ads" value="yes" <?php if((get_option('mp3_show_anchor_ads')=="") || (get_option('mp3_show_anchor_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp3_show_anchor_ads" id="mp3_show_anchor_ads_no" name="mp3_show_anchor_ads" value="no" <?php if((get_option('mp3_show_anchor_ads')!="") && (get_option('mp3_show_anchor_ads')=="no")){ ?>checked<?php } ?>>
        </td>

        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ad on the slide</th>
        <td><input type="number" name="mp3_show_anchor_ad_on_the_slide" value="<?php if(get_option('mp3_show_anchor_ad_on_the_slide')==""){ echo '1';}else{ echo esc_attr( get_option('mp3_show_anchor_ad_on_the_slide') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Advertisement text placement</th>
        <td>
            Left <input type="radio" class="mp3_advertisement_text_placement" id="mp3_advertisement_text_placement_left" name="mp3_advertisement_text_placement" value="left" <?php if((get_option('mp3_advertisement_text_placement')=="") || (get_option('mp3_advertisement_text_placement')=="left")){ ?>checked<?php } ?>><br>
            Center <input type="radio" class="mp3_advertisement_text_placement" id="mp3_advertisement_text_placement_center" name="mp3_advertisement_text_placement" value="center" <?php if((get_option('mp3_advertisement_text_placement')!="") && (get_option('mp3_advertisement_text_placement')=="center")){ ?>checked<?php } ?>><br>
            Right <input type="radio" class="mp3_show_anchor_ads" id="mp3_advertisement_text_placement_right" name="mp3_advertisement_text_placement" value="right" <?php if((get_option('mp3_advertisement_text_placement')!="") && (get_option('mp3_advertisement_text_placement')=="right")){ ?>checked<?php } ?>>
        </td>
    </table>
    
    <?php submit_button(); ?>
   </div>
<?php
  }
?>

<?php 
  //Mp5 layout page callback
  function my_healthdish_settings_mp5_layout_page(){
?>
   <div class="mp5_settings_wrap">
     <h1>MP5 Layout Settings</h1>
     <form method="post" action="options.php">
    <?php settings_fields( 'healthdish-settings-mp5-layout' ); ?>
    <?php do_settings_sections( 'healthdish-settings-mp5-layout' ); ?>
    <?php //echo get_option('mp5_make_bg_color_transparent'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Border on Ad place holder</th>
        <td>
            Yes <input type="radio" class="mp5_border_on_ad_placeholder" id="mp5_border_on_ad_placeholder_yes" name="mp5_border_on_ad_placeholder" value="yes" <?php if((get_option('mp5_border_on_ad_placeholder')=="") || (get_option('mp5_border_on_ad_placeholder')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_border_on_ad_placeholder" id="mp5_border_on_ad_placeholder_no" name="mp5_border_on_ad_placeholder" value="no" <?php if((get_option('mp5_border_on_ad_placeholder')!="") && (get_option('mp5_border_on_ad_placeholder')=="no")){ ?>checked<?php } ?>>
        
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Background Color of Ad Place holder</th>
        <td><input type="text" class="mp5_bg_color_ads healthdish_bg_color_pick" id="mp5_bg_color_ads" name="mp5_background_color_of_add_placeholder" value="<?php echo esc_attr( get_option('mp5_background_color_of_add_placeholder') ); ?>" data-default-color="#effeff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Make background Color transparent?</th>
        <td>
            Yes <input type="radio" class="mp5_make_bg_color_transparent" id="mp5_make_bg_color_transparent_yes" name="mp5_make_bg_color_transparent" value="yes" <?php if((get_option('mp5_make_bg_color_transparent')=="") || (get_option('mp5_make_bg_color_transparent')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_make_bg_color_transparent" id="mp5_make_bg_color_transparent_no" name="mp5_make_bg_color_transparent" value="no" <?php if((get_option('mp5_make_bg_color_transparent')!="") && (get_option('mp5_make_bg_color_transparent')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Ads before the title</th>
        <td>
            Yes <input type="radio" class="mp5_ads_before_the_title" id="mp5_ads_before_the_title_yes" name="mp5_ads_before_the_title" value="yes" <?php if((get_option('mp5_ads_before_the_title')=="") || (get_option('mp5_ads_before_the_title')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_ads_before_the_title" id="mp5_ads_before_the_title_no" name="mp5_ads_before_the_title" value="no" <?php if((get_option('mp5_ads_before_the_title')!="") && (get_option('mp5_ads_before_the_title')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after the image</th>
        <td>
            Yes <input type="radio" class="mp5_ads_after_the_image" id="mp5_ads_after_the_image_yes" name="mp5_ads_after_the_image" value="yes" <?php if((get_option('mp5_ads_after_the_image')=="") || (get_option('mp5_ads_after_the_image')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_ads_after_the_image" id="mp5_ads_after_the_image_no" name="mp5_ads_after_the_image" value="no" <?php if((get_option('mp5_ads_after_the_image')!="") && (get_option('mp5_ads_after_the_image')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after each 'N' charachers</th>
        <td><input type="number" name="mp5_ads_after_each_n_characters" value="<?php if(get_option('mp5_ads_after_each_n_characters')==""){ echo '230';}else{ echo esc_attr( get_option('mp5_ads_after_each_n_characters') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show left rail Ads</th>
        <td>
            Yes <input type="radio" class="mp5_show_left_rail_ads" id="mp5_show_left_rail_ads_yes" name="mp5_show_left_rail_ads" value="yes" <?php if((get_option('mp5_show_left_rail_ads')=="") || (get_option('mp5_show_left_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_show_left_rail_ads" id="mp5_show_left_rail_ads_no" name="mp5_show_left_rail_ads" value="no" <?php if((get_option('mp5_show_left_rail_ads')!="") && (get_option('mp5_show_left_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show right rail Ads</th>
        <td>
            Yes <input type="radio" class="mp5_show_right_rail_ads" id="mp5_show_right_rail_ads_yes" name="mp5_show_right_rail_ads" value="yes" <?php if((get_option('mp5_show_right_rail_ads')=="") || (get_option('mp5_show_right_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_show_right_rail_ads" id="mp5_show_right_rail_ads_no" name="mp5_show_right_rail_ads" value="no" <?php if((get_option('mp5_show_right_rail_ads')!="") && (get_option('mp5_show_right_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ads</th>
        <td>
            Yes <input type="radio" class="mp5_show_anchor_ads" id="mp5_show_anchor_ads_yes" name="mp5_show_anchor_ads" value="yes" <?php if((get_option('mp5_show_anchor_ads')=="") || (get_option('mp5_show_anchor_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp5_show_anchor_ads" id="mp5_show_anchor_ads_no" name="mp5_show_anchor_ads" value="no" <?php if((get_option('mp5_show_anchor_ads')!="") && (get_option('mp5_show_anchor_ads')=="no")){ ?>checked<?php } ?>>
        </td>

        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ad on the slide</th>
        <td><input type="number" name="mp5_show_anchor_ad_on_the_slide" value="<?php if(get_option('mp5_show_anchor_ad_on_the_slide')==""){ echo '1';}else{ echo esc_attr( get_option('mp5_show_anchor_ad_on_the_slide') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Advertisement text placement</th>
        <td>
            Left <input type="radio" class="mp5_advertisement_text_placement" id="mp5_advertisement_text_placement_left" name="mp5_advertisement_text_placement" value="left" <?php if((get_option('mp5_advertisement_text_placement')=="") || (get_option('mp5_advertisement_text_placement')=="left")){ ?>checked<?php } ?>><br>
            Center <input type="radio" class="mp5_advertisement_text_placement" id="mp5_advertisement_text_placement_center" name="mp5_advertisement_text_placement" value="center" <?php if((get_option('mp5_advertisement_text_placement')!="") && (get_option('mp5_advertisement_text_placement')=="center")){ ?>checked<?php } ?>><br>
            Right <input type="radio" class="mp5_show_anchor_ads" id="mp5_advertisement_text_placement_right" name="mp5_advertisement_text_placement" value="right" <?php if((get_option('mp5_advertisement_text_placement')!="") && (get_option('mp5_advertisement_text_placement')=="right")){ ?>checked<?php } ?>>
        </td>
    </table>
    
    <?php submit_button(); ?>
   </div>
<?php
  }
?>

<?php 
  //Mp8 layout page callback
  function my_healthdish_settings_mp8_layout_page(){
?>
   <div class="mp8_settings_wrap">
    <h1>MP8 Layout Settings</h1>
    <form method="post" action="options.php">
    <?php settings_fields( 'healthdish-settings-mp8-layout' ); ?>
    <?php do_settings_sections( 'healthdish-settings-mp8-layout' ); ?>
    <?php //echo get_option('mp8_make_bg_color_transparent'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Border on Ad place holder</th>
        <td>
            Yes <input type="radio" class="mp8_border_on_ad_placeholder" id="mp8_border_on_ad_placeholder_yes" name="mp8_border_on_ad_placeholder" value="yes" <?php if((get_option('mp8_border_on_ad_placeholder')=="") || (get_option('mp8_border_on_ad_placeholder')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_border_on_ad_placeholder" id="mp8_border_on_ad_placeholder_no" name="mp8_border_on_ad_placeholder" value="no" <?php if((get_option('mp8_border_on_ad_placeholder')!="") && (get_option('mp8_border_on_ad_placeholder')=="no")){ ?>checked<?php } ?>>
        
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Background Color of Ad Place holder</th>
        <td><input type="text" class="mp8_bg_color_ads healthdish_bg_color_pick" id="mp8_bg_color_ads" name="mp8_background_color_of_add_placeholder" value="<?php echo esc_attr( get_option('mp8_background_color_of_add_placeholder') ); ?>" data-default-color="#effeff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Make background Color transparent?</th>
        <td>
            Yes <input type="radio" class="mp8_make_bg_color_transparent" id="mp8_make_bg_color_transparent_yes" name="mp8_make_bg_color_transparent" value="yes" <?php if((get_option('mp8_make_bg_color_transparent')=="") || (get_option('mp8_make_bg_color_transparent')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_make_bg_color_transparent" id="mp8_make_bg_color_transparent_no" name="mp8_make_bg_color_transparent" value="no" <?php if((get_option('mp8_make_bg_color_transparent')!="") && (get_option('mp8_make_bg_color_transparent')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Ads before the title</th>
        <td>
            Yes <input type="radio" class="mp8_ads_before_the_title" id="mp8_ads_before_the_title_yes" name="mp8_ads_before_the_title" value="yes" <?php if((get_option('mp8_ads_before_the_title')=="") || (get_option('mp8_ads_before_the_title')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_ads_before_the_title" id="mp8_ads_before_the_title_no" name="mp8_ads_before_the_title" value="no" <?php if((get_option('mp8_ads_before_the_title')!="") && (get_option('mp8_ads_before_the_title')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after the image</th>
        <td>
            Yes <input type="radio" class="mp8_ads_after_the_image" id="mp8_ads_after_the_image_yes" name="mp8_ads_after_the_image" value="yes" <?php if((get_option('mp8_ads_after_the_image')=="") || (get_option('mp8_ads_after_the_image')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_ads_after_the_image" id="mp8_ads_after_the_image_no" name="mp8_ads_after_the_image" value="no" <?php if((get_option('mp8_ads_after_the_image')!="") && (get_option('mp8_ads_after_the_image')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after each 'N' charachers</th>
        <td><input type="number" name="mp8_ads_after_each_n_characters" value="<?php if(get_option('mp8_ads_after_each_n_characters')==""){ echo '230';}else{ echo esc_attr( get_option('mp8_ads_after_each_n_characters') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show left rail Ads</th>
        <td>
            Yes <input type="radio" class="mp8_show_left_rail_ads" id="mp8_show_left_rail_ads_yes" name="mp8_show_left_rail_ads" value="yes" <?php if((get_option('mp8_show_left_rail_ads')=="") || (get_option('mp8_show_left_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_show_left_rail_ads" id="mp8_show_left_rail_ads_no" name="mp8_show_left_rail_ads" value="no" <?php if((get_option('mp8_show_left_rail_ads')!="") && (get_option('mp8_show_left_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show right rail Ads</th>
        <td>
            Yes <input type="radio" class="mp8_show_right_rail_ads" id="mp8_show_right_rail_ads_yes" name="mp8_show_right_rail_ads" value="yes" <?php if((get_option('mp8_show_right_rail_ads')=="") || (get_option('mp8_show_right_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_show_right_rail_ads" id="mp8_show_right_rail_ads_no" name="mp8_show_right_rail_ads" value="no" <?php if((get_option('mp8_show_right_rail_ads')!="") && (get_option('mp8_show_right_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ads</th>
        <td>
            Yes <input type="radio" class="mp8_show_anchor_ads" id="mp8_show_anchor_ads_yes" name="mp8_show_anchor_ads" value="yes" <?php if((get_option('mp8_show_anchor_ads')=="") || (get_option('mp8_show_anchor_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp8_show_anchor_ads" id="mp8_show_anchor_ads_no" name="mp8_show_anchor_ads" value="no" <?php if((get_option('mp8_show_anchor_ads')!="") && (get_option('mp8_show_anchor_ads')=="no")){ ?>checked<?php } ?>>
        </td>

        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ad on the slide</th>
        <td><input type="number" name="mp8_show_anchor_ad_on_the_slide" value="<?php if(get_option('mp8_show_anchor_ad_on_the_slide')==""){ echo '1';}else{ echo esc_attr( get_option('mp8_show_anchor_ad_on_the_slide') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Advertisement text placement</th>
        <td>
            Left <input type="radio" class="mp8_advertisement_text_placement" id="mp8_advertisement_text_placement_left" name="mp8_advertisement_text_placement" value="left" <?php if((get_option('mp8_advertisement_text_placement')=="") || (get_option('mp8_advertisement_text_placement')=="left")){ ?>checked<?php } ?>><br>
            Center <input type="radio" class="mp8_advertisement_text_placement" id="mp8_advertisement_text_placement_center" name="mp8_advertisement_text_placement" value="center" <?php if((get_option('mp8_advertisement_text_placement')!="") && (get_option('mp8_advertisement_text_placement')=="center")){ ?>checked<?php } ?>><br>
            Right <input type="radio" class="mp8_show_anchor_ads" id="mp8_advertisement_text_placement_right" name="mp8_advertisement_text_placement" value="right" <?php if((get_option('mp8_advertisement_text_placement')!="") && (get_option('mp8_advertisement_text_placement')=="right")){ ?>checked<?php } ?>>
        </td>
    </table>
    
    <?php submit_button(); ?>
   </div>
<?php
  }
?>

<?php 
  //Mp9 layout page callback
  function my_healthdish_settings_mp9_layout_page(){
?>
   <div class="mp9_settings_wrap">
    <h1>MP9 Layout Settings</h1>
    <form method="post" action="options.php">
    <?php settings_fields( 'healthdish-settings-mp9-layout' ); ?>
    <?php do_settings_sections( 'healthdish-settings-mp9-layout' ); ?>
    <?php //echo get_option('mp9_border_on_ad_placeholder'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Border on Ad place holder</th>
        <td>
            Yes <input type="radio" class="mp9_border_on_ad_placeholder" id="mp9_border_on_ad_placeholder_yes" name="mp9_border_on_ad_placeholder" value="yes" <?php if((get_option('mp9_border_on_ad_placeholder')=="") || (get_option('mp9_border_on_ad_placeholder')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_border_on_ad_placeholder" id="mp9_border_on_ad_placeholder_no" name="mp9_border_on_ad_placeholder" value="no" <?php if((get_option('mp9_border_on_ad_placeholder')!="") && (get_option('mp9_border_on_ad_placeholder')=="no")){ ?>checked<?php } ?>>
        
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Background Color of Ad Place holder</th>
        <td><input type="text" class="mp9_bg_color_ads healthdish_bg_color_pick" id="mp9_bg_color_ads" name="mp9_background_color_of_add_placeholder" value="<?php echo esc_attr( get_option('mp9_background_color_of_add_placeholder') ); ?>" data-default-color="#effeff" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Make background Color transparent?</th>
        <td>
            Yes <input type="radio" class="mp9_make_bg_color_transparent" id="mp9_make_bg_color_transparent_yes" name="mp9_make_bg_color_transparent" value="yes" <?php if((get_option('mp9_make_bg_color_transparent')=="") || (get_option('mp9_make_bg_color_transparent')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_make_bg_color_transparent" id="mp9_make_bg_color_transparent_no" name="mp9_make_bg_color_transparent" value="no" <?php if((get_option('mp9_make_bg_color_transparent')!="") && (get_option('mp9_make_bg_color_transparent')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Ads before the title</th>
        <td>
            Yes <input type="radio" class="mp9_ads_before_the_title" id="mp9_ads_before_the_title_yes" name="mp9_ads_before_the_title" value="yes" <?php if((get_option('mp9_ads_before_the_title')=="") || (get_option('mp9_ads_before_the_title')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_ads_before_the_title" id="mp9_ads_before_the_title_no" name="mp9_ads_before_the_title" value="no" <?php if((get_option('mp9_ads_before_the_title')!="") && (get_option('mp9_ads_before_the_title')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after the image</th>
        <td>
            Yes <input type="radio" class="mp9_ads_after_the_image" id="mp9_ads_after_the_image_yes" name="mp9_ads_after_the_image" value="yes" <?php if((get_option('mp9_ads_after_the_image')=="") || (get_option('mp9_ads_after_the_image')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_ads_after_the_image" id="mp9_ads_after_the_image_no" name="mp9_ads_after_the_image" value="no" <?php if((get_option('mp9_ads_after_the_image')!="") && (get_option('mp9_ads_after_the_image')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Ads after each 'N' charachers</th>
        <td><input type="number" name="mp9_ads_after_each_n_characters" value="<?php if(get_option('mp9_ads_after_each_n_characters')==""){ echo '230';}else{ echo esc_attr( get_option('mp9_ads_after_each_n_characters') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show left rail Ads</th>
        <td>
            Yes <input type="radio" class="mp9_show_left_rail_ads" id="mp9_show_left_rail_ads_yes" name="mp9_show_left_rail_ads" value="yes" <?php if((get_option('mp9_show_left_rail_ads')=="") || (get_option('mp9_show_left_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_show_left_rail_ads" id="mp9_show_left_rail_ads_no" name="mp9_show_left_rail_ads" value="no" <?php if((get_option('mp9_show_left_rail_ads')!="") && (get_option('mp9_show_left_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show right rail Ads</th>
        <td>
            Yes <input type="radio" class="mp9_show_right_rail_ads" id="mp9_show_right_rail_ads_yes" name="mp9_show_right_rail_ads" value="yes" <?php if((get_option('mp9_show_right_rail_ads')=="") || (get_option('mp9_show_right_rail_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_show_right_rail_ads" id="mp9_show_right_rail_ads_no" name="mp9_show_right_rail_ads" value="no" <?php if((get_option('mp9_show_right_rail_ads')!="") && (get_option('mp9_show_right_rail_ads')=="no")){ ?>checked<?php } ?>>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ads</th>
        <td>
            Yes <input type="radio" class="mp9_show_anchor_ads" id="mp9_show_anchor_ads_yes" name="mp9_show_anchor_ads" value="yes" <?php if((get_option('mp9_show_anchor_ads')=="") || (get_option('mp9_show_anchor_ads')=="yes")){ ?>checked<?php } ?>><br>
            No <input type="radio" class="mp9_show_anchor_ads" id="mp9_show_anchor_ads_no" name="mp9_show_anchor_ads" value="no" <?php if((get_option('mp9_show_anchor_ads')!="") && (get_option('mp9_show_anchor_ads')=="no")){ ?>checked<?php } ?>>
        </td>

        </tr>

        <tr valign="top">
        <th scope="row">Show anchor Ad on the slide</th>
        <td><input type="number" name="mp9_show_anchor_ad_on_the_slide" value="<?php if(get_option('mp9_show_anchor_ad_on_the_slide')==""){ echo '1';}else{ echo esc_attr( get_option('mp9_show_anchor_ad_on_the_slide') ); } ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Advertisement text placement</th>
        <td>
            Left <input type="radio" class="mp9_advertisement_text_placement" id="mp9_advertisement_text_placement_left" name="mp9_advertisement_text_placement" value="left" <?php if((get_option('mp9_advertisement_text_placement')=="") || (get_option('mp9_advertisement_text_placement')=="left")){ ?>checked<?php } ?>><br>
            Center <input type="radio" class="mp9_advertisement_text_placement" id="mp9_advertisement_text_placement_center" name="mp9_advertisement_text_placement" value="center" <?php if((get_option('mp9_advertisement_text_placement')!="") && (get_option('mp9_advertisement_text_placement')=="center")){ ?>checked<?php } ?>><br>
            Right <input type="radio" class="mp9_show_anchor_ads" id="mp9_advertisement_text_placement_right" name="mp9_advertisement_text_placement" value="right" <?php if((get_option('mp9_advertisement_text_placement')!="") && (get_option('mp9_advertisement_text_placement')=="right")){ ?>checked<?php } ?>>
        </td>
    </table>
    
    <?php submit_button(); ?>
   </div>
<?php
  }
?>
