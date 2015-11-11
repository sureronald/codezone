<?php

	/**
	** This configuration file is automatically modifed by CodeZone. Please follow 
	** EDITING INSTRUCTIONS in the CodeZone manual if you have to do it manually
	** Last modified on : 7 of October 2010, at 7:28:26 pm
	**/

	class ecjConfig{

		//Application settings
		var $offline=0;
		var $allow_user_reg=1;
		var $offline_message='CodeZone is offline for routine maintenance. Please check back soon. May the `GoOgLe` be with you!';
		var $notify_mail='root@pluto.site';
		var $meta_desc='CodeZone: The match arena | for algorithm match competitions | sureronald | osure ronald osure';
		var $max_submissions=4;
		var $submission_timeout=180;
		
		//Session settings
		var $session_lifetime=1800;//seconds
		
		//Database settings
		var $db_host='localhost';
		var $table_prefix='cz_';
		var $db_user='root';
		var $db_pass='';
		var $db_name='cz';
		
		//Mail settings
		var $mail_protocol='php_mail';
		var $mail_from='ecm@codezone.com';
		var $smtp_username='';
		var $smtp_pass='';
		var $smtp_host='';
		var $smtp_port=25;
		
		//Author details
		var $author_url='http://sureronald.blogspot.com';
		

	}
		
?>
