<?php


 include '../../../wp-load.php';  
 global $wpdb;

 $table_name = $wpdb->prefix . 'solicitors';


       $ref_num = $_POST['ref_number'];
       $user_name = $_POST['user_name'];
       $branch = $_POST['branch'];
       $entered = $_POST['entered'];
       $club_id = $_POST['club_id']; ?>
       <table name="sol_dashboard" id="sol_dashboard" method="post" style="border:2px">
                  <tr>
                  <th>User ID</th>
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
                  <th>Action</th></tr>

      <?php if(!empty($ref_num)){
        
         

        if (!(isset($_GET['pagenum']))) { 
           $pagenum = 1; 
        } else {
          $pagenum = intval($_GET['pagenum']);    
        }

        //Number of results displayed per page  by default its 10.
        $page_limit =  ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? intval($_GET["show"]) : 10;

        $sql = "SELECT count(*) as count FROM $table_name WHERE ref_number = $ref_num AND user_id = $club_id" ;
        try {
            $stmt = $wpdb->get_results($sql);

        } catch (Exception $ex) {
            echo($ex->getMessage());
        }

         $cnt = $stmt[0]->count;

        //Calculate the last page based on total number of rows and rows per page. 
         $last = ceil($cnt/$page_limit); 

        //this makes sure the page number isn't below one, or more than our maximum pages 
        if ($pagenum < 1) { 
          $pagenum = 1; 
        } elseif ($pagenum > $last)  { 
           $pagenum = $last; 
        }
        
         $lower_limit = ($pagenum - 1) * $page_limit;

       
        $sql_result = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_number = $ref_num AND user_id = $club_id  ORDER BY id DESC limit  ". ($lower_limit)." ,  ". ($page_limit). " "); 
        
       ?>
        
              <div class="all_admin_result">
             
                <?php foreach ($sql_result as $result) { ?>
                
                                      <tr>

                                <td style="display:none;"><?php echo $i; ?></td>
                                <td id="user_id"><?php echo $result->id; ?></td>

                                <td><div id="barcodeTarget<?php echo $result->id; ?>" class="barcodeTarget"><?php echo $result->barcode; ?></div><input type="text" id="barcodeValue<?php echo $result->id; ?>" value="<?php echo $result->barcode; ?>" style="display:none;"></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><?php echo $result->ref_number; ?></td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->name; ?></td>
                                <td><?php echo $result->description; ?></td>
                                <td><?php echo $result->notes; ?></td>
                                <td><?php echo $result->no_of_file_boxes; ?></td>
                                <td><?php echo $result->no_sleaves; ?></td>
                                <td><?php echo $result->deposit_collection_in; ?></td>
                              
                                <?php $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){ ?>
                                <td id="in_date"><?php echo $date1->format('d/m/Y'); ?></td>
                                 <?php } 
                                 else{ ?><td id='in_date'>00/00/0000</td> <?php }

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <td id="actual_date"><?php echo $date2->format('d/m/Y'); ?></td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>">
                                  <?php }
                                 else{ ?>
                                    <td id='actual_date'>00/00/0000</td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="00/00/0000">
                                  <?php } ?>

                              
                                  <?php if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>"></td>
                                    <?php }
                                    else{ ?>

                                        <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="00/00/0000"></td>
                                   <?php  } ?>
                                <td><?php echo $result->delivery_retrieval_out; ?></td>
                
                               <?php   $date3 = new DateTime($result->out_date);
                                        if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                        <td id="out_date"><?php echo $date3->format('d/m/Y'); ?></td><td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>">
                                 <?php  }
                                 else{ ?><td id='out_date'>00/00/0000</td> 
                                 <td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="00/00/0000"><?php } ?>
 
                                
                                  <?php if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                 <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>"></td>
                                  <?php }
                                
                                else{ ?>
                                       <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="00/00/0000"></td>
                                <?php  } ?>
                                <input type="hidden" id="txtbarcode<?php echo $i; ?>" value="<?php echo $result->ref_number; ?>"/>
                                <td><a class="editbutton">Edit</a><a class="updatebutton" style="display:none;">Update</a></td>
                               <!-- <td></td>-->
                                </tr>

                          <?php       
                           $i++;
   
    }

    ?>

            <div class="height30"></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="2"  align="center">
            <tr>
              <td valign="top" align="left">
              
            <label> Rows Limit: 
            <select name="show" onChange="changeDisplayRowCount(this.value);">
                    <option value="10" <?php if ($_GET["show"] == 10 || $_GET["show"] == "" ) { echo ' selected="selected"'; }  ?> >10</option>
                    <option value="20" <?php if ($_GET["show"] == 20) { echo ' selected="selected"'; }  ?> >20</option>
                    <option value="30" <?php if ($_GET["show"] == 30) { echo ' selected="selected"'; }  ?> >30</option>
            </select>
            </label>

              </td>
              <td valign="top" align="center" >
             
              <?php
              if ( ($pagenum-1) > 0) {
              ?>  
                        <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
                        <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
              <?php
              }
              //Show page links
              for($i=1; $i<=$last; $i++) {
                if ($i == $pagenum ) {
            ?>
                        <a href="javascript:void(0);" class="selected" ><?php echo $i ?></a>
            <?php
              } else {  
            ?>
                       <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a>
            <?php 
              }
            } 
            if ( ($pagenum+1) <= $last) {
            ?>
                    <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links">Next</a>
                  <?php } if ( ($pagenum) != $last) { ?>  
                    <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links" >Last</a> 
            <?php
              } 
            ?>
                </td>
                        <td align="right" valign="top">
                        Page <?php echo $pagenum; ?> of <?php echo $last; ?>
                        </td>
                </tr>
            </table>
            <?php }



 elseif(!empty($user_name)){
        


        if (!(isset($_GET['pagenum']))) { 
           $pagenum = 1; 
        } else {
          $pagenum = intval($_GET['pagenum']);    
        }

        //Number of results displayed per page  by default its 10.
        $page_limit =  ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? intval($_GET["show"]) : 10;

        $sql = "SELECT count(*) as count FROM $table_name WHERE name = '$user_name' " ;
        try {
            $stmt = $wpdb->get_results($sql);

        } catch (Exception $ex) {
            echo($ex->getMessage());
        }

         $cnt = $stmt[0]->count;

        //Calculate the last page based on total number of rows and rows per page. 
         $last = ceil($cnt/$page_limit); 

        //this makes sure the page number isn't below one, or more than our maximum pages 
        if ($pagenum < 1) { 
          $pagenum = 1; 
        } elseif ($pagenum > $last)  { 
           $pagenum = $last; 
        }
        
         $lower_limit = ($pagenum - 1) * $page_limit;

        
        $sql_result = $wpdb->get_results(" SELECT * FROM $table_name WHERE name = '$user_name' AND user_id = $club_id ORDER BY id DESC limit ". ($lower_limit)." ,  ". ($page_limit). " "); 
        
       ?>
        
              <div class="all_admin_result">
              <?php foreach ($sql_result as $result) { ?>
                
                                      <tr>

                                <td style="display:none;"><?php echo $i; ?></td>
                                <td id="user_id"><?php echo $result->id; ?></td>

                                <td><div id="barcodeTarget<?php echo $result->id; ?>" class="barcodeTarget"><?php echo $result->barcode; ?></div><input type="text" id="barcodeValue<?php echo $result->id; ?>" value="<?php echo $result->barcode; ?>" style="display:none;"></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><?php echo $result->ref_number; ?></td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->name; ?></td>
                                <td><?php echo $result->description; ?></td>
                                <td><?php echo $result->notes; ?></td>
                                <td><?php echo $result->no_of_file_boxes; ?></td>
                                <td><?php echo $result->no_sleaves; ?></td>
                                <td><?php echo $result->deposit_collection_in; ?></td>
                              
                                <?php $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){ ?>
                                <td id="in_date"><?php echo $date1->format('d/m/Y'); ?></td>
                                 <?php } 
                                 else{ ?><td id='in_date'>00/00/0000</td> <?php }

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <td id="actual_date"><?php echo $date2->format('d/m/Y'); ?></td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>">
                                  <?php }
                                 else{ ?>
                                    <td id='actual_date'>00/00/0000</td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="00/00/0000">
                                  <?php } ?>

                              
                                  <?php if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>"></td>
                                    <?php }
                                    else{ ?>

                                        <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="00/00/0000"></td>
                                   <?php  } ?>
                                <td><?php echo $result->delivery_retrieval_out; ?></td>
                
                               <?php   $date3 = new DateTime($result->out_date);
                                        if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                        <td id="actual_date"><?php echo $date3->format('d/m/Y'); ?></td><td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>">
                                 <?php  }
                                 else{ ?><td id='out_date'>00/00/0000</td> 
                                 <td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="00/00/0000"><?php } ?>
 
                                
                                  <?php if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                 <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>"></td>
                                  <?php }
                                
                                else{ ?>
                                       <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="00/00/0000"></td>
                                <?php  } ?>
                                <input type="hidden" id="txtbarcode<?php echo $i; ?>" value="<?php echo $result->ref_number; ?>"/>
                                <td><a class="editbutton">Edit</a><a class="updatebutton" style="display:none;">Update</a></td>
                               <!-- <td></td>-->
                                </tr>

                          <?php       
                           $i++;
   
    }

    ?>
