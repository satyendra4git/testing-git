<?php 
/**
 * Register custom ads 1 widget.
 */
class hl_ads1_panel_widget extends WP_Widget
{
    public function __construct()
    {                      
        parent::__construct( 'hl_ads1_widget', 'Ads Widget 1' );
    }

    public function widget( $args, $instance )
    {
        //$args['before_widget'], wpautop( $instance['hladdcls'] ), $args['after_widget'];
		//echo '<div class="">jhjghjhgj</div>';
		if(isset($instance['hladdcls'])){ $hladdcls = $instance['hladdcls']; }else{ $hladdcls = ""; }
		if($instance['hlshow']=='yes'){
          echo '<div class="add add-section-1 text-center '. $hladdcls .'"><span>Advertisement</span></div>';
		}
    }

    public function form( $instance ) {

		if(isset( $instance[ 'hlshow' ] ) ){ $hlshow = $instance[ 'hlshow' ]; }else{	 $hlshow = 'yes'; }
		if(isset( $instance[ 'hladdcls' ] ) ){ $hladdcls = $instance[ 'hladdcls' ]; }else{	 $hladdcls = ""; }
   
	?>	
		<div class="add add-section-1 text-center"><span>Advertisement</span></div>
		  <label for="<?php echo $this->get_field_id( 'hlshow' ); ?>">Hide?:</label> 
			<select name="<?php echo $this->get_field_name( 'hlshow' ); ?>" id="<?php echo $this->get_field_id( 'hlshow' ); ?>">
				<option value="yes" <?php if($hlshow=="yes"){ echo "selected"; } ?>>Yes</option>
				<option value="no" <?php if($hlshow=="no"){ echo "selected"; } ?>>No</option>
			</select>
			<label for="<?php echo $this->get_field_id( 'hladdcls' ); ?>">Ad container class:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'hladdcls' ); ?>" name="<?php echo $this->get_field_name( 'hladdcls' ); ?>" type="text" value="<?php echo esc_attr( $hladdcls ); ?>" />

				
<?php

    }
}

function hl_ads1_register_widget() {
	register_widget( 'hl_ads1_panel_widget' );
	}
add_action( 'widgets_init', 'hl_ads1_register_widget' );


/**
 * Register custom ads 2 widget.
 */
class hl_ads2_panel_widget extends WP_Widget
{
    public function __construct()
    {                      
        parent::__construct( 'hl_ads2_widget', 'Ads Widget 2' );
    }

    public function widget( $args, $instance )
    {
		  //echo $args['before_widget'], wpautop( $instance['hladdcls'] ), $args['after_widget'];
		if(isset($instance['hladdcls'])){ $hladdcls = $instance['hladdcls']; }else{ $hladdcls = ""; }
		if($instance['hlshow']=='yes'){
		  echo '<div class="add add-section-1 text-center '. $hladdcls .'"><span>Advertisement</span></div>';
		}

    }

    public function form( $instance ){
		if(isset( $instance[ 'hlshow' ] ) ){ $hlshow = $instance[ 'hlshow' ]; }else{	 $hlshow = 'yes'; }
		if(isset( $instance[ 'hladdcls' ] ) ){ $hladdcls = $instance[ 'hladdcls' ]; }else{	 $hladdcls = ""; }

?>
		<div class="add add-section-1 text-center"><span>Advertisement</span></div>
		<label for="<?php echo $this->get_field_id( 'hlshow' ); ?>">Hide?:</label> 
			<select name="<?php echo $this->get_field_name( 'hlshow' ); ?>" id="<?php echo $this->get_field_id( 'hlshow' ); ?>">
				<option value="yes" <?php if($hlshow=="yes"){ echo "selected"; } ?>>Yes</option>
				<option value="no" <?php if($hlshow=="no"){ echo "selected"; } ?>>No</option>
			</select>
			<label for="<?php echo $this->get_field_id( 'hladdcls' ); ?>">Ad container class:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'hladdcls' ); ?>" name="<?php echo $this->get_field_name( 'hladdcls' ); ?>" type="text" value="<?php echo esc_attr( $hladdcls ); ?>" />
<?php 

    }
}

function hl_ads2_register_widget() {
	register_widget( 'hl_ads2_panel_widget' );
	}
add_action( 'widgets_init', 'hl_ads2_register_widget' );

