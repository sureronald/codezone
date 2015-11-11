<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Render the login form                       |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if(@$action=='loginform' && !$login){
?>
<link rel="stylesheet" href="theme/jquery.jqtransform/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="js/jquery.jqtransform.js" ></script>
<script language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'theme/jquery.jqtransform/img/'});
		});
	</script>

<form name="modlogin" class="modlogin" action="index.php?a=login&amp;hash=<?php echo base64_encode(time()); ?>" method="POST">
<ul class='login-form'>
<li><span class='dark1'>New to CodeZone?</span><br /><a href="index.php?a=register">Register now.</a><span class='size10'> After you complete the registration process, we will send your account activation code via email.</span></li>
<li><span class="dark1">Forgot your password?</span><br /><a href="index.php?a=register&amp;do=rpass">Click here</a></li>
<li><div class="rowElem"><input type="text" name="handle" onfocus="if(this.value=='Reg No:')this.value=''" onblur="if(this.value=='')this.value='Reg No:';" value="Reg No:" /></div></li>
<li><div class="rowElem"><input type="password" name="password" onfocus="if(this.value=='@@@@@@@@@')this.value=''" onblur="if(this.value=='')this.value='@@@@@@@@@';" value="@@@@@@@@@" /></div></li>
<li><div class="rowElem"><input type="submit" name="submit" value="Login" /></div></li>
</ul>
<input type="hidden" name="lkey" value="<?php echo base64_encode(time()); ?>" />
<input type="hidden" name="lurl" value="<?php echo md5($_SERVER['REQUEST_URI']); ?>" />
</form>
<?php
}
?>