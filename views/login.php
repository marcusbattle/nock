<div id="login-form" ng-controller="Login">
	<form name="loginForm" ng-submit="submit()" novalidate>
		<div>
			<label>Username</label>
			<input type="text" name="username" ng-model="data.username" autocomplete="off" autocorrect="off" required />
		</div>
		<div>
			<label>Password</label>
			<input type="password" placeholder="*******" ng-model="data.password" autocomplete="off" autocorrect="off"  required />
		</div>
		<div>
			<button type="submit">Knock</button>
		</div>
	</form>
</div>
