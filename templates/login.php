<!DOCTYPE html>
<html ng-app="social">
<head>
	<title>A New Private Social Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://marcusbattle.com" />
	<meta property="og:title" content="A Private Social Network by Marcus Battle" />
	<meta property="og:description" content="When important messages need to reach your audience. Facebook is too public." />
	<?php wp_head(); ?>
</head>
<body>
	<div class="container" style="max-width: 600px; margin: 0 auto;">
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
	</div>
	<?php wp_footer(); ?>
</body>
</html>