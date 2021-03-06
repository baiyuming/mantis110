<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Revision: 1.62 $
	# $Author: prichards $
	# $Date: 2007/07/29 18:10:41 $
	#
	# $Id: view_all_bug_page.php,v 1.62 2007/07/29 18:10:41 prichards Exp $
	# --------------------------------------------------------
?>
<?php
	require_once( 'core.php' );

	$t_core_path = config_get( 'core_path' );

	require_once( $t_core_path.'compress_api.php' );
	require_once( $t_core_path.'filter_api.php' );
	require_once( $t_core_path.'last_visited_api.php' );
?>
<?php auth_ensure_user_authenticated() ?>
<?php
	$f_page_number		= gpc_get_int( 'page_number', 1 );

	$t_per_page = null;
	$t_bug_count = null;
	$t_page_count = null;

	$rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, null, null, null, true );
	if ( $rows === false ) {
		print_header_redirect( 'view_all_set.php?type=0' );
	}

	$t_bugslist = Array();
	$t_row_count = sizeof( $rows );
	for($i=0; $i < $t_row_count; $i++) {
		array_push($t_bugslist, $rows[$i]["id"] );
	}

	gpc_set_cookie( config_get( 'bug_list_cookie' ), implode( ',', $t_bugslist ) );

	compress_enable();

	html_page_top1( lang_get( 'view_bugs_link' ) );

	if ( current_user_get_pref( 'refresh_delay' ) > 0 ) {
		html_meta_redirect( 'view_all_bug_page.php?page_number='.$f_page_number, current_user_get_pref( 'refresh_delay' )*60 );
	}

	html_page_top2();

	print_recently_visited();

	include( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view_all_inc.php' );
	

	html_page_bottom1( __FILE__ );
?>
<script type="text/javascript">

	 	function setValue(){
	  		var  teamArr = document.getElementById("reporter_id");
			   for(var i=0;i<teamArr.options.length;i++){
			 	   if(teamArr[i].selected){
			 		  document.getElementById("uname").value = teamArr[i].text;
					 }
			   }  
		 }
		 
	  	function changetText(){
	  		var uname = document.getElementById("uname").value; 
			
			var  teamArr = document.getElementById("reporter_id");
		   for(var i=0;i<teamArr.options.length;i++){

			  if(uname != ""){   
		         if( teamArr[i].text == uname){
		    	      teamArr[i].selected = true;
				  }
			  }else{
				  teamArr[0].selected = true;
			  }
		  }  
		}
</script>