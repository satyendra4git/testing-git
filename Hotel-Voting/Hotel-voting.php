<?php
/**
 * Plugin Name: Hotel Voting
 * Plugin URI: http://103.3.63.249/hotel-voting
 * Description: This plugin upload multiple PDF and resticted with different users .
 * Version: 1.0.0
 * Author: Seema Khare
 * Author URI: http://103.3.63.249/hotel-voting
 * License: GPL2 
 */
function my_assets10()
{
  
  wp_enqueue_style( 'admin-syle10', plugins_url ( 'css/manageusers-style.css',__FILE__)); 
         
} 

add_action( 'admin_enqueue_scripts', 'my_assets10' );

class WP_Analytify_Simple
{
    
    function __construct()
    {
        add_action('admin_menu', array(
            $this,
            'wpa_add_menu'
        ));
        register_activation_hook(__FILE__, array(
            $this,
            'wpa_install'
        ));
        register_deactivation_hook(__FILE__, array(
            $this,
            'wpa_uninstall'
        ));

      

    }

    
    
    /*
     * Actions perform at loading of admin menu
     */
    function wpa_add_menu()
    {
        
         $menu = add_menu_page('Analytify simple', 'Hotels Voting List', 'manage_options', 'hotel-voting-list', array($this,'wpa_voting_list'));

         $submenu1 = add_submenu_page( 'hotel-voting-list', 'Questionaire1' ,'Questionaire', 'manage_options', 'questionaire',array( $this, 'wpa_questionaire' ));
        
         $submenu2 = add_submenu_page( 'hotel-voting-list', 'Best Pool1' ,'Best Pool', 'manage_options', 'best_pool',array( $this, 'wpa_best_pool' ));
        

        $submenu3 = add_submenu_page( 'hotel-voting-list', 'Best Seminar Rooms1' ,'Best Seminar Rooms', 'manage_options', 'best_seminar_rooms', array( $this, 'wpa_best_seminar_rooms' ));



        add_action( 'admin_print_styles-' . $menu, 'admin_custom_css' );
        add_action( 'admin_print_styles-' . $submenu1, 'admin_custom_css' );
        add_action( 'admin_print_styles-' . $submenu2, 'admin_custom_css' );
        add_action( 'admin_print_styles-' . $submenu3, 'admin_custom_css' );

        function admin_custom_css(){ 
       wp_enqueue_style( 'admin-syle20', plugins_url ( 'css/bootstrap.min.css',__FILE__));
       wp_enqueue_style( 'admin-syle30', plugins_url ( 'css/font-awesome.min.css',__FILE__));
       } 
    
    }
    
    function wpa_voting_list()
    {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'upload_PDF';
        $current_user_id = get_current_user_id();
        $uploads = wp_upload_dir();
        $upload_path = $uploads['basedir'];
        
        $file = fopen($upload_path."/voting_list/hotel_voting_list.csv","w+");
        $myrows = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
        $headers = "ID, Customer ID, Customer Name, Customer Email, Hotel Name, Location , Voting Points";

         fputcsv($file,explode(', ', $headers));
      
       foreach($myrows as $result)
       {  
          fputcsv($file, $result);
        
       } ?>  
        <h1>Export Custmor Voting List</h1>
        <a href="<?php echo $uploads['baseurl']; ?>/voting_list/hotel_voting_list.csv" class="export_csv">Click Here to Export </a> 
         <?php fclose($file);
    }
    
