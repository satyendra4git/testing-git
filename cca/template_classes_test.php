<?php
/*
Template Name: Classes API Test
*/

$post_id              = get_the_ID();
$is_page_builder_used = et_pb_is_pagebuilder_used( $post_id );
$container_tag        = 'product' === get_post_type( $post_id ) ? 'div' : 'article'; 
global $wpdb;
$username = "TheCenterforContemporaryArtApi";
$password = "lxwYfWEz";
$remote_url = 'https://api112.imperisoft.com/api/OnlineProgramList';
$api_instructor_url = 'https://api112.imperisoft.com/api/Instructors';
$api_semester_url = 'https://api112.imperisoft.com/api/OnlineSemesters';
$api_type_url = 'https://api112.imperisoft.com/api/ProgramTypes';
$api_medium_url = 'https://api112.imperisoft.com/api/MediaTypes';

// Create a stream
$opts = array(
	'http'=>array(
		'method'=>"GET",
		'header' => "Authorization: Basic " . base64_encode("$username:$password")
	)
);

$context = stream_context_create($opts);

//get post_id and program id from relation table to check if we need to update or insert classes
$relationTable = $wpdb->prefix."classes_api_relation";
$relationQuery = "SELECT post_id, program_id from $relationTable";
$relationResult = $wpdb->get_results($relationQuery, ARRAY_A);
   //echo "<pre>";print_r($relationResult);
if(count($relationResult) > 0){
	$apiProgramIds = array_column($relationResult, 'program_id');
	$relClassIds = array_column($relationResult, 'post_id');
	$progIdClassIdCombnined = array_combine($apiProgramIds, $relClassIds);
}
//echo "<pre>";print_r($apiProgramIds);
 //Get class types taxonomy terms
$classTypeTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as progtypeid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'class_types' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'ProgramTypeId'";
$classTypeTerms = $wpdb->get_results($classTypeTermQuery, ARRAY_A); 
if(!empty($classTypeTerms)){
	$classTypeTermIds = array_column($classTypeTerms, 'term_id');
	$progtypeIds = array_column($classTypeTerms, 'progtypeid');
	$progtypeIdTrmidcombnined = array_combine($progtypeIds, $classTypeTermIds);
}
//Get semester taxonomy terms set term to class post
$semesterTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as semesterid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'semesters' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'SemesterId'";
$semesterTerms = $wpdb->get_results($semesterTermQuery, ARRAY_A);
if(!empty($semesterTerms)){
	$semesterTermIds = array_column($semesterTerms, 'term_id');
	$semesterIds = array_column($semesterTerms, 'semesterid');
	$semIdTrmidcombnined = array_combine($semesterIds, $semesterTermIds);
}
   //Get instructors taxonomy terms
$instructorsTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as instructorid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'instructors' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'InstructorId'";
$instructorsTerms = $wpdb->get_results($instructorsTermQuery);	   
$instructorTermInsertIds = [];
if(!empty($instructorsTerms)){

	$instructorTermIds = array_column($instructorsTerms, 'term_id');
	$instructorIds = array_column($instructorsTerms, 'instructorid');
	$instructorIdTrmidcombnined = array_combine($instructorIds, $instructorTermIds);
}  
   //Get class mediums taxonomy terms
$classMediumsTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as mediatypeid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'class_mediums' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'MediaTypeId'";
$classMediumsTerms = $wpdb->get_results($classMediumsTermQuery);
$mediaTermInsertIds = [];
if(!empty($classMediumsTerms)){

	$mediumTermIds = array_column($classMediumsTerms, 'term_id');
	$mediumIds = array_column($classMediumsTerms, 'mediatypeid');
	$mediumIdTrmidcombnined = array_combine($mediumIds, $mediumTermIds);
}

