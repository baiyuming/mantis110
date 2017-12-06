<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: view.php,v 1.3 2005/02/12 20:01:08 jlatour Exp $
	# --------------------------------------------------------
?>
<?php
	require_once( 'core.php' );

	$t_core_path = config_get( 'core_path' );

	require_once( $t_core_path.'string_api.php' );
?>
<?php
	// Copy 'id' parameter into 'bug_id' so it is found by the simple/advanced view page.
	$_GET['bug_id'] = gpc_get_int( 'id' );

	include string_get_bug_view_page();
?>
<script type="text/javascript">
	function setValue(){
		var  teamArr = document.getElementById("handler_id");
		   for(var i=0;i<teamArr.options.length;i++){
		 	   if(teamArr[i].selected){
		 		  document.getElementById("assigntext").value = teamArr[i].text;
				 }
		   } 
	}

	function changetText(){
  		var uname = document.getElementById("assigntext").value; 
		var  teamArr = document.getElementById("handler_id");

		var oname = new Object();
		
	   for(var i=0;i<teamArr.options.length;i++){
		   oname[teamArr[i].text] = teamArr[i].value;
		   var pinyin = teamArr[i].dataset.pinyin;
           oname[teamArr[i]['pinyin']] = pinyin;
		  if(uname != ""){   
	         if( teamArr[i].text == uname || pinyin.indexOf(uname)>-1){
	    	      teamArr[i].selected = true;
			  }
		  }
	   }
	   
	   // if(! oname[uname] && uname != ""){
	   //     console.log('--->>',oname,uname)
		// 	alert("请重新输入指派人或选择正确的指派人");
		// 	//document.getElementById("assigntext").value = "";
		// 	//return;
		// }
	}
    var  teamArr = document.getElementById("handler_id");
    if(teamArr){
        for( var i = 0 ; i < teamArr.length; i++ ){
            teamArr[i].dataset.pinyin = pinyinUtil.getFirstLetter(teamArr[i].text).toLocaleLowerCase();
        }
    }

</script>