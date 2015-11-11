
<!-- saved from url=(0066)http://plugins.jquery.com/files/jquery.countdownTimer.1-0-1.js.txt -->
<HTML><BODY><PRE style="word-wrap: break-word; white-space: pre-wrap;">/*
 * jCountdown
 * Creates a countdown timer from a jQuery object. Allows you to format
 * the way the time is displayed.
 *
 * $(&lt;selector&gt;).countdown({params});
 *
 * If you want to replace the 
 *
 */
jQuery.fn.countdown = function(params) {
    var self = this;

    //Properties
    //----------------------------------------------            
    //set the time and day to work with
    self.display = $(this);    
    self.target = new Date(params.date);        
    self.message = params.message?params.message:"It's Here!!";
    self.addZeros = params.addZeros?params.addZeros:false;
            
    //Events
    //----------------------------------------------
    self.onTick = params.onTick?params.onTick:function() { return true; };
    self.onFinish = params.onFinish?params.onFinish:function() { return true; };

    //Methods
    //----------------------------------------------   
    //Updates the text for the countdown timer
    self._tick = function() {

        //get the time difference
        var now = (self.target - new Date());
        
        //make sure success hasn't been reached
        if (now.valueOf() &lt; 0) {

            //clear the interval and run the event
            window.clearInterval(self._interval);
            if (!self.onFinish(self.display)) { return; }
            
            //display the finish message
            self.display.html(self.message);
            return;
            
        };
        
        //update the values
        var seconds = now.valueOf()/1000;        
        var day = (Math.floor(seconds/86400))%86400;
        var hrs = (Math.floor(seconds/3600))%24;
        var min = (Math.floor(seconds/60))%60;
        var sec = (Math.floor(seconds/1))%60;
                
        //run the event if needed
        if (!self.onTick(self.display,day,hrs,min,sec)) { return; }; 
        
        //check for zeros
        if (self.addZeros) {
            hrs = (hrs+"").length&lt;2?"0"+hrs:hrs;
            min = (min+"").length&lt;2?"0"+min:min;
            sec = (sec+"").length&lt;2?"0"+sec:sec;
        };
                
        //display the new time
        self.display.html(
            day+"&lt;span&gt;days&lt;/span&gt;"+
            hrs+"&lt;span&gt;hrs&lt;/span&gt;"+
            min+"&lt;span&gt;min&lt;/span&gt;"+
            sec+"&lt;span&gt;sec&lt;/span&gt;"
            );
    };
    
    
    //Setup Routine
    //----------------------------------------------
    self._interval = window.setInterval(
        self._tick,
        params.interval?params.interval:1000
        );
        
    //run immediately by default
    if (!params.delayStart) { self.update(); };

    //return itself
    return this;

};</PRE></BODY></HTML>