switch ($_GET['import-type']) {
	case 'classes':
		$classStatusArr = ['Underway','Scheduled'];
		$pageNo = isset($_GET['pg']) ? $_GET['pg'] : 1;
		
		$offset = 100;
		$limit = ( $offset * $pageNo ) - $offset;
		$classJsonData = file_get_contents($remote_url, false, $context);
		$classDataJson = json_decode($classJsonData, true);
	

		$allclassData = array_filter($classDataJson, function($row) {
			return in_array(trim($row['StatusDescription']), ['Underway','Scheduled']);
		});
		
		if(count($allclassData) < $limit) {
			echo "Imported successfully!!"; die;
		}

		$classData = array_slice($allclassData, $limit, $offset);
		
		$classData = json_decode(json_encode($classData));

		$userid = get_current_user_id() ? get_current_user_id() : 1;	
		$i=1;

		foreach($classData as $classes){
	    		//echo "<pre>"; print_r($classes);
				$programId = $classes->ProgramId;
				$programType = $classes->ProgramType->Description;
				//echo "<pre>";print_r($programType); 
				$shortDescription = $classes->ShortDescription;
				$description = $classes->Description;
				$statusDescription = $classes->StatusDescription;
				$numberOfSeats = $classes->NumberOfSeats;
				$minimumNumberOfRegistrations = $classes->MinimumNumberOfRegistrations;
				$numberRegistered = $classes->NumberRegistered;
				$numberWaitlisted = $classes->NumberWaitlisted;
				$startDate = $classes->StartDate;
				$endDate = $classes->EndDate;
				$numberOfWeeks = $classes->NumberOfWeeks;
				$tuitionFee = $classes->TuitionFee;
				$depositAmount = $classes->DepositAmount;
				$registrationOpenDate = $classes->RegistrationOpenDate;
				$classDays = [];
				$meetOnMonday = $classes->MeetOnMonday;
				if($meetOnMonday){ $classDays[] = 'Monday'; }
				$meetOnTuesday = $classes->MeetOnTuesday;
				if($meetOnTuesday){ $classDays[] = 'Tuesday'; }
				$meetOnWednesday = $classes->MeetOnWednesday;
				if($meetOnWednesday){ $classDays[] = 'Wednesday'; }
				$meetOnThursday = $classes->MeetOnThursday;
				if($meetOnThursday){ $classDays[] = 'Thursday'; }
				$meetOnFriday = $classes->MeetOnFriday;
				if($meetOnFriday){ $classDays[] = 'Friday'; }
				$meetOnSaturday = $classes->MeetOnSaturday;
				if($meetOnSaturday){ $classDays[] = 'Saturday'; }
				$meetOnSunday = $classes->MeetOnSunday;
				if($meetOnSunday){ $classDays[] = 'Sunday'; }
				if(!empty($classDays)){
					$dayStr = implode(', ', $classDays);
				}
				//echo $dayStr;
				$roomTypeDescription = $classes->RoomTypeDescription;
				$roomDescription = $classes->RoomDescription;
				$locationName = $classes->LocationName;
				$locationFullAddress = $classes->LocationFullAddress;
				$locationId = $classes->LocationId;
				$specialNotes = $classes->SpecialNotes;
				$title = $classes->Title;
				$onlineRegistrationDescription = $classes->OnlineRegistrationDescription;
				$level = $classes->Level;
				//echo "<br>";
				$minimumDueAtRegistration = $classes->MinimumDueAtRegistration;
				$imageUrl = $classes->ImageUrl;
				$semester = $classes->Semester->Description;
				$membershipGlobalOverrideAmount = $classes->MembershipGlobalOverrideAmount;
				$startTime = $classes->StartTime;
				$endTime = $classes->EndTime;
				$instructorListWithID = $classes->InstructorListWithID;
				$statusDescription = $classes->StatusDescription;
				$programTypeId = $classes->ProgramType->ProgramTypeId; 
				$organizationId = $classes->ProgramType->OrganizationId;
				$isActive = $classes->ProgramType->IsActive;
				$accountCode = $classes->ProgramType->AccountCode;
				$courseNumber = $classes->CourseNumber;
				$semesterId = $classes->Semester->SemesterId;
				$programDetailId = $classes->ProgramDetailId;
				$programMedia = $classes->ProgramMedia;

				//get program media 
				$mediaTypeId = [];
				$mediaDescription = [];
				if(!empty($programMedia) && is_array($programMedia)){
					foreach($programMedia as $classMedia){
						$mediaTypeId[] = $classMedia->MediaTypeId;
						$mediaDescription[] = $classMedia->Description;
					}
				}

				if(!empty($mediaTypeId)){
					$mediaTypeIdStr = implode(",", $mediaTypeId);
				}
				if(!empty($mediaDescription)){
					$mediaDescriptionStr = implode(",", $mediaDescription);
				}

				//instructer str convert to array

				$instructerData = [];
				$instructerDataArr = []; 
				$instructerDataFinalArr = [];
				if(!empty($instructorListWithID)){
					$instructerArr = explode('|', $instructorListWithID);
					if(!empty($instructerArr)){
						foreach($instructerArr as $instructor){
							$instructorNameArr = explode(":",$instructor);
					  //print_r($instructorNameArr);
							$instructerDataArr['id'] = $instructorNameArr[0];
							$instructerDataArr['name'] = $instructorNameArr[1];
							$instructerDataFinalArr[] = $instructerDataArr;
						}
					}
				}

				if(!empty($instructerDataFinalArr)){
					$instNames = [];
					$instids = [];
					foreach($instructerDataFinalArr as $instData){
						$instNames[] = $instData['name'];
						$instids[] = $instData['id'];
					}
				}

				$classes_args = array(
					'post_type'     => 'classes',
					'post_title'    => $title,
					'post_content'  => $description,
					'post_excerpt'  => $shortDescription,
					'post_status'   => 'publish',
					'post_author'   => $userid,
					'post_category' => array()
				);

				if( is_array($apiProgramIds) && count($apiProgramIds) > 0){
					if(in_array($programId, $apiProgramIds)){	
						$classIdToUpdate = (int)$progIdClassIdCombnined[$programId];
						$classes_args['ID'] = $classIdToUpdate;		
					}
				}
	   		//echo "<pre>";print_r($classes_args);
	  			// Insert the classes post into the database
				$classId = wp_insert_post( $classes_args );

	     		//set class types taxonomy terms to class post ids
				if( is_array($progtypeIds) && count($progtypeIds) > 0){
					if(in_array($programTypeId, $progtypeIds)){
						$progtypeTermId = (int)$progtypeIdTrmidcombnined[$programTypeId]; 
						if($progtypeTermId){ 
				           //set term to class post
							$termStatus = wp_set_object_terms($classId, $progtypeTermId, 'class_types');
						}
					}
				}
				//set semester taxonomy terms  class post ids
				if( is_array($semesterIds) && count($semesterIds) > 0){
					if(in_array($semesterId, $semesterIds)){
						$semTermId = (int)$semIdTrmidcombnined[$semesterId];
						if($semTermId){ 
				           //set term to class post
							$termStatus = wp_set_object_terms($classId, $semTermId, 'semesters');
						}
					}
				}
	   		//set instructors taxonomy terms	to class post ids   
				$instructorTermInsertIds = [];
				if(!empty($instids)){
					$matchedIds = array_intersect($instructorIds, $instids);
					if(!empty($matchedIds)){
						foreach($matchedIds as $mids){
							$instructorTermInsertIds[] = (int)$instructorIdTrmidcombnined[$mids];
						}
						if(!empty($instructorTermInsertIds)){
							$termStatus = wp_set_object_terms($classId, $instructorTermInsertIds, 'instructors'); 
						}
					}
				}

	   		//Get class mediums taxonomy terms
				$mediaTermInsertIds = [];
				if(!empty($mediaTypeId)){
					$matchedIds = array_intersect($mediumIds, $mediaTypeId);
					if(!empty($matchedIds)){				    
						foreach($matchedIds as $mids){
							$mediaTermInsertIds[] = (int)$mediumIdTrmidcombnined[$mids];
						}
						if(!empty($mediaTermInsertIds)){
							$termStatus = wp_set_object_terms($classId, $mediaTermInsertIds, 'class_mediums'); 
						}
					}   
				}



				if($classId){

					/************Insert class id and program id for establishing relation during update *****************/

					$wpdb->insert($relationTable, array(
						'post_id' => $classId,
						'program_id' => $programId 
					));

					/***********************add/update classes custom fields data*****************************/

					update_post_meta($classId, 'ImageUrl', $imageUrl);
					update_post_meta($classId, 'StartDate', $startDate);
					update_post_meta($classId, 'EndDate', $endDate);
					update_post_meta($classId, 'TuitionFee', $tuitionFee);
					update_post_meta($classId, 'NumberOfSeats', $numberOfSeats);
					update_post_meta($classId, 'NumberRegistered', $numberRegistered);
					update_post_meta($classId, 'NumberWaitlisted', $numberWaitlisted);
					update_post_meta($classId, 'RoomTypeDescription', $roomTypeDescription);
					update_post_meta($classId, 'RoomDescription', $roomDescription);
					update_post_meta($classId, 'LocationName', $locationName);
					update_post_meta($classId, 'LocationFullAddress', $locationFullAddress);
					update_post_meta($classId, 'SpecialNotes', $specialNotes);
					update_post_meta($classId, 'OnlineRegistrationDescription', $onlineRegistrationDescription);
					update_post_meta($classId, 'Level', $level);
					update_post_meta($classId, 'program_type', $programType);
					update_post_meta($classId, 'SemesterDescription', $semester);
					update_post_meta($classId, 'class_days', $dayStr);
					update_post_meta($classId, 'ProgramId', $programId);
					update_post_meta($classId, 'MembershipGlobalOverrideAmount', $membershipGlobalOverrideAmount);
					update_post_meta($classId, 'NumberOfWeeks', $numberOfWeeks);
					update_post_meta($classId, 'MinimumDueAtRegistration', $minimumDueAtRegistration);
					update_post_meta($classId, 'DepositAmount', $depositAmount);
					update_post_meta($classId, 'StartTime', $startTime);
					update_post_meta($classId, 'EndTime', $endTime);
					update_post_meta($classId, 'InstructorListWithID', $instructorListWithID);
					update_post_meta($classId, 'StatusDescription', $statusDescription);
					update_post_meta($classId, 'ProgramTypeId', $programTypeId);
					update_post_meta($classId, 'OrganizationId', $organizationId);
					update_post_meta($classId, 'IsActive', $isActive);
					update_post_meta($classId, 'AccountCode', $accountCode);
					update_post_meta($classId, 'CourseNumber', $courseNumber);
					update_post_meta($classId, 'SemesterId', $semesterId);
					update_post_meta($classId, 'LocationId', $locationId);
					update_post_meta($classId, 'MediaTypeIds', $mediaTypeIdStr);
					update_post_meta($classId, 'MediaTypeNames', $mediaDescriptionStr); 
					update_post_meta($classId, 'ProgramDetailId', $programDetailId); 


					$msg = "Class imported successfully.";

				}else{

					$msg = "Class not imported. Please try again.";
				}

				
		}
		$newPage = $pageNo++;
		wp_redirect(get_the_permalink() . '?pg=' . $newPage.'&import-type=classes');
		exit;
	break;
	
	default:
		// code...
	break;
}



