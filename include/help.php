<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone rules/guidelines                        |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if($action=='help'):

?>
<h3 class='arena-match-title'>CodeZone help manual</h3>
<hr class='h3-bottom-line' />
<div id="help">
<h4>Introduction</h4>
<p>Welcome to the CodeZone match arena. If this is your first time here, then you are on the right page. Take time and go through all the text on this page and then you will be ready to participate and become a &quot;red&quot;. You will get to know shortly who a &quot;red&quot; is.</p>
<p>To participate in the arena is simple. First let's make sure you have the following set up on your computer.</p>
<ul>
<li>Browser (preferably firefox,epiphany or chrome)</li>
<li>Text editor or IDE (Intergrated Development Environment)</li>
<li>Compilers (e.g bloodshed devC++ for C and C++) or interpreters (e.g. python, php) depending on your platform</li>
</ul>
<p>If your computer meets all the above requirements, then you are ready to roll. The best place to start for beginners is usually the <a href="index.php?a=practice">CodeZone practice room</a> where you will find old problems to practice with. Even experienced CodeZone members still do hang out in the practice rooms to sharpen their coding skills</p>
<h4>Practice rooms and live contests</h4>
<p>In CodeZone practice rooms or during a live algorithm contest, you will find a problem statement requiring you to implement a solution and then submit your source code along with your output file. For practice rooms you are not required to submit your source code. The intelligent CodeZone online judge will then give you a reply immediately on whether your solution was correct or not.<br />During live contests, there are time limits which means that you must submit your solution before it elapses. In live contests the number of submissions is always limited to a given number which will be visible in a contest's details before it starts and during the contest. As a rule of thumb, &quot;Use your submissions wisely!&quot; even the most experienced CodeZone coders will attest to this. Live contests are timed meaning you have limited time within which to submit your solution unlike in the practice room where there are no time limits. Check at the end of this page for more detailed rules.<p>
<h4>Coder colors</h4>
<p>On CodeZone, each coder (member) is identified by a color depicting their level of prowess in implementing algorithms. This is done automatically by CodeZone which gives members ranking based on their performances during <strong>live contests which are ranked</strong> (contests may affect or have no effect on a coder's ranking). Note that administrators are identified by the color orange. This may only appear when an admin posts an article on the frontpage. Below is an analysis of the colours and their respective ranking points.</p>
<table border="0" cellpadding="1" cellspacing="3">
<tr>
<th>#</th><th>Color</th><th>Points range</th>
</tr>
<tr>
<td>1</td><td><span class="coder_colors" style="background-color: red;">Red</span></td><td>2501 +</td>
</tr>
<tr>
<td>2</td><td><span class="coder_colors" style="background-color:  #FFCD04;">Yellow</span></td><td>2001 - 2500</td>
</tr>
<tr>
<td>3</td><td><span class="coder_colors" style="background-color:  blue;">Blue</span></td><td>1501 - 2000</td>
</tr>
<tr>
<td>4</td><td><span class="coder_colors" style="background-color: green;">Green</span></td><td>1001 - 1500</td>
</tr>
<tr>
<td>5</td><td><span class="coder_colors" style="background-color:  gray;">Gray</span></td><td>501 - 1000</td>
</tr>
<tr>
<td>6</td><td><span class="coder_colors" style="background-color:  #000;">Black</span></td><td>0 - 500</td>
</tr>
<tr>
<td></td><td><span class="coder_colors" style="background-color:  orange;">Orange</span></td><td>Administrators (Admins do not participate in matches!)</td>
</tr>
</table>
<p>The ball is in your court now, fight it out for the top 10 positions and book your place on the frontpage!</p>
<h4>Scoring</h4>
<p>Scoring points during a match strongly depends on the time taken by a coder to provide a correct solution for a given problem and also on the difficulty level set by the administrators. It is also dependent on the number of incorrect submissions made prior to making the correct submission. What you score for a given match boils down to these three things</p>
<ol>
<li>Difficulty</li>
<li>Time taken</li>
<li>Submissions penalty</li>
</ol>
<p><strong>1. Difficulty: </strong>This is a percentage (1-99) allocated to a match by administrators to influence the scoring of points. A match with a difficulty of 30, duration 2 hours and 300 points will have the following outcomes: </p>
<ul>
<li>If a coder provides a solution in less than 30% (difficulty) of the match duration that is in less than 36 minutes, then he/she will score the total match points</li>
<li>Any correct solution provided there after will then not score the full match points</li>
</ul>
<p><strong>2. Time taken: </strong>If the time taken is greater than 30% (difficulty) of the match duration, then the time elapsed past the difficulty stage is used to calculate the points scored therefore making the score less than the maximum possible match points which in this case is 300</p>
<p><strong>3. Submissions Penalty: </strong>The number of incorrect submissions made reduces the points scored if a correct submission is made later on. An incorrect submission calls for a penalty of 1% of the maximum match points. Using our dummy match data above, this will mean a 3 points penalty for each incorrect submission</p>
<h4>Contest rules</h4>
<p>As always in any game, rules must be there; so here you go:</p>
<ul>
<li>Any programming language is welcome as long as we can also find it's compiler or interpreter</li>
<li>We, just as any other coder should be able to compile and run your source code and get the output you submitted</li>
<li>You are only allowed to submit one source file whose size should not exceed 100KB</li>
<li>If you used a code generator, submit the generator itself!</li>
<li>If code submitted by two or more users is alike, then all the users will be disqualified</li>
<li>At the end of the contest all participants have the permission to view the source code of fellow coders</li>
<li><i>Administrators have the authority to disqualify coders who have broken rules</i></li>
</ul>
<p>If you have taken your time and read all text on this page then I can only wish you the best of luck and keep in mind these simple rules outlined above. Please do not come running to me when you have been disqualified for failing to adhere to these rules.</p>
<h4>Extras</h4>
<p>If you have any queries then please forward them to <i>eucossa@egerton.ac.ke</i> or you can as well contact me directly at <i>sureronald@gmail.com</i></p>
</div>
<?php
	endif;
?>