</table>

          <div class="height30"></div>
          <table width="100%" border="0" cellspacing="0" cellpadding="2"  align="center">
          <tr>
            <td valign="top" align="left">
            
          <label> Rows Limit: 
          <select name="show" onChange="changeDisplayRowCount(this.value);">
            <option value="10" <?php if ($_GET["show"] == 10 || $_GET["show"] == "" ) { echo ' selected="selected"'; }  ?> >10</option>
            <option value="20" <?php if ($_GET["show"] == 20) { echo ' selected="selected"'; }  ?> >20</option>
            <option value="30" <?php if ($_GET["show"] == 30) { echo ' selected="selected"'; }  ?> >30</option>
          </select>
          </label>

            </td>
            <td valign="top" align="center" >
           
            <?php
            if ( ($pagenum-1) > 0) {
            ?>  
             <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
            <?php
            }
            //Show page links
            for($i=1; $i<=$last; $i++) {
              if ($i == $pagenum ) {
          ?>
              <a href="javascript:void(0);" class="selected" ><?php echo $i ?></a>
          <?php
            } else {  
          ?>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a>
          <?php 
            }
          } 
          if ( ($pagenum+1) <= $last) {
          ?>
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links">Next</a>
          <?php } if ( ($pagenum) != $last) { ?>  
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links" >Last</a> 
          <?php
            } 
          ?>
          </td>
            <td align="right" valign="top">
            Page <?php echo $pagenum; ?> of <?php echo $last; ?>
            </td>
          </tr>
          </table>

          <?php }