/**
 * Register custom ads 3 widget.
 */
class hl_ads3_panel_widget extends WP_Widget
{
    public function __construct()
    {                      
        parent::__construct( 'hl_ads3_widget', 'Ads Widget 3' );
    }

    public function widget( $args, $instance )
    {
        //echo $args['before_widget'], wpautop( $instance['hlshow'] ), $args['after_widget'];
		//echo $args['before_widget'], wpautop( $instance['hladdcls'] ), $args['after_widget'];
		if(isset($instance['hladdcls'])){ $hladdcls = $instance['hladdcls']; }else{ $hladdcls = ""; }
		if($instance['hlshow']=='yes'){
		echo '<div class="last-add">
		       <div class="add add-section-2 text-center '. $hladdcls .'"><span>Advertisement</span></div>
	          </div>';
		}

    }

    public function form( $instance ){
		if(isset( $instance[ 'hlshow' ] ) ){ $hlshow = $instance[ 'hlshow' ]; }else{	 $hlshow = 'yes'; }
		if(isset( $instance[ 'hladdcls' ] ) ){ $hladdcls = $instance[ 'hladdcls' ]; }else{	 $hladdcls = ""; }	
	?>	
	   <div class="last-add"><div class="add add-section-2 text-center"><span>Advertisement</span></div></div>
	   <label for="<?php echo $this->get_field_id( 'hlshow' ); ?>">Hide?:</label> 
			<select name="<?php echo $this->get_field_name( 'hlshow' ); ?>" id="<?php echo $this->get_field_id( 'hlshow' ); ?>">
				<option value="yes" <?php if($hlshow=="yes"){ echo "selected"; } ?>>Yes</option>
				<option value="no" <?php if($hlshow=="no"){ echo "selected"; } ?>>No</option>
			</select>
			<label for="<?php echo $this->get_field_id( 'hladdcls' ); ?>">Ad container class:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'hladdcls' ); ?>" name="<?php echo $this->get_field_name( 'hladdcls' ); ?>" type="text" value="<?php echo esc_attr( $hladdcls ); ?>" />
			  

<?php 

    }
}

function hl_ads3_register_widget() {
	register_widget( 'hl_ads3_panel_widget' );
	}
add_action( 'widgets_init', 'hl_ads3_register_widget' );


/**
 * Register custom video ads widget.
 */
class hl_ads_video_widget extends WP_Widget
{
    public function __construct()
    {                      
        parent::__construct( 'hl_ads_video_widget', 'Video Widget' );
    }

    public function widget( $args, $instance )
    {
       //echo $args['before_widget'], wpautop( $instance['hlvideoid'] ), $args['after_widget'];
	   if(isset($instance['hlvideoid'])){ $hlvideoid = $instance['hlvideoid']; }else{ $hlvideoid = ""; }
	   echo '<video id="'. $hlvideoid .'" class="left-video-add" poster="<?php echo get_template_directory_uri(); ?>/assets/img/home-video-bg-01.png" style="width: 100%; height: auto;" controls="">
               <source src="https://webdevproof.com/click-funnel-videos/video.mp4" type="video/mp4">
               <source src="https://webdevproof.com/click-funnel-videos/video.webm" type="video/webm">
            </video>';

    }

    public function form( $instance ){
	if(isset( $instance[ 'hlvideoid' ] ) ){  $hlvideoid = $instance[ 'hlvideoid' ]; }else{	 $hlvideoid = ""; }	
?>
           <video class="left-video-add" poster="<?php echo get_template_directory_uri(); ?>/assets/img/home-video-bg-01.png" style="width: 100%; height: auto;" controls="">
               <source src="https://webdevproof.com/click-funnel-videos/video.mp4" type="video/mp4">
               <source src="https://webdevproof.com/click-funnel-videos/video.webm" type="video/webm">
            </video>
			<label for="<?php echo $this->get_field_id( 'hlvideoid' ); ?>">Video id:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'hlvideoid' ); ?>" name="<?php echo $this->get_field_name( 'hlvideoid' ); ?>" type="text" value="<?php echo esc_attr( $hlvideoid ); ?>" />
<?php

    }
}

function hl_ads_vido_register_widget() {
	register_widget( 'hl_ads_video_widget' );
	}
add_action( 'widgets_init', 'hl_ads_vido_register_widget' );
