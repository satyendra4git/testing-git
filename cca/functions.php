<?php/** * @author Divi Space * @copyright 2022 */if ( ! defined('ABSPATH') ) {	die();}add_action('wp_enqueue_scripts', 'ds_ct_enqueue_parent');function ds_ct_enqueue_parent() {	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');}add_action('wp_enqueue_scripts', 'ds_ct_loadjs');function ds_ct_loadjs() {	wp_enqueue_script('ds-theme-script', get_stylesheet_directory_uri() . '/ds-script.js', array('jquery'));	wp_enqueue_script('ds-cta-filters-custom', get_stylesheet_directory_uri() . '/js/cta-filters-custom.js', array('jquery'));}include('login-editor.php');// Register taxonomy mediumfunction ccd_register_my_taxes_class_mediums() {	/**	 * Taxonomy: Mediums.	 */	$labels = [		"name" => esc_html__( "Mediums", "custom-post-type-ui" ),		"singular_name" => esc_html__( "Medium", "custom-post-type-ui" ),	];		$args = [		"label" => esc_html__( "Mediums", "custom-post-type-ui" ),		"labels" => $labels,		"public" => true,		"publicly_queryable" => true,		"hierarchical" => true,		"show_ui" => true,		"show_in_menu" => true,		"show_in_nav_menus" => true,		"query_var" => true,		"rewrite" => [ 'slug' => 'class_mediums', 'with_front' => true,  'hierarchical' => true, ],		"show_admin_column" => false,		"show_in_rest" => true,		"show_tagcloud" => false,		"rest_base" => "class_mediums",		"rest_controller_class" => "WP_REST_Terms_Controller",		"rest_namespace" => "wp/v2",		"show_in_quick_edit" => true,		"sort" => false,		"show_in_graphql" => false,	];	register_taxonomy( "class_mediums", [ "classes" ], $args );}add_action( 'init', 'ccd_register_my_taxes_class_mediums' );//filter classes with ajaxadd_action ('wp_ajax_cta_filters_ajax', 'cta_filters_ajax_call');add_action ('wp_ajax_nopriv_cta_filters_ajax', 'cta_filters_ajax_call');function cta_filters_ajax_call(){	parse_str($_GET['formdata'], $dataArray);	//print_r($dataArray);	extract($dataArray);	$args = array(					'post_type' => 'classes',					'orderby' => 'DATE', 					'order' => 'DESC', 					'posts_per_page' => 6,					'post_status' => 'publish',				);	if(!empty($ccd_semester)){		$args['tax_query'] = array(		                            array(											'taxonomy' => 'semesters',											'field' => 'term_taxonomy_id',											'terms' => array( $ccd_semester ),											'operator' => 'IN',										)		                          );			}	   if(!empty($ccd_medium)){	            $args['tax_query'][] = 								array(										'taxonomy' => 'class_mediums',										'field' => 'term_taxonomy_id',										'terms' => array( $ccd_medium ),										'operator' => 'IN',									);  			  			}  if(!empty($ccd_class_type)){				$args['tax_query'] = array(		                            array(											'taxonomy' => 'class_types',											'field' => 'term_taxonomy_id',											'terms' => array( $ccd_class_type ),											'operator' => 'IN',										)		                          );	}		if(!empty($ccd_class_days)){				$args['meta_query'][] = 		//'relation' => 'AND',        array(            'key' => 'class_days',            'value' => $ccd_class_days,			'type' => 'CHAR',            'compare' => 'LIKE'        );	}     if(!empty($ccd_class_levels)){				$args['meta_query'][] = 		//'relation' => 'AND',        array(            'key' => 'Level',            'value' => $ccd_class_levels,			'type' => 'CHAR',            'compare' => '='        );	}	//echo $ccd_class_days;		//print_r($args);		$loop = new WP_Query($args);	//print_r($classObj);	//echo count();	if($loop->have_posts()):	while ( $loop->have_posts() ) : $loop->the_post(); 	?>					  <article class="classesgrid classesgrid-<?php echo get_the_id(); ?>">						<div class="allclasses">							<div class="classes-image">								<a href="<?php echo get_permalink(); ?>">									<?php 										  //$thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 										   $thumbnail_image = get_field('ImageUrl') ? get_field('ImageUrl') : $noImageRandom;										   $startDate = strtotime(get_field('StartDate'));										   $endDate = strtotime(get_field('EndDate'));											$startTime = strtotime(get_field('StartTime'));											$endTime = strtotime(get_field('EndTime'));											$instructerData = [];											$instructerDataArr = []; 											$instructerDataFinalArr = [];											if(!empty(get_field('InstructorListWithID'))){												  $instructerArr = explode('|', get_field('InstructorListWithID'));												  if(!empty($instructerArr)){													  foreach($instructerArr as $instructor){														  $instructorNameArr = explode(":",$instructor);														  //print_r($instructorNameArr);														  $instructerDataArr['id'] = $instructorNameArr[0];														  $instructerDataArr['name'] = $instructorNameArr[1];														  $instructerDataFinalArr[] = $instructerDataArr;													  }												  }											}											 //echo "<pre>";print_r($instructerDataFinalArr);											 if(!empty($instructerDataFinalArr)){												 $instNames = [];											     $instids = [];												 foreach($instructerDataFinalArr as $instData){													$instNames[] = $instData['name'];													$instids[] = $instData['id'];												 }											 }											 $instNamesStr = !empty($instNames) ? implode(' | ',$instNames) : "N/A";											 $regLink = "https://reg125.imperisoft.com/ContemporaryArt/ProgramDetail/".get_field('ProgramDetailId')."/Registration.aspx";												 									?>																			<?php if($thumbnail_image != ''): ?>										<div class="post-thumb">											<img src="<?php echo $thumbnail_image; ?>" class="img-responsive" alt="<?php the_title();  ?>">										</div>									<?php endif; ?>								</a>							</div>							<div class="classes-details">								<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>								<div class="excerptContent"><p><?php //echo strip_shortcodes(wp_trim_words( get_the_content(), 30 )); ?></p></div>								<div class="classes-additional-details">								   <p class="class-date"> <?php echo date('F d', $startDate); ?>  <?php echo date('F d', $endDate); ?>, <?php echo date('h:i a', $startTime); ?> - <?php echo date('h:i a', $endTime); ?> </p>								   <p class="class-days"><strong>Days:</strong> <?php echo get_field('class_days'); ?></p>								   <p class="class-tution"><strong>Tuition:</strong> <?php if(get_field('TuitionFee')){ echo '$'.get_field('TuitionFee'); } ?></p>								   <p class="class-member-tution"><strong>Member Tuition:</strong> <?php if(get_field('MembershipGlobalOverrideAmount')){ echo '$'. get_field('MembershipGlobalOverrideAmount'); } ?></p>								   <p class="class-member-totalseats"><strong>Location:</strong> <?php echo get_field('LocationName'); ?> </p>								   <p class="class-member-totalseats"><strong>Semester:</strong> <?php echo get_field('SemesterDescription'); ?> </p>								   <p class="class-member-totalseats"><strong>Level:</strong> <?php echo get_field('Level'); ?> </p>								   <p class="class-membe-instructor"><strong>Instructor:</strong> <a href="#"><?php echo $instNamesStr;  ?></a></p>								</div>								<div class="btnWrapper">									<a href="<?php echo $regLink; ?>" class="btn" target="_blank">Enroll Now</a>									<a href="<?php echo get_permalink(); ?>" class="btn">View Details</a>								</div>							</div>						</div>					</article><?php  endwhile;  else:?><div class="cta-no-posts"><h4>Data not found.</h4></div><?php 	  endif; die;	}wp_reset_query();wp_reset_postdata();//loadmore classes with ajaxadd_action ('wp_ajax_cta_loadmore_ajax', 'cta_loadmore_ajax_call');add_action ('wp_ajax_nopriv_cta_loadmore_ajax', 'cta_loadmore_ajax_call');function cta_loadmore_ajax_call(){	parse_str($_GET['formdata'], $dataArray);	//print_r($dataArray);	extract($dataArray);	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	//echo $_GET['offset'];	$args = array(					'post_type' => 'classes',					'orderby' => 'DATE', 					'order' => 'DESC', 					 'offset' => $_GET['offset'],					'posts_per_page' => 6,					'post_status' => 'publish',				);	if(!empty($ccd_semester)){		$args['tax_query'][] = 								array(										'taxonomy' => 'semesters',										'field' => 'term_taxonomy_id',										'terms' => array( $ccd_semester ),										'operator' => 'IN',									);		                          			}	   if(!empty($ccd_medium)){	   	 $args['tax_query'][] = 								array(										'taxonomy' => 'class_mediums',										'field' => 'term_taxonomy_id',										'terms' => array( $ccd_medium ),										'operator' => 'IN',									);  					}  if(!empty($ccd_class_type)){				$args['tax_query'][] = 							array(									'taxonomy' => 'class_types',									'field' => 'term_taxonomy_id',									'terms' => array( $ccd_class_type ),									'operator' => 'IN',								);		                         	}		if(!empty($ccd_class_days)){				$args['meta_query'][] = 								//'relation' => 'AND',								array(									'key' => 'class_days',									'value' => $ccd_class_days,									'type' => 'CHAR',									'compare' => 'LIKE'								);	}     if(!empty($ccd_class_levels)){				$args['meta_query'][] = 								//'relation' => 'AND',								array(									'key' => 'Level',									'value' => $ccd_class_levels,									'type' => 'CHAR',									'compare' => '='								);	}		$loop = new WP_Query($args);	//print_r($classObj);	//echo count();	if($loop->have_posts()):	while ( $loop->have_posts() ) : $loop->the_post(); 	?>					  <article class="classesgrid echo classesgrid-<?php echo get_the_id(); ?>">						<div class="allclasses">							<div class="classes-image">								<a href="<?php echo get_permalink(); ?>">									<?php 										  //$thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 										   $thumbnail_image = get_field('ImageUrl') ? get_field('ImageUrl') : $noImageRandom;										   $startDate = strtotime(get_field('StartDate'));										   $endDate = strtotime(get_field('EndDate'));											$startTime = strtotime(get_field('StartTime'));											$endTime = strtotime(get_field('EndTime'));											$instructerData = [];											$instructerDataArr = []; 											$instructerDataFinalArr = [];											if(!empty(get_field('InstructorListWithID'))){												  $instructerArr = explode('|', get_field('InstructorListWithID'));												  if(!empty($instructerArr)){													  foreach($instructerArr as $instructor){														  $instructorNameArr = explode(":",$instructor);														  //print_r($instructorNameArr);														  $instructerDataArr['id'] = $instructorNameArr[0];														  $instructerDataArr['name'] = $instructorNameArr[1];														  $instructerDataFinalArr[] = $instructerDataArr;													  }												  }											}											 //echo "<pre>";print_r($instructerDataFinalArr);											 if(!empty($instructerDataFinalArr)){												   $instNames = [];											       $instids = [];												 foreach($instructerDataFinalArr as $instData){													$instNames[] = $instData['name'];													$instids[] = $instData['id'];												 }											 }											 $instNamesStr = !empty($instNames) ? implode(' | ',$instNames) : "N/A";											 $regLink = "https://reg125.imperisoft.com/ContemporaryArt/ProgramDetail/".get_field('ProgramDetailId')."/Registration.aspx";												 									?>																			<?php if($thumbnail_image != ''): ?>										<div class="post-thumb">											<img src="<?php echo $thumbnail_image; ?>" class="img-responsive" alt="<?php the_title();  ?>">										</div>									<?php endif; ?>								</a>							</div>							<div class="classes-details">								<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>								<div class="excerptContent"><p><?php //echo strip_shortcodes(wp_trim_words( get_the_content(), 30 )); ?></p></div>								<div class="classes-additional-details">								   <p class="class-date"> <?php echo date('F d', $startDate); ?>  <?php echo date('F d', $endDate); ?>, <?php echo date('h:i a', $startTime); ?> - <?php echo date('h:i a', $endTime); ?> </p>								   <p class="class-days"><strong>Days:</strong> <?php echo get_field('class_days'); ?></p>								   <p class="class-tution"><strong>Tuition:</strong> <?php if(get_field('TuitionFee')){ echo '$'.get_field('TuitionFee'); } ?></p>								   <p class="class-member-tution"><strong>Member Tuition:</strong> <?php if(get_field('MembershipGlobalOverrideAmount')){ echo '$'. get_field('MembershipGlobalOverrideAmount'); } ?></p>								   <p class="class-member-totalseats"><strong>Location:</strong> <?php echo get_field('LocationName'); ?> </p>								   <p class="class-member-totalseats"><strong>Semester:</strong> <?php echo get_field('SemesterDescription'); ?> </p>								   <p class="class-member-totalseats"><strong>Level:</strong> <?php echo get_field('Level'); ?> </p>								   <p class="class-membe-instructor"><strong>Instructor:</strong> <a href="#"><?php echo $instNamesStr;  ?></a></p>								</div>								<div class="btnWrapper">									<a href="<?php echo $regLink; ?>" class="btn" target="_blank">Enroll Now</a>									<a href="<?php echo get_permalink(); ?>" class="btn">View Details</a>								</div>							</div>						</div>					</article><?php  endwhile;  else:?><div class="cta-no-posts"><h4>Data not found.</h4></div><?php 	  endif; die;	}?>