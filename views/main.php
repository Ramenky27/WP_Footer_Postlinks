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

	<form action="<?php echo add_query_arg( array( 'page' => 'footer-postlinks' ), admin_url( 'admin.php' ) ); ?>" method="POST">
		<input name="action" value="add_postlink" type="hidden">
		<?php wp_nonce_field( 'add_postlink' ); ?>
		<h2>Добавить ссылку на пост</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">по ID поста</th>
					<td><input name="post-id" type="text"></td>
				</tr>
				<tr>
					<th scope="row">по Псевдониму поста</th>
					<td><input name="post-slug" type="text"></td>
				</tr>
				<tr>
					<th scope="row">по Заголовку поста</th>
					<td><input name="post-name" type="text"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Добавить" type="submit"></p>
	</form>
	
	<?php 
		if($footer_posts):
	?>
	<h2>Добавленные посты</h2>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th scope="col" id="title" class="manage-column column-title column-primary">Заголовок</th>
				<th scope="col" id="action" class="manage-column column-link column-primary">Ссылка</th>
				<th scope="col" id="action" class="manage-column column-action">Действия</th>
			</tr>
		</thead>
	
		<tbody id="the-list">	
		<?php
			global $post;
			foreach($footer_posts as $post):
			setup_postdata($post);
		?>
		<tr id="post-<?php the_ID(); ?>">
			<td class="column-title column-primary"><strong><?php  the_title(); ?></strong></td>
			<td class="column-link column-primary"><strong><a href="<?php  the_permalink(); ?>"><?php the_permalink(); ?></a></strong></td>
			<td class="column-action"><a href="<?php echo add_query_arg( array( 'page' => 'footer-postlinks', 'action' => 'remove_postlink', 'id' => get_the_ID(), '_wpnonce' => wp_create_nonce( self::NONCE ) ), admin_url( 'admin.php' ) ); ?>">Удалить</a></td>
		</tr>
		<?php
			endforeach;
			wp_reset_postdata();
	?>
		</tbody>
	</table>
	<?php
		endif;
	?>
</div>