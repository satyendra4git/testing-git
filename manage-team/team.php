<?php
/*
Plugin Name: Team Management
Plugin URI: http://www.itzealot.com/
Description: This Plugin is made By Itzealot Team.
Version: 1.1
Author: Deepak Kumar Singh
Author URI: http://infoicon.co.in/
License: XXXXXXXXXXX
*/

register_activation_hook( __FILE__, 'team_install' );

function team_install () {
	global $wpdb; // wordpress connection variable
	
	$table_name = $wpdb->prefix . "team"; // write table name
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		
		if ( $wpdb->supports_collation() ) {
			if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		
		/* * Create table as per required */
		
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . "(
		member_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		member_name varchar(255) NOT NULL,
		member_post varchar(255) NOT NULL,
		member_image varchar(255) NOT NULL,
		member_logo varchar(255) NOT NULL,
		long_desc text NOT NULL,
		position int(11) NOT NULL,
		member_status int(11) NOT NULL,
		posted_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (member_id)
		) ".$charset_collate.";";
		
		
		/* * include upgrade.php for auto update and it has database library of wp. */
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql); // execute sql
		
		
		/* * insert default settings into wp_options table. Make sure that option name must be unique. */
		$toptions = $wpdb->prefix ."options";
		$defset = "INSERT INTO ".$toptions.
		"(option_name, option_value) " .
		"VALUES ('team_admng', 'update_plugins'),('team_deldata', 'yes'),".
		"('team_readmore', 'Read More'),('team_setlimit', '1')";
		$dodef = $wpdb->query( $defset );
	}


	/*
	* update version in options table.
	* It is not mandatory if you want to add feature any thing in this plugin then use below code.
	*/
	
	delete_option("team_version");
	add_option("team_version", "1");
}


register_deactivation_hook( __FILE__, 'team_deactivate' );

/* * team_deactivate is used to deactivate plugin */

function team_deactivate () {
	global $wpdb;
	
	$table_name = $wpdb->prefix . "team"; // table name
	
	$team_deldata = get_option('team_deldata'); // get value of "team_deldata" option from option table
	
	
	/* * Delete options from option table for this plugin */
	if ($team_deldata == 'yes') {
		$wpdb->query("DROP TABLE {$table_name}");
		delete_option("team_version");
		delete_option("team_readmore");
		delete_option("team_deldata");
		delete_option("team_setlimit");
		delete_option("team_admng");
	}
}

/*
* Add below function on top of the page and below the activate and deactive function
*/

add_action('init', 'team_addcss');


/* * Adding css file */
function team_addcss() {
	wp_enqueue_style('team_css', '/' . PLUGINDIR . '/manage-team/css/team-style.css' );
}

add_action('admin_menu', 'team_addpages');

function team_addpages() {

if (get_option('team_admng') == '') { $team_admng = 'update_plugins'; } else {$team_admng = get_option('team_admng'); }
	
	add_menu_page('Manage Members', 'Member List', $team_admng, 'team_manage', 'manage_team_member');
	add_submenu_page( 'team_manage', 'Add Member', 'Add Member', $team_admng, 'add_member', 'add_team_member' );
}


/*
* team_adminpage method will the added teams and add form of team.
*/
function manage_team_member() {
	global $wpdb;
	
	
	echo $string = '<div class="wrap"><h2>Manage Members</h2></div>';
	
	
	/* * Edit Member */
	if ($_GET['mode']=='edit_form') {
		team_edit($_REQUEST['member_id']);
		exit;
	}
	
	if (isset($_POST['team_edit_do'])) {
		update_member($_REQUEST['member_id']);
		echo '<div class="message">Member has been updated successfully.</div>';
	}
	
	if (isset($_POST['update_member'])) {
		team_member_update();
		echo '<div class="message">Member has been updated successfully.</div>';
	}
	
	if (isset($_REQUEST['mode'])=='delete') {
		team_delete($_REQUEST['member_id']);
		echo '<div class="message">Member has been deleted successfully.</div>';
	}
	
	/* * Add Member */
	
	if (isset($_POST['add_member'])) {
		team_insertnew();
		echo '<div class="message">Member has been added successfully.</div>';
	}
	
	
	echo $string = '<h2>Member List</h2><div>'.team_showlist().'</div>';
	
}

function add_team_member() {
	global $wpdb;
	
	if($_GET['page'] == 'add_member'){
		 echo $string = '<h3>Add New Member</h3>
	<div>'.team_newform().'</div>';
	}
	

}


/* * Create new form of team */

function team_newform() {
	return $string = '<form name="addnew" method="post" enctype="multipart/form-data" action="?page=team_manage">
	<table class="team_plugin">
	<tr>
	<td>Player Name : </td>
	<td><input name="member_name" id="member_name" type="text" size="38"></td>
	</tr>
	<tr>
	<td>Player Post:</td>
	<td><input name="member_post" id="member_post" type="text" size="10"></td>
	</tr>
	<tr>
	<td>Player Image:</td>
	<td><input name="member_image" id="member_image" type="file" size="55"></td>
	</tr>

	<tr>
	<td>Player Logo:</td>
	<td><input name="member_logo" id="member_logo" type="file" size="55"></td>
	</tr>

	<tr>
	<td>Player Details:</td>
	<td><input type="text" name="long_desc" id="long_desc" size="45" ></td>
	</tr>
	
	<tr>
	<td></td>
	<td><input type="submit" name="add_member" value="Add Member" class="sumnitBtn" /></td>
	</tr>
	</table>
	</form>';
}


