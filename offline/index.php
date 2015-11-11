<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone offline mode - Includes a login form    |
|                                                          |
*----------------------------------------------------------*
*/
session_start();
define( 'IN_APP',1 );
define('DS',DIRECTORY_SEPARATOR);
//To stop people accessing this page unecessarily we need to make sure CodeZone is really offline
require_once('..'.DS.'configuration.php');
$ecjConfig=new ecjConfig;
$offline=$ecjConfig->offline;
$offline_message=$ecjConfig->offline_message;
unset($ecjConfig);

require_once('..'.DS.'include'.DS.'utilityFunctions.php');

if($offline==0) #CodeZone is not offline! Get out of here!!
	header('Location: ..');
?>
<html>
<head><TITLE>CodeZone - The match arena .::. CodeZone is OFFLINE</TITLE>
<link rel="stylesheet" type="text/css" href="../theme/themes.css" />
<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jquery.jgrowl.css" />
<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jgrowl.custom.css" />
<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.jgrowl.min.js"></script>
<script type="text/javascript" src="../js/basicFunctions.js"></script>
<script type="text/javascript" src="../js/offLineLoginHandler.js"></script>
</head>
<body>
<center>
<noscript>If you can see this, then you have javascript disabled. For CodeZone to work properly javascript must be enabled!</noscript>
<div id="offline-container">
<form name="modlogin" class="modlogin" action="index.php?a=login&amp;hash=<?php echo base64_encode(time()); ?>" method="POST">
<h1 class='dark'>CodeZone is Offline</h1>
<p><img src="../theme/images/code-zone-logo.png" title="CodeZone" /></p>

<div class="offline_message">
<?php echo $offline_message; ?>
</div>
<br />
<p>
<input type="text" id="off_handle" name="handle" onfocus="if(this.value=='Reg No:')this.value=''" onblur="if(this.value=='')this.value='Reg No:';" value="Reg No:" />
<input type="password" id="off_password" name="password" onfocus="if(this.value=='@@@@@@@@@')this.value=''" onblur="if(this.value=='')this.value='@@@@@@@@@';" value="@@@@@@@@@" />
<input type="button" id="off_login" name="submit" value="Login" onClick="loginRequest()" />
</p>
<input type="hidden" name="lkey" value="<?php echo base64_encode(time()); ?>" />
<input type="hidden" name="lurl" value="<?php echo md5($_SERVER['REQUEST_URI']); ?>" />
</form>
<br />
<p>Copyright &copy; 2009, 2010  <a class=""  href="http://sureronald.blogspot.com" target="_blank" title="sureronald - R Labs">sureronald</a></p>
</div>
</center>
</body>
</html>