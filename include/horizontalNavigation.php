<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Horizontal Navigation                       |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

$active_link=((isset($_GET['a']))?$_GET['a']:"");
$style="style=\"background: transparent url(theme/images/menu-bg-a.png) no-repeat;\"";
?>
		

<ul class="horizontal-nav-bar">
	<li  <?php if($active_link=="") echo $style; ?>><a href="index.php">Home</a></li>
	<li <?php if($active_link=="practice") echo $style; ?>><a href="index.php?a=practice">Practice</a></li>
	<li <?php if($active_link=="schedule") echo $style; ?>><a href="index.php?a=schedule">Schedule</a></li>
	<li <?php if($active_link=="halloffame") echo $style; ?>><a href="index.php?a=halloffame">Hall of Fame</a></li>
	<li <?php if($active_link=="faqs") echo $style; ?>><a href="index.php?a=faqs">FAQs</a></li>
	<li <?php if($active_link=="help") echo $style; ?>><a href="index.php?a=help">Help</a></li>
</ul>