elseif(!empty($branch)){
        


        if (!(isset($_GET['pagenum']))) { 
           $pagenum = 1; 
        } else {
          $pagenum = intval($_GET['pagenum']);    
        }

        //Number of results displayed per page  by default its 10.
        $page_limit =  ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? intval($_GET["show"]) : 10;

        $sql = "SELECT count(*) as count FROM $table_name WHERE branch = '$branch' " ;
        try {
            $stmt = $wpdb->get_results($sql);

        } catch (Exception $ex) {
            echo($ex->getMessage());
        }

         $cnt = $stmt[0]->count;

        //Calculate the last page based on total number of rows and rows per page. 
         $last = ceil($cnt/$page_limit); 

        //this makes sure the page number isn't below one, or more than our maximum pages 
        if ($pagenum < 1) { 
          $pagenum = 1; 
        } elseif ($pagenum > $last)  { 
           $pagenum = $last; 
        }
        
         $lower_limit = ($pagenum - 1) * $page_limit;

       
        $sql_result = $wpdb->get_results(" SELECT * FROM $table_name WHERE branch = '$branch' AND user_id = $club_id ORDER BY id DESC limit ". ($lower_limit)." ,  ". ($page_limit). " "); 
        
       ?>
        
              <div class="all_admin_result">
             
                <?php foreach ($sql_result as $result) { ?>
                
                                       <tr>

                                <td style="display:none;"><?php echo $i; ?></td>
                                <td id="user_id"><?php echo $result->id; ?></td>

                                <td><div id="barcodeTarget<?php echo $result->id; ?>" class="barcodeTarget"><?php echo $result->barcode; ?></div><input type="text" id="barcodeValue<?php echo $result->id; ?>" value="<?php echo $result->barcode; ?>" style="display:none;"></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><?php echo $result->ref_number; ?></td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->name; ?></td>
                                <td><?php echo $result->description; ?></td>
                                <td><?php echo $result->notes; ?></td>
                                <td><?php echo $result->no_of_file_boxes; ?></td>
                                <td><?php echo $result->no_sleaves; ?></td>
                                <td><?php echo $result->deposit_collection_in; ?></td>
                              
                                <?php $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){ ?>
                                <td id="in_date"><?php echo $date1->format('d/m/Y'); ?></td>
                                 <?php } 
                                 else{ ?><td id='in_date'>00/00/0000</td> <?php }

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <td id="actual_date"><?php echo $date2->format('d/m/Y'); ?></td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>">
                                  <?php }
                                 else{ ?>
                                    <td id='actual_date'>00/00/0000</td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="00/00/0000">
                                  <?php } ?>

                              
                                  <?php if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>"></td>
                                    <?php }
                                    else{ ?>

                                        <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="00/00/0000"></td>
                                   <?php  } ?>
                                <td><?php echo $result->delivery_retrieval_out; ?></td>
                
                               <?php   $date3 = new DateTime($result->out_date);
                                        if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                        <td id="actual_date"><?php echo $date3->format('d/m/Y'); ?></td><td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>">
                                 <?php  }
                                 else{ ?><td id='out_date'>00/00/0000</td> 
                                 <td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="00/00/0000"><?php } ?>
 
                                
                                  <?php if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                 <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>"></td>
                                  <?php }
                                
                                else{ ?>
                                       <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="00/00/0000"></td>
                                <?php  } ?>
                                <input type="hidden" id="txtbarcode<?php echo $i; ?>" value="<?php echo $result->ref_number; ?>"/>
                                <td><a class="editbutton">Edit</a><a class="updatebutton" style="display:none;">Update</a></td>
                               <!-- <td></td>-->
                                </tr>

                          <?php       
                           $i++;
   
    }

    ?>
