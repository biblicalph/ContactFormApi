<?php 
	$mandrillConfig = array(
		'apiKey' => getenv('MANDRILL_API_KEY'),
		// Email and password are not used by the app
		'email' => getenv('RECIPIENT_EMAIL'),
		'password' => getenv('RECIPIENT_PASSWORD')
	);
?>