/* * Insert record in database */

function team_insertnew() {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
	$imgFolderPath = ABSPATH . 'wp-content/plugins/manage-team/uploads/';
	$logoFolderPath = ABSPATH . 'wp-content/plugins/manage-team/logo/';
	
	$member_name = $wpdb->escape($_POST['member_name']);
	$member_post = $wpdb->escape($_POST['member_post']);
	$long_desc = $wpdb->escape($_POST['long_desc']);
	
	$imageName = $wpdb->escape($_FILES['member_image']['name']);
	$imageTmpName = $_FILES['member_image']['tmp_name'];
	$member_image = date('ymdhis').$imageName;
	move_uploaded_file($imageTmpName, $imgFolderPath.$member_image);
	
	$memberLogo = $wpdb->escape($_FILES['member_logo']['name']);
	$memberImageTmpName = $_FILES['member_logo']['tmp_name'];
	$memberLogoName = date('ymdhis').$memberLogo;
	move_uploaded_file($memberImageTmpName, $logoFolderPath.$memberLogoName);

	
	$insertSQL = "INSERT INTO " . $table_name . " SET member_name = '$member_name', member_post = '$member_post', long_desc = '$long_desc', member_image = '$member_image', member_logo='$memberLogoName', member_status = '1', posted_date = NOW()";
	
	$results = $wpdb->query( $insertSQL );
}

function team_member_update()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	foreach($_POST['position'] as $key=>$position)
	{
		$updateSql = "UPDATE ".$table_name." SET position='$position', member_status = '".$_POST['member_status'.$key]."' WHERE member_id = '".$_POST['memberID'][$key]."'";
		$wpdb->query($updateSql);
	}
}


/* * Listing of added teams */

function team_showlist() {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	$pluginURL = plugins_url();
	$imgFolderPath = $pluginURL . '/manage-team/uploads/';
	$logoFolderPath = $pluginURL . '/manage-team/logo/';
	
	$teamsArr = $wpdb->get_results("SELECT * FROM $table_name ORDER BY position ASC");
	$returnString = '<form name="update_frm" action="" method="post"><table width="100%" cellpadding="2" cellspacing="0">
	<tr class="darkGrayBG">
		<th width="70">S. No.</th>
		<th align="left">Player Name</th>
		<th>Image</th>
		<th>Logo</th>
		<th width="60">Position</th>
		<th width="50">Status</th>
		<th width="80">Action</th>
	</tr>';
	if(count($teamsArr)){
		$i = 1;
		$counter = 0;
		foreach($teamsArr as $teamRow){
			$class = $i % 2 != '0' ? 'lightGrayBG' : 'grayBG';
			$returnString .= '<tr class="'.$class.'">
			<th>'.$i.'<input type="hidden" name="memberID[]" value="'.$teamRow->member_id.'"></th>
			<td>'.stripslashes($teamRow->member_name).'</td>
			<td align="center"><img src="'.$imgFolderPath.$teamRow->member_image.'" height="30"></td>
			<td align="center"><img src="'.$logoFolderPath.$teamRow->member_logo.'" height="30"></td>
			<td align="center"><input name="position[]" type="text" value="'.$teamRow->position.'" class="team_post"></td>
			<td align="center"><input type="checkbox" name="member_status'.$counter.'" value="1" '.($teamRow->member_status == "1" ? "checked": "" ).'></td>
			<td align="center"><a href="admin.php?page=team_manage&mode=edit_form&member_id='.$teamRow->member_id.'"><img src="'.$pluginURL.'/manage-team/images/edit.png" alt="Edit" title="Edit"></a>
			 &nbsp;<a href="admin.php?page=team_manage&mode=delete&member_id='.$teamRow->member_id.'" onClick="return confirm(\'Are you sure ? You want to delete this member\')"><img src="'.$pluginURL.'/manage-team/images/delete.png" alt="Delete" title="Delete"></a></td>
			</tr>';
			$i++; $counter++;
		}
		$returnString .= '<tr>
		<td colspan="6" align="right"><input type="submit" name="update_member" value="update" class="sumnitBtn"></td>
		</tr>';
	}
	else{
		$returnString .= '<tr>
		<td colspan="6" class="error" align="center">No Member Found.</td>
		</tr>';
	}
	$returnString .= '</table></form>';
	return $returnString;
}


/*  * Create edit team form */

