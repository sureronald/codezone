<?php
/**
 * Get the current server time in the below format
 * to be used by countdown timers
 */
$now = new DateTime(); 
echo $now->format("M j, Y H:i:s O")."\n"; 
?>