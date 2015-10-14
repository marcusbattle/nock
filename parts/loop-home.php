<div class="status">
	<div class="header">
		<span class="avatar"></span>
		<span class="author">
			<?php 
				if ( get_the_author_id() == get_current_user_id() ) { 
					echo _e( "Me" );
				} else {
					echo the_author_meta( 'display_name' );
				} 
			?>
		</span> 
		<span class="time-published"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
	</div>
	<?php echo the_content(); ?>
</div>