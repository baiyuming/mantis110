<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: manage_config_email_page.php,v 1.9 2006/01/01 02:56:39 thraxisp Exp $
	# --------------------------------------------------------

	require_once( 'core.php' );

	$t_core_path = config_get( 'core_path' );
	require_once( $t_core_path . 'email_api.php' );

/**
 * array_merge_recursive2()
 *
 * Similar to array_merge_recursive but keyed-valued are always overwritten.
 * Priority goes to the 2nd array.
 *
 * @static yes
 * @public yes
 * @param $paArray1 array
 * @param $paArray2 array
 * @return array
 */
    function array_merge_recursive2($p_Array1, $p_Array2) {
        if (!is_array($p_Array1) or !is_array($p_Array2)) { 
            return $p_Array2; 
        }
        foreach ( $p_Array2 AS $t_Key2 => $t_Value2) {
            $p_Array1[$t_Key2] = array_merge_recursive2( @$p_Array1[$t_Key2], $t_Value2);
        }
        return $p_Array1;
    }

	# --------------------
	# get_notify_flag cloned from email_notify_flag
	# Get the value associated with the specific action and flag.
	# For example, you can get the value associated with notifying "admin"
	# on action "new", i.e. notify administrators on new bugs which can be
	# ON or OFF.
	function get_notify_flag( $action, $flag ) {
		global $t_notify_flags, $t_default_notify_flags;
        
		$val = OFF;
		if ( isset ( $t_notify_flags[$action][$flag] ) ) {
			$val = $t_notify_flags[$action][$flag];
		} elseif ( isset ( $t_default_notify_flags[$flag] ) ) {
			$val = $t_default_notify_flags[$flag];
		} 
		return $val;
	}
	
    function colour_notify_flag ( $p_action, $p_flag ) {
        global $t_notify_flags, $t_global_notify_flags, $t_file_notify_flags, $t_colour_project, $t_colour_global;
        
        $t_file = isset( $t_file_notify_flags[$p_action][$p_flag] ) ? ( $t_file_notify_flags[$p_action][$p_flag] ? 1 : 0 ): -1;
        $t_global = isset( $t_global_notify_flags[$p_action][$p_flag] ) ? ( $t_global_notify_flags[$p_action][$p_flag]  ? 1 : 0 ): -1;
        $t_project = isset( $t_notify_flags[$p_action][$p_flag] ) ? ( $t_notify_flags[$p_action][$p_flag]  ? 1 : 0 ): -1;
           
        $t_colour = '';
        if ( $t_global >= 0 ) {
            if ( $t_global != $t_file ) {
                $t_colour = ' bgcolor="' . $t_colour_global . '" '; # all projects override
            }
        }
        if ( $t_project >= 0 ) {
            if ( $t_project != $t_global ) {
                $t_colour = ' bgcolor="' . $t_colour_project . '" '; # project overrides
            }
        } 
        return $t_colour;
    }
    
    
	# Get the value associated with the specific action and flag.
	function show_notify_flag( $p_action, $p_flag ) {
		global $t_can_change_flags , $t_can_change_defaults;
		$t_flag = get_notify_flag( $p_action, $p_flag );
		if ( $t_can_change_flags || $t_can_change_defaults ) {
			$t_flag_name = $p_action . ':' . $p_flag;
			$t_set = $t_flag ? "CHECKED" : "";
			return "<input type=\"checkbox\" name=\"flag[]\" value=\"$t_flag_name\" $t_set />";
		} else {
			return ( $t_flag ? '<img src="images/ok.gif" width="20" height="15" title="X" alt="X" />' : '&nbsp;' );
		}
	}

    function colour_threshold_flag ( $p_access, $p_action ) {
        global $t_notify_flags, $t_global_notify_flags, $t_file_notify_flags, $t_colour_project, $t_colour_global;
        
        $t_file = ( $p_access >= $t_file_notify_flags[$p_action]['threshold_min'] )
			             && ( $p_access <= $t_file_notify_flags[$p_action]['threshold_max'] );
        $t_global = ( $p_access >= $t_global_notify_flags[$p_action]['threshold_min'] )
			             && ( $p_access <= $t_global_notify_flags[$p_action]['threshold_max'] );
        $t_project = ( $p_access >= $t_notify_flags[$p_action]['threshold_min'] )
			             && ( $p_access <= $t_notify_flags[$p_action]['threshold_max'] );
           
        $t_colour = '';
        if ( $t_global != $t_file ) {
            $t_colour = ' bgcolor="' . $t_colour_global . '" '; # all projects override
        }
        if ( $t_project != $t_global ) {
            $t_colour = ' bgcolor="' . $t_colour_project . '" '; # project overrides
        } 
        return $t_colour;
    }
    
	function show_notify_threshold( $p_access, $p_action ) {
		global $t_can_change_flags , $t_can_change_defaults;
		$t_flag = ( $p_access >= get_notify_flag( $p_action, 'threshold_min' ) )
			&& ( $p_access <= get_notify_flag( $p_action, 'threshold_max' ) );
		if ( $t_can_change_flags  || $t_can_change_defaults ) {
			$t_flag_name = $p_action . ':' . $p_access;
			$t_set = $t_flag ? "CHECKED" : "";
			return "<input type=\"checkbox\" name=\"flag_threshold[]\" value=\"$t_flag_name\" $t_set />";
		} else {
			return $t_flag ? '<img src="images/ok.gif" width="20" height="15" title="X" alt="X" />' : '&nbsp;';
		}
	}

	function get_section_begin_for_email( $p_section_name ) {
		global $t_project;
		$t_access_levels = explode_enum_string( config_get( 'access_levels_enum_string' ) );
		echo '<table class="width100">';
		echo '<tr><td class="form-title" colspan="' . ( count( $t_access_levels ) + 7 ) . '">' . strtoupper( $p_section_name ) . '</td></tr>' . "\n";
		echo '<tr><td class="form-title" width="30%" rowspan="2">' . lang_get( 'message' ) . '</td>';
		echo'<td class="form-title" style="text-align:center" rowspan="2">&nbsp;' . lang_get( 'issue_reporter' ) . '&nbsp;</td>';
		echo '<td class="form-title" style="text-align:center" rowspan="2">&nbsp;' . lang_get( 'issue_handler' ) . '&nbsp;</td>';
		echo '<td class="form-title" style="text-align:center" rowspan="2">&nbsp;' . lang_get( 'users_monitoring_bug' ) . '&nbsp;</td>';
		echo '<td class="form-title" style="text-align:center" rowspan="2">&nbsp;' . lang_get( 'users_added_bugnote' ) . '&nbsp;</td>';
		echo '<td class="form-title" style="text-align:center" colspan="' . count( $t_access_levels ) . '">&nbsp;' . lang_get( 'access_levels' ) . '&nbsp;</td></tr><tr>';
		foreach( $t_access_levels as $t_access_level ) {
			$t_entry_array = explode_enum_arr( $t_access_level );
			echo '<td class="form-title" style="text-align:center">&nbsp;' . get_enum_to_string( lang_get( 'access_levels_enum_string' ), $t_entry_array[0] ) . '&nbsp;</td>';
		}
		echo '</tr>' . "\n";
	}

	function get_capability_row_for_email( $p_caption, $p_message_type ) {
		$t_access_levels = explode_enum_string( config_get( 'access_levels_enum_string' ) );

		echo '<tr ' . helper_alternate_class() . '><td>' . string_display( $p_caption ) . '</td>';
		echo '<td class="center"' . colour_notify_flag( $p_message_type, 'reporter' ) . '>' . show_notify_flag( $p_message_type, 'reporter' )  . '</td>';
		echo '<td class="center"' . colour_notify_flag( $p_message_type, 'handler' ) . '>' . show_notify_flag( $p_message_type, 'handler' ) . '</td>';
		echo '<td class="center"' . colour_notify_flag( $p_message_type, 'monitor' ) . '>' . show_notify_flag( $p_message_type, 'monitor' ) . '</td>';
		echo '<td class="center"' . colour_notify_flag( $p_message_type, 'bugnotes' ) . '>' . show_notify_flag( $p_message_type, 'bugnotes' ) . '</td>';

		foreach( $t_access_levels as $t_access_level ) {
			$t_entry_array = explode_enum_arr( $t_access_level );
			echo '<td class="center"' . colour_threshold_flag( (int)$t_entry_array[0], $p_message_type ) . '>' . show_notify_threshold( (int)$t_entry_array[0], $p_message_type ) . '</td>';
		}
		echo '</tr>' . "\n";
	}

	function get_section_end_for_email() {
		echo '</table><br />' . "\n";
	}


	html_page_top1( lang_get( 'manage_email_config' ) );
	html_page_top2();

	print_manage_menu( 'adm_permissions_report.php' );
	print_manage_config_menu( 'manage_config_email_page.php' );

	$t_access = current_user_get_access_level();
	$t_project = helper_get_current_project();
	
	$t_colour_project = config_get( 'colour_project');
	$t_colour_global = config_get( 'colour_global');
	
	# build a list of all of the actions
	$t_actions = array( 'owner', 'reopened', 'deleted', 'bugnote' );
	if( config_get( 'enable_sponsorship' ) == ON ) {
		$t_actions[] = 'sponsor';
	}
	if( config_get( 'enable_relationship' ) == ON ) {
		$t_actions[] = 'relationship';
	}
	$t_statuses = get_enum_to_array( config_get( 'status_enum_string' ) );
	foreach( $t_statuses as $t_status ) {
		$t_actions[] =  $t_status;
	}
	
	# build a composite of the status flags, exploding the defaults
	$t_global_default_notify_flags = config_get( 'default_notify_flags', null, null, ALL_PROJECTS );
	$t_global_notify_flags = array();
	foreach ( $t_global_default_notify_flags as $t_flag => $t_value ) {
	   foreach ($t_actions as $t_action ) {
	       $t_global_notify_flags[$t_action][$t_flag] = $t_value;
	   }
	}
	$t_global_notify_flags = array_merge_recursive2( $t_global_notify_flags, config_get( 'notify_flags', null, null, ALL_PROJECTS ) );

	$t_file_default_notify_flags = config_get_global( 'default_notify_flags' );
	$t_file_notify_flags = array();
	foreach ( $t_file_default_notify_flags as $t_flag => $t_value ) {
	   foreach ($t_actions as $t_action ) {
	       $t_file_notify_flags[$t_action][$t_flag] = $t_value;
	   }
	}
	$t_file_notify_flags = array_merge_recursive2( $t_file_notify_flags, config_get_global( 'notify_flags' ) );
	
	$t_default_notify_flags = config_get( 'default_notify_flags' );
	$t_notify_flags = array();
	foreach ( $t_default_notify_flags as $t_flag => $t_value ) {
	   foreach ($t_actions as $t_action ) {
	       $t_notify_flags[$t_action][$t_flag] = $t_value;
	   }
	}
	$t_notify_flags = array_merge_recursive2( $t_notify_flags, config_get( 'notify_flags' ) );

	$t_can_change_flags = $t_access >= config_get_access( 'notify_flags' );
	$t_can_change_defaults = $t_access >= config_get_access( 'default_notify_flags' );

	echo '<br /><br />';

	# Email notifications
	if( config_get( 'enable_email_notification' ) == ON ) {

		if ( $t_can_change_flags  || $t_can_change_defaults ) {
			echo "<form name=\"mail_config_action\" method=\"post\" action=\"manage_config_email_set.php\">\n";
		}

	    if ( ALL_PROJECTS == $t_project ) {
	        $t_project_title = lang_get( 'config_all_projects' );
	    } else {
	        $t_project_title = sprintf( lang_get( 'config_project' ) , string_display( project_get_name( $t_project ) ) );
	    }
	    echo '<p class="bold">' . $t_project_title . '</p>' . "\n";
	    echo '<p>' . lang_get( 'colour_coding' ) . '<br />';
	    if ( ALL_PROJECTS <> $t_project ) {
	        echo '<span style="background-color:' . $t_colour_project . '">' . lang_get( 'colour_project' ) .'</span><br />';
	    }
	    echo '<span style="background-color:' . $t_colour_global . '">' . lang_get( 'colour_global' ) . '</span></p>';

		get_section_begin_for_email( lang_get( 'email_notification' ) );
#		get_capability_row_for_email( lang_get( 'email_on_new' ), 'new' );  # duplicate of status change to 'new'
		get_capability_row_for_email( lang_get( 'email_on_assigned' ), 'owner' );
		get_capability_row_for_email( lang_get( 'email_on_reopened' ), 'reopened' );
		get_capability_row_for_email( lang_get( 'email_on_deleted' ), 'deleted' );
		get_capability_row_for_email( lang_get( 'email_on_bugnote_added' ), 'bugnote' );
		if( config_get( 'enable_sponsorship' ) == ON ) {
			get_capability_row_for_email( lang_get( 'email_on_sponsorship_changed' ), 'sponsor' );
		}
		if( config_get( 'enable_relationship' ) == ON ) {
			get_capability_row_for_email( lang_get( 'email_on_relationship_changed' ), 'relationship' );
		}
		$t_statuses = explode_enum_string( config_get( 'status_enum_string' ) );
		foreach( $t_statuses as $t_status ) {
			list( $t_state, $t_label ) = explode_enum_arr( $t_status );
			get_capability_row_for_email( lang_get( 'status_changed_to' ) . ' \'' . get_enum_element( 'status', $t_state ) . '\'', $t_label );
		}

		get_section_end_for_email();

		if ( $t_can_change_flags  || $t_can_change_defaults ) {
			echo '<p>' . lang_get( 'notify_actions_change_access' ) . ':';
			echo '<select name="notify_actions_access">';
			print_enum_string_option_list( 'access_levels', config_get_access( 'notify_flags' ) );
			echo '</select> </p>';

			echo "<input type=\"submit\" class=\"button\" value=\"" . lang_get( 'change_configuration' ) . "\" />\n";

			echo "</form>\n";
			
			echo "<div class=\"right\"><form name=\"mail_config_action\" method=\"post\" action=\"manage_config_revert.php\">\n";
			echo "<input name=\"revert\" type=\"hidden\" value=\"notify_flags,default_notify_flags\"></input>";
			echo "<input name=\"project\" type=\"hidden\" value=\"$t_project\"></input>";
			echo "<input name=\"return\" type=\"hidden\" value=\"" . $_SERVER['PHP_SELF'] ."\"></input>";
			echo "<input type=\"submit\" class=\"button\" value=\"";
			if ( ALL_PROJECTS == $t_project ) {
                echo lang_get( 'revert_to_system' );
            } else {
                echo lang_get( 'revert_to_all_project' );
            } 
			echo "\" />\n";
			echo "</form></div>\n";
		}

	}

	html_page_bottom1( __FILE__ );
?>
