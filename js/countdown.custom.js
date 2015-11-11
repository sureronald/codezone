
function countDown(seconds){
		$('#timeout').countdown({ layout:'<span="">{mnn}:{snn} </span>',
    until:+seconds,onExpiry:function(){
    //Send timeout notification to server
    $.ajax({
	   type: "GET",
	   url: "ajphp/arenaValidator.php",
	   data: "a=arenaval&v=timedout",
	   //success:function(){alert('');}
    });
    //Notify user of expiry
    alert('Submission timeout!');
    $("#arena-applet-submission-form").slideUp("slow");
    $("#contactLink").slideDown("slow");
    } }); 
}

function matchRemainingTime(seconds){
	$('#match-rem-time').countdown({ layout:"<span >Remaining time: <span class='countdown-timer-text'>"+ 
    '{mn} {ml},{sn} {sl}</span></span>',
    until:+seconds, serverSync: serverTime});
}
