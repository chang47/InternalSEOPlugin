<?php

/*
Plugin Name: Internal-Site SEO
Plugin URI: 
Description: This plugin helps you create a silo site where each posts will return back to your homepage and category from where your posts are fun.
Version: 1.0
Author: Joshua Chang
Author URL: http://totalseopackage.com
License: GPLv2
*/

/* Copyright 2013 Joshua Chang (email : coralbue@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

define("VERSION", "1.0");

register_activation_hook(__FILE__, 'iss_jj_set_default_options');

add_filter('the_content', 'iss_jj_link_adder');

add_action('admin_init', 'iss_jj_admit_init');



function iss_jj_set_default_options() {
	if(get_option('iss_jj_options') === false) {
		$new_options['ga_account_name'] = 'UA-000000-0';
		$new_options['track_outgoing_links'] = "false";
		$new_options['version'] = VERSION;
		add_option('iss_jj_options', $new_options);
	}
}

function iss_jj_link_adder($content) {
	$categories = get_the_category();
	if($categories[0]) {
		$content .= '<p> Return back to <a href="' . get_category_link($categories[0]) . '">' . $categories[0]->name . '</a>';
		if($categories[1]) {
			foreach ($categories as $category) {
				if(!($category->name == $categories[0]->name)) {
					$content .= ', <a href="' . get_category_link($category) . '">' . $category->name . '</a>';
				}
			}
		}
	}
	$content .= '</p>';
	$content .= '<p> Return back to <a href=" ' . site_url() . '">Home' . '</a></p>';
	return $content;
}

function iss_jj_admin_init() {
	register_setting('iss_jj_settings', 
		'iss_jj_options', 
		'iss_jj_validate_options');
	
	add_settings_selection('iss_jj_main_section', 
		'Main Settings', 
		'iss_jj_main_setting_section_callback', 
		'iss_jj_settings_section');
	
	add_settings_field('ga_account_name', 
		'Account Name', 
		'iss_jj_display_text_field', 
		'iss_jj_settings_section', 
		'iss_jj_main_section', 
		array('name' => 'ga_account_name'));
		
	add_settings_field( 'track_outgoing_links',
		'Track Outgoing Links',
		'ch3sapi_display_check_box',
		'ch3sapi_settings_section',
		'ch3sapi_main_section',
		array( 'name' => 'track_outgoing_links'));
}

function iss_jj_validate_options($input) {
	$input['version'] = VERSION;
	return $input;
}

?>