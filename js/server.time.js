function serverTime() { 
    var time = null; 
    $.ajax({url: 'ajphp/serverTime.php', 
        async: false, dataType: 'text', 
        success: function(text) { 
            time = new Date(Date.parse(text)); 
        }, error: function(http, message, exc) {
		alert('Unable to reach server!');
            time = new Date(); 
    }}); 
    return time; 
}  