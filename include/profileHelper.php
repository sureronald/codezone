<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Profile helper class                        |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');


class profileHelper
{
	/** User logged in variable */
	var $logged_in=false;
	
	/** Stores user's nick_name to load his/her profile */
	var $handle='';
	
	
	/** profileHelper constructor */
	
	function __construct($handle)
	{
		global $login;
		$this->logged_in=$login;
		$this->handle=$handle;
		
	}
	
	/**
	 * Load a members profile for viewing
	 */

	function view_profile()
	{
		global $db,$_pre;
		
		//Profile is for superuser?
		$superuser=false;
		//Build query.
		$query="SELECT {$_pre}users.*,{$_pre}profile.* FROM {$_pre}users,{$_pre}profile WHERE {$_pre}users.nick_name='{$this->handle}' AND {$_pre}users.registration_no={$_pre}profile.registration_no";
		
		$db->setQuery($query);
		if($db->foundRows==0) //Have we found something?
			return;
		$ud=$db->fetch_assoc();
		
		//If superuser, set $superuser to true
		if($ud['user_type']=='su')
			$superuser=true;
		
		?>
		<h3 class='arena-match-title'>CodeZone member .::. <?php echo $ud['nick_name']; ?>'s profile</h3>
		<hr class='h3-bottom-line' />
		<div id="view_profile">
		<ul>
		<li class='label'>avatar</li>
		<li class='no_style'><img class='user_avatar' src="images/avatars/<?php echo (strlen($ud['avatar_path']))?$ud['avatar_path']:'default.gif'; ?>" alt="<?php echo $ud['nick_name']; ?>"/></li>
		<li class='label'>full name</li>
		<li class='value'><?php echo "<span class='".(($superuser)?get_user_class(0,true):get_user_class($ud['ranking_pts']))."'>{$ud['full_names']}</span>"; ?></li>
		<?php
		if(!$superuser)
		echo "<li class='label'>cumulative ranking points</li>
		<li class='value'><span class='".get_user_class($ud['ranking_pts'])."'>{$ud['ranking_pts']}</span> </li>";
		
		if($this->logged_in)
		echo "<li class='label'>registration number</li>
		<li class='value'>{$ud['registration_no']}</li>";
		//if(!$superuser)
		echo "<li class='label'>favourite language</li>
		<li class='value1'>".((strlen($ud['language']))?$ud['language']:'None selected')." </li>";
		$jd=explode(' ',$ud['register_date']); //Explode so as to get the date part
		echo "<li class='label'>join date</li>
		<li class='value'>$jd[0]</li>";
		
		?>
		<li class='label'>Quote</li>
		<li class='value2'>&quot;<?php echo stripslashes($ud['quote']); ?>&quot;</li>
		<?php
		if($this->logged_in):
		echo "<li class='label'>Me in a nutshell</li>
		<li class='no_style'><div class='bg_grey'>".stripslashes($ud['about_me'])."</div></li>";
		
		if(!$superuser)
		{
			$query="SELECT * FROM {$_pre}user_match_log WHERE registration_no='{$ud['registration_no']}' AND participated=1 ORDER by match_date DESC";
			$db->setQuery($query);
			
			echo "<li class='label'>Matches participated in</li><li class='no_style'><div class='bg_grey'>";
			echo "<ul class='profile_matches'>";
			
			while($row=$db->fetch_assoc())
			{
				echo "<li><span class='size10'><strong>{$row['title']}</strong> -> ".time_stamp_to_date($row['match_date'])."</span></li>";
			}
			echo "</ul>";
			echo "</div></li>";
		}
		endif;
		?>
		
		
		<?php
		if($this->logged_in && $ud['registration_no']==$_SESSION['user_row_data']['registration_no'])
		echo "<li class='no_style'><a class='update_profile_link' href='index.php?a=profile&amp;do=updateProfile'>Update profile</a></li>";
		if(!$this->logged_in)echo "<p class='notify'>Some information is hidden, Login to view</p>";
		
		?>
		</ul>
		<?php
		if(!$superuser):
		?>
		<iframe id="coder_history" src="ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&amp;do=show_coder_history&amp;reg_no=<?php echo $ud['registration_no']; ?>" scrolling="auto"></iframe>
		<?php endif; ?>
		</div>
		<?php
		
	}
	
	/**
	 * Show fields to allow for updating profile
	 */
	
