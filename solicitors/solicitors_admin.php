<?php 
/**
 * Plugin Name: Solicitors
 * Plugin URI: http://stashedaway.co.uk
 * Description: This plugin adds some Facebook Open Graph tags to our single posts.
 * Version: 1.0.0
 * Author: Daniel Pataki
 * Author URI: http://stashedaway.co.uk
 * License: GPL2 
 */
?>

  <?php
  
function my_assets()
{
  
    wp_enqueue_style( 'jquery-ui', plugins_url ( 'css/jquery-ui.css',__FILE__));
	wp_enqueue_style( 'admin-syle', plugins_url ( 'css/admin-syle.css',__FILE__));
    wp_enqueue_script( 'jquery-barcode.min',  plugins_url ( 'js/jquery-barcode.min.js',__FILE__), array( 'jquery' ) );
    wp_enqueue_script( 'jquery-barcode',  plugins_url ( 'js/jquery-barcode.js',__FILE__), array( 'jquery' ) );
    wp_enqueue_script( 'barcode',  plugins_url ( 'js/barcode.js',__FILE__), array( 'jquery' ) );
    wp_enqueue_script( 'jquery-ui',  plugins_url ( 'js/jquery-ui.js',__FILE__), array( 'jquery' ) );
  
          
}

add_action( 'admin_enqueue_scripts', 'my_assets' );



class WP_Analytify_Simple{

  function __construct() 
        		{
						    add_action( 'admin_menu', array( $this, 'wpa_add_menu' ));
				        register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
				        register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );
   			  	}



    /*
      * Actions perform at loading of admin menu
      */
    function wpa_add_menu() {

                  add_menu_page( 'Analytify simple', 'Solicitors', 'manage_options', 'analytify-dashboard', array( $this, 'wpa_solicitors' ));

                  add_submenu_page( 'analytify-dashboard', 'My subpae title' ,'Solicitors Dashboard', 'manage_options', 'all_details', array( $this, 'wpa_solicitors_dashboard' ));
				  
                 }

    function wpa_solicitors()
    {

              global $wpdb;
              $table_name = $wpdb->prefix . 'solicitors';
              $current_user_id = get_current_user_id();  
              
                      echo "<h1>Log Book Details</h1>";
                         
                    $myrows = $wpdb->get_results( "SELECT DISTINCT club_name,user_id FROM $table_name" );  
                     if($wpdb->num_rows > 0 )
                     {
                     ?>
                     <table name="sol_dashboard" id="sol_dashboard" method="post" style="border:2px">
                            <tr>
                            <th>Club Name</th>
                            <th>Action</th>
                            </tr>
                           

                           <?php 
						   
						   foreach($myrows as $result)
                           { 

                                 $genre_url = add_query_arg('genre', $result->user_id,'?page=all_details');
                                 echo  '<tr><td>'.$result->club_name.'</td><td><a href="'. $genre_url.'" class="seeall">See All</</td></tr>';


                           }
                     }
                                   
                }


  function wpa_solicitors_dashboard()
        {

          
            global $wpdb;
              $table_name = $wpdb->prefix . 'solicitors';
              $current_user_id = get_current_user_id(); 

                     //echo "<h1>Log Book Details</h1>";
              
                           echo "<h1>CLUB RECORDS</h1>";
                      echo '<div class="all_search_admin">
                            <label>Search By:</label>
                            <select class="ref_num">
                     
                            <option value="">--Select Ref Number--</option>';
                            $myrows = $wpdb->get_results( "SELECT DISTINCT ref_number FROM $table_name" );
                                  foreach($myrows as $result)
                                  { 
                                   echo '  <option value="'.$result->ref_number.'">'. $result->ref_number.'</option>';
                                    }
                                echo '</select>

                           <select class="user_name">

                           <option value="">--Select Name--</option>';
                            $myrows = $wpdb->get_results( "SELECT DISTINCT name FROM $table_name" );
                                foreach($myrows as $result)
                                { 
                                
                                 echo '<option value="'.$result->name.'">'.$result->name.'</option>';
                                 }
                               echo '</select>

                           <select class="user_branch">

                           <option value="">--Select Branch--</option>';
                            $myrows = $wpdb->get_results( "SELECT DISTINCT branch FROM $table_name" );
                                 foreach($myrows as $result)
                                 { 
                              echo '.<option value="'.$result->branch .'">'.$result->branch .'</option>';
                                 } 
                              echo '</select>

                           <select class="entered_by">

                           <option value="">--Select Entered By--</option>';
                            $myrows = $wpdb->get_results( "SELECT DISTINCT user_name FROM $table_name" );
                                foreach($myrows as $result)
                                { 
                                echo '<option value="'.$result->user_name.'">'.$result->user_name.'</option>';
                                }
                              echo '</select>

                          </div>'; 
                          echo "<input type='button' name='clearall' class='clearall' value='Reset'>"; ?>

                        <div id="results"></div>
 



      <?php  }



 
   
    /*
     * Actions perform on activation of plugin
     */
    function wpa_install() {

              global $wpdb;
              $charset_collate = $wpdb->get_charset_collate();
              $table_name = $wpdb->prefix . 'solicitors';
          if ( $table_name == wp_solicitors) { 

           $sql = "CREATE TABLE $table_name (
                      id mediumint(9) NOT NULL AUTO_INCREMENT,
                      user_id mediumint(9) NOT NULL,
                      barcode VARCHAR(50) NULL,
                      club_name VARCHAR(20) NOT NULL,
                      ref_number mediumint(10) NOT NULL,
                      branch VARCHAR(20) NOT NULL,
                      name VARCHAR(20) NOT NULL,
                      description VARCHAR(100) NOT NULL,
                      notes VARCHAR(50) NOT NULL,
                      no_of_file_boxes mediumint(10) NOT NULL,
                      no_sleaves mediumint(10) NOT NULL,
                      deposit_collection_in VARCHAR(10) NOT NULL,
                      in_date TIMESTAMP ,
                      actual_in_date TIMESTAMP ,
                      delivery_retrieval_out VARCHAR(10) NOT NULL,
                      out_date TIMESTAMP ,
                      user_name VARCHAR(10) NOT NULL,
                      UNIQUE KEY id (id)
                    ) ";
                   
               require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
              dbDelta( $sql );
            }
              
              global $wpdb;
              $charset_collate = $wpdb->get_charset_collate();
              $table_name1 = $wpdb->prefix . 'request';
              
            if ( $table_name1 == wp_request) { 
             $sql1 = "CREATE TABLE $table_name1 (
                      user_id mediumint(9) NOT NULL,
                      user_name VARCHAR(20) NOT NULL,
                      full_name VARCHAR(20) NOT NULL,
                      email VARCHAR(20) NOT NULL,
                      method_option VARCHAR(20) NOT NULL,
                      datetime TIMESTAMP
                      
                    
                    ) "; 

        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
             dbDelta( $sql1 );

             
            }
            
  }

    

    /*
     * Actions perform on de-activation of plugin
     */
    function wpa_uninstall() 
    {
             global $wpdb;
     /*
              $table_name = $wpdb->base_prefix . "solicitors";
              $sql = "DROP TABLE IF EXISTS $table_name;";
              $wpdb->query($sql);

              $table_name2 = $wpdb->base_prefix . "request";
              $sql = "DROP TABLE IF EXISTS $table_name2;";
              $wpdb->query($sql);*/
  }

}

new WP_Analytify_Simple();