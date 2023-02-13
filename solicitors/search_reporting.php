<?php

   include '../../../wp-load.php'; 

     $ref_number = $_POST['ref_number'];
     $user_name = $_POST['user_name'];
     $branch = $_POST['branch'];
     $entered = $_POST['entered'];

     global $wpdb;
     $table_name = $wpdb->prefix . 'solicitors';
     //$current_user_id = get_current_user_id(); 

       echo '<table name="sol_dashboard" id="sol_dashboard" method="post" style="border:2px">
                  <tr>
                  <th>S.NO</th>
                  <th>BarCode</th>
                  <th>User Name</th>
                  <th>Ref Number</th>
                  <th>Branch</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Notes</th>
                  <th>File Boxes Number</th>
                  <th>No Sleaves</th>
                  <th>Deposit/Collection IN</th>
                  <th>In Date</th>
                  <th>Actual In Date</th>
                  <th>Retrieval/Delivery OUT</th>
                  <th>Out Date</th>
                  <th>Action</th>
                  
                  </tr>';
    
     if(!empty($ref_number))
     {
           $results = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_number = '$ref_number'");

                    $i=1;
                          foreach($results as $result)
                          { 
                                 echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';
                                
                           $i++; ?>
                     
                  <?php } '</table></div>'; 
        
    }
    
     elseif(!empty($user_name))
     {

   
         $results = $wpdb->get_results("SELECT * FROM $table_name WHERE name = '$user_name'");

                    $i=1;
                          foreach($results as $result)
                          { 
                               
                                echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';
                                
                           $i++; ?>
                      
                  <?php } '</table></div>'; 

    }

     elseif(!empty($branch))
     {
    

      $results = $wpdb->get_results("SELECT * FROM $table_name WHERE branch = '$branch'");

                    $i=1;
                          foreach($results as $result)
                          { 
                               
                               echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';

                                
                           $i++; ?>
                      
                  <?php } '</table></div>'; 
   

    }



     elseif(!empty($entered))
     {


       $results = $wpdb->get_results("SELECT * FROM $table_name WHERE  user_name = '$entered'");

                    $i=1;
                          foreach($results as $result)
                          { 
                                 echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';

                                
                           $i++; ?>
                      
                  <?php } '</table></div>'; 

     

    }

    elseif(empty($ref_number && $user_name && $branch && $entered))
     {
                 $results = $wpdb->get_results("SELECT * FROM $table_name");

                    $i=1;
                          foreach($results as $result)
                          { 
                               
                                echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';

                                
                           $i++; ?>
                     
                  <?php } '</table></div>'; 

            
    }

    elseif(empty($branch && $entered))
     {
      
        echo "SELECT * FROM $table_name WHERE  ref_number = '$ref_number' AND name = '$user_name' AND user_id = $current_user_id";
        $all_row = $wpdb->get_results( "SELECT * FROM $table_name WHERE  ref_number = '$ref_number' AND name = '$user_name' AND user_id = $current_user_id");
        if( $wpdb->num_rows > 0 ){
        foreach($all_row as $all_rows)
          { 
          
           echo '<tr>';

                                echo '<td style="display: none;">'.$i.'</td>';
                                echo '<td id="user_id">'.$result->id .'</td>';

                                echo '<td><div id="barcodeTarget'.$result->id .'" class="barcodeTarget">'.$result->barcode .'</div><input type="text" id="barcodeValue'.$result->id .'" value="'.$result->barcode .'" style="display:none;"></td>';
                                echo '<td>'.$result->user_name .'</td>';
                                echo '<td>'.$result->ref_number .'</td>';
                                echo '<td>'.$result->branch .'</td>';
                                echo '<td>'.$result->name .'</td>';
                                echo '<td>'.$result->description .'</td>';
                                echo '<td>'.$result->notes .'</td>';
                                echo '<td>'.$result->no_of_file_boxes .'</td>';
                                echo '<td>'.$result->no_sleaves .'</td>';
                                echo '<td>'.$result->deposit_collection_in .'</td>';
                              
                                $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="in_date">'.$date1->format('d/m/Y') .'</td>';
                                 } 
                                 else{ echo "<td id='in_date'>00/00/0000</td>"; };

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date2->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='actual_date'>00/00/0000</td>"; }

                                echo '<td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in'.$result->id .'" value="'.$date2->format('d/m/Y').'"><input type="text" class="dateall" id="datepicker'.$result->id .'" value="'.$date2->format('d/m/Y').'"></td>';
                                 echo '<td>'.$result->delivery_retrieval_out .'</td>';
                
                                 $date3 = new DateTime($result->out_date);
                                 if(($result->out_date) != '0000-00-00 00:00:00'){
                                echo '<td id="actual_date">'.$date3->format('d/m/Y') .'</td>';
                                  }
                                 else{ echo "<td id='out_date'>00/00/0000</td>"; };

                                echo '<td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out'.$result->id .'" value="'.$date3->format('d/m/Y').'"><input type="text" class="datefinal" id="datepicker1'.$result->id .'" value="'.$date3->format('d/m/Y').'"></td>';
                echo '<input type="hidden" id="txtbarcode'.$i.'" value="'.$result->ref_number.'"/>';
                                echo '<td><a class="editbutton">Edit</a></td>'; 
                                echo '<td><a class="updatebutton" style="display:none;">Update</a></td>'; 
                                echo '</tr>';

                                
                           $i++; ?>
                     
                  <?php } '</table></div>'; 

          }
        }
        else
        {
          echo "No Result Found";

        

    }
  ?>