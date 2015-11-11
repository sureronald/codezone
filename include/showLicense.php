<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Show the GNU/GPL license in docs/ folder    |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');


if(@$action=='showlicense'):
?>
<div>
			<div class="font_size_10"><p>The CodeZone license</p></div>
<iframe src="docs/gpl.html" frameborder="1" scrolling="auto" height="250" width="100%" align="left" />
</div>
<?php endif; ?>