get_header();




// Open the file using the HTTP headers set above and get classes data
$classJsonData = file_get_contents($remote_url, false, $context);
$classData = json_decode($classJsonData);


// Open the file using the HTTP headers set above and get instructer data
$instructerJsonData = file_get_contents($api_instructor_url, false, $context);
$instructerData = json_decode($instructerJsonData);


// Open the file using the HTTP headers set above and get Semester data
$semesterJsonData = file_get_contents($api_semester_url, false, $context);
$semesterData = json_decode($semesterJsonData);


// Open the file using the HTTP headers set above and get instructer data
$typeJsonData = file_get_contents($api_type_url, false, $context);
$typeData = json_decode($typeJsonData);


// Open the file using the HTTP headers set above and get instructer data
$mediumJsonData = file_get_contents($api_medium_url, false, $context);
$mediumData = json_decode($mediumJsonData);

//get post_id and program id from relation table to check if we need to update or insert classes
$relationTable = $wpdb->prefix."classes_api_relation";
$relationQuery = "SELECT post_id, program_id from $relationTable";
$relationResult = $wpdb->get_results($relationQuery, ARRAY_A);
   //echo "<pre>";print_r($relationResult);
if(count($relationResult) > 0){
	$apiProgramIds = array_column($relationResult, 'program_id');
	$relClassIds = array_column($relationResult, 'post_id');
	$progIdClassIdCombnined = array_combine($apiProgramIds, $relClassIds);
}
//echo "<pre>";print_r($apiProgramIds);
 //Get class types taxonomy terms
$classTypeTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as progtypeid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'class_types' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'ProgramTypeId'";
$classTypeTerms = $wpdb->get_results($classTypeTermQuery, ARRAY_A); 
if(!empty($classTypeTerms)){
	$classTypeTermIds = array_column($classTypeTerms, 'term_id');
	$progtypeIds = array_column($classTypeTerms, 'progtypeid');
	$progtypeIdTrmidcombnined = array_combine($progtypeIds, $classTypeTermIds);
}
//Get semester taxonomy terms set term to class post
$semesterTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as semesterid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'semesters' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'SemesterId'";
$semesterTerms = $wpdb->get_results($semesterTermQuery, ARRAY_A);
if(!empty($semesterTerms)){
	$semesterTermIds = array_column($semesterTerms, 'term_id');
	$semesterIds = array_column($semesterTerms, 'semesterid');
	$semIdTrmidcombnined = array_combine($semesterIds, $semesterTermIds);
}
   //Get instructors taxonomy terms
$instructorsTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as instructorid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'instructors' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'InstructorId'";
$instructorsTerms = $wpdb->get_results($instructorsTermQuery);	   
$instructorTermInsertIds = [];
if(!empty($instructorsTerms)){

	$instructorTermIds = array_column($instructorsTerms, 'term_id');
	$instructorIds = array_column($instructorsTerms, 'instructorid');
	$instructorIdTrmidcombnined = array_combine($instructorIds, $instructorTermIds);
}  
   //Get class mediums taxonomy terms
