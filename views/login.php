<div id="login-screen" class="screen">
	<div id="login-form">
		<form ng-controller="Login" ng-submit="submit()">
			<div>
				<label>Username</label>
				<input type="text" placeholder="(336) 555-5555" name="mobile" ng-model="formData.username" class="field" />
			</div>
			<div>
				<label>Password</label>
				<input type="password" placeholder="*******" name="password" ng-model="formData.password" class="field" />
			</div>
			<div>
				<button class="btn btn-default" type="submit">Knock</button>
			</div>
		</form>
	</div>
</div>