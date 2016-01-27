<?php

class footerPostlinks_Admin {
	
	private static $initiated = false;
	
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_init', array( 'footerPostlinks_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'footerPostlinks_Admin', 'admin_menu' ) );
	}	
	
	public static function admin_init() {
		self::init_settings();
	}
	
	//Создание подпункта меню админки
	public static function admin_menu() {
		add_submenu_page( 'tools.php', 'Footer postlinks', 'Footer postlinks', 'manage_options', 'footer-postlinks', array( 'footerPostlinks_Admin', 'display_page' ) ); 
	}
	
	//Инициализация настроек
	public static function init_settings(){
		register_setting( 'footer-postlinks-main', 'postlinks', array('footerPostlinks_Admin', 'sanitize_callback') );
	}
	
	//Очистка ввода
	public static function sanitize_callback( $options ){ 
		foreach( $options as $name => & $val ){
			if( $name == 'ids' )
				$val = strip_tags( $val );
		}
	
		return $options;
	}
	
	//Отображение страницы настроек
	public static function display_page() {
		include('view.admin.php');
	}
}