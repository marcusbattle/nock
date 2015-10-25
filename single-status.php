<!DOCTYPE html>
<html>
<head>
	<title>A New Private Social Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php wp_head(); ?>
</head>
<body>
	<div id="viewport" class="container" style="max-width: 600px; margin: 0 auto;">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'parts/loop', 'status' ); ?>
		<?php endwhile; else : ?>
			<p><?php _e( 'Say something.' ); ?></p>
		<?php endif; ?>
		
		<div class="ad">
			<h4>Ad</h4>
			<p>Here is an ad. They keep the network free.</p>
		</div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>