$classMediumsTermQuery = "SELECT wp_terms.term_id, wp_termmeta.meta_value as mediatypeid FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'class_mediums' INNER JOIN wp_termmeta ON wp_termmeta.term_id = wp_term_taxonomy.term_id AND wp_termmeta.meta_key = 'MediaTypeId'";
$classMediumsTerms = $wpdb->get_results($classMediumsTermQuery);
$mediaTermInsertIds = [];
if(!empty($classMediumsTerms)){

	$mediumTermIds = array_column($classMediumsTerms, 'term_id');
	$mediumIds = array_column($classMediumsTerms, 'mediatypeid');
	$mediumIdTrmidcombnined = array_combine($mediumIds, $mediumTermIds);
}	
/**********************************************************************/
$msg = "";
if(isset($_POST['import-classes'])){
	$userid = get_current_user_id() ? get_current_user_id() : 1;	
	$i=1;
	$classStatusArr = ['Underway','Scheduled'];
	foreach($classData as $classes){
		if(in_array(trim($classes->StatusDescription), $classStatusArr)){
    		//echo "<pre>"; print_r($classes);
			$programId = $classes->ProgramId;
			$programType = $classes->ProgramType->Description;
			//echo "<pre>";print_r($programType); 
			$shortDescription = $classes->ShortDescription;
			$description = $classes->Description;
			$statusDescription = $classes->StatusDescription;
			$numberOfSeats = $classes->NumberOfSeats;
			$minimumNumberOfRegistrations = $classes->MinimumNumberOfRegistrations;
			$numberRegistered = $classes->NumberRegistered;
			$numberWaitlisted = $classes->NumberWaitlisted;
			$startDate = $classes->StartDate;
			$endDate = $classes->EndDate;
			$numberOfWeeks = $classes->NumberOfWeeks;
			$tuitionFee = $classes->TuitionFee;
			$depositAmount = $classes->DepositAmount;
			$registrationOpenDate = $classes->RegistrationOpenDate;
			$classDays = [];
			$meetOnMonday = $classes->MeetOnMonday;
			if($meetOnMonday){ $classDays[] = 'Monday'; }
			$meetOnTuesday = $classes->MeetOnTuesday;
			if($meetOnTuesday){ $classDays[] = 'Tuesday'; }
			$meetOnWednesday = $classes->MeetOnWednesday;
			if($meetOnWednesday){ $classDays[] = 'Wednesday'; }
			$meetOnThursday = $classes->MeetOnThursday;
			if($meetOnThursday){ $classDays[] = 'Thursday'; }
			$meetOnFriday = $classes->MeetOnFriday;
			if($meetOnFriday){ $classDays[] = 'Friday'; }
			$meetOnSaturday = $classes->MeetOnSaturday;
			if($meetOnSaturday){ $classDays[] = 'Saturday'; }
			$meetOnSunday = $classes->MeetOnSunday;
			if($meetOnSunday){ $classDays[] = 'Sunday'; }
			if(!empty($classDays)){
				$dayStr = implode(', ', $classDays);
			}
			//echo $dayStr;
			$roomTypeDescription = $classes->RoomTypeDescription;
			$roomDescription = $classes->RoomDescription;
			$locationName = $classes->LocationName;
			$locationFullAddress = $classes->LocationFullAddress;
			$locationId = $classes->LocationId;
			$specialNotes = $classes->SpecialNotes;
			$title = $classes->Title;
			$onlineRegistrationDescription = $classes->OnlineRegistrationDescription;
			$level = $classes->Level;
			//echo "<br>";
			$minimumDueAtRegistration = $classes->MinimumDueAtRegistration;
			$imageUrl = $classes->ImageUrl;
			$semester = $classes->Semester->Description;
			$membershipGlobalOverrideAmount = $classes->MembershipGlobalOverrideAmount;
			$startTime = $classes->StartTime;
			$endTime = $classes->EndTime;
			$instructorListWithID = $classes->InstructorListWithID;
			$statusDescription = $classes->StatusDescription;
			$programTypeId = $classes->ProgramType->ProgramTypeId; 
			$organizationId = $classes->ProgramType->OrganizationId;
			$isActive = $classes->ProgramType->IsActive;
			$accountCode = $classes->ProgramType->AccountCode;
			$courseNumber = $classes->CourseNumber;
			$semesterId = $classes->Semester->SemesterId;
			$programDetailId = $classes->ProgramDetailId;
			$programMedia = $classes->ProgramMedia;

			//get program media 
			$mediaTypeId = [];
			$mediaDescription = [];
			if(!empty($programMedia) && is_array($programMedia)){
				foreach($programMedia as $classMedia){
					$mediaTypeId[] = $classMedia->MediaTypeId;
					$mediaDescription[] = $classMedia->Description;
				}
			}

			if(!empty($mediaTypeId)){
				$mediaTypeIdStr = implode(",", $mediaTypeId);
			}
			if(!empty($mediaDescription)){
				$mediaDescriptionStr = implode(",", $mediaDescription);
			}

			//instructer str convert to array

			$instructerData = [];
			$instructerDataArr = []; 
			$instructerDataFinalArr = [];
			if(!empty($instructorListWithID)){
				$instructerArr = explode('|', $instructorListWithID);
				if(!empty($instructerArr)){
					foreach($instructerArr as $instructor){
						$instructorNameArr = explode(":",$instructor);
				  //print_r($instructorNameArr);
						$instructerDataArr['id'] = $instructorNameArr[0];
						$instructerDataArr['name'] = $instructorNameArr[1];
						$instructerDataFinalArr[] = $instructerDataArr;
					}
				}
			}

			if(!empty($instructerDataFinalArr)){
				$instNames = [];
				$instids = [];
				foreach($instructerDataFinalArr as $instData){
					$instNames[] = $instData['name'];
					$instids[] = $instData['id'];
				}
			}

			$classes_args = array(
				'post_type'     => 'classes',
				'post_title'    => $title,
				'post_content'  => $description,
				'post_excerpt'  => $shortDescription,
				'post_status'   => 'publish',
				'post_author'   => $userid,
				'post_category' => array()
			);

			if( is_array($apiProgramIds) && count($apiProgramIds) > 0){
				if(in_array($programId, $apiProgramIds)){	
					$classIdToUpdate = (int)$progIdClassIdCombnined[$programId];
					$classes_args['ID'] = $classIdToUpdate;		
				}
			}
   		//echo "<pre>";print_r($classes_args);
  			// Insert the classes post into the database
			$classId = wp_insert_post( $classes_args );

     		//set class types taxonomy terms to class post ids
			if( is_array($progtypeIds) && count($progtypeIds) > 0){
				if(in_array($programTypeId, $progtypeIds)){
					$progtypeTermId = (int)$progtypeIdTrmidcombnined[$programTypeId]; 
					if($progtypeTermId){ 
			           //set term to class post
						$termStatus = wp_set_object_terms($classId, $progtypeTermId, 'class_types');
					}
				}
			}
			//set semester taxonomy terms  class post ids
			if( is_array($semesterIds) && count($semesterIds) > 0){
				if(in_array($semesterId, $semesterIds)){
					$semTermId = (int)$semIdTrmidcombnined[$semesterId];
					if($semTermId){ 
			           //set term to class post
						$termStatus = wp_set_object_terms($classId, $semTermId, 'semesters');
					}
				}
			}
   		//set instructors taxonomy terms	to class post ids   
			$instructorTermInsertIds = [];
			if(!empty($instids)){
				$matchedIds = array_intersect($instructorIds, $instids);
				if(!empty($matchedIds)){
					foreach($matchedIds as $mids){
						$instructorTermInsertIds[] = (int)$instructorIdTrmidcombnined[$mids];
					}
					if(!empty($instructorTermInsertIds)){
						$termStatus = wp_set_object_terms($classId, $instructorTermInsertIds, 'instructors'); 
					}
				}
			}

   		//Get class mediums taxonomy terms
			$mediaTermInsertIds = [];
			if(!empty($mediaTypeId)){
				$matchedIds = array_intersect($mediumIds, $mediaTypeId);
				if(!empty($matchedIds)){				    
					foreach($matchedIds as $mids){
						$mediaTermInsertIds[] = (int)$mediumIdTrmidcombnined[$mids];
					}
					if(!empty($mediaTermInsertIds)){
						$termStatus = wp_set_object_terms($classId, $mediaTermInsertIds, 'class_mediums'); 
					}
				}   
			}



			if($classId){

				/************Insert class id and program id for establishing relation during update *****************/

				$wpdb->insert($relationTable, array(
					'post_id' => $classId,
					'program_id' => $programId 
				));

				/***********************add/update classes custom fields data*****************************/

				update_post_meta($classId, 'ImageUrl', $imageUrl);
				update_post_meta($classId, 'StartDate', $startDate);
				update_post_meta($classId, 'EndDate', $endDate);
				update_post_meta($classId, 'TuitionFee', $tuitionFee);
				update_post_meta($classId, 'NumberOfSeats', $numberOfSeats);
				update_post_meta($classId, 'NumberRegistered', $numberRegistered);
				update_post_meta($classId, 'NumberWaitlisted', $numberWaitlisted);
				update_post_meta($classId, 'RoomTypeDescription', $roomTypeDescription);
				update_post_meta($classId, 'RoomDescription', $roomDescription);
				update_post_meta($classId, 'LocationName', $locationName);
				update_post_meta($classId, 'LocationFullAddress', $locationFullAddress);
				update_post_meta($classId, 'SpecialNotes', $specialNotes);
				update_post_meta($classId, 'OnlineRegistrationDescription', $onlineRegistrationDescription);
				update_post_meta($classId, 'Level', $level);
				update_post_meta($classId, 'program_type', $programType);
				update_post_meta($classId, 'SemesterDescription', $semester);
				update_post_meta($classId, 'class_days', $dayStr);
				update_post_meta($classId, 'ProgramId', $programId);
				update_post_meta($classId, 'MembershipGlobalOverrideAmount', $membershipGlobalOverrideAmount);
				update_post_meta($classId, 'NumberOfWeeks', $numberOfWeeks);
				update_post_meta($classId, 'MinimumDueAtRegistration', $minimumDueAtRegistration);
				update_post_meta($classId, 'DepositAmount', $depositAmount);
				update_post_meta($classId, 'StartTime', $startTime);
				update_post_meta($classId, 'EndTime', $endTime);
				update_post_meta($classId, 'InstructorListWithID', $instructorListWithID);
				update_post_meta($classId, 'StatusDescription', $statusDescription);
				update_post_meta($classId, 'ProgramTypeId', $programTypeId);
				update_post_meta($classId, 'OrganizationId', $organizationId);
				update_post_meta($classId, 'IsActive', $isActive);
				update_post_meta($classId, 'AccountCode', $accountCode);
				update_post_meta($classId, 'CourseNumber', $courseNumber);
				update_post_meta($classId, 'SemesterId', $semesterId);
				update_post_meta($classId, 'LocationId', $locationId);
				update_post_meta($classId, 'MediaTypeIds', $mediaTypeIdStr);
				update_post_meta($classId, 'MediaTypeNames', $mediaDescriptionStr); 
				update_post_meta($classId, 'ProgramDetailId', $programDetailId); 


				$msg = "Class imported successfully.";

			}else{

				$msg = "Class not imported. Please try again.";
			}

			$i++;
			if($i==21) break;	
		}
	}
	
}

