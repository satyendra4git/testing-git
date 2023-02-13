<?php 
/**
 * Plugin Name: Manage Users
 * Plugin URI: http://www.infoicontechnologies.com
 * Description: For managing Massage24/7 Users.
 * Version: 1.0.0
 * Author: Satyendra Kumar
 * Author URI: http://www.infoicontechnologies.com
 * License: GPL2 
 */
 
 function my_assets()
{
  
   
	wp_enqueue_style( 'admin-syle', plugins_url ( 'css/manageusers-style.css',__FILE__));
	//wp_enqueue_style( 'bootstrap-syle', plugins_url ( 'css/bootstrap.min.css',__FILE__));
	//wp_enqueue_style( 'font-awesome', plugins_url ( 'css/font-awesome.min.css',__FILE__));
	//wp_enqueue_style( 'bootstrap-multiselect', plugins_url ( 'css/bootstrap-multiselect.css',__FILE__));
	//wp_enqueue_script( 'jquery-lib',  plugins_url ( 'js/jquery-1.12.0.min.js',__FILE__), array() );
	//wp_enqueue_script( 'bootstrap.min',  plugins_url ( 'js/bootstrap.min.js',__FILE__), array( 'jquery-lib' ) );
	//wp_enqueue_script( 'bootstrap-multiselect',  plugins_url ( 'js/bootstrap-multiselect.js',__FILE__), array( 'jquery-lib' ) );
   
  
          
} 

add_action( 'admin_enqueue_scripts', 'my_assets' );

class Massage_Manage_users{

