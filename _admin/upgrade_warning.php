<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: upgrade_warning.php,v 1.6 2007/06/23 03:42:47 vboctor Exp $
	# --------------------------------------------------------
?>
<?php
	$g_skip_open_db = true;  # don't open the database in database_api.php
	require_once ( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'core.php' );
	$g_error_send_page_header = false; # suppress page headers in the error handler

	# @@@ upgrade list moved to the bottom of upgrade_inc.php

	$f_advanced = gpc_get_bool( 'advanced', false );

	$result = @db_connect( config_get_global( 'dsn', false ), config_get_global( 'hostname' ), config_get_global( 'db_username' ), config_get_global( 'db_password' ), config_get_global( 'database_name' ) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title> Mantis Administration - Check Installation </title>
<link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr class="top-bar">
		<td class="links">
			[ <a href="index.php">Back to Administration</a> ]
		</td>
		<td class="title">
			Upgrade Installation
		</td>
	</tr>
</table>
<br /><br />

<p><b>WARNING:</b> - Always backup your database data before upgrading.  For example, if you use a mysql database, From the command line you can do this with the mysqldump command.</p>
<p>eg:</p>
<p><tt>mysqldump -u[username] -p[password] [database_name] > [filename]</tt></p>
<p>This will dump the contents of the specified database into the specified filename.</p>
<p>If an error occurs you can re-create your previous database by just importing your backed up database data.  You'll need to drop and recreate your database (or remove each table).</p>
<p><tt>mysql -u[username] -p[password] [database_name] < [filename]</tt></p>

<p>Upgrades may take several minutes depending on the size of your database.</p>

<div align="center">
	<table width="80%" bgcolor="#222222" border="0" cellpadding="10" cellspacing="1">
		<tr bgcolor="#ffffff">
			<?php if ( false == $result ) { ?>
			 <td align="center" nowrap="nowrap"><p>Opening connection to database [<?php echo config_get_global( 'database_name' ) ?>] on host [<?php echo config_get_global( 'hostname' ) ?>] with username [<?php echo config_get_global( 'db_username' ) ?>] failed ( <?php echo db_error_msg() ?> ).</p></td>
			<?php } else { 
				# check to see if the new installer was used
    			if ( -1 != config_get( 'database_version', -1 ) ) {
				?>
				<td align="center" nowrap="nowrap"><p>When you have backed up your database click the link below to continue</p>[ <a href="install.php">Upgrade Now</a> ]</td>
				<?php } else { ?>
				<td align="center" nowrap="nowrap"><p>When you have backed up your database click the link below to continue</p>[ <a href="upgrade_list.php">Upgrade Now</a> ]</td>
				<?php } ?>
			<?php } ?>
		</tr>
	</table>
</div>
</body>
</html>