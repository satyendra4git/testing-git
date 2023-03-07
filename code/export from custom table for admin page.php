<?php 
///put this code in functions.php for export functionality/////

//////// add menu page //////////
function add_billing_form_menu_item()
 {
	 add_menu_page("Billing Submittion", "Billing Submittion", "manage_options", "billing-form", "settings_page", null, 99);
 }
 add_action("admin_menu", "add_billing_form_menu_item");
function settings_page(){
?>
<div class="wrap">
	<form class="fol_billing_search" method="get" action="" >
		 <p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Export:</label>
			<?php if(isset($_GET['page']) & !empty(isset($_GET['page']))){ ?> <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>"> <?php } ?>
			 <input type="submit" name="export-to-csv" class="button billing_search" value="Export to csv">
		 </p>
	</form>
</div>
<?php	
}	
////////////////////// export ////////////////////////////////

function bbg_csv_export() {
	if ( ! is_super_admin() ) {
		return;
	}

	if ( ! isset( $_GET['export-to-csv'] ) ) {
		return;
	}
	$filename = 'fol-billing-' . time() . '.csv';
	$header_row = array(
		0 => 'Display Name',
		1 => 'Email',
		2 => 'Institution',
		3 => 'Registration Date',
	);

	$data_rows = array();

	global $wpdb, $bp;
	$users = $wpdb->get_results( "SELECT ID, user_email, user_registered FROM {$wpdb->users} WHERE user_status = 0" );
    //echo "<pre>";print_r($users);die;
	foreach ( $users as $u ) {
		$row = array();
		$row[0] = get_user_by( 'id', $u->ID )->display_name;
		$row[1] = $u->user_email;
		$row[2] = 'test info';
		$row[3] = $u->user_registered;

		$data_rows[] = $row;
	}

	$fh = @fopen( 'php://output', 'w' );

	fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );

	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );

	fputcsv( $fh, $header_row );

	foreach ( $data_rows as $data_row ) {
		fputcsv( $fh, $data_row );
	}

	fclose( $fh );
	die();
}
add_action( 'admin_init', 'bbg_csv_export' );

