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

		add_settings_section( 'main_section', 'Основные настройки', '', 'footer-postlinks' ); 
	
		add_settings_field('posts_ids', 'ID постов', array('footerPostlinks_Admin', 'field_posts_ids'), 'footer-postlinks', 'main_section' );
	}
	
	//Заполнение поля с ID постов
	public static function field_posts_ids(){
		$postlinks = get_option('postlinks');
		$ids = $postlinks['ids'];
		?>
		<input type="text" name="postlinks[ids]" value="<?php echo esc_attr( $ids ) ?>" />
		<?php
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
	?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>
	
			<form action="options.php" method="POST">
				<?php
					settings_fields( 'footer-postlinks-main' );     // скрытые защитные поля
					do_settings_sections( 'footer-postlinks' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
					submit_button();
				?>
			</form>
		</div>
	<?php		
	}
}