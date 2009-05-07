<?php
/*
Plugin Name: Says Something Else
Plugin URI: http://www.robotwithaheart.com/wordpress-work/says-something-else
Description: Uses Javascript to replace "SoAndSo says" in the default threaded comments presentation with "SoAndSo [whatever you want]".
Version: 1.0
Author: Norman Yung
Author URI: http://www.robotwithaheart.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


define('SSE_PLUGPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');

//add defaults to an array
$sse_plugopts = array(
	'words'=>'says',
);

//add 2 db
add_option('SaysSomethingElse', $sse_plugopts);

//reload
$sse_plugopts = get_option('SaysSomethingElse');

//add sidebar link to settings page
function sse_menu_link() {
	if (function_exists('add_options_page')) {
		add_options_page('Says Something Else', 'Says Something Else', 8, basename(__FILE__), 'sse_settings_page');
	}
}

function sse_settings_page() {
	global $sse_plugopts;
	if (isset($_POST['save_changes'])) {
		$sse_plugopts['words'] = $_POST['words'];
		update_option('SaysSomethingElse', $sse_plugopts);
	}
	print <<<EOF
<div id="sse_admin" class="wrap">
	<h2>Says Something Else</h2>
	<form id="sse-options" action="" method="post">
		<label for="sse-words">Words (comma separated):</label>
		<textarea name="words" id="sse-words">{$sse_plugopts['words']}</textarea>
		<input type="hidden" name="save_changes" value="1" />
		<div class="submit"><input type="submit" value="Save Changes" /></div>
	</form>
</div>
EOF;
}

//styles for admin area
add_action('admin_head', 'sse_admin');
function sse_admin() {
	wp_register_style('sse-admin', SSE_PLUGPATH.'admin.css', false, '1.0', 'all');
	wp_print_styles('sse-admin');
}

// wrapper function on trim to use w/ array_walk
function sse_trim(&$value, $key) {
	$value=trim($value);
}

//write the <head> code
add_action('wp_head', 'sse_public');
function sse_public() {
	global $sse_plugopts;
	print '<script type="text/javascript">';
	$words=explode(',', $sse_plugopts['words']);
	array_walk($words, 'sse_trim');
	print 'var sse_words='.json_encode($words);
	print '</script>';
	wp_register_script('sse-public-js', SSE_PLUGPATH.'public.js', array('jquery'), '1.0');
	wp_print_scripts('sse-public-js');
}

//add a sidebar menu link
//hook the menu to "the_content"
add_action('admin_menu', 'sse_menu_link');
?>