</table>

<div class="height30"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="2"  align="center">
<tr>
  <td valign="top" align="left">
  
<label> Rows Limit: 
<select name="show" onChange="changeDisplayRowCount(this.value);">
  <option value="10" <?php if ($_GET["show"] == 10 || $_GET["show"] == "" ) { echo ' selected="selected"'; }  ?> >10</option>
  <option value="20" <?php if ($_GET["show"] == 20) { echo ' selected="selected"'; }  ?> >20</option>
  <option value="30" <?php if ($_GET["show"] == 30) { echo ' selected="selected"'; }  ?> >30</option>
</select>
</label>

  </td>
  <td valign="top" align="center" >
 
  <?php
  if ( ($pagenum-1) > 0) {
  ?>  
   <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
  <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
  <?php
  }
  //Show page links
  for($i=1; $i<=$last; $i++) {
    if ($i == $pagenum ) {
?>
    <a href="javascript:void(0);" class="selected" ><?php echo $i ?></a>
<?php
  } else {  
?>
  <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a>
<?php 
  }
} 
if ( ($pagenum+1) <= $last) {
?>
  <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links">Next</a>
<?php } if ( ($pagenum) != $last) { ?>  
  <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links" >Last</a> 
<?php
  } 
?>
</td>
  <td align="right" valign="top">
  Page <?php echo $pagenum; ?> of <?php echo $last; ?>
  </td>
