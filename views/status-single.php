<div class="status single">
	<div class="nav">
		<a href="#{{status.ID}}"><i class="fa fa-chevron-left"></i></a>
	</div>
	<div class="header">
		<span class="avatar"></span>
		<span class="author">{{status.author}}</span> 
		<span class="time-published">{{status.published}}</span>
		<span class="visibility private pull-right"><a href="#"><i class="fa fa-lock"></i><i class="fa fa-unlock"></i></a></span>
		<span class="group">{{status.group}}</span>
	</div>
	<div class="content">{{status.post_content}}</div>
	<div id="comments">
		<div id="comment-entry">
			<textarea placeholder="Add a comment"></textarea>
		</div>
	</div>
</div>