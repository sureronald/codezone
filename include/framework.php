<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Application framework                       |
|                                                          |
*----------------------------------------------------------*
*/

//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );


require_once('modLoginForm.php'); //Render login form
require_once('frontpage.php'); //Render front page
require_once('register.php'); //Render registration form
require_once('activate.php'); //Activate user accounts
require_once('arenaApplet.php'); //Render the CodeZone arena
require_once('adminViewCpanel.php'); //Administration screen
require_once('practice.php'); //Load arena in practice mode
require_once('schedule.php'); //Show schedule
require_once('help.php'); //Show the help manual
require_once('halloffame.php'); //Load hall of fame
require_once('profile.php'); //Load user profile
require_once('faqs.php'); //Load FAQs
require_once('scoreboard.php'); //Show scoreboard
require_once('showCoders.php'); //Show all coders listing
require_once('registerUserForMatch.php'); //Register a user for an upcoming active match
require_once('showLicense.php'); //Load the GNU/GPL license
require_once('showStory.php');

?>
