<?php

class footerPostlinks_Admin {
	
	const NONCE = 'footer-postlinks-key';
	
	private static $initiated = false;
	private static $notices = array();
	
	public static function init() {
		//Если по методу POST было передано действие
		if(isset($_POST['action']) && $_POST['action'] == 'add_postlink'){
			self::add_post();
		}
		
		if(isset($_GET['action']) && $_GET['action'] == 'remove_postlink'){
			self::remove_post();
		}
		
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_init', array( 'footerPostlinks_Admin', 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( 'footerPostlinks_Admin', 'load_resources' ) );
		add_action( 'admin_menu', array( 'footerPostlinks_Admin', 'admin_menu' ) );
		add_action( 'admin_notices', array( 'footerPostlinks_Admin', 'display_notice' ) );
	}
	
	public static function admin_init() {
		self::init_settings();
	}
	
	//Загрузка сss и js для вида
	public static function load_resources() {
		wp_register_style( 'footer-postlist.css', FOOTER_POSTLINKS_URL . 'assets/footer-postlist.css' );
		wp_enqueue_style( 'footer-postlist.css');
	}
	
	//Создание подпункта меню админки
	public static function admin_menu() {
		add_submenu_page( 'tools.php', 'Footer postlinks', 'Footer postlinks', 'manage_options', 'footer-postlinks', array( 'footerPostlinks_Admin', 'display_page' ) ); 
	}
	
	//Инициализация настроек
	public static function init_settings(){
		register_setting( 'footer-postlinks-main', 'postlinks', array('footerPostlinks_Admin', 'sanitize_callback') );
	}
	
	//Поиск поста и добавление ссылки
	public static function add_post(){
		if ( ! empty( $_POST ) && check_admin_referer( 'add_postlink' ) ) {	
		
			if(isset($_POST['post-id']) && !empty($_POST['post-id'])){
				$id = $_POST['post-id'];
				$searched_post = get_post( $id );
			}elseif(isset($_POST['post-slug']) && !empty($_POST['post-slug'])){
				$slug = $_POST['post-slug'];
				$searched_post = get_page_by_path( $slug, OBJECT, 'post' );
			}elseif(isset($_POST['post-name']) && !empty($_POST['post-name'])){
				$title = $_POST['post-name'];
				$searched_post = get_page_by_title( $title, OBJECT, 'post' );
			}else{
				self::add_notice('error', 'Не указаны данные для поиска');
				return;
			}
			
			if(!$searched_post){
				self::add_notice('error', 'Пост не найден');
				return;
			}
			
			update_post_meta($searched_post->ID, '_fpl_show', true);
			
			self::add_notice('updated', 'Пост успешно добавлен');
		}
	}
	
	//Удаление ссылки
	public static function remove_post(){
		if( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], self::NONCE ) && isset($_GET['id']) ){
			
			$id = $_GET['id'];
			
			if(!get_post_meta($id, '_fpl_show', true)){
				self::add_notice('error', 'Пост с таким ID не найден');
				return;	
			}
			
			delete_post_meta($id, '_fpl_show');
			self::add_notice('updated', 'Пост успешно удален');
		}
	}
	
	//Очистка ввода
	public static function sanitize_callback( $options ){ 
		foreach( $options as $name => & $val ){
			if( $name == 'ids' )
				$val = strip_tags( $val );
		}
	
		return $options;
	}
	
	public static function add_notice($type, $message){
		self::$notices[] = array('type' => $type, 'message' => $message);
	}
	
	//Отображение ошибок и уведомлений
	public static function display_notice(){
		
		if( count(self::$notices) == 0 ) return;
		
		foreach(self::$notices as $notice){
			self::view('notice', $notice);
		}
	}
	
	//Отображение страниц админки
	public static function display_page(){
		$footer_posts = footerPostlinks::get_posts();
		
		self::view('main', compact('footer_posts'));
	}
	
	//Вывод страниц и уведомлений
	public static function view( $name, array $args = array() ) {
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}

		$file = FOOTER_POSTLINKS_DIR . 'views/'. $name . '.php';

		include( $file );
	}
}