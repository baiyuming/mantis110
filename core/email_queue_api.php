<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2006  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: email_queue_api.php,v 1.2 2007/07/22 14:39:14 prichards Exp $
	# --------------------------------------------------------

	class EmailData {
		// properties set during creation
		var $email = '';
		var $subject = '';
		var $body = '';
		var $metadata = array( 'headers' => array() );

		// auto-populated properties
		var $email_id = 0;
		var $submitted = '';
	};

	# --------------------
	# Return a copy of the bug structure with all the instvars prepared for db insertion
	function email_queue_prepare_db( $p_email_data ) {
		$t_email_data = new EmailData;

		$t_email_data->email_id = db_prepare_int( $p_email_data->email_id );
		$t_email_data->email = db_prepare_string( $p_email_data->email );
		$t_email_data->subject = db_prepare_string( $p_email_data->subject );
		$t_email_data->body = db_prepare_string( $p_email_data->body );

		$t_email_data->metadata = array();

		foreach( $p_email_data->metadata as $t_key => $t_value ) {
			if ( $t_key != 'headers' ) {
				$t_email_data->metadata[$t_key] = db_prepare_string( $t_value );
			}
		}

		foreach( $p_email_data->metadata['headers'] as $t_key => $t_value ) {
			$t_email_data->metadata['headers'][$t_key] = db_prepare_string( $t_value );
		}

		$t_email_data->submitted = db_prepare_string( $p_email_data->submitted );

		return $t_email_data;
	}

	# --------------------
	function email_queue_add( $p_email_data ) {
		$t_email_data = email_queue_prepare_db( $p_email_data );

		# email cannot be blank
		if ( is_blank( $t_email_data->email ) ) {
			error_parameters( lang_get( 'email' ) );
			trigger_error( ERROR_EMPTY_FIELD, ERROR );
		}

		# subject cannot be blank
		if ( is_blank( $t_email_data->subject ) ) {
			error_parameters( lang_get( 'subject' ) );
			trigger_error( ERROR_EMPTY_FIELD, ERROR );
		}

		# body cannot be blank
		if ( is_blank( $t_email_data->body ) ) {
			error_parameters( lang_get( 'body' ) );
			trigger_error( ERROR_EMPTY_FIELD, ERROR );
		}

		$t_email_table = config_get( 'mantis_email_table' );

		$c_email = $t_email_data->email;
		$c_subject = $t_email_data->subject;
		$c_body = $t_email_data->body;
		$c_metadata = serialize( $t_email_data->metadata );

		$query = "INSERT INTO $t_email_table
				    ( email,
				      subject,
					  body,
					  submitted,
					  metadata)
				  VALUES
				    ( '$c_email',
				      '$c_subject',
				      '$c_body',
					  " . db_now() . ",
					  '$c_metadata'
					)";
		db_query( $query );

		return db_insert_id( $t_email_table );
	}

	# --------------------
	function email_queue_row_to_object( $p_row ) {
		# typically this function takes as an input the result of db_fetch_array() which can be false.
		if ( $p_row === false ) {
			return false;
		}

		$t_row = $p_row;
		$t_row['submitted']	= db_unixtimestamp( $t_row['submitted'] );
		$t_row['metadata'] = unserialize( $t_row['metadata'] );

		$t_email_data = new EmailData;

		$t_row_keys = array_keys( $t_row );
		$t_vars = get_object_vars( $t_email_data );

		# Check each variable in the class
		foreach ( $t_vars as $t_var => $t_value ) {
			# If we got a field from the DB with the same name
			if ( in_array( $t_var, $t_row_keys, true ) ) {
				# Store that value in the object
				$t_email_data->$t_var = $t_row[$t_var];
			}
		}

		return $t_email_data;
	}

	# --------------------
	function email_queue_get( $p_email_id ) {
		$c_email_id = db_prepare_int( $p_email_id );
		$t_email_table = config_get( 'mantis_email_table' );

		$query = "SELECT *
				  FROM $t_email_table
				  WHERE email_id='$c_email_id'";
		$result = db_query( $query );

		$t_row = db_fetch_array( $result );

		return email_queue_row_to_object( $t_row );
	}
	
	# --------------------
	function email_queue_delete( $p_email_id ) {
		$c_email_id = db_prepare_int( $p_email_id );
		$t_email_table = config_get( 'mantis_email_table' );

		$query = "DELETE FROM $t_email_table
				  WHERE email_id='$c_email_id'";
		db_query( $query );
	}

	# --------------------
	function email_queue_get_ids() {
		$t_email_table = config_get( 'mantis_email_table' );

		$query = "SELECT email_id
				  FROM $t_email_table
				  ORDER BY email_id DESC";
		$result = db_query( $query );

		$t_ids = array();
		while ( ( $t_row = db_fetch_array( $result ) ) !== false ) {
			$t_ids[] = $t_row['email_id'];
		}

		return $t_ids;
	}
?>