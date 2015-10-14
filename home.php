<!DOCTYPE html>
<html>
<head>
	<title>A New Private Social Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php wp_head(); ?>
</head>
<body>
	<div class="container" style="max-width: 600px; margin: 0 auto;">
		<div id="status-box" class="status">
			<textarea placeholder="Say something."></textarea>
			<div>
				<button id="post-status" class="btn btn-default">Post</button>
			</div>
		</div>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'parts/loop', 'home' ); ?>
		<?php endwhile; else : ?>
			<p><?php _e( 'Say something.' ); ?></p>
		<?php endif; ?>
		
	</div>
	<?php wp_footer(); ?>
</body>
</html>