    function wpa_questionaire(){

     //echo "Questionaire";
        global $wpdb;
        $table_name      = $wpdb->prefix . 'upload_PDF';

        $perpage = 4;
            if(isset($_GET['pages']) & !empty($_GET['pages'])){
              $curpage = $_GET['pages'];
              $start = ($curpage * $perpage) - $perpage;
            }else{
              $curpage = 1;
              $start  = 0;
            }  

       $results = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name ORDER BY date_time  DESC", OBJECT );
       //echo "<pre>";print_r($results);

            $totalres = count($results); 
            $endpage = ceil($totalres/$perpage);
            $startpage = 1;
            $nextpage = $curpage + 1;
            $previouspage = $curpage - 1;  
            
            $resultsfinal = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name ORDER BY date_time  DESC LIMIT $start,$perpage", OBJECT );
            $total_query =  count($resultsfinal);



?>
      <div class="manageusers_cnt">
       <?php /** if(!empty($msg)){ ?>
      <div class="form-msg <?php echo $cls; ?>"> 
      <?php echo $msg;  ?>
      </div>
      <?php }//end if */ ?>
      
          <div class="table-responsive hotel-votes-list">
          <form method="post" action="<?php echo admin_url('admin.php?page=user-listings'); ?>" class="verifyform" id="verifyform">
          <table class="table">
          <tr>
          <th>Sr.No.</th>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>Hotel Name</th>
          <th>Location</th>
          <th>Voting Point</th>
          </tr>
          
          <?php $k=$start+1; $tc=1;
          
                foreach($resultsfinal as $res){
                  $vname = $res->user_name;
                  $vemail = $res->email_id;
                  $hotel_name = $res->hotel_name;
                  $location = $res->location;
                  $voting_point = $res->voting_point;
                  
          ?>
          <tr <?php if($tc%2==0){ ?> class="success" <?php } ?>>
          <td><?php echo $k; ?></td>
          <td><?php echo $vname; ?></td>
          <td><?php echo $vemail; ?></td>
          <td><?php echo $hotel_name; ?></td>
          <td><?php echo $location; ?></td>
          <td><?php echo $voting_point; ?></td>
          </tr> 
          
          <?php $k++; $tc++; }//end foreach ?>
          
          </table>
          </form>
       </div> 

  <ul class="pagination">
   <?php if($curpage != $startpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=questionaire&pages=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">First</span>
      </a>
    </li>
    <?php } ?>
  
  <?php if($endpage!=1){ $ikk=0; for($pn=$endpage; $pn>=1; $pn--){  $ikk++; ?>
  
  <li class="page-item <?php if($ikk == $curpage){ echo "active"; } ?>"><a class="page-link" href="?page=questionaire&pages=<?php echo $ikk; ?>"><?php echo $ikk; ?></a></li>
  
  <?php } } ?>
  
    <?php if($curpage != $endpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=questionaire&pages=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul> 
            
      </div><!-- .manageuser_cnt -->
<?php } 

    function wpa_best_pool(){

     //echo "Best Pool";

        global $wpdb;
        $table_name      = $wpdb->prefix . 'generalvotes';

        $perpage = 4;
            if(isset($_GET['pages']) & !empty($_GET['pages'])){
              $curpage = $_GET['pages'];
              $start = ($curpage * $perpage) - $perpage;
            }else{
              $curpage = 1;
              $start  = 0;
            }  

       $results = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name WHERE voting_type='247' ORDER BY date_time  DESC", OBJECT );
       //echo "<pre>";print_r($results);

            $totalres = count($results); 
            $endpage = ceil($totalres/$perpage);
            $startpage = 1;
            $nextpage = $curpage + 1;
            $previouspage = $curpage - 1;  
            
            $resultsfinal = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name WHERE voting_type='247' ORDER BY date_time  DESC LIMIT $start,$perpage", OBJECT );
            $total_query =  count($resultsfinal);



?>
      <div class="manageusers_cnt">
       <?php /** if(!empty($msg)){ ?>
      <div class="form-msg <?php echo $cls; ?>"> 
      <?php echo $msg;  ?>
      </div>
      <?php }//end if */ ?>
      
          <div class="table-responsive hotel-votes-list">
          <form method="post" action="<?php echo admin_url('admin.php?page=user-listings'); ?>" class="verifyform" id="verifyform">
          <table class="table">
          <tr>
          <th>Sr.No.</th>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>Hotel Name</th>
          <th>Location</th>
          <th>Voting Point</th>
          </tr>
          
          <?php $k=$start+1; $tc=1;
          
                foreach($resultsfinal as $res){
                  $vname = $res->user_name;
                  $vemail = $res->email_id;
                  $hotel_name = $res->hotel_name;
                  $location = $res->location;
                  $voting_point = $res->voting_point;
                  
          ?>
          <tr <?php if($tc%2==0){ ?> class="success" <?php } ?>>
          <td><?php echo $k; ?></td>
          <td><?php echo $vname; ?></td>
          <td><?php echo $vemail; ?></td>
          <td><?php echo $hotel_name; ?></td>
          <td><?php echo $location; ?></td>
          <td><?php echo $voting_point; ?></td>
          </tr> 
          
          <?php $k++; $tc++; }//end foreach ?>
          
          </table>
          </form>
       </div> 

  <ul class="pagination">
   <?php if($curpage != $startpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=best_pool&pages=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">First</span>
      </a>
    </li>
    <?php } ?>
  
  <?php if($endpage!=1){ $ikk=0; for($pn=$endpage; $pn>=1; $pn--){  $ikk++; ?>
  
  <li class="page-item <?php if($ikk == $curpage){ echo "active"; } ?>"><a class="page-link" href="?page=best_pool&pages=<?php echo $ikk; ?>"><?php echo $ikk; ?></a></li>
  
  <?php } } ?>
  
    <?php if($curpage != $endpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=best_pool&pages=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul> 
            
      </div><!-- .manageuser_cnt -->
<?php 

    }

    function wpa_best_seminar_rooms(){

      //echo "Best Seminar Rooms";

      global $wpdb;
        $table_name      = $wpdb->prefix . 'generalvotes';

        $perpage = 4;
            if(isset($_GET['pages']) & !empty($_GET['pages'])){
              $curpage = $_GET['pages'];
              $start = ($curpage * $perpage) - $perpage;
            }else{
              $curpage = 1;
              $start  = 0;
            }  

       $results = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name WHERE voting_type='249' ORDER BY date_time  DESC", OBJECT );
       //echo "<pre>";print_r($results);

            $totalres = count($results); 
            $endpage = ceil($totalres/$perpage);
            $startpage = 1;
            $nextpage = $curpage + 1;
            $previouspage = $curpage - 1;  
            
            $resultsfinal = $wpdb->get_results( "SELECT DISTINCT * FROM  $table_name WHERE voting_type='249' ORDER BY date_time  DESC LIMIT $start,$perpage", OBJECT );
            $total_query =  count($resultsfinal);



?>
      <div class="manageusers_cnt">
       <?php /** if(!empty($msg)){ ?>
      <div class="form-msg <?php echo $cls; ?>"> 
      <?php echo $msg;  ?>
      </div>
      <?php }//end if */ ?>
      
          <div class="table-responsive hotel-votes-list">
          <form method="post" action="<?php echo admin_url('admin.php?page=user-listings'); ?>" class="verifyform" id="verifyform">
          <table class="table">
          <tr>
          <th>Sr.No.</th>
          <th>NAME</th>
          <th>EMAIL</th>
          <th>Hotel Name</th>
          <th>Location</th>
          <th>Voting Point</th>
          </tr>
          
          <?php $k=$start+1; $tc=1;
          
                foreach($resultsfinal as $res){
                  $vname = $res->user_name;
                  $vemail = $res->email_id;
                  $hotel_name = $res->hotel_name;
                  $location = $res->location;
                  $voting_point = $res->voting_point;
                  
          ?>
          <tr <?php if($tc%2==0){ ?> class="success" <?php } ?>>
          <td><?php echo $k; ?></td>
          <td><?php echo $vname; ?></td>
          <td><?php echo $vemail; ?></td>
          <td><?php echo $hotel_name; ?></td>
          <td><?php echo $location; ?></td>
          <td><?php echo $voting_point; ?></td>
          </tr> 
          
          <?php $k++; $tc++; }//end foreach ?>
          
          </table>
          </form>
       </div> 

  <ul class="pagination">
   <?php if($curpage != $startpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=best_seminar_rooms&pages=<?php echo $startpage ?>" tabindex="-1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">First</span>
      </a>
    </li>
    <?php } ?>
  
  <?php if($endpage!=1){ $ikk=0; for($pn=$endpage; $pn>=1; $pn--){  $ikk++; ?>
  
  <li class="page-item <?php if($ikk == $curpage){ echo "active"; } ?>"><a class="page-link" href="?page=best_seminar_rooms&pages=<?php echo $ikk; ?>"><?php echo $ikk; ?></a></li>
  
  <?php } } ?>
  
    <?php if($curpage != $endpage){ ?>
    <li class="page-item">
      <a class="page-link" href="?page=best_seminar_rooms&pages=<?php echo $endpage ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Last</span>
      </a>
    </li>
    <?php } ?>
  </ul> 
            
      </div><!-- .manageuser_cnt -->
<?php 

    }
   
    /*
     * Actions perform on activation of plugin
     */
    function wpa_install()
    {
        
        /*global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . 'upload_PDF';
        if ($table_name == wp_upload_PDF) {
            
            $sql = "CREATE TABLE $table_name (
                      id mediumint(50) NOT NULL AUTO_INCREMENT,
                      user_id mediumint(50) NOT NULL,
                      user_name VARCHAR(100),
                      email_id VARCHAR(100),
                      hotel_name VARCHAR(100),
                      hotel_name VARCHAR(100),
                      location VARCHAR(100),
                      voting_point VARCHAR(100),
                      UNIQUE KEY id (id)
                    ) ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        */
        
    }
    
    /*
     * Actions perform on de-activation of plugin
     */
    function wpa_uninstall()
    {
        /*global $wpdb;
        
        $table_name2 = $wpdb->base_prefix . "upload_PDF";
        $sql = "DROP TABLE IF EXISTS $table_name2;";
        $wpdb->query($sql);*/
    }
    
}

new WP_Analytify_Simple(); 
?>
