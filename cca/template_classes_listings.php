<?php
/*
Template Name: Classes
*/

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$limit = 6;
$argsp = array(
				'post_type' => 'classes',
				'orderby' => 'DATE', 
				'order' => 'DESC', 
				'paged'=> $paged,
				'posts_per_page' => $limit,
				'post_status' => 'publish',
			);
$loop = new WP_Query($argsp);
//echo "<pre>";print_r($loop);
//Get random images for the classes having no image
$randImgArr = [];
if(have_rows('class_random_images')){
	while ( have_rows('class_random_images') ){ the_row();
        $imgArr = get_sub_field('image');
        //echo "<pre>";print_r($imgArr);
		 $randImgArr[] = $imgArr['sizes']['medium'];
   }	
}	
//echo "<pre>";print_r($randImgArr);
$noImageRandom = $randImgArr[array_rand($randImgArr)];			
?>
 <div id="main-content" class="classesWrapper">
	<div class="container">
		<div id="content-area" class="clearfix content-area">
			<div class="left-area" id="left-area">
			 <h2>Find a Class</h2>
			 	<h3>Filter Classes</h3>
				<div class="filtersWrapper">
				<div class="cta-ajax-loader">
				<img id="cta-loader" src="<?php echo get_stylesheet_directory_uri().'/images/ajax-loader.gif' ?>" style="display: none">
				</div>
				 <form method="post" action="" class="ctafilters" id="ctafilters"> 
				 
					<div class="facet-wrap facet-wrap--semesters">
						<h6 class="facet-header">Semesters</h6>
						<div class="facetwp-facet facetwp-facet-classes_type facetwp-type-checkboxes">
						   <?php    $semesterTerms = get_terms([
														'taxonomy' => "semesters",
														'hide_empty' => false,
											       ]);
									 //echo "<pre>";print_r($semesterTerms);
                                     if(!empty($semesterTerms) && !is_wp_error($semesterTerms)){
						  ?>
						   <select name="ccd_semester" class="ccd_semester ctafilters_select">
						   <option value="" selected>Any</option>
                          <?php 								
                                    foreach($semesterTerms as $semester){ 										 
		                  ?>
						  <option value="<?php echo $semester->term_id; ?>"><?php echo $semester->name; ?> (<?php echo $semester->count; ?>)</option>
							
						 <?php        }//endforeach ?>
						 </select>
						<?php        }//endif    ?>
							
						</div>
					</div>
					<div class="facet-wrap facet-wrap--class-type">
						<h6 class="facet-header">Medium</h6>
						<div class="facetwp-facet facetwp-facet-classes_type facetwp-type-checkboxes">
						<?php    $mediumTerms = get_terms([
														'taxonomy' => "class_mediums",
														'hide_empty' => false,
											       ]);
									 //echo "<pre>";print_r($semesterTerms);
                                     if(!empty($mediumTerms) && !is_wp_error($mediumTerms)){
						?>
						<select name="ccd_medium" class="ccd_medium ctafilters_select">
						   <option value="">Any</option>
                        <?php 						
                                     foreach($mediumTerms as $medium){ 										 
		                ?>
						    <option value="<?php echo $medium->term_id; ?>"><?php echo $medium->name; ?> (<?php echo $medium->count; ?>)</option>
							<?php        }    ?>
							</select>
							<?php        }    ?>
							
						</div>
					</div>
					
					<div class="facet-wrap facet-wrap--class-type">
						<h6 class="facet-header">Program Type</h6>
						<div class="facetwp-facet facetwp-facet-classes_type facetwp-type-checkboxes">
						<?php    $classTypeTerms = get_terms([
														'taxonomy' => "class_types",
														'hide_empty' => false,
											       ]);
									 //echo "<pre>";print_r($classTypeTerms);
                                     if(!empty($classTypeTerms) && !is_wp_error($classTypeTerms)){
						?>
						<select name="ccd_class_type" class="ccd_class_type ctafilters_select">
						   <option value="" selected>Any</option>
						<?php 
                                     foreach($classTypeTerms as $classType){ 										 
		                ?>
						   <option value="<?php echo $classType->term_id; ?>"><?php echo $classType->name; ?> (<?php echo $classType->count; ?>)</option>
							
						<?php        }    ?>
						</select>
						<?php        }    ?>
							
						</div>
					</div>
					
					  <div class="facet-wrap facet-wrap--class-type">
							<h6 class="facet-header">Days</h6>
							<div class="facetwp-facet facetwp-facet-classes_type facetwp-type-checkboxes">
							  <select name="ccd_class_days" class="ccd_class_days ctafilters_select">
								 <option value="" selected>Any</option>
								  <option value="Sunday">Sunday</option>
								  <option value="Monday">Monday</option>
								  <option value="Tuesday">Tuesday</option>
								  <option value="Wednesday">Wednesday</option>
								  <option value="Thursday">Thursday</option>
								  <option value="Friday">Friday</option>
								  <option value="Saturday">Saturday</option>
							</select>
							</div>
						</div>
						<div class="facet-wrap facet-wrap--class-type">
							<h6 class="facet-header">Level</h6>
							<div class="facetwp-facet facetwp-facet-classes_type facetwp-type-checkboxes">
							  <select name="ccd_class_levels" class="ccd_class_days ctafilters_select">
								  <option value="" selected>Any</option>
								  <option value="Children (Ages 5-8)">Children (Ages 5-8)</option>
								  <option value="Children (Ages 9-11)">Children (Ages 9-11)</option>
								  <option value="Teens (Ages 12-15)">Teens (Ages 12-15)</option>
								  <option value="Special Needs (ages 6-10)">Special Needs (ages 6-10)</option>
								  <option value="Special Needs (ages 11-15)">Special Needs (ages 11-15)</option>
								  <option value="Adult-All Levels">Adult-All Levels</option>
								  <option value="Adult-Advanced">Adult-Advanced</option>
								  <option value="Adult & Teens 16+ - Beginner">Adult & Teens 16+ - Beginner</option>
							</select>
							</div>
						</div>
						<a href="#" class="cta-reset-filter btn" id="cta-reset-filter" style="display:none">Reset Filter</a>
					</form>
				</div>
			<?php if($loop->have_posts()): ?>
					<div class="entry-content classes-list cta-classes-list" data-total-post="<?php echo $loop->found_posts; ?>">
					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					  <article class="classesgrid">
							<div class="allclasses">
								<div class="classes-image">
									<a href="<?php echo get_permalink(); ?>">
										<?php   
											  //$thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
											   $thumbnail_image = get_field('ImageUrl') ? get_field('ImageUrl') : $noImageRandom;
											   $startDate = strtotime(get_field('StartDate'));
											   $endDate = strtotime(get_field('EndDate'));
												$startTime = strtotime(get_field('StartTime'));
												$endTime = strtotime(get_field('EndTime'));
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
													 $instNames = [];
													 $instids = [];
													 foreach($instructerDataFinalArr as $instData){
													    $instNames[] = $instData['name'];
													    $instids[] = $instData['id'];
													 }
												 }
												 $instNamesStr = !empty($instNames) ? implode(' | ',$instNames) : "N/A";
												 $regLink = "https://reg125.imperisoft.com/ContemporaryArt/ProgramDetail/".get_field('ProgramDetailId')."/Registration.aspx";
													 
										?>
											
										<?php if($thumbnail_image != ''): ?>
											<div class="post-thumb">
												<img src="<?php echo $thumbnail_image; ?>" class="img-responsive" alt="<?php the_title();  ?>">
											</div>
										<?php endif; ?>
									</a>
								</div>
								<div class="classes-details">
									<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="excerptContent"><p><?php //echo strip_shortcodes(wp_trim_words( get_the_content(), 30 )); ?></p></div>
									<div class="classes-additional-details">
									   <p class="class-date"> <?php echo date('F d', $startDate); ?> - <?php echo date('F d', $endDate); ?>, <?php echo date('h:i a', $startTime); ?> - <?php echo date('h:i a', $endTime); ?> </p>
									   <p class="class-days"><strong>Days:</strong> <?php echo get_field('class_days'); ?></p>
									   <p class="class-tution"><strong>Tuition:</strong> <?php if(get_field('TuitionFee')){ echo '$'.get_field('TuitionFee'); } ?></p>
									   <p class="class-member-tution"><strong>Member Tuition:</strong> <?php if(get_field('MembershipGlobalOverrideAmount')){ echo '$'. get_field('MembershipGlobalOverrideAmount'); } ?></p>
									   <p class="class-member-totalseats"><strong>Location:</strong> <?php echo get_field('LocationName'); ?> </p>
									   <p class="class-member-totalseats"><strong>Semester:</strong> <?php echo get_field('SemesterDescription'); ?> </p>
									   <p class="class-member-totalseats"><strong>Level:</strong> <?php echo get_field('Level'); ?> </p>
									   <p class="class-membe-instructor"><strong>Instructor:</strong> <a href="#"><?php echo $instNamesStr;  ?></a></p>
									</div>
									<div class="btnWrapper">
										<a href="<?php echo $regLink; ?>" class="btn" target="_blank">Enroll Now</a>
										<a href="<?php echo get_permalink(); ?>" class="btn viwebtn">View Details</a> 
									</div>
								</div>
							</div>
						</article>
					 <?php endwhile; ?>
					   
					</div>
					<?php if( $loop->found_posts > $limit){ ?>
					<div class="pagination btnWrapper">
					   <a href="#" class="load-more-classes-home btn"><img class="loadmore-loader-img" src="<?php echo get_stylesheet_directory_uri().'/images/ajax-loader.gif' ?>" style="display:none">Load More</a>	 
                    </div>
					<?php } ?>
					
			<?php endif; ?>		
			</div>
			<!--<div class="right-area">
			</div>-->
		</div>
	</div>
</div>

<?php

get_footer();
