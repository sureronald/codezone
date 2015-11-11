<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone shedule                                 |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if($action=='schedule'):
	echo "<h3 class='arena-match-title'>CodeZone match schedule</h3>
	<hr class='h3-bottom-line' />";
	echo "<div id='schedule'>";
	$query="SELECT * FROM ".$_pre."matches WHERE start_time>".time()." ORDER BY start_time";
	$db->setQuery($query);
	if($db->foundRows==0)
		echo "<p>There are no upcoming contests, Please check back soon! You can always practice  <a href='index.php?a=practice'>here</a> with old CodeZone contests</p>";
	else
	{
		?>
		<table border="0" cellspacing="0" cellpadding="3">
		<tr class='theader'>
		<td>M::No</td><td>Match Title</td><td>Difficulty /  100</td><td>Affects ranking?</td><td>Date</td><td>Duration (hrs)</td><td>Points</td>
		</tr>
		<?php
		while($row=$db->fetch_assoc())
		{
			$duration=create_time($row['duration']);
			$duration=$duration['hrs'].':'.$duration['min'].':'.$duration['sec'];
			echo "<tr class='tr_data_large'><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['difficulty']}</td><td>".(($row['match_ranked'])?"yes":"no")."</td><td>".date( "j \of\f F Y, \a\\t g:i:s a",$row['start_time'])."</td><td>$duration</td><td>{$row['match_points']}</td></tr>";
		}
		echo "</table>";
	}
	echo "</div>";
	endif;

?>