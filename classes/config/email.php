<?php 
	$emailConfig = array(
		'recipients' => array(
			array('email' => getenv('RECIPIENT_EMAIL'), 'name' => getenv('RECIPIENT_NAME'))
		),
		// Default subject for emails - would be use if mail subject is not provided by user
		'subject' => getenv('MAIL_SUBJECT')
	);
?>
