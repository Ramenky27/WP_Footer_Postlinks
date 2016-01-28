<?php

class footerPostlinks {
	private static $initiated = false;
	private static $options;
	private static $posts;
	private static $query = array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'nopaging'		=> true,
			'orderby'       => 'post_date',
			'meta_key'		=> '_fpl_show',
			'meta_value'	=> true,
			'order'         => 'DESC',
			'numberposts'	=> -1
		);
			
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
		
		//self::get_options();
		self::$posts = self::get_posts();
	}	
	
	public static function init_hooks() {
		self::$initiated = true;
		
		add_action( 'wp_footer', array('footerPostlinks', 'display'));		
	}
	
	public static function get_options() {
		self::$options = get_option( 'postlinks' );
		if(self::$options){
			if(isset(self::$options['ids'])) self::$query['include'] = self::$options['ids'];
			if(isset(self::$options['orderby'])) self::$query['orderby'] = self::$options['orderby'];
			if(isset(self::$options['order'])) self::$query['order'] = self::$options['order'];
			if(isset(self::$options['count'])) self::$query['numberposts'] = self::$options['count'];
		}		
	}
	
	public static function get_posts() {		
		 return get_posts( self::$query );		
	}
	
	public static function display() {
		global $post;
		
		if(!self::$posts) return;
		
		$output = '<ul class="footer-postlinks">';
		
		foreach(self::$posts as $post){ 
			setup_postdata($post);
			$output .= '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
		}
		wp_reset_postdata();
		
		$output .= '</ul>';		
		
		echo $output;
	}
}