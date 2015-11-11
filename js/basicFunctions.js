/*
 * Get and return the server time
 */
// function serverTime() { 
//     var time = null; 
//     $.ajax({url: 'http://myserver.com/serverTime.php', 
//         async: false, dataType: 'text', 
//         success: function(text) { 
//             time = new Date(Date.parse(text)); 
//         }, error: function(http, message, exc) { 
//             time = new Date(); 
//     }}); 
//     return time; 
// }

/*
 * Custom jGrowl error messages
 */

function jgrowl_messages(message,theming,stick){
	$.jGrowl(message,{
	header:'CodeZone says...',
	theme:theming,
	sticky:stick,
	life:3000/*,
 	animateOpen: { height :'slideDown'},
  	animateClose: { height :'slideUp'}*/
	});
}

/*
 * Tooltip for user avatars
 */

$(function() {
	$("#user_avatar").tooltip({
		track: true, 
		delay: 0, 
		showURL: false, 
		showBody: " - ",
		extraClass: "user_avatar_hover",
		fade: 250
});
});

/*
 * Resubmit form with appropriate values to aid in pagination
 */
 
function submitForm(url,limit)
{
 	url+='&page_limit='+limit;
	window.location.href=url;
}

/*
 * Submit form with filter options added to url
 */
 
function submitFilter(input_field_id,url)
{
	var filter_string=$('#'+input_field_id).val();
	if(filter_string.length==0)
		jgrowl_messages('This field is required','error',false);
	else
	{
		url+='&filter='+filter_string;
		window.location.href=url;
	}
	
}

/*
 * Submit filter reset query
 */
 
function submitFilterReset(url)
{
	window.location.href=url;
}

/*
 * Checks all checkboxes with a given prefix
 */
 
function checkAll(prefix,count,toggle)
{
	for(var inc=1;inc<=count;inc++)
	{
		var cbox=document.getElementById(prefix+inc);
		if(toggle)
		cbox.checked=true;
		else
			cbox.checked=false;
	}
}

/*
 *Load match edit form in admin panel -> edit match tab
 */

function loadMatchForm(match_id)
{
	if(match_id=='none')
		return;
	$('#edit_match_form').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_edit_match_form&m_id='+match_id);
}

/*
 * Load the edit form in the admin panel -> stories tab
 */

function editStory(story_id)
{
	//This function is called when the story title is clicked
	$('#edit_story_form').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_edit_story_form&s_id='+story_id);
}

/*
 * Load the new story form i nthe admin panel -> stories tab
 */

function newStory()
{
	//send request
	$('#edit_story_form').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_new_story_form');
}

/*
 * Load the edit form in the admin panel -> stories tab from a checkbox selection
 */

function editStoryCb(prefix,cb_count)
{
	var x=0;
	for(var inc=1;inc<=cb_count;inc++)
	{
		var cbox=document.getElementById(prefix+inc);
		if(cbox.checked)
		{
			var story_id=cbox.value;
			break;
		}
		x++;
	}
	if(x==cb_count) //meaning no checkbox was selected
	{
		jgrowl_messages('Please select a story to edit','error',false);
		return;
	}
	
	//send request
	$('#edit_story_form').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_edit_story_form&s_id='+story_id);
}

/*
 * Submit the stories form after altering the hidden input value to reflect on the chosen action
 */

function storyModify(action_on_selected_story)
{
	$('#story_modify_action').attr({'value': action_on_selected_story }); //Alter the value attribute of the hidden input field #story_modify_action
	//alert($('#story_modify_action').val()); debug
	$('#modify_news_story').submit();
}

// $(document).ready(
// function()
// {
// 	var last_move=0,diff=0,ts;
// 	$('#ecm_body').mousemove(function(event){
// 	ts = Math.round(new Date().getTime() / 1000);
// 	if(last_move>0)
// 	{
// 		diff=ts-last_move;
// 	}
// //alert(event.pageX+event.pageY);
// });
// });

/*
 * Mail tab toggle send to field
 */

