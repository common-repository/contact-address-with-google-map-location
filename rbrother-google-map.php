<?php
/**
 * Plugin Name:       Address and Google Map Widget
 * Plugin URI:        http://devinlabs.com/
 * Description:       Widget that displays the HTML Address and google map.
 * Version:           1.0.0
 * Author:            Ravi Kumar
 * Author URI:        http://devinlabs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

class rbrother_google_map extends WP_Widget {

	public function __construct(){
	
		$widget_ops = array(
			'classname' => 'widget_rbrother',
			'description' => __( 'Show the HTML Address and Google Map' ),
			'customize_selective_refresh' => true,
		);
		$control_ops = array( 'width' => 500, 'height' => 400 );
		
		 // Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	
	
		parent::__construct('rbrother-google-map',$name = __('Address and Google Map Widget'), $widget_ops,$control_ops);
	}
	
	public function widget($args,$instance){
	
		$widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';
	
		$text = apply_filters( 'widget_text', $widget_text, $instance, $this );
		

		extract( $args );
        
		echo $before_widget;
			
		$title = apply_filters( 'widget_title', empty($instance['title']) ? __( 'Rbrother Google Map' ) : $instance['title'], $instance, $this->id_base );		
		  
		 echo $before_title .$title. $after_title;
		?>
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		  
		  /* Add your location here */				
		$location = $instance['location']?$instance['location']:'chandigarh';
		?>
		
		<div id="ravigoogleMap" style="width:100%;"></div>
			<iframe width="100%" height="auto" frameborder="0" src="https://maps.google.it/maps?q=<?php echo $location; ?>&output=embed"></iframe>
		

		<?php
		echo $after_widget;
	}
	
	public function form($instance){// Widget Inputs
		$title = '';
		$location = 'chandigarh';
		if(isset($instance['title'])){ $title = $instance['title']; }
		if(isset($instance['location'])){ $location = strip_tags($instance['location']); }
		
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('location'); ?>"><?php _e('Store Address or Correct Store Name:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo esc_attr($location); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Store Address:' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		
		<?php
	}
	
	public function update($new_instance,$old_instance){ // Update and Clean the Data
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['location'] = sanitize_text_field( $new_instance['location'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}
	
	
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate


	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

}

add_action('widgets_init',function(){
	register_widget('rbrother_google_map');
});
?>