// Instructer taxonomy insert
$instructmsg = "";
if(isset($_POST['import-instructors'])){
	$i = 1;
	foreach($instructerData as $instructers){
		//echo "<pre>"; print_r($instructers);
		$instructorId = $instructers->InstructorId;
		$unitId = $instructers->UnitId;
		$contactId = $instructers->Contact->ContactId;
		$contactUnitId = $instructers->Contact->UnitId;
		$contactEmployeeID = $instructers->Contact->EmployeeID;
		$contactTitle = $instructers->Contact->Title;
		$contactSalutation = $instructers->Contact->Salutation;
		$contactFirstName = $instructers->Contact->FirstName;
		$contactLastName = $instructers->Contact->LastName;
		$contactOrganizationName = $instructers->Contact->OrganizationName;
		$contactHomePhone = $instructers->Contact->HomePhone;
		$contactMobile = $instructers->Contact->Mobile;
		$contactEmail = $instructers->Contact->Email;
		$contactIsActive = $instructers->Contact->IsActive;
		$contactIsAdult = $instructers->Contact->IsAdult;
		$contactImageUrl = $instructers->Contact->ImageUrl;
		$instructorTypeId = $instructers->InstructorType->InstructorTypeId;
		$instructorOrganizationId = $instructers->InstructorType->OrganizationId;
		$instructorDescription = $instructers->InstructorType->Description;
		$instructorIsActive = $instructers->IsActive;
		$instructorBio = $instructers->Bio;
		$instructorImageUrl = $instructers->ImageUrl;
		//generate term slug
		$slugStr = strtolower(trim($contactFirstName))." ".strtolower(trim($contactLastName))." ".trim($instructorId);
		$termSlug = str_replace(' ', '-', $slugStr);
		$termName = trim($contactFirstName)." ".trim($contactLastName);
		//check if term already exists
		$termCheckId = term_exists($termSlug);
		 //echo "<pre>";print_r($termCheck);
		if($termCheckId){ 
		   //Update the category
			$termData = wp_update_term(
				$termCheckId, 
				'instructors', 
				array(
					'name' => $termName,
					'slug' => $termSlug,
					'description'  => $instructorBio
				) );
		}else{
		 //create the category
			$termData = wp_insert_term(
				$termName, 
				'instructors', 
				array(
					'slug'           => $termSlug,
					'description'    => $instructorBio
				));
		}
		//echo "<pre>";print_r($termData);die;
		
		if(!is_wp_error($termData)){
			//get term id
			$instTermId = 'instructors'.'_'.$termData['term_id'];
			
			update_field('InstructorId', $instructorId, $instTermId);
			update_field('UnitId', $unitId, $instTermId);
			update_field('ContactId', $contactId, $instTermId);
			update_field('ContactUnitId', $contactUnitId, $instTermId);
			update_field('EmployeeID', $contactEmployeeID, $instTermId);
			update_field('ContactTitle', $contactTitle, $instTermId);
			update_field('ContactSalutation', $contactSalutation, $instTermId);
			update_field('ContactFirstName', $contactFirstName, $instTermId); 
			update_field('ContactLastName', $contactLastName, $instTermId); 
			update_field('ContactOrganizationName', $contactOrganizationName, $instTermId); 
			update_field('ContactHomePhone', $contactHomePhone, $instTermId); 
			update_field('ContactMobile', $contactMobile, $instTermId); 
			update_field('ContactEmail', $contactEmail, $instTermId); 
			update_field('ContactEmail', $contactEmail, $instTermId); 
			update_field('ContactIsActive', $contactIsActive, $instTermId); 
			update_field('ContactIsActive', $contactIsActive, $instTermId); 
			update_field('ContactIsAdult', $contactIsAdult, $instTermId); 
			update_field('ContactImageUrl', $contactImageUrl, $instTermId); 
			update_field('ContactImageUrl', $contactImageUrl, $instTermId); 
			update_field('InstructorTypeId', $instructorTypeId, $instTermId); 
			update_field('InstructorOrganizationId', $instructorOrganizationId, $instTermId); 
			update_field('InstructorDescription', $instructorDescription, $instTermId); 
			update_field('InstructorIsActive', $instructorIsActive, $instTermId); 
			update_field('instructorImageUrl', $instructorImageUrl, $instTermId); 
			
			
			$instructmsg = "Instructer Term inserted.";
			
		}else{
			
			$instructmsg = "Instructer Term Not inserted. Please try again.";	
		}
		$i++;
		if($i==11) break;	 
	}
}
//Semester term insert
$msgSemester = "";
if(isset($_POST['import-semester'])){
	if(!empty($semesterData)){
		foreach($semesterData as $semester){
			//echo "<pre>";print_r($semester);
			//echo $semester->Description;
			//echo "<br>";
			$semesterId = $semester->SemesterId;
			$description = $semester->Description;
			$startDate = $semester->StartDate;
			$endDate = $semester->EndDate;
			$isOpen = $semester->IsOpen;
			$isCurrentlyOnline = $semester->IsCurrentlyOnline;
			
		 //generate term slug
			$slugStr = strtolower(trim($description))." ".trim($semesterId);
			$termSlug = str_replace(' ', '-', $slugStr);
			$termName = trim($description);
		 //check if term already exists
			$termCheckId = term_exists($termSlug);
		 //echo "<pre>";print_r($termCheck);
			if($termCheckId){ 
		   //Update the category
				$termData = wp_update_term(
					$termCheckId, 
					'semesters', 
					array(
						'name' => $termName,
						'slug' => $termSlug
					) );
			}else{
		 //create the category
				$termData = wp_insert_term(
					$termName, 
					'semesters', 
					array(
						'slug'  => $termSlug
					));
			}
		//echo "<pre>";print_r($termData);die;

			if(!is_wp_error($termData)){
			// get term id
				$semTermId = 'semesters'.'_'.$termData['term_id'];

				update_field('SemesterId', $semesterId, $semTermId);
				update_field('StartDate', $startDate, $semTermId);
				update_field('EndDate', $endDate, $semTermId);
				update_field('IsOpen', $isOpen, $semTermId);
				update_field('IsCurrentlyOnline', $isCurrentlyOnline, $semTermId);

				$msgSemester = "Semester Term Imported.";

			}else{

				$msgSemester = "Semester Term Not Imported. Please try again.";	
			}
			

		}
	}
}