function team_edit($teamid){
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	$teamArr = $wpdb->get_row("SELECT * FROM $table_name WHERE member_id = $teamid");
	
	echo '<h3>Edit Member</h3>';
	echo $string = '<form name="addnew" method="post" enctype="multipart/form-data" action="admin.php?page=team_manage">
	<table class="team_plugin">
	<tr>
	<td>Player Name : </td>
	<td><input name="member_name" id="member_name" type="text" value="'.stripslashes($teamArr->member_name).'"></td>
	</tr>
	<tr>
	<td>Player Post:</td>
	<td><input name="member_post" id="member_post" type="text" value="'.stripslashes($teamArr->member_post).'"></td>
	</tr>
	<tr>
	<td>Player Image:</td>
	<td><input name="member_image" id="member_image" type="file"></td>
	</tr>
	<tr>
	<td>Player Logo:</td>
	<td><input name="member_logo" id="member_logo" type="file"></td>
	</tr>
	<tr>
	<td>Desctiption:</td>
	<td><input type="text" name="long_desc" id="long_desc" value="'.stripslashes($teamArr->long_desc).'"></td>
	</tr>
	<tr>
	<td><input type="hidden" name="member_id" value="'.$teamArr->member_id.'">
	<input type="hidden" name="imageName" value="'.$teamArr->member_image.'"></td>
	<td><input type="submit" name="team_edit_do" value="Update Member" class="sumnitBtn" /></td>
	</tr>
	</table>
	</form>';
}


/* * update team in DB */

function update_member($memberID){
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
	$imgFolderPath = ABSPATH . 'wp-content/plugins/manage-team/uploads/';
	$logoFolderPath = ABSPATH . 'wp-content/plugins/manage-team/logo/';
	
	
	$member_name = $wpdb->escape($_POST['member_name']);
	$member_post = $wpdb->escape($_POST['member_post']);
	$long_desc = $wpdb->escape($_POST['long_desc']);
	
	$imageName = $wpdb->escape($_FILES['member_image']['name']);
	$imageTmpName = $_FILES['member_image']['tmp_name'];
	$member_image = date('ymdhis').$imageName;
	move_uploaded_file($imageTmpName, $imgFolderPath.$member_image);
	$member_image = $imageName == "" ? $_POST['imageName'] : $member_image; 
	
	
	$memberLogo = $wpdb->escape($_FILES['member_logo']['name']);
	
	$memberImageTmpName = $_FILES['member_logo']['tmp_name'];
	
	$memberLogoName = date('ymdhis').$memberLogo;
	
	move_uploaded_file($memberImageTmpName, $logoFolderPath.$memberLogoName);
	
	$memberLogoName = $memberLogoName == "" ? $_POST['member_logo'] : $memberLogoName; 


	
	$updateSql = "UPDATE " . $table_name ." SET member_name = '$member_name', member_post = '$member_post', long_desc = '$long_desc', member_image = '$member_image', member_logo='$memberLogoName' WHERE member_id = '$memberID'";
	$wpdb->query($updateSql);
}



/* * delete teams from DB */

function team_delete($teamid) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	$insert = "DELETE FROM " . $table_name . " WHERE member_id = ".$teamid ."";
	
	$results = $wpdb->query( $insert );
}



/* * List of all testimonials on front-end */

//function team_showall() {}


add_shortcode('SHOW_TEAM', 'team_showall');
/* * Listing of added teams */

function team_showall() {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	
	$pluginURL = plugins_url();
	$imgFolderPath = $pluginURL . '/manage-team/uploads/';
	$logoFolderPath = $pluginURL . '/manage-team/logo/';
	
	$teamsArr = $wpdb->get_results("SELECT * FROM $table_name ORDER BY position ASC");
	$returnString = '<form name="update_frm" action="" method="post"><table width="100%" cellpadding="2" cellspacing="0">
	<tr class="darkGrayBG" style="color:#FFFFFF;">
		<th width="70" style="color:#FFFFFF;">S. No.</th>
		<th align="left" style="color:#FFFFFF;">Player Name</th>
		<th style="color:#FFFFFF;">Image</th>
		<th style="color:#FFFFFF;">Logo</th>
	</tr>';
	if(count($teamsArr)){
		$i = 1;
		$counter = 0;
		foreach($teamsArr as $teamRow){
			$class = $i % 2 != '0' ? 'lightGrayBG' : 'grayBG';
			$returnString .= '<tr class="'.$class.'">
			<th>'.$i.'<input type="hidden" name="memberID[]" value="'.$teamRow->member_id.'"></th>
			<td>'.stripslashes($teamRow->member_name).'</td>
			<td align="center"><img src="'.$imgFolderPath.$teamRow->member_image.'" height="30"></td>
			<td align="center"><img src="'.$logoFolderPath.$teamRow->member_logo.'" height="30"></td>
			</tr>';
			$i++; $counter++;
		}
/*		$returnString .= '<tr>
		<td colspan="6" align="right"><input type="submit" name="update_member" value="update" class="sumnitBtn"></td>
		</tr>';
*/	}
	else{
/*		$returnString .= '<tr>
		<td colspan="6" class="error" align="center">No Member Found.</td>
		</tr>';
*/	}
	$returnString .= '</table></form>';
	return $returnString;
}



?>
