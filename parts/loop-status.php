<div class="status">
	<div class="header">
		<span class="avatar"></span>
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
		<a href="<?php echo home_url(); ?>">Back</a>
	</div>
</div>