//Class type term insert
$msgType = "";
if(isset($_POST['import-type'])){
	$i=1;
	if(!empty($typeData)){
		foreach($typeData as $type){
			
			//echo "<pre>";print_r($type);
			$programTypeId = $type->ProgramTypeId;
			$organizationId = $type->OrganizationId;
			$description = $type->Description;
			$isActive = $type->IsActive;
			$accountCode = $type->AccountCode;
			
			
		 //generate term slug
			$slugStr = strtolower(trim($description))." ".trim($programTypeId);
			$termSlug = str_replace(' ', '-', $slugStr);
			$termName = trim($description);

		//check if term already exists
			$termCheckId = term_exists($termSlug);
		 //echo "<pre>";print_r($termCheck);
			if($termCheckId){ 
		   //Update the category
				$termData = wp_update_term(
					$termCheckId, 
					'class_types', 
					array(
						'name' => $termName,
						'slug' => $termSlug
					) );
			}else{
		 //create the category
				$termData = wp_insert_term(
					$termName, 
					'class_types', 
					array(
						'slug'            => $termSlug
					));
			}
		//echo "<pre>";print_r($termData);die;


			if(!is_wp_error($termData)){
			//get term id
				$typeTermId = 'class_types'.'_'.$termData['term_id'];

				update_field('ProgramTypeId', $programTypeId, $typeTermId);
				update_field('OrganizationId', $organizationId, $typeTermId);
				update_field('IsActive', $isActive, $typeTermId);
				update_field('accountCode', $accountCode, $typeTermId);

				$msgType = "Class type Term Imported.";

			}else{

				$msgType = "Class type Not Imported. Please try again.";	
			} 

		//$i++;
		//if($i==2) break;
			
		}
	}
}

