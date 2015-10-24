<!DOCTYPE html>
<html>
<head>
	<title>A New Private Social Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php wp_head(); ?>
</head>
<body>
	<div id="viewport" class="container" style="max-width: 600px; margin: 0 auto;">
		<form id="status-box" enctype="multipart/form-data">
			<div class="header">
				<span class="action"><a href="#" class="add-picture">Add Picture</a></span>
				<input type="file" accept="image/*" id="image-selector" style="display: none;" />
			</div>
			<div id="image-preview"></div>
			<textarea name="status" placeholder="Say something."></textarea>
			<div>
				<button id="post-status" class="btn btn-default">Post</button>
				<span id="upload-percentage"></span>
				<a class="close-box" href="#">Close</a>
			</div>
			<input type="hidden" name="action" value="post_status" />
			<input type="hidden" name="image[data]" value="" />
			<input type="hidden" name="image[name]" value="" />
			<input type="hidden" name="image[type]" value="" />
			<input type="hidden" name="image[modified]" value="" />
		</form>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'parts/loop', 'home' ); ?>
		<?php endwhile; else : ?>
			<p><?php _e( 'Say something.' ); ?></p>
		<?php endif; ?>
		
	</div>
	<div id="status-bar">
		<a href="#">Say something.</a>
	</div>
	<?php wp_footer(); ?>
</body>
</html>