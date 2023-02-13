<?php 
 if(isset($_POST['submitpdf']))
 	{
	 		 $user_id = $_POST['user_id'];
	 		 $user_name = $_POST['user_name'];
			 $file_name = $_FILES['uploaded']['name'];

			 $uploads = wp_upload_dir();
			 $basedir = $uploads['basedir'];
		     
		     $myNewFolderPath = $basedir."/".$user_name;
		     mkdir($myNewFolderPath, 0755, true); 
			    
			
			$source_file = $_FILES['uploaded']['tmp_name'];
			$dest_file = $myNewFolderPath.'/'.$file_name;

		
		 if(move_uploaded_file( $source_file, $dest_file ))
			{
			    $sql = "INSERT INTO `wp_upload_PDF` (user_id,user_name,pdf_name) VALUES ('$user_id','$user_name',' $file_name')";

			    if($wpdb->query($sql)) 
				 {
					echo "Pdf file uploaded successfully!";
				 }
			}
			
	}

