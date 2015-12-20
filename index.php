<!DOCTYPE html>
<html ng-app="nock">
<head>
	<base href="<?php echo site_url( '/', 'relative' ); ?>">
	<title>A New Private Social Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php wp_head(); ?>
</head>
<body>
	<div ng-view></div>
	<?php wp_footer(); ?>
</body>
</html>