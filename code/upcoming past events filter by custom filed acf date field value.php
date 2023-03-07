<div class="o-main__content">
				<?php //$loop = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6 ) ); ?>
				<?php 
	             $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				//$post_per_page = $paged==1?11:12;
				 $event_catobj = get_field('event_category');
				//echo "<pre>";print_r($event_catobj);die;
				$event_catslug = !empty($event_catobj) ? $event_catobj->slug : "events";
				$args = array(
					'post_type' => 'post',
					//'orderby' => 'DATE', 
					//'order' => 'DESC', 
					'paged'=> $paged,
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'tax_query' => array(
											array(
												'taxonomy' => 'category',
												'terms' => $event_catslug,
												'field' => 'slug',
												'include_children' => true,
												'operator' => 'IN'
											)
										),
					'meta_key'          => 'event_date',
					'orderby'           => 'meta_value',
				);
				// order upcoming evevents by desc
				$args['order'] = 'DESC';
				$args['meta_query'] = array(
											array(
												'key' => 'event_date',
												'value' => date('Y-m-d H:i:s'),
												'compare' => '>='
											)); 
				
				$loop = new WP_Query($args);
				//echo "<pre>";print_r($args);
				echo count($loop->posts);

				?>
				   <?php if($loop->have_posts()): ?>
					<section class="events-wrapper rp-upcoming-events">
						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<article class="eventsgrid">
							<div class="events-image">
								<a href="<?php echo get_permalink(); ?>">
									<?php $thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
									if($thumbnail_image != ''): ?>
									<div class="post-thumb">
										<img src="<?=$thumbnail_image;?>" class="img-responsive" alt="img" style="max-width:100%" width="1170" height="768">
									</div>
									<?php endif; ?>
									<?php $thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
									if($thumbnail_image == ''): ?>
									<div class="post-thumb">
										<img src="/wp-content/uploads/2021/05/square-logo.jpg" class="img-responsive" alt="img" style="max-width:100%" width="1170" height="768">
									</div>
									<?php endif; ?>
								</a>
							</div>
							<div class="events-details">
								<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
								<span class="date"><?php the_time( 'l, F jS, Y, H:i' ); ?></span>
							</div>
						</article>
						<?php endwhile; ?>
					</section>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				<h2>Past Events</h2>
				<?php 
				      //print_r($args);
				      unset($args['order']);
				      unset($args['meta_query']); 
				       // Order past  events by asc
						$args['order'] = 'ASC';
						$args['meta_query'] = array(
													array(
														'key' => 'event_date',
														'value' => date('Y-m-d H:i:s'),
														'compare' => '<='
													)); 
				       //print_r($args);
				       $loop = new WP_Query($args);
				      //echo "<pre>";print_r($loop);
				      //echo count($loop->posts);
				?>
				 <?php if($loop->have_posts()): ?>
					<section class="events-wrapper rp-past-events">
						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<article class="eventsgrid">
							<div class="events-image">
								<a href="<?php echo get_permalink(); ?>">
									<?php $thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
									if($thumbnail_image != ''): ?>
									<div class="post-thumb">
										<img src="<?=$thumbnail_image;?>" class="img-responsive" alt="img" style="max-width:100%" width="1170" height="768">
									</div>
									<?php endif; ?>
									<?php $thumbnail_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
									if($thumbnail_image == ''): ?>
									<div class="post-thumb">
										<img src="/wp-content/uploads/2021/05/square-logo.jpg" class="img-responsive" alt="img" style="max-width:100%" width="1170" height="768">
									</div>
									<?php endif; ?>
								</a>
							</div>
							<div class="events-details">
								<h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
								<span class="date"><?php the_time( 'l, F jS, Y, H:i' ); ?></span>
							</div>
						</article>
						<?php endwhile; ?>
					</section>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
				
						<?php Hybrid\View\display( 'partials', 'footer-cta-statement' ); ?>
			</div>
