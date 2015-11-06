<div class="status cell" ng-repeat="status in statuses">
	<div class="header">
		<span class="avatar"></span>
		<span class="author">{{status.author}}</span> 
		<span class="time-published">{{status.published}}</span>
		<span class="visibility private pull-right"><a href="#"><i class="fa fa-lock"></i><i class="fa fa-unlock"></i></a></span>
		<span class="group">Group{{status.group}}</span>
	</div>
	<div class="content">{{status.post_content}}</div>
	<div class="actions">
		<div class="btn-group" role="group" aria-label="Justified button group">
			<a class="btn btn-default" href="{{status.guid}}#comments">Comments ({{status.comment_count}})</a>
	    </div>
	</div>
</div>