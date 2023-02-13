
<?php


  $actual_in = str_replace('/', '-', $_POST['updated_actual_date']);
  $in_actual = date("Y-m-d", strtotime($actual_in));

  $out_final = str_replace('/', '-', $_POST['updated_date_out']);
  $out_final = date("Y-m-d", strtotime($out_final));

  $current_user_id =  $_POST['current_user_id'];
  $barcode_value = $_POST['barcode_value'];

  include '../../../wp-load.php';

  $table_name = $wpdb->prefix . 'solicitors';

  if($barcode_value === "")
  {

   $import = $wpdb->update( $table_name, 
                    array( 'actual_in_date' => $in_actual , 'out_date' => $out_final ), 
                    array( 'id' => $current_user_id));
 }
 else
 {


   $import = $wpdb->update( $table_name, 
                    array( 'actual_in_date' => $in_actual , 'out_date' => $out_final, 'barcode' => $barcode_value ), 
                    array( 'id' => $current_user_id));

 }
  if( $import) : echo 1; else: echo 0; endif;