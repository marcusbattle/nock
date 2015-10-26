<div id="<?php echo get_the_ID(); ?>" class="status single">
	<div class="nav">
		<a href="<?php echo home_url(); ?>"><i class="fa fa-chevron-left"></i></a>
	</div>
	<div class="header">
		<span class="avatar"><?php echo get_avatar( get_the_author_id(), 75 ); ?> </span>
		<span class="author">
			<?php 
				if ( get_the_author_id() == get_current_user_id() ) { 
					echo _e( "me" );
				} else {
					echo the_author_meta( 'display_name' );
				} 
			?>
		</span> 
		<span class="time-published"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
		<span class="visibility private pull-right"><a href="#"><i class="fa fa-lock"></i><i class="fa fa-unlock"></i></a></span>
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
	<div id="comments" data-status-id="<?php echo get_the_ID(); ?>">
		<?php 
			$comments = get_comments( array( 'post_id' => get_the_ID(), 'order' => 'ASC' ) ); 

			if ( $comments ) {

				foreach ( $comments as $comment ) {
					$comment_author = 'author';
					echo "<div class=\"comment\"><span class=\"comment-author\">{$comment_author}</span>{$comment->comment_content}</div>";
				}

			}
		?>
		<div id="comment-entry">
			<textarea placeholder="Add a comment"></textarea>
		</div>
	</div>
</div>