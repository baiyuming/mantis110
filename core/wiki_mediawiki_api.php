<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2007  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details
 
	# --------------------------------------------------------
	# $Id: wiki_mediawiki_api.php,v 1.1 2007/07/08 03:46:30 vboctor Exp $
	# --------------------------------------------------------

	# ----------------------
	# Gets the URL for the page with the specified page id.  This function is used
	# internally by this API.
	function wiki_mediawiki_get_url_for_page_id( $p_page_id ) {
		$t_root_url = config_get_global( 'wiki_engine_url' );
 
		$t_root_namespace = config_get( 'wiki_root_namespace' );
 
		if ( is_blank( $t_root_namespace ) ) {
			$t_page_id = $p_page_id;
		} else {
			$t_page_id = $t_root_namespace . ':' . $p_page_id;
		}
 
		return $t_root_url . 'index.php/' . urlencode( $t_page_id );
	}
 
	# ----------------------
	# Gets the page id for the specified issue.  The page id can then be converted
	# to a URL using wiki_mediawiki_get_url_for_page_id().
	function wiki_mediawiki_get_page_id_for_issue( $p_issue_id ) {
		$c_issue_id = db_prepare_int( $p_issue_id );
 		return $c_issue_id;
	}
 
 	# ----------------------
	# Gets the page url for the specified issue id.
	function wiki_mediawiki_get_url_for_issue( $p_issue_id ) {
		return wiki_mediawiki_get_url_for_page_id( wiki_mediawiki_get_page_id_for_issue( $p_issue_id ) );
	}
 
	# ----------------------
	# Gets the page id for the specified project.  The project id can be ALL_PROJECTS
	# The page id can then be converted to URL using wiki_mediawiki_get_url_for_page_id().
	function wiki_mediawiki_get_page_id_for_project( $p_project_id ) {
		$t_home = 'start';
		if ( $p_project_id == ALL_PROJECTS ) {
			return $t_home;
		} else {
			$t_project_name = project_get_name( $p_project_id );
			#return $t_project_name . ':' . $t_home;
			return 'Project:'.$t_project_name;
		}
	}
 
 	# ----------------------
	# Get URL for the specified project id.  The project is can be ALL_PROJECTS.
	function wiki_mediawiki_get_url_for_project( $p_project_id ) {
		return wiki_mediawiki_get_url_for_page_id( wiki_mediawiki_get_page_id_for_project( $p_project_id ) );
	}

	/*
	function wiki_mediawiki_string_display_links( $p_string ) {
		#$t_string = $p_string;
		#$t_string = str_replace( '[[', '{', $p_string );
	
		$t_wiki_web = config_get_global( 'wiki_engine_url' );
		
		preg_match_all( '/(^|.+?)(?:(?<=^|\W)' . '\[\[' . '([a-zA-Z0-9_:]+)\]\]|$)/s',
								$p_string, $t_matches, PREG_SET_ORDER );
		$t_result = '';
 
		foreach ( $t_matches as $t_match ) {
			$t_result .= $t_match[1];
 
			if ( isset( $t_match[2] ) ) {
				$t_result .= '<a href="' . wiki_mediawiki_get_url_for_page_id( $t_match[2] ) . '">[[' . $t_match[2] . ']]</a>'; 
			}
		}
 
		return $t_result;
	}
	*/
?>