//Class medium term insert
$msgMedium = "";
if(isset($_POST['import-medium'])){
	$i = 1;
	if(!empty($mediumData)){
		
		foreach($mediumData as $medium){
			
			//echo "<pre>";print_r($medium);
			$mediaTypeId = $medium->MediaTypeId;
			$organizationId = $medium->OrganizationId;
			$description = $medium->Description;
			$isActive = $medium->IsActive;
			
		 //generate term slug
			$slugStr = strtolower(trim($description))." ".trim($mediaTypeId);
			$termSlug = str_replace(' ', '-', $slugStr);
			$termName = trim($description);

		 //check if term already exists
			$termCheckId = term_exists($termSlug);
		 //echo "<pre>";print_r($termCheck);
			if($termCheckId){ 
		   //Update the category
				$termData = wp_update_term(
					$termCheckId, 
					'class_mediums', 
					array(
						'name' => $termName,
						'slug' => $termSlug
					) );
			}else{
		 //create the category
				$termData = wp_insert_term(
					$termName, 
					'class_mediums', 
					array(
						'slug' => $termSlug
					));
			}
		//echo "<pre>";print_r($termData);die;


			if(!is_wp_error($termData)){

			//get term id
				$mediumTermId = 'class_mediums'.'_'.$termData['term_id'];

				update_field('MediaTypeId', $mediaTypeId, $mediumTermId);
				update_field('OrganizationId', $organizationId, $mediumTermId);
				update_field('IsActive', $isActive, $mediumTermId);

				$msgMedium = "Medium Term Imported.";

			}else{

				$msgMedium = "Medium Not Imported. Please try again.";	
			} 

		//$i++;
		//if($i==2) break;
			
		}
		
	}
	
}

?>

<div id="main-content">

	<?php if ( ! $is_page_builder_used ) : ?>

		<div class="container">
			<div id="content-area" class="clearfix">
				<div id="left-area">

				<?php endif; ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<<?php echo $container_tag; ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php if ( ! $is_page_builder_used ) : ?>

						<h1 class="main_title"><?php the_title(); ?></h1>
						<?php

						$thumb = '';
						$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

						$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
						$classtext = 'et_featured_image';
						$titletext = get_the_title();
						$alttext = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
						$thumbnail = get_thumbnail( $width, $height, $classtext, $alttext, $titletext, false, 'Blogimage' );
						$thumb = $thumbnail["thumb"];

						if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
							print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
						?>

					<?php endif; ?>

					<div class="entry-content">
						<h3><?php echo $msg; ?></h3>
						<div class="import-form">
							<form action="" method="post">
								<input type="submit" name="import-classes" class="et_pb_button" value="Import Classes" style="">
							</form>
						</div>
						<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
						?>
					</div>
					
					<div class="entry-content">
						<h3><?php echo $instructmsg; ?></h3>
						<div class="import-form">
							<form action="" method="post">
								<input type="submit" name="import-instructors" class="et_pb_button" value="Import instructors" style="">
							</form>
						</div>

					</div>
					
					<div class="entry-content">
						<h3><?php echo $msgSemester; ?></h3>
						<div class="import-form">
							<form action="" method="post">
								<input type="submit" name="import-semester" class="et_pb_button" value="Import Semester" style="">
							</form>
						</div>

					</div>
					
					<div class="entry-content">
						<h3><?php echo $msgType; ?></h3>
						<div class="import-form">
							<form action="" method="post">
								<input type="submit" name="import-type" class="et_pb_button" value="Import class type" style="">
							</form>
						</div>

					</div>
					
					<div class="entry-content">
						<h3><?php echo $msgMedium; ?></h3>
						<div class="import-form">
							<form action="" method="post">
								<input type="submit" name="import-medium" class="et_pb_button" value="Import medium" style="">
							</form>
						</div>

					</div>
					

					<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
					?>

					</<?php echo et_core_intentionally_unescaped( $container_tag, 'fixed_string' ); ?>>

				<?php endwhile; ?>

				<?php if ( ! $is_page_builder_used ) : ?>

				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>

	<?php endif; ?>

</div>

<?php

get_footer();