  function __construct() 
        		{
						add_action( 'admin_menu', array( $this, 'massage_add_menu' ));
				        register_activation_hook( __FILE__, array( $this, 'massage_install' ) );
				        register_deactivation_hook( __FILE__, array( $this, 'massage_uninstall' ) );
   			  	}
				
				
				 function massage_add_menu() {

                  add_menu_page( 'User Listings', 'Mnage Users', 'manage_options', 'user-listings', array( $this, 'massage_manageusers' ));

                  /* add_submenu_page( 'user-listings', 'More User Listings' ,'Manage', 'manage_options', 'user_details', array( $this, 'massage_manageusers_dashboard' )); */
				  
                 }
				
				
				 function massage_manageusers(){
					 
					 /** Pagination start  **/
					 
					/*  $userargs_count = array( 
	                    //'role' => 'subscriber', 
						'role__in'     => array('subscriber','privat'),
						'fields' => 'all', 
						'number'    => 999999      
						);
						
						
						$userobjc = get_users( $userargs_count );
	                    $ucountc =  count($userobjc);
					 
					 
					    $total_users = $userobj ? count($userobj) : 1;
						$page = isset($_GET['p']) ? $_GET['p'] : 1;
						$users_per_page = 5;

						// calculate the total number of pages.
						$total_pages = 1;
						$offset = $users_per_page * ($page - 1);
						$total_pages = ceil($total_users / $users_per_page); */
						
						$number   = 80;
						
						$pnum = $_GET['paged'];
						//$paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;
						 $paged    = ($pnum) ? $pnum : 1;
						
						$offset   = ($paged - 1) * $number;
						$users    = get_users(array('role__in' => array('subscriber','privat')));
						//$query    = get_users('&offset='.$offset.'&number='.$number);
						$total_users = count($users);
						
						
					     /*** Pagination end **/
					 
					 $userargs = array( 
	                    //'role' => 'subscriber', 
						'role__in'     => array('subscriber','privat'),
                        'orderby' => 'registered',
                        'order' => 'DESC',
						'fields' => 'all',
                        'number'    => $number,
                        'offset'    => $offset 						
						
						);
						
						$userobj = get_users( $userargs );
	                    $total_query =  count($userobj);
						//$total_query = count($query);
					 $total_pages = intval($total_users / $number) + 1;
						
						
						
					    //echo "<pre>"; print_r($userobj);
						 //echo plugins_url()."/js/jquery-1.12.0.min.js";
						 //echo plugins_url ( 'js/jquery-1.12.0.min.js',__FILE__);
					 ?>
					 <link rel="stylesheet" href="<?php echo plugins_url ( 'css/bootstrap.min.css',__FILE__); ?>">
					 <link rel="stylesheet" href="<?php echo plugins_url ( 'css/font-awesome.min.css',__FILE__); ?>">
					 <script src="<?php echo plugins_url ( 'js/jquery-1.12.0.min.js',__FILE__); ?>"></script> 
					 <script src="<?php echo plugins_url ( 'js/bootstrap.min.js',__FILE__); ?>"></script> 
					 <div class="manageusers_cnt">
					 
					 <?php 
					 
					    //echo "heloo Main"; 
						$msg = "";
						
					   $actionval = $_GET['action'];
						
						 if(isset($actionval)){
							
						  $acuid = $_GET['uid'];	
						 
						
						
						if($actionval=="approve"){	
						
						 /** Start User Approval **/	
						//update_user_status( $acuid, 'user_status', 1 );
						$st = 1;
						
						update_user_meta($acuid, '_ustatus', $st); 
						
						$msg = "User Approved Successfully";
						$cls = "rsucess";	
						/***  End User Approval **/
						?>
		 <?php if(!empty($msg)){ ?>
		  <div class="form-msg <?php echo $cls; ?>"> 
		  <?php echo $msg;  ?>
		  </div>
		  <?php }//end if ?>
      <div class="table-responsive">
		  <table class="table">
		  <tr>
		  <th>ID</th>
		  <th>NAME</th>
		  <th>EMAIL</th>
		  <!--<th>TYPE</th>-->
		  <th>SEX</th>
		  <th>CITY</th>
		  <th>Services</th>
		  <th>Areas</th>
		  <th>STATUS</th>
		  <th>ACTION</th>
		  </tr>
		  
		  <?php $k=1;
		  
            foreach($userobj as $users){
				
              $user_id = $users->ID;
              $role = $users->roles[0];
              //$ustatus = $users->data->user_status;
			  $uemail = $users->data->user_email;
			  $profilenm = $users->data->display_name;
			  $uworkspaces = get_user_meta( $user_id, "_uworkspaces", true);
			  $ucity = get_user_meta( $user_id, "_ucity", true);
			  $uservices = get_user_meta( $user_id, "_uservices", true);
			  $uprofiltext = get_user_meta( $user_id, "_uprofiltext", true);	
			  $ustatus = get_user_meta( $user_id, "_ustatus", true);
			  $usex = get_user_meta($user_id, '_usex', true);
			  $ucity = get_user_meta($user_id, '_ucity', true);

              $uworkspaceswords = join(', ', array_map('ucfirst', explode(',', $uworkspaces)));
              $userviceswords = join(', ', array_map('ucfirst', explode(',', $uservices)));	

               if($ustatus==0){ $statusval = "Inactive"; $actiontxt = "approve"; }else{ $statusval = "Active"; $actiontxt = "disapprove"; }			  

		  ?>
		  <tr>
		  <td><?php echo $k; ?></td>
		  <td><?php echo $profilenm; ?></td>
		  <td><?php echo $uemail; ?></td>
		  <!--<td><?php //echo $role; ?></td>-->
		  <td><?php if($usex!=""){ echo $usex; }else{ echo "N/A"; } ?></td>
		  <td><?php if($ucity!=""){ echo $ucity; }else{ echo "N/A"; } ?></td>
		  <td><?php if($userviceswords!=""){ echo $userviceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($uworkspaceswords!=""){ echo $uworkspaceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($statusval!=""){ echo $statusval; }else{ echo "N/A"; } ?></td>
		  <td>
		      <span class="edituser"><a href="?page=user-listings&action=edit&uid=<?php echo trim($user_id); ?>">Edit</a></span> |
		      <span class="deleteuser"><a href="?page=user-listings&action=del&uid=<?php echo trim($user_id); ?>" onClick="return confirm('Are you sure ? You want to delete this member')">Delete</a></span> |
		      <span class="stuser"><a href="?page=user-listings&action=<?php echo trim($actiontxt); ?>&uid=<?php echo trim($user_id); ?>"><?php echo ucfirst($actiontxt); ?></a></span>
			  
		</td>
		  </tr>	
		  
			<?php $k++; }//end foreach ?>
		  
		  </table>
   </div> 
						
						<?php 
						
                        }else if($actionval=="disapprove"){
							
							/** Start User Disapproval **/
							
							$st = 0;
							update_user_meta($acuid, '_ustatus', $st); 

							 //update_user_status( $acuid, 'user_status', 0 );
							 $msg = "User Disapproved Successfully";						 
							 $cls = "rsucess";	
							 
							/** End User Disapproval **/
							?>
							
							
		<?php if(!empty($msg)){ ?>
		  <div class="form-msg <?php echo $cls; ?>"> 
		  <?php echo $msg;  ?>
		  </div>
		  <?php }//end if ?>
      <div class="table-responsive">
		  <table class="table">
		  <tr>
		 <th>ID</th>
		  <th>NAME</th>
		  <th>EMAIL</th>
		  <!--<th>TYPE</th>-->
		  <th>SEX</th>
		  <th>CITY</th>
		  <th>Services</th>
		  <th>Areas</th>
		  <th>STATUS</th>
		  <th>ACTION</th>
		  </tr>
		  
		  <?php $k=1;
		  
            foreach($userobj as $users){
				
              $user_id = $users->ID;
			  $role = $users->roles[0];
              //$ustatus = $users->data->user_status;
			  $uemail = $users->data->user_email;
			  $profilenm = $users->data->display_name;
			  $uworkspaces = get_user_meta( $user_id, "_uworkspaces", true);
			  $ucity = get_user_meta( $user_id, "_ucity", true);
			  $uservices = get_user_meta( $user_id, "_uservices", true);
			  $uprofiltext = get_user_meta( $user_id, "_uprofiltext", true);	
			  $ustatus = get_user_meta( $user_id, "_ustatus", true);
			  $usex = get_user_meta($user_id, '_usex', true);
			  $ucity = get_user_meta($user_id, '_ucity', true);

              $uworkspaceswords = join(', ', array_map('ucfirst', explode(',', $uworkspaces)));
              $userviceswords = join(', ', array_map('ucfirst', explode(',', $uservices)));	

               if($ustatus==0){ $statusval = "Inactive"; $actiontxt = "approve"; }else{ $statusval = "Active"; $actiontxt = "disapprove"; }			  

		  ?>
		  <tr>
		  <td><?php echo $k; ?></td>
		  <td><?php echo $profilenm; ?></td>
		  <td><?php echo $uemail; ?></td>
		  <!--<td><?php //echo $role; ?></td>-->
		  <td><?php if($usex!=""){ echo $usex; }else{ echo "N/A"; } ?></td>
		  <td><?php if($ucity!=""){ echo $ucity; }else{ echo "N/A"; } ?></td>
		  <td><?php if($userviceswords!=""){ echo $userviceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($uworkspaceswords!=""){ echo $uworkspaceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($statusval!=""){ echo $statusval; }else{ echo "N/A"; } ?></td>
		  <td>
		      <span class="edituser"><a href="?page=user-listings&action=edit&uid=<?php echo trim($user_id); ?>">Edit</a></span> |
		      <span class="deleteuser"><a href="?page=user-listings&action=del&uid=<?php echo trim($user_id); ?>" onClick="return confirm('Are you sure ? You want to delete this member')">Delete</a></span> |
		      <span class="stuser"><a href="?page=user-listings&action=<?php echo trim($actiontxt); ?>&uid=<?php echo trim($user_id); ?>"><?php echo ucfirst($actiontxt); ?></a></span>
			  
		</td>
		  </tr>	
		  
			<?php $k++; }//end foreach ?>
		  
		  </table>
   </div> 	
							
					    <?php
						
						
						}else if($actionval=="del"){
							
							 /*** Start Delete users  ***/
							 
							 // Success!
							 
							$udelted = wp_delete_user( $acuid );
							
							if($udelted){ 
							
							$msg = "Successfully deleted.";
							$cls = "rsucess";
							
							}else{
								
							$msg = "Error in deleting.";	
							$cls = "rerror";
									
							}
							
							/*  if($del){
								 
							 $msg = "User Deleted Successfully";
							 $cls = "rsucess";
							 
						   }else{
							 
							 $msg = "Error in deleting user"; 
							 $cls = "rerror"; 
							 
						 }	 */
						 /*** End Delete Users   ***/
	?>

	
						 
	<?php if(!empty($msg)){ ?>
		  <div class="form-msg <?php echo $cls; ?>"> 
		  <?php echo $msg;  ?>
		  </div>
		  <?php }//end if ?>
      <div class="table-responsive">
		  <table class="table">
		  <tr>
		  <th>ID</th>
		  <th>NAME</th>
		  <th>EMAIL</th>
		 <!-- <th>TYPE</th>-->
		  <th>SEX</th>
		  <th>CITY</th>
		  <th>Services</th>
		  <th>Areas</th>
		  <th>STATUS</th>
		  <th>ACTION</th>
		  </tr>
		  
		  <?php $k=1;
		     $userobjdel = get_users( $userargs );
		  
            foreach($userobjdel as $users){
				
              $user_id = $users->ID;
			  $role = $users->roles[0];
              //$ustatus = $users->data->user_status;
			  $uemail = $users->data->user_email;
			  $profilenm = $users->data->display_name;
			  $uworkspaces = get_user_meta( $user_id, "_uworkspaces", true);
			  $ucity = get_user_meta( $user_id, "_ucity", true);
			  $uservices = get_user_meta( $user_id, "_uservices", true);
			  $uprofiltext = get_user_meta( $user_id, "_uprofiltext", true);	
			  $ustatus = get_user_meta( $user_id, "_ustatus", true);
			  $usex = get_user_meta($user_id, '_usex', true);
			  $ucity = get_user_meta($user_id, '_ucity', true);

              $uworkspaceswords = join(', ', array_map('ucfirst', explode(',', $uworkspaces)));
              $userviceswords = join(', ', array_map('ucfirst', explode(',', $uservices)));	

               if($ustatus==0){ $statusval = "Inactive"; $actiontxt = "approve"; }else{ $statusval = "Active"; $actiontxt = "disapprove"; }			  

		  ?>
		  <tr>
		  <td><?php echo $k; ?></td>
		  <td><?php echo $profilenm; ?></td>
		  <td><?php echo $uemail; ?></td>
		  <!--<td><?php //echo $role; ?></td>-->
		   <td><?php if($usex!=""){ echo $usex; }else{ echo "N/A"; } ?></td>
		  <td><?php if($ucity!=""){ echo $ucity; }else{ echo "N/A"; } ?></td>
		  <td><?php if($userviceswords!=""){ echo $userviceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($uworkspaceswords!=""){ echo $uworkspaceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($statusval!=""){ echo $statusval; }else{ echo "N/A"; } ?></td>
		  <td>
		      <span class="edituser"><a href="?page=user-listings&action=edit&uid=<?php echo trim($user_id); ?>">Edit</a></span> |
		      <span class="deleteuser"><a href="?page=user-listings&action=del&uid=<?php echo trim($user_id); ?>" onClick="return confirm('Are you sure ? You want to delete this member')">Delete</a></span> |
		      <span class="stuser"><a href="?page=user-listings&action=<?php echo trim($actiontxt); ?>&uid=<?php echo trim($user_id); ?>"><?php echo ucfirst($actiontxt); ?></a></span>
			  
		</td>
		  </tr>	
		  
			<?php $k++; }//end foreach ?>
		  
		  </table>
   </div> 						 
						 
						 
						 
						 
						 
   <?php 
						
				 }else if($actionval=="edit"){
							
						/** Edit User **/	
						
				      //echo "this is edit section";
					  $uid = $acuid;
					 
					
					/** Start saving user data  **/

					if(isset($_POST['saveuser'])){
						
						extract($_POST);
						
						//echo "<pre>"; print_r($_POST);
						
						  $edituworkspacesstr = implode(",", $edituworkspaces);
						  $edituservicesstr = implode(",", $edituservices);
						 
						
						$user_id = wp_update_user( array( 'ID' => $uid, 'display_name' => $editprofilename ) );

						if ( is_wp_error( $user_id ) ) {
							// There was an error, probably that user doesn't exist.
						    $msg = "Error in updating. Please try again";
							$cls = "rerror";
							
						} else {
							// Success!
							$msg = "Successfully Updated.";
							$cls = "rsucess";
							
						}
						
					
					   update_user_meta($uid, '_ucity', $editucity); 	
					   update_user_meta($uid, '_uaddr', $editucontact);   
					   update_user_meta($uid, '_uworkspaces', $edituworkspacesstr); 	
					   update_user_meta($uid, '_uservices', $edituservicesstr); 	
					   update_user_meta($uid, '_utelephone', $editutelephon); 
					   update_user_meta($uid, '_uprofiltext', $edituprofiltext); 
					
						
						
					}

					/* if(isset($_POST['saveuserprofil'])){
						
						extract($_POST);
						//echo "<pre>"; print_r($_POST);
						
					update_user_meta($uid, '_uprofiltext', $edituprofiltext); 		
					$msg = "Successfully Updated.";
					$cls = "rsucess";	
						
						
					}	 */

					if(isset($_POST['saveusergalleria'])){
						
						 extract($_POST);
						//echo "<pre>"; print_r($_POST);
						//echo "<pre>"; print_r($_FILES);
						$farr = array();
						$ds  = "/";  //1
					 
						$storeFolder = 'useruploads';   //2
						//echo $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
					 
					if (!empty($_FILES)) {
						 
						 $tempFile = $_FILES['filesa']['tmp_name'];          //3             
						  
						 //echo $targetPath = dirname(dirname( __FILE__ )) . $ds. $storeFolder . $ds;  //4
						 echo $targetPath = get_template_directory().$ds. "useruploads" . $ds;  //4
						 
						 //echo "<br>"; 
						  //echo get_template_directory().$ds. "useruploads" . $ds;
						  
						 $targetFile =  $targetPath. $_FILES['filesa']['name'];  //5
					 
						 $fmoved = move_uploaded_file($tempFile, $targetFile); //6
					   
					   if($fmoved){ 
					   
						$uimgs = get_user_meta($uid, '_uimages', true); 
						
						if($uimgs!=""){  
		
						$uimgsarr = explode(",", $uimgs); 	
						//echo "<pre>";print_r($uimgsarr);
						array_push($uimgsarr,$_FILES['filesa']['name']);
						//echo "<pre>";print_r($uimgsarr);
						 $uimgsnew = implode(",",$uimgsarr);
						 update_user_meta($uid, '_uimages', $uimgsnew );
						 
						}else{
							
						  $ppic = get_user_meta($uid, '_uprofilepic', true);
							
							if($ppic!=""){
							
							  //$uimgsnew = $_FILES['filesa']['name'];
							  
							  update_user_meta($uid, '_uimages', $_FILES['filesa']['name'] );
							  
							}else{
								   update_user_meta($uid, '_uprofilepic', $_FILES['filesa']['name'] );	
								
							}
						  
						  
						}
						
						
						$msg = "Image Successfully Uploaded.";
						$cls = "rsucess";	
						//echo "moved - $fmoved";	
						//$farr['filenm'] = $_FILES['file']['name'];
						//header('Content-Type: application/json');
						//print_r();
						//echo json_encode($farr);
						
						   
					   }else{
						 
						  $msg = "Error in image uploading.";
						  $cls = "rerror";	   
						   
					   }
						 
					}
							
						
						
						
					}	

					/* if(isset($_POST['saveusergallerib'])){
						
						 extract($_POST); 
						//echo "<pre>"; print_r($_POST);
						
						
					}	 */

					/* if(isset($_POST['saveuseradgangskode'])){
						
						 extract($_POST);
						//echo "<pre>"; print_r($_POST);	
						
						if( $current_user && wp_check_password( $oldupassword, $current_user->data->user_pass, $current_user->ID) ){
							
						   
						   if($newupassword == $connewupassword){
							   
							wp_set_password( $newupassword, $current_user->ID );
									$creds = array();
									$creds['user_login'] = $current_user->data->user_login;
									$creds['user_password'] = $newupassword;
								   /* if($userrem){
										$creds['remember'] = true;
									}
									else{
										$creds['remember'] = false;
									}*/
									/**$user_ = wp_signon( $creds, false );
									if ( is_wp_error($user_) ){
										
									$msg = $user_->get_error_message(); 	
									
									}else{
										
											$msg = "Password changed successfully."; 
											$cls = "rsucess";			
									}
						  }else{
							  
							$msg = "Password donot match";  
							$cls = "rerror";
							  
						  }	 
						
						}else{
						 
						 $msg = "Wrong old password"; 
						 $cls = "rerror";
							
							
						}	
						
						
					}	 */

					if(isset($_POST['saveuserstatus'])){
						
						 extract($_POST);
						 //echo "<pre>"; print_r($_POST);	
						 if(isset($accountstatus)){
							update_user_meta($uid, '_accountstatus', $accountstatus);  
						 }else{
							$active = 1; 
							update_user_meta($uid, '_accountstatus', $active);   
						 }
						 
						 update_user_meta($uid, '_userflag', $edituserflag); 
						 
						 $msg = "Status Updated Successfully.";
						 $cls = "rsucess";		
						 
						
						
						
						
						
					}	

					/** End saving user data  **/
						
					/**** get user fields   ***/	
						
					$user = get_user_by('id', $acuid);
					//echo "<pre>";print_r($user);
						
					$uid = $user->ID;
					$role = $user->roles[0];
					  
					$profilename = $user->data->display_name;
					$username = $user->data->user_login;
					$uemail = $user->data->user_email;

					$ucity = get_user_meta($uid, '_ucity', true);
					$uaddr = get_user_meta($uid, '_uaddr', true);
					$uworkspaces = get_user_meta($uid, '_uworkspaces', true);
					$uservices = get_user_meta($uid, '_uservices', true);
					$usex = get_user_meta($uid, '_usex', true);
					$uprofiltext = get_user_meta($uid, '_uprofiltext', true);

					$uimages = get_user_meta($uid, '_uimages', true);

					if($uimages!=""){
					$uimagesarr = explode(",", $uimages);
					}else{
						
					$uimagesarr = array();
						
					}

					$uworkspacesarr = explode(",", $uworkspaces);
					$uservicesarr = explode(",", $uservices);


					$uprofilepic = get_user_meta( $uid, "_uprofilepic", true);

					$utelephone = get_user_meta( $uid, "_utelephone", true);

					$remarr = array();
					$remarr[] = $uprofilepic;

					//echo "<pre>"; print_r($uimagesarr); 
					//echo "<pre>"; print_r($remarr); 

					if($uprofilepic != ""){

						$uimagesarr = array_diff($uimagesarr, $remarr);	
						
					}else{
						
						 $uprofilepic = 	$uimagesarr[0];
						
					}

					//echo "<pre>"; print_r($uimagesarr); 
					//echo "<pre>";print_r($uimagesarr);
					 $acntst = get_user_meta($uid, '_accountstatus', true); 
					 $uflag = get_user_meta($uid, '_userflag', true); 
					 
					 /**** end get user fields   ***/	
					
 ?>
 
 
          <div class="dashborad-wrap">
			<h1 class="page-title">Edit User</h1>
			   <hr>
                 <?php if(!empty($msg)){ ?>
					<div class="form-msg <?php echo $cls; ?>"> 
					 <h5><?php echo $msg; ?> </h5>
					</div>
				<?php }//end if  ?>
	
				<div class="tab-content">
				  
				  	<div class="kontakt" id="kontakt">
						<form method="post" action="" class="editfrmkontakt" id="editfrmkontakt">
						   <div class="form-group input-group-sm col-sm-6">
								<strong>E-mail</strong>
								<input type="text" name="edituemail" placeholder="Din e-mail" disabled="" value="<?php echo $uemail; ?>" required="" class="form-control">
							</div>
							<div class="form-group input-group-sm col-sm-6">
								<strong>User name</strong>
								<input type="text" name="editprofilename" placeholder="Brugernavn" value="<?php echo $profilename; ?>"  class="form-control">
							</div>
							<?php if($role!="privat"){ ?>
							<div class="form-group input-group-sm col-sm-6 only_profile">
								<strong>City name (The city user in)</strong>
								<!--<input type="text" name="editucity" placeholder="Den by du arbejder i" value="<?php //echo $ucity; ?>" required="" class="form-control">-->
								
														<select name="editucity" id="editucity" class="form-control ctown">
						  <option value="">Vælg byer</option>
						  <option value="Aabenraa" <?php if($ucity == "Aabenraa"){ ?>selected="selected"<?php } ?>>Aabenraa</option>
						  <option value="Aabybro" <?php if($ucity == "Aabybro"){ ?>selected="selected"<?php } ?>>Aabybro</option>
						  <option value="Aakirkeby" <?php if($ucity == "Aakirkeby"){ ?>selected="selected"<?php } ?>>Aakirkeby</option>
						  <option value="Aalborg" <?php if($ucity == "Aalborg"){ ?>selected="selected"<?php } ?>>Aalborg</option>
						  <option value="Ålsgårde" <?php if($ucity == "Ålsgårde"){ ?>selected="selected"<?php } ?>>Ålsgårde</option>
						  <option value="Aarhus" <?php if($ucity == "Aarhus"){ ?>selected="selected"<?php } ?>>Aarhus</option>
						  <option value="Aars" <?php if($ucity == "Aars"){ ?>selected="selected"<?php } ?>>Aars</option>
						  <option value="Albertslund" <?php if($ucity == "Albertslund"){ ?>selected="selected"<?php } ?>>Albertslund</option>
						  <option value="Allerød" <?php if($ucity == "Allerød"){ ?>selected="selected"<?php } ?>>Allerød</option>
						  <option value="Allinge" <?php if($ucity == "Allinge"){ ?>selected="selected"<?php } ?>>Allinge</option>
						  <option value="Assens" <?php if($ucity == "Assens"){ ?>selected="selected"<?php } ?>>Assens</option>
						  <option value="Bagsværd" <?php if($ucity == "Bagsværd"){ ?>selected="selected"<?php } ?>>Bagsværd</option>
						  <option value="Ballerup" <?php if($ucity == "Ballerup"){ ?>selected="selected"<?php } ?>>Ballerup</option>
						  <option value="Beder-Malling" <?php if($ucity == "Beder-Malling"){ ?>selected="selected"<?php } ?>>Beder-Malling</option>
						  <option value="Bellinge" <?php if($ucity == "Bellinge"){ ?>selected="selected"<?php } ?>>Bellinge</option>
						  <option value="Billund" <?php if($ucity == "Billund"){ ?>selected="selected"<?php } ?>>Billund</option>
						  <option value="Birkerød" <?php if($ucity == "Birkerød"){ ?>selected="selected"<?php } ?>>Birkerød</option>
						  <option value="Bjerringbro" <?php if($ucity == "Bjerringbro"){ ?>selected="selected"<?php } ?>>Bjerringbro</option>
						  <option value="Borup" <?php if($ucity == "Borup"){ ?>selected="selected"<?php } ?>>Borup</option>
						  <option value="Brande" <?php if($ucity == "Brande"){ ?>selected="selected"<?php } ?>>Brande</option>
						  <option value="Brøndby" <?php if($ucity == "Brøndby"){ ?>selected="selected"<?php } ?>>Brøndby</option>
						  <option value="Brøndby Strand" <?php if($ucity == "Brøndby Strand"){ ?>selected="selected"<?php } ?>>Brøndby Strand</option>
						  <option value="Brønderslev" <?php if($ucity == "Brønderslev"){ ?>selected="selected"<?php } ?>>Brønderslev</option>
						  <option value="Brønshøj" <?php if($ucity == "Brønshøj"){ ?>selected="selected"<?php } ?>>Brønshøj</option>
						  <option value="Brørup" <?php if($ucity == "Brørup"){ ?>selected="selected"<?php } ?>>Brørup</option>
						  <option value="Børkop" <?php if($ucity == "Børkop"){ ?>selected="selected"<?php } ?>>Børkop</option>
						  <option value="Charlottenlund" <?php if($ucity == "Charlottenlund"){ ?>selected="selected"<?php } ?>>Charlottenlund</option>
						  <option value="Dianalund" <?php if($ucity == "Dianalund"){ ?>selected="selected"<?php } ?>>Dianalund</option>
						  <option value="Dragør" <?php if($ucity == "Dragør"){ ?>selected="selected"<?php } ?>>Dragør</option>
						  <option value="Dronningmølle" <?php if($ucity == "Dronningmølle"){ ?>selected="selected"<?php } ?>>Dronningmølle</option>
						  <option value="Dyssegård" <?php if($ucity == "Dyssegård"){ ?>selected="selected"<?php } ?>>Dyssegård</option>
						  <option value="Esbjerg" <?php if($ucity == "Esbjerg"){ ?>selected="selected"<?php } ?>>Esbjerg</option>
						  <option value="Espergærde" <?php if($ucity == "Espergærde"){ ?>selected="selected"<?php } ?>>Espergærde</option>
						  <option value="Farum" <?php if($ucity == "Farum"){ ?>selected="selected"<?php } ?>>Farum</option>
						  <option value="Fensmark" <?php if($ucity == "Fensmark"){ ?>selected="selected"<?php } ?>>Fensmark</option>
						  <option value="Fløng" <?php if($ucity == "Fløng"){ ?>selected="selected"<?php } ?>>Fløng</option>
						  <option value="Fredensborg" <?php if($ucity == "Fredensborg"){ ?>selected="selected"<?php } ?>>Fredensborg</option>
						   <option value="Fredericia" <?php if($ucity == "Fredericia"){ ?>selected="selected"<?php } ?>>Fredericia</option>
						   <option value="Frederiksberg C" <?php if($ucity == "Frederiksberg C"){ ?>selected="selected"<?php } ?>>Frederiksberg C</option>
						   <option value="Frederikshavn" <?php if($ucity == "Frederikshavn"){ ?>selected="selected"<?php } ?>>Frederikshavn</option>
						   <option value="Frederikssund" <?php if($ucity == "Frederikssund"){ ?>selected="selected"<?php } ?>>Frederikssund</option>
						   <option value="Frederiksværk" <?php if($ucity == "Frederiksværk"){ ?>selected="selected"<?php } ?>>Frederiksværk</option>
						   <option value="Faaborg" <?php if($ucity == "Faaborg"){ ?>selected="selected"<?php } ?>>Faaborg</option>
						   <option value="Galten" <?php if($ucity == "Galten"){ ?>selected="selected"<?php } ?>>Galten</option>
						   <option value="Gentofte" <?php if($ucity == "Gentofte"){ ?>selected="selected"<?php } ?>>Gentofte</option>
						   <option value="Gilleleje" <?php if($ucity == "Gilleleje"){ ?>selected="selected"<?php } ?>>Gilleleje</option>
						   <option value="Give" <?php if($ucity == "Give"){ ?>selected="selected"<?php } ?>>Give</option>
						   <option value="Glostrup" <?php if($ucity == "Glostrup"){ ?>selected="selected"<?php } ?>>Glostrup</option>
						   <option value="Grenaa" <?php if($ucity == "Grenaa"){ ?>selected="selected"<?php } ?>>Grenaa</option>
						   <option value="Grindsted" <?php if($ucity == "Grindsted"){ ?>selected="selected"<?php } ?>>Grindsted</option>
						   <option value="Græsted" <?php if($ucity == "Græsted"){ ?>selected="selected"<?php } ?>>Græsted</option>
						   <option value="Gråsten" <?php if($ucity == "Gråsten"){ ?>selected="selected"<?php } ?>>Gråsten</option>
						   <option value="Gudhjem" <?php if($ucity == "Gudhjem"){ ?>selected="selected"<?php } ?>>Gudhjem</option>
						   <option value="Gørløse" <?php if($ucity == "Gørløse"){ ?>selected="selected"<?php } ?>>Gørløse</option>
						   <option value="Hadsten" <?php if($ucity == "Hadsten"){ ?>selected="selected"<?php } ?>>Hadsten</option>
						   <option value="Hadsund" <?php if($ucity == "Hadsund"){ ?>selected="selected"<?php } ?>>Hadsund</option>
						   <option value="Hammel" <?php if($ucity == "Hammel"){ ?>selected="selected"<?php } ?>>Hammel</option>
						   <option value="Hasle" <?php if($ucity == "Hasle"){ ?>selected="selected"<?php } ?>>Hasle</option>
						   <option value="Haslev" <?php if($ucity == "Haslev"){ ?>selected="selected"<?php } ?>>Haslev</option>
						   <option value="Hedehusene" <?php if($ucity == "Hedehusene"){ ?>selected="selected"<?php } ?>>Hedehusene</option>
						   <option value="Hedehusene" <?php if($ucity == "Hedehusene"){ ?>selected="selected"<?php } ?>>Hedehusene</option>
						   <option value="Hele landet" <?php if($ucity == "Hele landet"){ ?>selected="selected"<?php } ?>>Hele landet</option>
						   <option value="Hellerup" <?php if($ucity == "Hellerup"){ ?>selected="selected"<?php } ?>>Hellebæk</option>
						   <option value="Helsinge" <?php if($ucity == "Helsinge"){ ?>selected="selected"<?php } ?>>Helsinge</option>
						   <option value="Helsingør" <?php if($ucity == "Helsingør"){ ?>selected="selected"<?php } ?>>Helsingør</option>
						  <option value="Herlev" <?php if($ucity == "Herlev"){ ?>selected="selected"<?php } ?>>Herlev</option>
						  <option value="Hillerød" <?php if($ucity == "Hillerød"){ ?>selected="selected"<?php } ?>>Hillerød</option>
						  <option value="Hinnerup" <?php if($ucity == "Hinnerup"){ ?>selected="selected"<?php } ?>>Hinnerup</option>
						  <option value="Hirtshals" <?php if($ucity == "Hirtshals"){ ?>selected="selected"<?php } ?>>Hirtshals</option>
						  <option value="Hjørring" <?php if($ucity == "Hjørring"){ ?>selected="selected"<?php } ?>>Hjørring</option>
						  <option value="Hobro" <?php if($ucity == "Hobro"){ ?>selected="selected"<?php } ?>>Hobro</option>
						  <option value="Holbæk" <?php if($ucity == "Holbæk"){ ?>selected="selected"<?php } ?>>Holbæk</option>
						  <option value="Holstebro" <?php if($ucity == "Holstebro"){ ?>selected="selected"<?php } ?>>Holstebro</option>
						  <option value="Holte" <?php if($ucity == "Holte"){ ?>selected="selected"<?php } ?>>Holte</option>
						  <option value="Hornbæk" <?php if($ucity == "Hornbæk"){ ?>selected="selected"<?php } ?>>Hornbæk</option>
						  <option value="Hornslet" <?php if($ucity == "Hornslet"){ ?>selected="selected"<?php } ?>>Hornslet</option>
						  <option value="Horsens" <?php if($ucity == "Horsens"){ ?>selected="selected"<?php } ?>>Horsens</option>
						  <option value="Humlebæk" <?php if($ucity == "Humlebæk"){ ?>selected="selected"<?php } ?>>Humlebæk</option>
						  <option value="Hundested" <?php if($ucity == "Hundested"){ ?>selected="selected"<?php } ?>>Hundested</option>
						  <option value="Hvidovre" <?php if($ucity == "Hvidovre"){ ?>selected="selected"<?php } ?>>Hvidovre</option>
						  <option value="Høng" <?php if($ucity == "Høng"){ ?>selected="selected"<?php } ?>>Høng</option>
						  <option value="Hørning" <?php if($ucity == "Hørning"){ ?>selected="selected"<?php } ?>>Hørning</option>
						  <option value="Hørsholm" <?php if($ucity == "Hørsholm"){ ?>selected="selected"<?php } ?>>Hørsholm</option>
						  <option value="Ikast" <?php if($ucity == "Ikast"){ ?>selected="selected"<?php } ?>>Ikast</option>
						  <option value="Ishøj" <?php if($ucity == "Ishøj"){ ?>selected="selected"<?php } ?>>Ishøj</option>
						  <option value="Jyderup" <?php if($ucity == "Jyderup"){ ?>selected="selected"<?php } ?>>Jyderup</option>
						  <option value="Jyllinge" <?php if($ucity == "Jyllinge"){ ?>selected="selected"<?php } ?>>Jyllinge</option>
						  <option value="Jægerspris" <?php if($ucity == "Jægerspris"){ ?>selected="selected"<?php } ?>>Jægerspris</option>
						  <option value="Kastrup" <?php if($ucity == "Kastrup"){ ?>selected="selected"<?php } ?>>Kastrup</option>
						  <option value="Kerteminde" <?php if($ucity == "Kerteminde"){ ?>selected="selected"<?php } ?>>Kerteminde</option>
						  <option value="Kirke Hvalsø" <?php if($ctown == "Kirke Hvalsø"){ ?>selected="selected"<?php } ?>>Kirke Hvalsø</option>
						  <option value="Kjellerup" <?php if($ucity == "Kjellerup"){ ?>selected="selected"<?php } ?>>Kjellerup</option>
						   <option value="Klampenborg" <?php if($ucity == "Klampenborg"){ ?>selected="selected"<?php } ?>>Klampenborg</option>
						   <option value="Klarup" <?php if($ucity == "Klarup"){ ?>selected="selected"<?php } ?>>Klarup</option>
						   <option value="Klemensker" <?php if($ucity == "Klemensker"){ ?>selected="selected"<?php } ?>>Klemensker</option>
						   <option value="Kokkedal" <?php if($ucity == "Kokkedal"){ ?>selected="selected"<?php } ?>>Kokkedal</option>
						   <option value="Kolding" <?php if($ucity == "Kolding"){ ?>selected="selected"<?php } ?>>Kolding</option>
						   <option value="Kongens Lyngby" <?php if($ucity == "Kongens Lyngby"){ ?>selected="selected"<?php } ?>>Kongens Lyngby</option>
						   <option value="Korsør" <?php if($ucity == "Korsør"){ ?>selected="selected"<?php } ?>>Korsør</option>
						   <option value="Korsør" <?php if($ucity == "Korsør"){ ?>selected="selected"<?php } ?>>Korsør</option>
						   <option value="Kvistgård" <?php if($ucity == "Kvistgård"){ ?>selected="selected"<?php } ?>>Kvistgård</option>
						   <option value="København" <?php if($ucity == "København"){ ?>selected="selected"<?php } ?>>København</option>
						   <option value="København K" <?php if($ucity == "København K"){ ?>selected="selected"<?php } ?>>København K</option>
						   <option value="København N" <?php if($ucity == "København N"){ ?>selected="selected"<?php } ?>>København N</option>

						   <option value="København NV" <?php if($ucity == "København NV"){ ?>selected="selected"<?php } ?>>København NV</option>
						   <option value="København S" <?php if($ucity == "København S"){ ?>selected="selected"<?php } ?>>København S</option>
						   <option value="København SV" <?php if($ucity == "København SV"){ ?>selected="selected"<?php } ?>>København SV</option>
						   <option value="København V" <?php if($ucity == "København V"){ ?>selected="selected"<?php } ?>>København V</option>
						   <option value="København Ø" <?php if($ucity == "København Ø"){ ?>selected="selected"<?php } ?>>København Ø</option>
						   <option value="Køge" <?php if($ucity == "Køge"){ ?>selected="selected"<?php } ?>>Køge</option>
						   <option value="Langeskov" <?php if($ucity == "Langeskov"){ ?>selected="selected"<?php } ?>>Langeskov</option>
						   <option value="Lemvig" <?php if($ucity == "Lemvig"){ ?>selected="selected"<?php } ?>>Lemvig</option>
						   <option value="Lillerød" <?php if($ucity == "Lillerød"){ ?>selected="selected"<?php } ?>>Lillerød</option>
						   <option value="Liseleje" <?php if($ucity == "Liseleje"){ ?>selected="selected"<?php } ?>>Liseleje</option>
						   <option value="Lynge" <?php if($ucity == "Lynge"){ ?>selected="selected"<?php } ?>>Lynge</option>
						   <option value="Lystrup" <?php if($ucity == "Lystrup"){ ?>selected="selected"<?php } ?>>Lystrup</option>
						   <option value="Løgstør" <?php if($ucity == "Løgstør"){ ?>selected="selected"<?php } ?>>Løgstør</option>
						   <option value="Løgten" <?php if($ucity == "Løgten"){ ?>selected="selected"<?php } ?>>Løgten</option>
						   <option value="Løsning" <?php if($ucity == "Løsning"){ ?>selected="selected"<?php } ?>>Løsning</option>
						   <option value="Maribo" <?php if($ucity == "Maribo"){ ?>selected="selected"<?php } ?>>Maribo</option>
						   <option value="Melby" <?php if($ucity == "Melby"){ ?>selected="selected"<?php } ?>>Melby</option>
						   <option value="Middelfart" <?php if($ucity == "Middelfart"){ ?>selected="selected"<?php } ?>>Middelfart</option>
						   <option value="Munkebo" <?php if($ucity == "Munkebo"){ ?>selected="selected"<?php } ?>>Munkebo</option>
						   <option value="Måløv" <?php if($ucity == "Måløv"){ ?>selected="selected"<?php } ?>>Måløv</option>
						   <option value="Mårslet" <?php if($ucity == "Mårslet"){ ?>selected="selected"<?php } ?>>Mårslet</option>
						   <option value="Nakskov" <?php if($ucity == "Nakskov"){ ?>selected="selected"<?php } ?>>Nakskov</option>
						   <option value="Nexø" <?php if($ucity == "Nexø"){ ?>selected="selected"<?php } ?>>Nexø</option>
						   <option value="Nibe" <?php if($ucity == "Nibe"){ ?>selected="selected"<?php } ?>>Nibe</option>
						   <option value="Nivå" <?php if($ucity == "Nivå"){ ?>selected="selected"<?php } ?>>Nivå</option>
						   <option value="Nordborg" <?php if($ucity == "Nordborg"){ ?>selected="selected"<?php } ?>>Nordborg</option>
						   <option value="Nyborg" <?php if($ucity == "Nyborg"){ ?>selected="selected"<?php } ?>>Nyborg</option>
						   <option value="Nykøbing F" <?php if($ucity == "Nykøbing F"){ ?>selected="selected"<?php } ?>>Nykøbing F</option>
						   <option value="Nykøbing M" <?php if($ucity == "Nykøbing M"){ ?>selected="selected"<?php } ?>>Nykøbing M</option>
						   <option value="Nykøbing S" <?php if($ucity == "Nykøbing S"){ ?>selected="selected"<?php } ?>>Nykøbing S</option>
						   <option value="Nærum" <?php if($ucity == "Nærum"){ ?>selected="selected"<?php } ?>>Nærum</option>
						   <option value="Næstved" <?php if($ucity == "Næstved"){ ?>selected="selected"<?php } ?>>Næstved</option>
						   <option value="Nørresundby" <?php if($ucity == "Nørresundby"){ ?>selected="selected"<?php } ?>>Nørresundby</option>
						   <option value="Odder" <?php if($ucity == "Odder"){ ?>selected="selected"<?php } ?>>Odder</option>
						   <option value="Odense" <?php if($ucity == "Odense"){ ?>selected="selected"<?php } ?>>Odense</option>
						   <option value="Otterup" <?php if($ucity == "Otterup"){ ?>selected="selected"<?php } ?>>Otterup</option>
						   <option value="Padborg" <?php if($ucity == "Padborg"){ ?>selected="selected"<?php } ?>>Padborg</option>
						   <option value="Randers" <?php if($ucity == "Randers"){ ?>selected="selected"<?php } ?>>Randers</option>
						   <option value="Ribe" <?php if($ucity == "Ribe"){ ?>selected="selected"<?php } ?>>Ribe</option>
						   <option value="Ringe" <?php if($ucity == "Ringe"){ ?>selected="selected"<?php } ?>>Ringe</option>
						   <option value="Ringkøbing" <?php if($ucity == "Ringkøbing"){ ?>selected="selected"<?php } ?>>Ringkøbing</option>
						   <option value="Ringkøbing" <?php if($ucity == "Ringkøbing"){ ?>selected="selected"<?php } ?>>Ringkøbing</option>
							<option value="Roskilde" <?php if($ucity == "Roskilde"){ ?>selected="selected"<?php } ?>>Roskilde</option>
							<option value="Rudkøbing" <?php if($ucity == "Rudkøbing"){ ?>selected="selected"<?php } ?>>Rudkøbing</option>
							<option value="Rungsted Kyst" <?php if($ucity == "Rungsted Kyst"){ ?>selected="selected"<?php } ?>>Rungsted Kyst</option>
							<option value="Ry" <?php if($ucity == "Ry"){ ?>selected="selected"<?php } ?>>Ry</option>
							<option value="Rødekro" <?php if($ucity == "Rødekro"){ ?>selected="selected"<?php } ?>>Rødekro</option>
							<option value="Rødovre" <?php if($ucity == "Rødovre"){ ?>selected="selected"<?php } ?>>Rødovre</option>
							<option value="Rønne" <?php if($ucity == "Rønne"){ ?>selected="selected"<?php } ?>>Rønne</option>
							<option value="Sakskøbing" <?php if($ucity == "Sakskøbing"){ ?>selected="selected"<?php } ?>>Sakskøbing</option>
							<option value="Silkeborg" <?php if($ucity == "Silkeborg"){ ?>selected="selected"<?php } ?>>Silkeborg</option>
							<option value="Skagen" <?php if($ucity == "Skagen"){ ?>selected="selected"<?php } ?>>Skagen</option>
							<option value="Skanderborg" <?php if($ucity == "Skanderborg"){ ?>selected="selected"<?php } ?>>Skanderborg</option>
							<option value="Skibby" <?php if($ucity == "Skibby"){ ?>selected="selected"<?php } ?>>Skibby</option>
							<option value="Skive" <?php if($ucity == "Skive"){ ?>selected="selected"<?php } ?>>Skive</option>
							<option value="Skjern" <?php if($ucity == "Skjern"){ ?>selected="selected"<?php } ?>>Skjern</option>
							<option value="Skodsborg" <?php if($ucity == "Skodsborg"){ ?>selected="selected"<?php } ?>>Skodsborg</option>
							<option value="Skovlunde" <?php if($ucity == "Skovlunde"){ ?>selected="selected"<?php } ?>>Skovlunde</option>
							<option value="Skælskør" <?php if($ucity == "Skælskør"){ ?>selected="selected"<?php } ?>>Skælskør</option>
							<option value="Skævinge" <?php if($ucity == "Skævinge"){ ?>selected="selected"<?php } ?>>Skævinge</option>
							<option value="Slagelse" <?php if($ucity == "Slagelse"){ ?>selected="selected"<?php } ?>>Slagelse</option>
							<option value="Slangerup" <?php if($ucity == "Slangerup"){ ?>selected="selected"<?php } ?>>Slangerup</option>
							<option value="Smørum" <?php if($ucity == "Smørum"){ ?>selected="selected"<?php } ?>>Smørum</option>
							<option value="Smørumnedre" <?php if($ucity == "Smørumnedre"){ ?>selected="selected"<?php } ?>>Smørumnedre</option>
							<option value="Snekkersten" <?php if($ucity == "Snekkersten"){ ?>selected="selected"<?php } ?>>Snekkersten</option>
							<option value="Solrød Strand" <?php if($ucity == "Solrød Strand"){ ?>selected="selected"<?php } ?>>Solrød Strand</option>
							<option value="Sorø" <?php if($ucity == "Sorø"){ ?>selected="selected"<?php } ?>>Sorø</option>
							<option value="Stenløse" <?php if($ucity == "Stenløse"){ ?>selected="selected"<?php } ?>>Stenløse</option>
							<option value="Strib" <?php if($ucity == "Strib"){ ?>selected="selected"<?php } ?>>Strib</option>
							<option value="Struer" <?php if($ucity == "Struer"){ ?>selected="selected"<?php } ?>>Struer</option>
							<option value="Strøby Egede" <?php if($ucity == "Strøby Egede"){ ?>selected="selected"<?php } ?>>Strøby Egede</option>
							<option value="Støvring" <?php if($ucity == "Støvring"){ ?>selected="selected"<?php } ?>>Støvring</option>
							<option value="Sunds" <?php if($ucity == "Sunds"){ ?>selected="selected"<?php } ?>>Sunds</option>
							<option value="Svaneke" <?php if($ucity == "Svaneke"){ ?>selected="selected"<?php } ?>>Svaneke</option>
							<option value="Svejbæk" <?php if($ucity == "Svejbæk"){ ?>selected="selected"<?php } ?>>Svejbæk</option>
							<option value="Svendborg" <?php if($ucity == "Svendborg"){ ?>selected="selected"<?php } ?>>Svendborg</option>
							<option value="Svenstrup" <?php if($ucity == "Svenstrup"){ ?>selected="selected"<?php } ?>>Svenstrup</option>
							<option value="Svogerslev" <?php if($ucity == "Svogerslev"){ ?>selected="selected"<?php } ?>>Svogerslev</option>
							<option value="Sæby" <?php if($ucity == "Sæby"){ ?>selected="selected"<?php } ?>>Sæby</option>
							<option value="Søborg" <?php if($ucity == "Søborg"){ ?>selected="selected"<?php } ?>>Søborg</option>
							<option value="Sønderborg" <?php if($ucity == "Sønderborg"){ ?>selected="selected"<?php } ?>>Sønderborg</option>
							<option value="Tarm" <?php if($ucity == "Tarm"){ ?>selected="selected"<?php } ?>>Tarm</option>
							<option value="Thisted" <?php if($ucity == "Thisted"){ ?>selected="selected"<?php } ?>>Thisted</option>
							<option value="Tikøb" <?php if($ucity == "Tikøb"){ ?>selected="selected"<?php } ?>>Tikøb</option>
							<option value="Tisvildeleje" <?php if($ucity == "Tisvildeleje"){ ?>selected="selected"<?php } ?>>Tisvildeleje</option>
							<option value="Tune" <?php if($ucity == "Tune"){ ?>selected="selected"<?php } ?>>Tune</option>
							<option value="Tønder" <?php if($ucity == "Tønder"){ ?>selected="selected"<?php } ?>>Tønder</option>
							<option value="Taastrup" <?php if($ucity == "Taastrup"){ ?>selected="selected"<?php } ?>>Taastrup</option>
							<option value="Valby" <?php if($ucity == "Valby"){ ?>selected="selected"<?php } ?>>Valby</option>
							<option value="Vallensbæk" <?php if($ucity == "Vallensbæk"){ ?>selected="selected"<?php } ?>>Vallensbæk</option>
							<option value="Vallensbæk Strand" <?php if($ctown == "Vallensbæk Strand"){ ?>selected="selected"<?php } ?>>Vallensbæk Strand</option>
							<option value="Vamdrup" <?php if($ucity == "Vamdrup"){ ?>selected="selected"<?php } ?>>Vamdrup</option>
							<option value="Vanløse" <?php if($ucity == "Vanløse"){ ?>selected="selected"<?php } ?>>Vanløse</option>
							<option value="Varde" <?php if($ucity == "Varde"){ ?>selected="selected"<?php } ?>>Varde</option>
							<option value="Vedbæk" <?php if($ucity == "Vedbæk"){ ?>selected="selected"<?php } ?>>Vedbæk</option>
							<option value="Vejby" <?php if($ucity == "Vejby"){ ?>selected="selected"<?php } ?>>Vejby</option>
							<option value="Vejen" <?php if($ucity == "Vejen"){ ?>selected="selected"<?php } ?>>Vejen</option>
							<option value="Vejle" <?php if($ucity == "Vejle"){ ?>selected="selected"<?php } ?>>Vejle</option>
							<option value="Veksø Sjælland" <?php if($ctown == "Veksø Sjælland"){ ?>selected="selected"<?php } ?>>Veksø Sjælland</option>
							<option value="Viborg" <?php if($ucity == "Viborg"){ ?>selected="selected"<?php } ?>>Viborg</option>
							<option value="Viby S" <?php if($ucity == "Viby S"){ ?>selected="selected"<?php } ?>>Viby S</option>
							<option value="Videbæk" <?php if($ucity == "Videbæk"){ ?>selected="selected"<?php } ?>>Videbæk</option>
							<option value="Vildbjerg" <?php if($ucity == "Vildbjerg"){ ?>selected="selected"<?php } ?>>Vildbjerg</option>
							<option value="Virum" <?php if($ucity == "Virum"){ ?>selected="selected"<?php } ?>>Virum</option>
							<option value="Vodskov" <?php if($ucity == "Vodskov"){ ?>selected="selected"<?php } ?>>Vodskov</option>
							<option value="Vojens" <?php if($ucity == "Vojens"){ ?>selected="selected"<?php } ?>>Vojens</option>
							<option value="Vordingborg" <?php if($ucity == "Vordingborg"){ ?>selected="selected"<?php } ?>>Vordingborg</option>
							<option value="Værløse" <?php if($ucity == "Værløse"){ ?>selected="selected"<?php } ?>>Værløse</option>
							<option value="Ølsted" <?php if($ucity == "Ølsted"){ ?>selected="selected"<?php } ?>>Ølsted</option>
							<option value="Ølstykke" <?php if($ucity == "Ølstykke"){ ?>selected="selected"<?php } ?>>Ølstykke</option>
							<option value="Østermarie" <?php if($ucity == "Østermarie"){ ?>selected="selected"<?php } ?>>Østermarie</option>
							
						</select>
														

								
							</div>
							<div class="form-group input-group-sm col-sm-6 only_profile">
								<strong>Contact information</strong>
								<input type="text" name="editucontact" placeholder="Tlf" value="<?php echo $uaddr;  ?>" required="" class="form-control">
							</div>
							
							<div class="form-group input-group-sm col-sm-6 only_profile">
								<strong>Telephon</strong>
								<input type="text" name="editutelephon" placeholder="Tlf" value="<?php echo $utelephone;  ?>" required="" class="form-control">
							</div>
							
							<div class="form-group input-group-sm col-sm-6 only_profile">
							 <strong>Workspaces</strong>
							    <select name="edituworkspaces[]" id="uworkspaces" class="form-control" multiple>
									<option value="Bornholm" <?php if(in_array("Bornholm", $uworkspacesarr)){ echo "selected"; } ?>>Bornholm</option>
								<option value="Hele landet" <?php if(in_array("Hele landet", $uworkspacesarr)){ echo "selected"; } ?>>Hele landet</option>
								<option value="Midtfyn" <?php if(in_array("Midtfyn", $uworkspacesarr)){ echo "selected"; } ?>>Midtfyn</option>
								<option value="Midtjylland" <?php if(in_array("Midtjylland", $uworkspacesarr)){ echo "selected"; } ?>>Midtjylland</option>
								<option value="Midtsjælland" <?php if(in_array("Midtsjælland", $uworkspacesarr)){ echo "selected"; } ?>>Midtsjælland</option>
								<option value="Nordsjælland" <?php if(in_array("Nordsjælland", $uworkspacesarr)){ echo "selected"; } ?>>Nordsjælland</option>
								<option value="Nordjylland" <?php if(in_array("Nordjylland", $uworkspacesarr)){ echo "selected"; } ?>>Nordjylland</option>
								<option value="Nordfyn" <?php if(in_array("Nordfyn", $uworkspacesarr)){ echo "selected"; } ?>>Nordfyn</option>
								<option value="Østfyn" <?php if(in_array("Østfyn", $uworkspacesarr)){ echo "selected"; } ?>>Østfyn</option>
								<option value="Østjylland" <?php if(in_array("Østjylland", $uworkspacesarr)){ echo "selected"; } ?>>Østjylland</option>
								<option value="Østsjælland" <?php if(in_array("Østsjælland", $uworkspacesarr)){ echo "selected"; } ?>>Østsjælland</option>
								<option value="Storkøbenhavn" <?php if(in_array("Storkøbenhavn", $uworkspacesarr)){ echo "selected"; } ?>>Storkøbenhavn</option>
								<option value="Sydsjælland" <?php if(in_array("Sydsjælland", $uworkspacesarr)){ echo "selected"; } ?>>Sydsjælland</option>
								<option value="Sydfyn" <?php if(in_array("Sydfyn", $uworkspacesarr)){ echo "selected"; } ?>>Sydfyn</option>
								<option value="Vestfyn" <?php if(in_array("Vestfyn", $uworkspacesarr)){ echo "selected"; } ?>>Vestfyn</option>
								<option value="Vestjylland" <?php if(in_array("Vestjylland", $uworkspacesarr)){ echo "selected"; } ?>>Vestjylland</option>
								<option value="Vestsjælland" <?php if(in_array("Vestsjælland", $uworkspacesarr)){ echo "selected"; } ?>>Vestsjælland</option>
								<option value="Sjælland" <?php if(in_array("Sjælland", $uworkspacesarr)){ echo "selected"; } ?>>Sjælland</option>
								<option value="Fyn" <?php if(in_array("Fyn", $uworkspacesarr)){ echo "selected"; } ?>>Fyn</option>
								<option value="Jylland" <?php if(in_array("Jylland", $uworkspacesarr)){ echo "selected"; } ?>>Jylland</option>
								</select>

							</div>
							<div class="form-group input-group-sm col-sm-6 only_profile">
							<strong>Services</strong>
								<select name="edituservices[]" id="uservices" class="form-control" multiple>
								<option value="escort" <?php if(in_array("escort", $uservicesarr)){ echo "selected"; } ?>>Escort</option>
								<option value="massage" <?php if(in_array("massage", $uservicesarr)){ echo "selected"; } ?>>Massage</option>
								<option value="dominans" <?php if(in_array("dominans", $uservicesarr)){ echo "selected"; } ?>>Dominans</option>
							  </select>
							</div>
					<div class="col-xs-12">
						<strong>Profile description</strong>
						  <textarea name="edituprofiltext" id="edituprofiltext" class="form-control" style="height:250px">
						
						    <?php echo trim($uprofiltext); ?>
							
                        </textarea>	
                        <button type="submit" name="saveuser" class="btn btn-purple-light pull-right save-setting"><i class="fa fa-cog"></i> save settings</button>
					</div>
				 <?php }//end if ?>
						
						</form>
					</div><!-- #kontakt  --->
					<?php /**
				  <div class="profil" id="profil">
				  	<div class="row">
					<form method="post" action="" class="editfrmprofil" id="editfrmprofil">
					  <div class="col-xs-12">
						<strong>Profile description</strong>
						  <textarea name="edituprofiltext" id="edituprofiltext" class="form-control" style="height:250px">
						
						    <?php echo $uprofiltext; ?>
							
                        </textarea>	
					</div>
					<button type="submit" name="saveuserprofil" class="btn btn-purple-light pull-right"><i class="fa fa-cog"></i> Gem indstillinger</button>
						
				  </form>
						
				  
					</div>
				  </div><!-- #profil  --->
				 */ ?>
				 
				  <div class="galleri" id="galleri">
				  
				     <form method="post" action="" class="editfrmgalleria" id="editfrmgalleria" enctype="multipart/form-data">
					    	<div class="upload-pictures">
					    		<h3>Upload pictures</h3>
					    		<p>Upload photos to your gallery. Images must be in JPG / JPEG format. </p>
					    		<input type="file" name="filesa"> 
					    		<br>
					    		<button type="submit" name="saveusergalleria" class="btn btn-success"><i class="fa fa-upload"></i> Upload billeder</button>
					    	</div><!-- Upload pictures -->
						</form>
					    	<div class="your-pics-wrap">
					    		<h3>User pictures</h3>
					    		
					    		<div class="galeriwrap">
								<div class="col-md-3 col-sm-3">
									
									   <?php if($uprofilepic != ""){ ?>
									   <div class="profile_cnt">
										<a href="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $uprofilepic; ?>" class="swipebox">
										
										  <img src="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $uprofilepic; ?>" title="" alt=""/>
											
										</a>
										</div>
										<br>
										
										<a href="javascript:;" class="btn delbtn btn-xs btn-danger confirm" data="<?php echo $uprofilepic; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-trash-o"></i> Remove</a>
										
										<a href="javascript:;" class="btn btn-xs btn-success" data="<?php echo $uprofilepic; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-check"></i> Profile picture</a>
                                        <br><br>
										
									  <?php  }else{ ?>
									  <?php if(count($uimagesarr)>0){ ?>
									  <div class="profile_cnt">
									  <a href="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $uimagesarr[0]; ?>" class="swipebox">
									  
									   <img src="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $uimagesarr[0]; ?>" title="" alt=""/>
											
										</a>
									 </div>
										<br>
										
										<a href="javascript:;" class="btn delbtn btn-xs btn-danger confirm" data="<?php echo $uprofilepic; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-trash-o"></i> Remove</a>
										<a href="javascript:;" class="btn btn-xs btn-success" data="<?php echo $uprofilepic; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-check"></i> Profile picture</a>

									  <br><br>
									  <?php } ?>
									  <?php } ?>
									</div>
									
								<?php 
								
								 /* echo "COUNT".count($uimagesarr); 
								echo "<pre>"; print_r($uimagesarr);   */
								
								if(count($uimagesarr)>0){ 
								
								?>	
								<?php foreach($uimagesarr as $galimg){ ?>
								<div class="col-md-3 col-sm-3">
								       <div class="generic_cnt">
										<a href="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $galimg; ?>" class="swipebox">
									  
									   <img src="<?php bloginfo('template_url'); ?>/useruploads/<?php echo $galimg; ?>" title="" alt=""/>
											
										</a>
										</div>
										<br>
										
										<a href="javascript:;" class="btn delbtn btn-xs btn-danger confirm"data="<?php echo $galimg; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-trash-o"></i> Remove</a>
										<a href="javascript:;" class="btn profilebtn btn-xs btn-default" data="<?php echo $galimg; ?>" data-uid="<?php echo $uid; ?>"><i class="fa fa-check"></i> Profile picture</a>
										<input type="hidden" name="Profilbilledeimg" value="<?php echo $galimg; ?>">
									  <br><br>
								</div>
								<?php }//end while ?>
								<?php }//end if ?> 
																				
								</div>
						 </div>	  
							
				    </div><!-- #galleri  --->

				  <?php /**	
				  <div class="adgangskode" id="adgangskode">
				    <form method="post" action="" class="editfrmadgangskode" id="editfrmadgangskode">
				  	<div class="form-group input-group-sm">
						<strong>Current password</strong>
						<input type="password" name="oldupassword" autocomplete="off" placeholder="Angiv din nuværende"  class="form-control">
					</div>
				  	<div class="form-group input-group-sm">
						<strong>New password</strong>
						<input type="password" name="newupassword" autocomplete="off" placeholder="Adgangskode, min 6 tegn"  class="form-control">
					</div>
					<div class="form-group input-group-sm">
						<strong>Repeat new password</strong>
						<input type="password" name="connewupassword" autocomplete="off" placeholder="Gentag dine nye adgangskode"  class="form-control">
					</div>
					  
					<button type="submit" name="saveuseradgangskode" class="btn btn-purple-light pull-right"><i class="fa fa-cog"></i> Gem indstillinger</button>
					</form>
				  </div><!-- #adgangskode -->
				  */ ?>

				  <?php if($role!="privat"){ ?>
				  <div class="status" id="status">
				   <form method="post" action="" class="editfrmstatus" id="editfrmstatus">
					<div class="form-group input-group-sm">
						<strong>Select status for your profile</strong>
						<select name="edituserflag" class="form-control selectpicker">
							<option value="1" <?php if($uflag == "1"){ ?> selected="" <?php } ?>>Active</option>
							<option value="0" <?php if($uflag == "0"){ ?> selected="" <?php } ?>>Inactive   </option>
						</select>
					</div>
					<div class="form-group input-group-sm">
						<strong>Disable profile</strong><br>
						
						<div class="checkbox">
		                	<input id="delete" type="checkbox" name="accountstatus" class="deletebtn" value="0" style="opacity: 1;" <?php if($acntst == 0){ ?> checked="checked" <?php } ?>>
							
		                	
							<a href="javascript:;" id="deleteBtn" class="disabled btn-default">Check the box if you want to deactivate your profile</a>
						</div>
					</div>
					<button type="submit" name="saveuserstatus" class="btn btn-purple-light pull-right"><i class="fa fa-cog"></i> save settings</button>
					</form>
				  </div><!-- # status -->
				  <?php }//end if ?>
				  
				  
				</div>
		     
		      
		      
			</div>
			
			<script>
		$(function(){
			
			$(".profilebtn").click(function(){
		  
		  //alert('jhjhjk');
		  var pimg = $(this).attr("data");
		  //alert(img);
		  var uid = $(this).attr("data-uid");
		  //alert(uid);
		   var datastringpr = "spec=profile&pimg="+pimg+"&uid="+uid;
		  //alert(datastringdel);
		  
		  var request = $.ajax({
							  type: "POST",
							  url: "<?php echo get_page_link(256); ?>",
							  data: datastringpr,
							  cache: false,
							  success: function(data){
								  //alert(data);   
								 $(".galeriwrap").html(data);
							 }
						   });
					  
					  request.done(function(msg) {
						  //alert("done");
						  
					  });
					  
					  

		});

		$(".delbtn").click(function(){
		  
		  //alert('jhjhjk');
		  var pimg = $(this).attr("data");
		  //alert(pimg);
		  var uid = $(this).attr("data-uid");
		  //alert(uid);
		  var datastringdel = "spec=del&pimg="+pimg+"&uid="+uid;
		  //alert(datastringdel);
		  var request = $.ajax({
							  type: "POST",
							  url: "<?php echo get_page_link(256); ?>",
							  data: datastringdel,
							  cache: false,
							  success: function(data){
								  //alert(data);   
								 $(".galeriwrap").html(data);
							 }
						   });
					  
					  request.done(function(msg) {
						  //alert("done");
						  
					  });
		  


		});

		});
			
		</script>
			
			<script>
				/* $(function(){
					
				$('#uservices').multiselect({
							nonSelectedText: 'Vælg ydelser'
						});	
				$('#uworkspaces').multiselect({
							nonSelectedText: 'Vælg områder'
						});	
				}); */
         </script>
		 
 
 
 <?php 
			 
					   /*** End editing user  ***/
							
						}						 
						 
						
						}else{
  ?>
      <?php if(!empty($msg)){ ?>
		  <div class="form-msg <?php echo $cls; ?>"> 
		  <?php echo $msg;  ?>
		  </div>
		  <?php }//end if ?>
      <div class="table-responsive">
		  <table class="table">
		  <tr>
		  <th>ID</th>
		  <th>NAME</th>
		  <th>EMAIL</th>
		  <!--<th>TYPE</th>-->
	      <th>SEX</th>
		  <th>CITY</th>
		  <th>Services</th>
		  <th>Areas</th>
		  <th>STATUS</th>
		  <th>ACTION</th>
		  </tr>
		  
		  <?php $k= ($offset+1);
		  
            foreach($userobj as $users){
				
              $user_id = $users->ID;
			  $role = $users->roles[0];
              //$ustatus = $users->data->user_status;
			  $uemail = $users->data->user_email;
			  $profilenm = $users->data->display_name;
			  $uworkspaces = get_user_meta( $user_id, "_uworkspaces", true);
			  $ucity = get_user_meta( $user_id, "_ucity", true);
			  $uservices = get_user_meta( $user_id, "_uservices", true);
			  $uprofiltext = get_user_meta( $user_id, "_uprofiltext", true);	
			  $ustatus = get_user_meta( $user_id, "_ustatus", true);
			  $usex = get_user_meta($user_id, '_usex', true);
			  $ucity = get_user_meta($user_id, '_ucity', true);

              $uworkspaceswords = join(', ', array_map('ucfirst', explode(',', $uworkspaces)));
              $userviceswords = join(', ', array_map('ucfirst', explode(',', $uservices)));	

               if($ustatus==0){ $statusval = "Inactive"; $actiontxt = "approve"; }else{ $statusval = "Active"; $actiontxt = "disapprove"; }			  

		  ?>
		  <tr>
		  <td><?php echo $k; ?></td>
		  <td><?php echo $profilenm; ?></td>
		  <td><?php echo $uemail; ?></td>
		  <!--<td><?php //echo $role; ?></td>-->
		 <td><?php if($usex!=""){ echo $usex; }else{ echo "N/A"; } ?></td>
		  <td><?php if($ucity!=""){ echo $ucity; }else{ echo "N/A"; } ?></td>
		  <td><?php if($userviceswords!=""){ echo $userviceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($uworkspaceswords!=""){ echo $uworkspaceswords; }else{ echo "N/A"; } ?></td>
		  <td><?php if($statusval!=""){ echo $statusval; }else{ echo "N/A"; } ?></td>
		  <td>
		      <span class="edituser"><a href="?page=user-listings&action=edit&uid=<?php echo trim($user_id); ?>">Edit</a></span> |
		      <span class="deleteuser"><a href="?page=user-listings&action=del&uid=<?php echo trim($user_id); ?>" onClick="return confirm('Are you sure ? You want to delete this member')">Delete</a></span> |
		      <span class="stuser"><a href="?page=user-listings&action=<?php echo trim($actiontxt); ?>&uid=<?php echo trim($user_id); ?>"><?php echo ucfirst($actiontxt); ?></a></span>
			  
		 </td>
		  </tr>	
		  
			<?php $k++; }//end foreach ?>
		  
		  </table>
   </div> 
  <?php 
			}//end if
  ?>
  
 <?php  if($actionval!='edit'){
		if ($total_users > $total_query) {
			
		echo '<div id="pagination" class="clearfix">';
 ?>
 
	<ul class="page-numbers">
	
	<?php $ikk=0; for($pn=$total_pages; $pn>=1; $pn--){  $ikk++; ?>
	
	<?php 
	        
			
	
	?>
	
	<li class="<?php if($ikk == $paged){ echo "active"; } ?>"><a class="page-numbers" href="?page=user-listings&paged=<?php echo $ikk; ?>"><?php echo $ikk; ?></a></li>
	
	<?php } ?>
	
	
   </ul>
   
  <?php 
		echo '</div>';
		
		}
 }
  ?>
  
   <?php   
   
         /** Pagination Start  **/

		/*  // grab the current query parameters
		$query_string = $_SERVER['QUERY_STRING'];

		// The $base variable stores the complete URL to our page, including the current page arg

		// if in the admin, your base should be the admin URL + your page
		echo $base = admin_url() . '?' . remove_query_arg('p', $query_string) . '%_%';

		// if on the front end, your base is the current page
		//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';

		echo paginate_links( array(
			'base' => $base, // the base URL, including query arg
			'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
			'prev_text' => __('&laquo; Previous'), // text for previous page
			'next_text' => __('Next &raquo;'), // text for next page
			'total' => $total_pages, // the total number of pages we have
			'current' => $page, // the current page
			'end_size' => 1,
			'mid_size' => 5,
		));  */
		
		/*** Pagination End **/
 
   ?>
      </div><!-- .manageuser_cnt --->
<?php   
				 }
				 
				 

                /*  function massage_manageusers_dashboard(){
					 
					 echo "heloo sub";
					 
				 } */
				 
				

     /*
     * Actions perform on activation of plugin
     */
    function massage_install() {

     //some actions here        
  }

    /*
     * Actions perform on de-activation of plugin
     */
    function massage_uninstall() 
    {
      //some actions here        
    }
				
				
}

new Massage_Manage_users();				
