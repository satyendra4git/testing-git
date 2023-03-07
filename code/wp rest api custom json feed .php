<?php 
/** 
//wp json create custom feed of events
URL- https://bigearsstage.wpengine.com/wp-json/feeds/events
syntax example:- https://bigearsstage.wpengine.com/wp-json/{{namespace}}/{{path}}
**/
/**
 * Get All Pages Rest Route
 */
add_action('rest_api_init', function () {
	$namespace = 'feeds';
	$path = 'events';

	register_rest_route($namespace, $path,
		[
			'args'     => [
				'locales' => [
					'required' => false,
					'type'     => 'array'
				]
			],
			'methods'  => 'GET',
			'callback' => __NAMESPACE__ . '\get_events_feed'
		]
	);
});

/**
 * Get Events Feed
 *
 * @return array [array]
 */
function get_events_feed(): array
{
	global $wpdb;
	$events_query = $wpdb->prepare("
SELECT ID, post_name from wp_posts
WHERE wp_posts.post_status = 'publish'
AND wp_posts.post_type = 'event'
ORDER BY wp_posts.post_name
");

	/**
	 * WPDB Get results
	 */
	$events = $wpdb->get_results($events_query);

	return array_map(static function ($event) {
		// We need an alphabetical array to be used in multiple events to convert key to alphabet
		$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X ', 'Y', 'Z');
		$event_id = $event->ID;
		$event_type_obj = get_the_terms($event_id, 'event_type');
		$event_venue_obj = get_the_terms($event_id, 'event_venue')[0] ?? null;
		$dates_times_obj = get_field('dates_times', $event_id) ?: [];
		$event_venue = null;
		$artists_obj = get_field('artists', $event_id) ?: [];
		$event_content = null;
		$content_blocks = parse_blocks(get_post($event_id)->post_content);
		foreach ($content_blocks as $block) {
			if ($block['blockName'] === 'core/paragraph') {
				$event_content[] = render_block($block);
			}
		}
		$event_title = wp_strip_all_tags(get_the_title($event_id));
		$event_subtitle = get_field('subtitle', $event_id) ?: null;
		$event_quick_introduction = get_field('quick_introduction', $event_id) ?: null;
		$event_excerpt = $event_content ? implode(' ', $event_content) : null;
		$event_description = [];

		if ($event_subtitle) {
			$event_description[] = wp_sprintf('<h5>%s</h5>', $event_subtitle);
		}

		if ($event_quick_introduction) {
			$event_description[] = $event_quick_introduction;
		}
		if ($event_excerpt) {
			$event_description[] = $event_excerpt;
		}

		$performers = array_map(static function ($artist) {
			return [
				'id'    => $artist->ID,
				'title' => $artist->post_title,
			];
		}, $artists_obj);

		// Check if there are more than one dates
		if (is_array($dates_times_obj) && count($dates_times_obj) > 1) {
			$multiple_events = [];
			// Loop through dates and add multiple event dates to array
			foreach ($dates_times_obj as $key => $dates_time_obj) {
				$event_start_date = $dates_times_obj[$key]['event_date'];
				$event_end_date = $dates_times_obj[$key]['event_date'];
				$event_start_time = $dates_times_obj[$key]['event_time'];
				$event_end_time = $dates_times_obj[$key]['end_time'];
                
				// Check if end time is smaller than start time, which means that it should be after midnight
				// If the condition is true, then bump the end date by 1 day.
				if (strtotime($event_end_time) < strtotime($event_start_time)) {
					$event_end_date = date('Y-m-d', strtotime($event_end_date . ' +1 day'));
				}

				$start_time = !empty($dates_times_obj[$key]) ? wp_sprintf('%s %s', $event_start_date, $event_start_time) : null;
				$end_time = !empty($dates_times_obj[$key]) ? wp_sprintf('%s %s', $event_end_date, $event_end_time) : null;

				$multiple_events[$key]['id'] = wp_sprintf('%s-%s', $event_id, $alpha[$key]);
				$multiple_events[$key]['title'] = $event_title;
				$multiple_events[$key]['photo_large'] = get_the_post_thumbnail_url($event_id, 'large');
				$multiple_events[$key]['start_time'] = $start_time;
				$multiple_events[$key]['end_time'] = $end_time;
				$multiple_events[$key]['updated_at'] = get_the_modified_date('Y-m-d H:i:s', $event_id);
				$multiple_events[$key]['description'] = implode(' ', $event_description);
				$multiple_events[$key]['preview_text'] = implode(' ', $event_description);
				$multiple_events[$key]['common_button_text'] = '';
				$multiple_events[$key]['common_button_url'] = '';
				$multiple_events[$key]['common_button_color'] = '#000000';
				$multiple_events[$key]['status_message'] = '';
				$multiple_events[$key]['performers'] = $performers;
				$multiple_events[$key]['stage'] = get_term_object_by_id($dates_time_obj['venue']);
				$multiple_events[$key]['event_categories'] = !empty($event_type_obj) ? wp_list_pluck($event_type_obj, 'name') : [];
				$multiple_events[$key]['is_published'] = true;
			}

			return $multiple_events;
		}

		$event_start_date = $dates_times_obj[0]['event_date'];
		$event_end_date = $dates_times_obj[0]['event_date'];
		$event_start_time = $dates_times_obj[0]['event_time'];
		$event_end_time = $dates_times_obj[0]['end_time'];

		// Check if end time is smaller than start time, which means that it should be after midnight
		// If the condition is true, then bump the end date by 1 day.
		if (strtotime($event_end_time) < strtotime($event_start_time)) {
			$event_end_date = date('Y-m-d', strtotime($event_end_date . ' +1 day'));
		}

		$start_time = !empty($dates_times_obj) ? wp_sprintf('%s %s', $event_start_date, $event_start_time) : null;
		$end_time = !empty($dates_times_obj) ? wp_sprintf('%s %s', $event_end_date, $event_end_time) : null;

		return [
			'id'                  => $event_id,
			'title'               => $event_title,
			'photo_large'         => get_the_post_thumbnail_url($event_id, 'large'),
			'start_time'          => $start_time,
			'end_time'            => $end_time,
			'updated_at'          => get_the_modified_date('Y-m-d H:i:s', $event_id),
			'description'         => implode(' ', $event_description),
			'preview_text'        => implode(' ', $event_description),
			'common_button_text'  => '',
			'common_button_url'   => '',
			'common_button_color' => '#000000',
			'status_message'      => '',
			'performers'          => $performers,
			'stage'               => get_term_object_by_id($dates_times_obj[0]['venue']),
			'event_categories'    => !empty($event_type_obj) ? wp_list_pluck($event_type_obj, 'name') : [],
			'event_time'          => 'strt-> '.$event_start_time.' end-> '.$event_end_time,          
			'is_published'        => true,
		];
	}, $events);

}