<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: news_menu_page.php,v 1.34 2007/07/13 07:58:33 giallu Exp $
	# --------------------------------------------------------
?>
<?php require_once( 'core.php' ) ?>
<?php
	access_ensure_project_level( config_get( 'manage_news_threshold' ) );
?>
<?php html_page_top1( lang_get( 'edit_news_link' ) ) ?>
<?php html_page_top2() ?>

<?php # Add News Form BEGIN ?>
<br />
<div align="center">
<form method="post" action="news_add.php">
<table class="width75" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
		<?php echo lang_get( 'add_news_title' ) ?>
	</td>
</tr>
<tr class="row-1">
	<td class="category" width="25%">
		<span class="required">*</span><?php echo lang_get( 'headline' ) ?>
	</td>
	<td width="75%">
		<input type="text" name="headline" size="64" maxlength="64" />
	</td>
</tr>
<tr class="row-2">
	<td class="category">
		<span class="required">*</span><?php echo lang_get( 'body' ) ?>
	</td>
	<td>
		<textarea name="body" cols="60" rows="8"></textarea>
	</td>
</tr>
<tr class="row-2">
	<td class="category">
		<?php echo lang_get( 'announcement' ) ?><br />
		<span class="small"><?php echo lang_get( 'stays_on_top' ) ?></span>
	</td>
	<td>
		<input type="checkbox" name="announcement" />
	</td>
</tr>
<tr class="row-1">
	<td class="category" width="25%">
		<?php echo lang_get( 'view_status' ) ?>
	</td>
	<td width="75%">
		<select name="view_state">
			<?php print_enum_string_option_list( 'view_state' ) ?>
		</select>
	</td>
</tr>
<tr>
	<td>
		<span class="required">* <?php echo lang_get( 'required' ) ?></span>
	</td>
	<td class="center">
		<input type="submit" class="button" value="<?php echo lang_get( 'post_news_button' ) ?>" />
	</td>
</tr>
</form>
</table>
</div>
<?php # Add News Form END ?>

<?php # Edit/Delete News Form BEGIN ?>
<br />
<div align="center">
<form method="post" action="news_edit_page.php">
<table class="width75" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
		<?php echo lang_get( 'edit_or_delete_news_title' ) ?>
	</td>
</tr>
<tr class="row-1">
	<td class="center" colspan="2">
		<input type="radio" name="action" value="edit" checked="checked" /> <?php echo lang_get( 'edit_post' ) ?>
		<input type="radio" name="action" value="delete" /> <?php echo lang_get( 'delete_post' ) ?>
	</td>
</tr>
<tr class="row-2">
	<td class="category" width="25%">
		<?php echo lang_get( 'select_post' ) ?>
	</td>
	<td width="75%">
		<select name="news_id">
			<?php print_news_item_option_list() ?>
		</select>
	</td>
</tr>
<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="<?php echo lang_get( 'submit_button' ) ?>" />
	</td>
</tr>
</table>
</form>
</div>
<?php # Edit/Delete News Form END ?>

<?php html_page_bottom1( __FILE__ ) ?>
