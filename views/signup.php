<div id="messages"></div>
<form ng-submit="submit()">
	<div>
		<label>Name</label>
		<input type="text" name="name" placeholder="Marcus Battle" ng-model="formData.name" />
		<span class="help-block"></span>
	</div>
	<div>
		<label>Mobile #</label>
		<input type="text" placeholder="(336) 555-5555" ng-model="formData.mobile" />
		<span class="help-block"></span>
	</div>
	<div>
		<button class="btn btn-default" type="submit">Knock</button>
		<input type="submit" value="submit" />
	</div>
	{{ formData }}
</form>