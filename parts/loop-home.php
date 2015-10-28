<div id="<?php echo get_the_ID(); ?>" class="status">
	<div class="header">
		<span class="avatar"><?php echo get_avatar( get_the_author_id(), 40 ); ?> </span>
		<span class="author">
			<?php 
				if ( get_the_author_id() == get_current_user_id() ) { 
					echo _e( "me" );
				} else {
					echo the_author_meta( 'display_name' );
				} 
			?>
		</span> 
		<span class="time-published"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span> in <span class="group">Group</span>
		<span class="visibility private pull-right"><a href="#<?php echo get_the_ID(); ?>"><i class="fa fa-lock"></i><i class="fa fa-unlock"></i></a></span>
	</div>
	<?php $images = get_attached_media( 'image' ); ?>
	<div class="media" <?php echo ( $images ) ? '' : 'style="display: none;"'; ?>>
		<?php

			foreach ( $images as $image ) {
				$image_url = wp_get_attachment_url( $image->ID );
				echo "<div class=\"image\"><img src=\"{$image_url}\" /></div>";
			}
		?>
	</div>
	<div class="content"><?php echo the_content(); ?></div>
	<div class="actions">
		<div class="btn-group" role="group" aria-label="Justified button group">
			<!-- <a class="btn btn-default btn-sm" href="#">Tag Fram</a> -->
			<a class="btn btn-default" href="<?php the_permalink(); ?>#comments">Comments (<?php echo get_comments_number() ?>)</a>
			<a class="btn btn-default">Tagged (0)</a>
	    </div>
	    <a class="topic new" href="#<?php echo get_the_ID(); ?>">#Topic</a>
	</div>
</div>