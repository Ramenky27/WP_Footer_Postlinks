<?php
	add_settings_section( 'main_section', 'Основные настройки', '', 'footer-postlinks' ); 
	add_settings_field('posts_ids', 'ID постов', function () {
		$postlinks = get_option('postlinks');
		$ids = $postlinks['ids'];
		echo '<input type="text" name="postlinks[ids]" value="' . esc_attr( $ids )  . '" />';
	}, 'footer-postlinks', 'main_section' );
?>

<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

	<form action="options.php" method="POST">
		<?php
			settings_fields( 'footer-postlinks-main' );
			do_settings_sections( 'footer-postlinks' );
			submit_button();
		?>
	</form>
</div>