</tr>
</table>

<?php }


elseif(!empty($entered)){
        


        if (!(isset($_GET['pagenum']))) { 
           $pagenum = 1; 
        } else {
          $pagenum = intval($_GET['pagenum']);    
        }

        //Number of results displayed per page  by default its 10.
        $page_limit =  ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? intval($_GET["show"]) : 10;

        $sql = "SELECT count(*) as count FROM $table_name WHERE user_name = '$entered' " ;
        try {
            $stmt = $wpdb->get_results($sql);

        } catch (Exception $ex) {
            echo($ex->getMessage());
        }

         $cnt = $stmt[0]->count;

        //Calculate the last page based on total number of rows and rows per page. 
         $last = ceil($cnt/$page_limit); 

        //this makes sure the page number isn't below one, or more than our maximum pages 
        if ($pagenum < 1) { 
          $pagenum = 1; 
        } elseif ($pagenum > $last)  { 
           $pagenum = $last; 
        }
        
         $lower_limit = ($pagenum - 1) * $page_limit;

       
        $sql_result = $wpdb->get_results(" SELECT * FROM $table_name WHERE user_name = '$entered' AND user_id = $club_id ORDER BY id DESC limit ". ($lower_limit)." ,  ". ($page_limit). " "); 
        
       ?>
        
              <div class="all_admin_result">
            
                <?php foreach ($sql_result as $result) { ?>
                
                                         <tr>

                                <td style="display:none;"><?php echo $i; ?></td>
                                <td id="user_id"><?php echo $result->id; ?></td>

                                <td><div id="barcodeTarget<?php echo $result->id; ?>" class="barcodeTarget"><?php echo $result->barcode; ?></div><input type="text" id="barcodeValue<?php echo $result->id; ?>" value="<?php echo $result->barcode; ?>" style="display:none;"></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><?php echo $result->ref_number; ?></td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->name; ?></td>
                                <td><?php echo $result->description; ?></td>
                                <td><?php echo $result->notes; ?></td>
                                <td><?php echo $result->no_of_file_boxes; ?></td>
                                <td><?php echo $result->no_sleaves; ?></td>
                                <td><?php echo $result->deposit_collection_in; ?></td>
                              
                                <?php $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){ ?>
                                <td id="in_date"><?php echo $date1->format('d/m/Y'); ?></td>
                                 <?php } 
                                 else{ ?><td id='in_date'>00/00/0000</td> <?php }

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <td id="actual_date"><?php echo $date2->format('d/m/Y'); ?></td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>">
                                  <?php }
                                 else{ ?>
                                    <td id='actual_date'>00/00/0000</td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="00/00/0000">
                                  <?php } ?>

                              
                                  <?php if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>"></td>
                                    <?php }
                                    else{ ?>

                                        <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="00/00/0000"></td>
                                   <?php  } ?>
                                <td><?php echo $result->delivery_retrieval_out; ?></td>
                
                               <?php   $date3 = new DateTime($result->out_date);
                                        if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                        <td id="actual_date"><?php echo $date3->format('d/m/Y'); ?></td><td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>">
                                 <?php  }
                                 else{ ?><td id='out_date'>00/00/0000</td> 
                                 <td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="00/00/0000"><?php } ?>
 
                                
                                  <?php if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                 <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>"></td>
                                  <?php }
                                
                                else{ ?>
                                       <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="00/00/0000"></td>
                                <?php  } ?>
                                <input type="hidden" id="txtbarcode<?php echo $i; ?>" value="<?php echo $result->ref_number; ?>"/>
                                <td><a class="editbutton">Edit</a><a class="updatebutton" style="display:none;">Update</a></td>
                               <!-- <td></td>-->
                                </tr>

                          <?php       
                           $i++;
   
    }

    ?>
</table>

<div class="height30"></div>
<table width="50%" border="0" cellspacing="0" cellpadding="2"  align="center">
<tr>
  <td valign="top" align="left">
  
