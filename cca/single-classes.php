<?php
get_header();
?>
<div id="main-content" class="classesWrapper classesDetail">
    
  <?php if ( have_posts() ) :  ?>
    <?php while ( have_posts() ) : the_post(); 
	       $startDate = strtotime(get_field('StartDate'));
		   $endDate = strtotime(get_field('EndDate'));
		   $startTime = strtotime(get_field('StartTime'));
		   $endTime = strtotime(get_field('EndTime'));
		   //echo get_field('InstructorListWithID');
           /*if(!empty(get_field('InstructorListWithID'))){
				$instructerArr = explode(':', get_field('InstructorListWithID'));
		   }
		   $instructorName = !empty($instructerArr) ? $instructerArr[1] : 'N/A';
		   */
		  $instructerData = [];
			$instructerDataArr = []; 
			$instructerDataFinalArr = [];
			if(!empty(get_field('InstructorListWithID'))){
				  $instructerArr = explode('|', get_field('InstructorListWithID'));
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
			 //echo "<pre>";print_r($instructerDataFinalArr);
			 if(!empty($instructerDataFinalArr)){
				 foreach($instructerDataFinalArr as $instData){
					$instNames[] = $instData['name'];
					$instids[] = $instData['id'];
				 }
			 }
			 $instNamesStr = !empty($instNames) ? implode(' | ',$instNames) : "N/A";
			 $regLink = "https://reg125.imperisoft.com/ContemporaryArt/ProgramDetail/".get_field('ProgramDetailId')."/Registration.aspx";
			  $instructers = get_the_terms( $post->ID, 'instructors' ); 
			  //echo "<pre>";print_r($instructers);
			  
	?>
	<div class="container">
		<div id="content-area" class="clearfix content-area">
			<div class="class-detail-left-area left-area">
		
				<article id="post-<?php the_ID(); ?>" class="class-detail">
					 <h1 class="class-title"><?php the_title(); ?></h1>
					<div class="entry-content">
					<?php echo get_field('OnlineRegistrationDescription'); ?>
					<h3>Special Notes</h3>
					<?php echo get_field('SpecialNotes'); ?>
					</div>

				</article>
				
              <?php if(!is_wp_error($instructers)){	 ?>
				<div class="instructor">
					<h3>About the Instructor</h3>
					<?php foreach ( $instructers as $inst){ 
					     $termImage =  get_field('InstructorImageUrl', 'instructors_'.$inst->term_id);
						 $instructerImage = !empty($termImage) ? $termImage : 'https://via.placeholder.com/285x380';
						 
					?>
					<div class="instructorOuter">
						<div class="instructor_image">
							<img src="<?php echo $instructerImage; ?>"> 
						</div>
						<div class="instructor_bio">
							<h4><?php echo $inst->name; ?></h4> 
							<p><?php echo $inst->description; ?></p>
							<div class="btnWrapper">
								<a class="button button--small btn" href="#">See More Classes By Instructor</a>
  							</div>
						</div>
					</div>	
			  <?php } ?>					
				</div>
			  <?php } ?>
				<div class="backBTn btnWrapper">
					<a href="<?php echo get_page_link(274485); ?>" class="btn">Back to Classes</a>
				</div>
			</div>
			<div class="class-detail-right-area right-area">
			  <div class="right-area-content">
			  <div class="top-detail">
			  <h3>REGISTER FOR THIS CLASS</h3>
			  <div class="btnWrapper">
			  	<a href="<?php echo $regLink; ?>" class="button button--small btn" target="_blank">Enroll Now</a>
  			  </div> 
			  </div>
				<div class="classes-additional-details">
				 <h3>Class Details</h3>
				   <p class="class-date"> <?php echo date('F d', $startDate); ?> - <?php echo date('F d', $endDate); ?>, <?php echo date('h:i a', $startTime); ?> - <?php echo date('h:i a', $endTime); ?> </p>
				   <p class="class-days"><strong>Days:</strong> <?php echo get_field('class_days'); ?></p>
				   <p class="class-tution"><strong>Tuition:</strong> <?php if(get_field('TuitionFee')){ echo '$'.get_field('TuitionFee'); } ?></p>
				   <p class="class-member-tution"><strong>Member Tuition:</strong> <?php if(get_field('MembershipGlobalOverrideAmount')){ echo '$'. get_field('MembershipGlobalOverrideAmount'); } ?></p>
				   <p class="class-member-totalseats"><strong>Location:</strong> <?php echo get_field('LocationName'); ?> </p>
				   <p class="class-member-totalseats"><strong>Semester:</strong> <?php echo get_field('SemesterDescription'); ?> </p>
				   <p class="class-member-totalseats"><strong>Level:</strong> <?php echo get_field('Level'); ?> </p>
				   <p class="class-membe-instructor"><strong>Instructor:</strong> <a href="#"><?php echo $instNamesStr; ?></a></p>
				</div>
			  </div>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
	<?php endif; ?>
</div>

<?php

get_footer();
