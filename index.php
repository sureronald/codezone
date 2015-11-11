<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Application main driver                     |
|                                                          |
*----------------------------------------------------------*
*/

define( 'IN_APP',1 );
define( 'APP_BASE_DIR',dirname(__FILE__) );
define( 'DS',DIRECTORY_SEPARATOR );
$action='frontpage';// Initialize the action variable to default
$login=false; //If set will load registered member modules
$su=false; //Superuser: If set, will load superuser modules
$expire=false; //Triggers system error message for an expired session when true
$request_expired=false; //Triggers the system error message for an expired form ie sent too late
$request_toofast=false; //Triggers error message for form sent too fast
$reg_pass_no=false; //Triggers system error message for non-matching reg no + password
$acc_active=false; //Triggers system error message for an account not activated
$acc_disabled=false; //Triggers system error message for a disabled account
$login_notify=false; //Triggers welcome message
$logout_notify=false; //Triggers logout message

require_once( APP_BASE_DIR.DS.'configuration.php' );
require_once( APP_BASE_DIR.DS.'include'.DS.'defines.php' );
require_once( APP_BASE_DIR.DS.'include'.DS.'utilityFunctions.php'); //Helper functions;
require_once( APP_BASE_DIR.DS.'include'.DS.'cleanPostAndGet.php'); //Clean $_POST and $_GET of malicious queries

//Session Management
$session->begin();

require_once( APP_BASE_DIR.DS.'include'.DS.'checkEcmStatus.php' ); /** Is application off/on line? */

//Initialize application
require_once( APP_BASE_DIR.DS.'include'.DS.'getRequest.php' );

//Login helper
require_once( APP_BASE_DIR.DS.'include'.DS.'login.php' );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="index, follow" />
<meta name="description" content="<?php echo $_meta_desc; ?>" />

<title>The CodeZone match arena</title>
<link rel="shortcut icon" href="theme/images/favicon.ico"> 
<link type="text/css" rel="stylesheet" href="theme/themes.css" />
<link type="text/css" rel="stylesheet" href="theme/system.css" />
<link type="text/css" rel="stylesheet" href="theme/jquery.tooltip/jquery.tooltip.css" />
<link type="text/css" rel="stylesheet" href="theme/jquery.tooltip/tooltip.custom.css" />
<link type="text/css" media="screen" rel="stylesheet" href="theme/jquery.jgrowl/jquery.jgrowl.css" />
<link type="text/css" media="screen" rel="stylesheet" href="theme/jquery.jgrowl/jgrowl.custom.css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

<script type="text/javascript" src="js/jquery.easing.js"></script>
<script type="text/javascript" src="js/jquery.accordion.js"></script>
<script type="text/javascript" src="js/jquery.accordion.custom.js"></script>
<script type="text/javascript" src="js/server.time.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.min.js"></script>
<script type="text/javascript" src="js/horizontal.navigation.js"></script>
<script type="text/javascript" src="js/jquery.tooltip.min.js" ></script>
<script type="text/javascript" src="js/basicFunctions.js"></script>
</head>
<body id="ecm_body">
<div id="all">
	<noscript>If you can see this, then you have javascript disabled. For CodeZone to work properly javascript must be enabled!</noscript>
	<div id="header">
		<div>
			<table class="header-area" width="100%">
			<tr>
				<td width="86%" valign="top"><h3 id="logo">&nbsp;</h3></td>
				<td width="14%" valign="top" style="text-align:right;"><?php require_once( APP_BASE_DIR.DS.'include'.DS.'modLogin.php' ); ?></td>
			</tr>
			</table>
		</div>
	</div>
	<div id="nav-bar-container">
	<?php require_once('include'.DS.'horizontalNavigation.php'); ?>
	</div>
	
	<div id="wrapper">
		<div id="clear"></div>
		<div id="left">
			<?php require_once( APP_BASE_DIR.DS.'include'.DS.'mainmenu.php' ); ?>
		</div>
		<div id="component">
			<?php
			require_once( APP_BASE_DIR.DS.'include'.DS.'systemMessages.php' );
			require_once( APP_BASE_DIR.DS.'include'.DS.'framework.php' );
			?>
		</div>
	</div>
</div>
<div id="footer">
	<a href="http://code.google.com/p/codezone/" target="_blank" title="CodeZone project Home @ Google Projects">Project Home</a> :: Designed by <a class=""  href="http://sureronald.blogspot.com" target="_blank" title="sureronald - R Labs">sureronald</a> :: &copy; 2009, 2010
</div>
</body>
</html>