<label> Rows Limit: 
<select name="show" onChange="changeDisplayRowCount(this.value);">
  <option value="10" <?php if ($_GET["show"] == 10 || $_GET["show"] == "" ) { echo ' selected="selected"'; }  ?> >10</option>
  <option value="20" <?php if ($_GET["show"] == 20) { echo ' selected="selected"'; }  ?> >20</option>
  <option value="30" <?php if ($_GET["show"] == 30) { echo ' selected="selected"'; }  ?> >30</option>
</select>
</label>

  </td>
  <td valign="top" align="center" >
 
  <?php
  if ( ($pagenum-1) > 0) {
  ?>  
   <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
  <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
  <?php
  }
  //Show page links
  for($i=1; $i<=$last; $i++) {
    if ($i == $pagenum ) {
?>
    <a href="javascript:void(0);" class="selected" ><?php echo $i ?></a>
<?php
  } else {  
?>
  <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a>
<?php 
  }
} 
if ( ($pagenum+1) <= $last) {
?>
  <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links">Next</a>
<?php } if ( ($pagenum) != $last) { ?>  
  <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links" >Last</a> 
<?php
  } 
?>
</td>
  <td align="right" valign="top">
  Page <?php echo $pagenum; ?> of <?php echo $last; ?>
  </td>
</tr>
</table>