	function update_profile_fields()
	{
		global $db,$_pre;
		$ud=$_SESSION['user_row_data'];
		?>
		<link rel="stylesheet" href="theme/jquery.jqtransform/jqtransform.css" type="text/css" media="all" />
		<script type="text/javascript" src="js/jquery.form.js"></script>
		<script type="text/javascript" src="js/jquery.jqtransform.js" ></script>
		<script language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'theme/jquery.jqtransform/img/'});
			$('.update_profile').ajaxForm({
				dataType:'json',
				beforeSubmit:function(){$('#loading-bar').css({'display':'inline'});},
				success:notifyUser,
				error:function(e,w){alert('Error communicating with server');}
			});
		});
		
		//Code to send avatar delete request
		var ajax_load = "<img src='images/anim_load.gif' alt='loading...' />"; 
		var loadUrl = "ajphp/updateProfile.php";
		
		$(function(){
			$("#del_avatar").click(function(){
			$("#temp").html(ajax_load).load(loadUrl,'a=profile&do=delete_avatar', function(responseText){
				if(responseText=='done')
					$('img.user_avatar').attr({'src':'images/avatars/default.gif'});
		}); 
		});
		});
		//User notification
		function notifyUser(data){
			$('#loading-bar').css({'display':'none'});
			if(data.error){
				$.jGrowl(data.error,{header:'CodeZone says...',theme:'error',life:3000});
				return;
			}
			if(data.success){
				$.jGrowl(data.success,{header:'CodeZone says...',theme:'success',life:3000});
				//return;
			}
			//Reload all the visible user avatars
			if(data.path){
				$('img.user_avatar').attr({'src':data.path});
			}
		}
		</script>
		
		<h3 class='arena-match-title'>CodeZone update profile</h3>
		<hr class='h3-bottom-line' />
		<div id="update_profile">
		<img id='loading-bar' src='images/anim_load.gif' />
		<a class='update_profile_link' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name=<?php echo $ud['nick_name']; ?>'>View profile</a>
		<fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>Personal details</legend>
		<!--Update profile form -->
		
		<form name="personal_details" class="update_profile" action="ajphp/updateProfile.php?a=profile&amp;do=save_personal_details" method="POST">
		<ul>
		<li class='form_label'>full name</li>
		<li><div class="rowElem"><input type="text" name="full_names" value="<?php echo $ud['full_names']; ?>" /></div></li>
		<li class='form_label'>nick name</li>
		<li><div class="rowElem"><input type="text" name="nick_name" value="<?php echo $ud['nick_name']; ?>" /></div></li>
		<li class='form_label'>current password</li>
		<li><div class="rowElem"><input type="password" name="cur_password"  /></div></li>
		<li class='form_label'>new password</li>
		<li><div class="rowElem"><input type="password" name="new_password1"  /></div></li>
		<li class='form_label'>confirm password</li>
		<li><div class="rowElem"><input type="password" name="new_password2"  /></div></li>
		<li class='form_label'>email</li>
		<li><div class="rowElem"><input type="text" name="email" value="<?php echo $ud['email']; ?>" /></div></li>
		
		<li><div class="rowElem"><input type="submit" value="save changes" /></div></li>
		</ul>
		<input type="hidden" name="f" value="<?php echo base64_encode('save personal'); ?>" />
		</form>
		</fieldset>
		<fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>Change/Upload avatar</legend>
		<!--Update profile form -->
		<form name="change_avatar" class="update_profile" action="ajphp/updateProfile.php?a=profile&amp;do=save_avatar" method="POST" enctype="multipart/form-data">
		<img class="user_avatar" src="images/avatars/<?php echo (strlen($ud['avatar_path']))?$ud['avatar_path']:'default.gif'; ?>" alt="<?php echo $ud['nick_name']; ?>" />
		<ul>
		<li class='form_label'>Upload a new avatar. Large images will be resized, Max file size 1MB, Allowed extensions are png, jpg and gif</li>
		<li><input type="file" name="avatar" /></li>
		<li><div class="rowElem"><input type="submit" value="upload" /></div></li>
		<input type="hidden" name="f" value="<?php echo base64_encode('save avatar'); ?>" />
		</ul>
		</form>
		<h4><button id="del_avatar">delete avatar</button>&nbsp;<span id='temp'>&nbsp;</span></h4>
		</fieldset>
		<fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>Extras</legend>
		<!--Update profile extra info -->
		<form name="extras" class="update_profile" action="ajphp/updateProfile.php?a=profile&amp;do=save_extras" method="POST">
		<ul>
		<li class='form_label'>Favourite language</li>
		<li><div class='rowElem'>
		<select name='language'>
		<option <?php if($ud['language']=='C') echo "selected='selected'"; ?> value="C">C</option>
		<option <?php if($ud['language']=='C++') echo "selected='selected'"; ?> value="C++">C++</option>
		<option <?php if($ud['language']=='Java') echo "selected='selected'"; ?> value="Java">Java</option>
		<option <?php if($ud['language']=='Python') echo "selected='selected'"; ?> value="Python">Python</option>
		<option <?php if($ud['language']=='C#') echo "selected='selected'"; ?> value="C#">C#</option>
		<option <?php if($ud['language']=='Ruby') echo "selected='selected'"; ?> value="Ruby">Ruby</option>
		<option <?php if($ud['language']=='PHP') echo "selected='selected'"; ?> value="PHP">PHP</option>
		<option <?php if($ud['language']=='') echo "selected='selected'"; ?> value="Other">Other</option>
		</select>
		</div>
		</li>
		<li class='form_label'>quote (MAX 250 chars)</li>
		<li><div class='rowElem'><textarea name="quote"><?php echo stripslashes($ud['quote']); ?></textarea></div></li>
		<li class='form_label'>About me</li>
		<li><div class='rowElem'><textarea name="about_me"><?php echo stripslashes($ud['about_me']); ?></textarea></div></li>
		<li class='form_label'></li>
		<li><div class="rowElem"><input type="submit" value="save changes" /></div></li>
		</ul>
		<input type="hidden" name="f" value="<?php echo base64_encode('save extras'); ?>" />
		</form>
		</fieldset>
		</div>
		<?php
	}
	
}

?>
