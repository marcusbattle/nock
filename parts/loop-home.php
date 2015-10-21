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
		<div class="btn-group" role="group" aria-label="Justified button group">
      		<div class="btn-group" role="group">
		        <button type="button" class="btn btn-default btn-sm">Tag Fram</button>
			</div>
			<div class="btn-group" role="group">
		        <button type="button" class="btn btn-default btn-sm">Comments</button>
			</div>
	    </div>
	</div>
</div>