//Custom javascript functions to aid in arena file downloads

function startDownload(url,m_id,mf_name,is_name,d_count)
{
	var iframe_url=url+"?a=arenaval&v=is_download&m_id="+m_id+"&mf_name="+mf_name+"&is_name="+is_name+"&d_count="+d_count;
	//alert(iframe_url); return;
	$('#if_download').attr({'src':iframe_url}); //Set hidden iframe src attribute
}

//This function has been deprecated??????????????????????
function reDownload(fileName){
	window.location.href="../competition_uploads/"+fileName+"";
}

function sourceDownload(path1,path2){
	if(confirm('Disclaimer: The running or compiling of the file you are about to download...'))
	{
		if(path2=='')
		{
			alert('Source file not submitted');
			return;
		}
		window.location.href="competition_uploads/"+path1+"/"+path2;
	}
}