<form id="new-status" enctype="multipart/form-data">
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