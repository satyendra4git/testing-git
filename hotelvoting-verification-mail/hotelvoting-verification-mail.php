<?php 
/**
 * Plugin Name: Verification mail
 * Plugin URI: http://www.infoicontechnologies.com
 * Description: For managing Hotelvoting24/7 Users.
 * Version: 1.0.0
 * Author: Satyendra Kumar
 * Author URI: http://www.infoicontechnologies.com
 * License: GPL2 
 */
 
 function my_assets1()
{
  
   
	wp_enqueue_style( 'admin-syle', plugins_url ( 'css/manageusers-style.css',__FILE__));
	
  
          
} 

add_action( 'admin_enqueue_scripts', 'my_assets1' );

class Hotelvoting_Manage_users{

  function __construct() 
        		{
						add_action( 'admin_menu', array( $this, 'hotelvoting_add_menu' ));
				        register_activation_hook( __FILE__, array( $this, 'hotelvoting_install' ) );
				        register_deactivation_hook( __FILE__, array( $this, 'hotelvoting_uninstall' ) );
   			  	}
				
				
				 function hotelvoting_add_menu() {

                  add_menu_page( 'User Listings', 'Verifcation mail', 'manage_options', 'user-listings', array( $this, 'hotelvoting_manageusers' ));

				  
                 }
				
				
				 function hotelvoting_manageusers(){
					 
					 
                        $msg = "";
                        $users    = get_users(array('role__in' => array('subscriber')));
						$total_users = count($users);    
					 
					  $userargs = array( 
							'role__in'     => array('subscriber'),
	                        'orderby' => 'registered',
	                        'order' => 'DESC',
							'fields' => 'all',
	                        'number'    => $number 						
							); 	

						$userobj = get_users( $userargs );
	                    $total_query =  count($userobj);
						
	              
					 ?>
					 <link rel="stylesheet" href="<?php echo plugins_url ( 'css/bootstrap.min.css',__FILE__); ?>">
					 <link rel="stylesheet" href="<?php echo plugins_url ( 'css/font-awesome.min.css',__FILE__); ?>">
					 <script src="<?php echo plugins_url ( 'js/jquery-1.12.0.min.js',__FILE__); ?>"></script> 
					 <script src="<?php echo plugins_url ( 'js/bootstrap.min.js',__FILE__); ?>"></script> 
		<?php 

         $actionval = $_GET['action'];

         if($actionval=="sendvmail"){

             $uid = $_GET['uid'];
             $uhash = md5($uid);
             $vmuobj = get_user_by('id', $uid);
             //echo "<pre>";print($vmuobj);
             $uemail = $vmuobj->data->user_email;
             $profilenm = $vmuobj->data->display_name;
             $verifylink = get_page_link(50)."?uvi=".$uid."&uvcode=".$uhash;

             if($uemail){

		    $adminemail = 'satyendra4test@gmail.com';

		    //$to = $uemail . ',' . $adminemail;
		    $to = $uemail;

			$subject = 'Verification mail by Hotel Voting';

			$headers = "From: " . strip_tags($adminemail) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($uemail) . "\r\n";
			//$headers .= "CC: susan@example.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
			$headers .= 'X-Mailer: PHP/'.phpversion();
			//echo $headers;
			$message = '<html><body>';
			$message .= "<h4>Hello ".$profilenm." <".strip_tags($uemail)."> ,</h4><br/>";
			//$message .= "<strong>Name:</strong> " . strip_tags($uname) . "<br/><br/>";
			//$message .= "<strong>E-mail:</strong> " . strip_tags($uemail) . "<br/><br/>";
			$message .= "<strong>Please click below link to fill up questionaire form </strong> <br/><br/>";
			$message .= "<a href='$verifylink' target='_blank'>" . $verifylink . "</a><br/><br/><br/><br/>";

			$message .= "<strong>Hotel Voting Team</strong><br/><br/>";

			$message .= "</body></html>";

			$issent = wp_mail($to, $subject, $message, $headers); 

			 if($issent){
			  $msg = "<b>Verification mail sent to ".$profilenm."</b>.";
			  $cls = "rsucess";	
			}else{
			   $msg = "<b>Mail sending failed. Please try again.</b>"; 
			   $cls = "rerror";	
			} 


             }
         }

         if(isset($_POST['sendbulk'])){

         	extract($_POST);
         	//echo "<pre>";print_r($_POST);

         	if(!empty($vmail)){

         		foreach($vmail as $verifyid){

                //echo "id->".$verifyid."<br>";

             $uid = $verifyid;
             $uhash = md5($uid);
             $vmuobj = get_user_by('id', $uid);
             //echo "<pre>";print($vmuobj);
             $uemail = $vmuobj->data->user_email;
             $profilenm = $vmuobj->data->display_name;
             $verifylink = get_page_link(50)."?uvi=".$uid."&uvcode=".$uhash;

             if($uemail){

		    $adminemail = 'satyendra4test@gmail.com';

		    //$to = $uemail . ',' . $adminemail;
		    $to = $uemail;

			$subject = 'Verification mail by Hotel Voting';

			$headers = "From: " . strip_tags($adminemail) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($uemail) . "\r\n";
			//$headers .= "CC: susan@example.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
			$headers .= 'X-Mailer: PHP/'.phpversion();
			//echo $headers;
			$message = '<html><body>';
			$message .= "<h4>Hello ".$profilenm." <".strip_tags($uemail)."> ,</h4><br/>";
			//$message .= "<strong>Name:</strong> " . strip_tags($uname) . "<br/><br/>";
			//$message .= "<strong>E-mail:</strong> " . strip_tags($uemail) . "<br/><br/>";
			$message .= "<strong>Please click below link to fill up questionaire form </strong> <br/><br/>";
			$message .= "<a href='$verifylink' target='_blank'>" . $verifylink . "</a><br/><br/><br/><br/>";

			$message .= "<strong>Hotel Voting Team</strong><br/><br/>";

			$message .= "</body></html>";

			$issent = wp_mail($to, $subject, $message, $headers); 

			 if($issent){
			  $msg = "<b>Verification mail sent to ".$profilenm."</b>.";
			  $cls = "rsucess";	
			}else{
			   $msg = "<b>Mail sending failed. Please try again.</b>"; 
			   $cls = "rerror";	
			} 


             }	


         		}

              

         	}else{
         		$msg = "<b>Select some users to send.</b>"; 
			    $cls = "rerror";	

         	}


         }

          
	    ?>		
					 
		   <div class="manageusers_cnt">
		   <?php if(!empty($msg)){ ?>
		  <div class="form-msg <?php echo $cls; ?>"> 
		  <?php echo $msg;  ?>
		  </div>
		  <?php }//end if ?>
		  <div class="send-bulk-cnt"></div>
		      <div class="table-responsive">
		      <form method="post" action="<?php echo admin_url('admin.php?page=user-listings'); ?>" class="verifyform" id="verifyform">
				  <table class="table">
				  <tr>
				  <th>Sr.No.</th>
				  <th><input type="submit" name="sendbulk" class="sendbulk" id="sendbulk" value="Send in  Bulk"></th>
				  <th>NAME</th>
				  <th>EMAIL</th>
				  <th>ACTION</th>
				  </tr>
				  
				  <?php $k=1;
				  
		            foreach($userobj as $users){
		              $user_id = $users->ID;
		              $role = $users->roles[0];
					  $uemail = $users->data->user_email;
					  $profilenm = $users->data->display_name; 
				  ?>
				  <tr>
				  <td><?php echo $k; ?></td>
				  <td><input type="checkbox" name="vmail[]" value="<?php echo $user_id; ?>"/></td>
				  <td><?php echo $profilenm; ?></td>
				  <td><?php echo $uemail; ?></td>
				  <td><span class="edituser"><a href="?page=user-listings&action=sendvmail&uid=<?php echo trim($user_id); ?>">SEND</a></span> </td>
				  </tr>	
				  
					<?php $k++; }//end foreach ?>
				  
				  </table>
				  </form>
		   </div> 
						
      </div><!-- .manageuser_cnt -->
    <?php   
				 }
				 
				 

              
				

     /*
     * Actions perform on activation of plugin
     */
    function hotelvoting_install() {

     //some actions here        
  }

    /*
     * Actions perform on de-activation of plugin
     */
    function hotelvoting_uninstall() 
    {
      //some actions here        
    }
				
				
}

new Hotelvoting_Manage_users();				