<?php }


  else{
  
      $club_id = $_GET['club_id'];
        if (!(isset($_GET['pagenum']))) { 
                   $pagenum = 1; 
                } else {
                  $pagenum = intval($_GET['pagenum']);    
                }

                //Number of results displayed per page  by default its 10.
                $page_limit =  ($_GET["show"] <> "" && is_numeric($_GET["show"]) ) ? intval($_GET["show"]) : 10;

                // Get the total number of rows in the table

                $sql = "SELECT count(*) as count FROM $table_name WHERE user_id = $club_id " ;
                try {
                    $stmt = $wpdb->get_results($sql);

                } catch (Exception $ex) {
                    echo($ex->getMessage());
                }

                 $cnt = $stmt[0]->count;

                //Calculate the last page based on total number of rows and rows per page. 
                 $last = ceil($cnt/$page_limit); 

                //this makes sure the page number isn't below one, or more than our maximum pages 
                if ($pagenum < 1) { 
                  $pagenum = 1; 
                } elseif ($pagenum > $last)  { 
                  $pagenum = $last; 
                }
                 $lower_limit = ($pagenum - 1) * $page_limit;

                $sql_full = " SELECT * FROM $table_name WHERE user_id = $club_id  ORDER BY id DESC limit ". ($lower_limit)." ,  ". ($page_limit). " ";
                try {
                    
                    $stmt = $wpdb->get_results($sql_full);
                  } 
                  catch (Exception $ex) {
                    echo($ex->getMessage());
                }
                        
       ?>
        
             
           <?php foreach ($stmt as $result) { ?>
                
                                <tr>

                                <td style="display:none;"><?php echo $i; ?></td>
                                <td id="user_id"><?php echo $result->id; ?></td>

                                <td><div id="barcodeTarget<?php echo $result->id; ?>" class="barcodeTarget"><?php echo $result->barcode; ?></div><input type="text" id="barcodeValue<?php echo $result->id; ?>" value="<?php echo $result->barcode; ?>" style="display:none;"></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><?php echo $result->ref_number; ?></td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->name; ?></td>
                                <td><?php echo $result->description; ?></td>
                                <td><?php echo $result->notes; ?></td>
                                <td><?php echo $result->no_of_file_boxes; ?></td>
                                <td><?php echo $result->no_sleaves; ?></td>
                                <td><?php echo $result->deposit_collection_in; ?></td>
                              
                                <?php $date1 = new DateTime($result->in_date);
                                if(($result->in_date) != '0000-00-00 00:00:00'){ ?>
                                <td id="in_date"><?php echo $date1->format('d/m/Y'); ?></td>
                                 <?php } 
                                 else{ ?><td id='in_date'>00/00/0000</td> <?php }

                                $date2 = new DateTime($result->actual_in_date);
                                if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <td id="actual_date"><?php echo $date2->format('d/m/Y'); ?></td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>">
                                  <?php }
                                 else{ ?>
                                    <td id='actual_date'>00/00/0000</td>
                                    <td style="display:none;" class="actual_date"><input type="hidden" name="updated_date" id="updated_date_in<?php echo $result->id; ?>" value="00/00/0000">
                                  <?php } ?>

                              
                                  <?php if(($result->actual_in_date) != '0000-00-00 00:00:00'){ ?>
                                    <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="<?php echo $date2->format('d/m/Y'); ?>"></td>
                                    <?php }
                                    else{ ?>

                                        <input type="text" class="dateall" id="datepicker<?php echo $result->id; ?>" value="00/00/0000"></td>
                                   <?php  } ?>
								                <td><?php echo $result->delivery_retrieval_out; ?></td>
								
								               <?php   $date3 = new DateTime($result->out_date);
                                 				if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                				<td id="actual_date"><?php echo $date3->format('d/m/Y'); ?></td><td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>">
                                 <?php  }
                                 else{ ?><td id='out_date'>00/00/0000</td> 
                                 <td style="display:none;" class="outdate"><input type="hidden" name="updated_date" id="updated_date_out<?php echo $result->id; ?>" value="00/00/0000"><?php } ?>
 
                                
                                  <?php if(($result->out_date) != '0000-00-00 00:00:00'){ ?>
                                 <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="<?php echo $date3->format('d/m/Y'); ?>"></td>
                                  <?php }
                                
                                else{ ?>
                                       <input type="text" class="datefinal" id="datepicker1<?php echo $result->id; ?>" value="00/00/0000"></td>
                                <?php  } ?>
								                <input type="hidden" id="txtbarcode<?php echo $i; ?>" value="<?php echo $result->ref_number; ?>"/>
                                <td class="actionbutton"><a class="editbutton">Edit</a><a class="updatebutton" style="display:none;">Update</a></td>
                               <!-- <td></td>-->
                                </tr>

                          <?php       
                           $i++;
   
    }
          ?>
          </table>
          <div class="height30"></div>
          <table width="50%" border="0" cellspacing="0" cellpadding="2"  align="center">
          <tr>
            <td valign="top" align="left">
            
          <label> Rows Limit: 
          <select name="show" onChange="changeDisplayRowCount(this.value);">
            <option value="10" <?php if ($_GET["show"] == 10 || $_GET["show"] == "" ) { echo ' selected="selected"'; }  ?> >10</option>
            <option value="20" <?php if ($_GET["show"] == 20) { echo ' selected="selected"'; }  ?> >20</option>
            <option value="30" <?php if ($_GET["show"] == 30) { echo ' selected="selected"'; }  ?> >30</option>
          </select>
          </label>

            </td>
            <td valign="top" align="center" >
           
            <?php
            if ( ($pagenum-1) > 0) {
            ?>  
             <a href="javascript:void(0);" class="links" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
            <?php
            }
            //Show page links
            for($i=1; $i<=$last; $i++) {
              if ($i == $pagenum ) {
          ?>
              <a href="javascript:void(0);" class="selected" ><?php echo $i ?></a>
          <?php
            } else {  
          ?>
            <a href="javascript:void(0);" class="links"  onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $i; ?>');" ><?php echo $i ?></a>
          <?php 
            }
          } 
          if ( ($pagenum+1) <= $last) {
          ?>
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links">Next</a>
          <?php } if ( ($pagenum) != $last) { ?>  
            <a href="javascript:void(0);" onclick="displayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links" >Last</a> 
          <?php
            } 
          ?>
          </td>
            <td align="right" valign="top">
            Page <?php echo $pagenum; ?> of <?php echo $last; ?>
            </td>
          </tr>
          </table>
          <?php } ?>