$(document).ready(function(){
$('#mail_group').hide(); //Hide the mail to group option
$("input[name='toggle_send_to']").click(function(){
if($(this).val()=='to_group'){ $('#mail_group').show(); $('#mail_individual').hide();}
if($(this).val()=='to_individual'){ $('#mail_individual').show(); $('#mail_group').hide(); }
});

});

/*
 * Load coder select box for a given match
 */

function loadCoderSelect(match_id)
{
	if(match_id=='none')
		return;
	$('#vs_coder_select').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_coder_select&m_id='+match_id);
}

/*
 * Load coder source. The argument coder data contains registration number and submitted file name
 */

function loadCodersSource(coder_data,dir_name)
{
	tmp=coder_data.split(',');
	var reg_no=tmp[0];
	var files=tmp[1];
	
	$.ajax({url: 'ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_coder_source&dir='+dir_name+'&reg_no='+reg_no+'&files='+files,
	dataType: 'json',
	success: function(dataObj) { 
		if(dataObj.state!=true)
		{
			jgrowl_messages(dataObj.message,'error',false);
			return;
		}
		$('#vs_coder_name').html(dataObj.vs_coder_name);
		//Load source code via ajax using the url returned from the previous ajax request
		$.ajax({url:'ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_source_from_file&path='+dataObj.vs_source+'',success: function(user_code){$('#vs_code_view').val(user_code);}});
		$('#vs_code_tt').html(dataObj.vs_code_tt);
		$('#vs_submissions').html(dataObj.vs_submissions);
		$('#vs_language').html(dataObj.vs_language);
		$('#vs_disqualified').html(((dataObj.vs_disqualified==1)?'Yes':'No'));
		$('#vs_disq_user').html('<button class="admin-panel-button" onclick="disqUserToggle(\''+dataObj.reg_no+'\','+dataObj.vs_disqualified+',\''+dir_name+'\')">'+((dataObj.vs_disqualified==1)?'Qualify this user':'Disqualify this user')+'</button>');//Load disqualify user button
		$('#vs_downloads').html(dataObj.vs_downloads);
		$('#vs_correct').html(((dataObj.vs_correct==1)?'Yes':'No'));
		$('#vs_score').html(dataObj.vs_score);
  }, error: function(http, message, exc) {
	  alert('There was an error communicating to the server');
  }}); 
}

/*
 * Disqualifies / Qualifies a user
 */

function disqUserToggle(reg_no,disq_state,dir_name)
{
	//dir_name is also the match table name
	$.ajax({url:'ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=disq_user&reg_no='+reg_no+'&disq_state='+String(disq_state)+'&dir_name='+dir_name,error: function(http, message, exc) {
		alert('There was an error communicating to the server');
	}});
	
	var toggled_state=0;
	if(disq_state==1)
		toggled_state=0;
	else
		toggled_state=1;
	$('#vs_disqualified').html(((toggled_state==1)?'Yes':'No'));
	$('#vs_disq_user').html('<button class="admin-panel-button" onclick="disqUserToggle(\''+reg_no+'\','+toggled_state+',\''+dir_name+'\')">'+((disq_state==1)?'Disqualify this user':'Qualify this user')+'</button>');
}

/*
 * Show problem statement in admin panel edit match
 */

function showProblemToggle(dir_name,problem_sheet)
{
	if($('#show_problem_st').val()=='hidden')
	{
		//Show the problem sheet and set #show_problem_st value attribute to showing
		$('#show_problem_st').attr({'value':'showing'});
		$('#problem_statement_view').load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=show_problem_sheet&dir_name='+dir_name+'&problem_sheet='+problem_sheet);
		
	}
	else
	{
		//Hide it and set #show_problem_st value attribute to hidden
		$('#show_problem_st').attr({'value':'hidden'});
		//$('#problem_statement_view').fadeTo('slow',0.3).slideUp('slow');
		$('#problem_statement_view').html('&nbsp;');
	}
}

/*
 * Start download of input/answer sheet in admin panel edit match
 */

function downloadSheet(dir_name,sheet,sheet_type)
{
	var iframe_url='ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=download_sheet&dir_name='+dir_name+'&sheet='+sheet+'&type='+sheet_type;
	
	$('#sheet_download').attr({'src':iframe_url});
}
