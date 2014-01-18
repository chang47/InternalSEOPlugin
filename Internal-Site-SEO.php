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


//Sets version value
define("VERSION", "1.0");

//Calls the method to creates the default option for the plugin
register_activation_hook(__FILE__, 'iss_jj_set_default_options');

//Calls the method to add the changes into the body 
add_filter('the_content', 'iss_jj_link_adder');

// settings API to create the values of the admin page
add_action('admin_init', 'iss_jj_admin_init');

//Calls the method that creates an an admin page under settings
add_action( 'admin_menu', 'iss_jj_settings_menu' );


// Sets the defualt options for the plugin
function iss_jj_set_default_options() {
	if(get_option('iss_jj_options') === false) {
		$new_options['ga_account_name'] = 'UA-000000-0';
		$new_options['track_outgoing_links'] = "false";
		$new_options['default_check'] = "true";
		$new_options['version'] = VERSION;
		add_option('iss_jj_options', $new_options);
	}
}

// Receives the content of the post and adds a link back to the parent category of the post and the homepage
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

//Allows users to set new options for the plugin on the admin page
function iss_jj_admin_init() {
	register_setting('iss_jj_settings', 
		'iss_jj_options', 
		'iss_jj_validate_options');
	
	add_settings_section('iss_jj_main_section', 
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
		'iss_jj_display_check_box',
		'iss_jj_settings_section',
		'iss_jj_main_section',
		array( 'name' => 'track_outgoing_links'));
		
	add_settings_field( 'Select_List', 'Select List',
		'iss_jj_select_list',
		'iss_jj_settings_section', 'iss_jj_main_section',
		array( 'name' => 'Select_List',
		'choices' => array( 'First', 'Second', 'Third', 'Fourth' ) ) );
	
	add_settings_field('Text_Field', 
		'Text Field', 
		'iss_jj_display_text_area', 
		'iss_jj_settings_section', 
		'iss_jj_main_section', 
		array('name' => 'ga_account_name'));
	
	add_settings_field(  
		'Radio_Button_Elements',  
		'Radio Button',  
		'sandbox_radio_element_callback',  
		'iss_jj_settings_section',  
		'iss_jj_main_section'); 
}

//Validates that the user is using the correct version
function iss_jj_validate_options($input) {
	$input['version'] = VERSION;
	return $input;
}

//Displays the front of the admin page
function iss_jj_main_setting_section_callback() { 
	?>
	<p>This is the main configuration section.</p>
	<?php 
}

//Displays the text field for the admin page
function iss_jj_display_text_field( $data = array() ) {
	extract( $data );
	$options = get_option( 'iss_jj_options' );
	?>
	<input type="text" name="iss_jj_options[<?php echo $name;
	?>]" value="<?php echo esc_html( $options[$name] );
	?>"/><br />
	<?php 
}

//Displays the check box field for the admin page
function iss_jj_display_check_box( $data = array() ) {
	extract ( $data );
	$options = get_option( 'iss_jj_options' );
	?>
	<input type="checkbox"
	name="iss_jj_options[<?php echo $name; ?>]"
	<?php if ( $options[$name] ) echo ' checked="checked"';
	?>/>
<?php }

function iss_jj_select_list( $data = array() ) {
	extract($data);
	$options = get_option('iss_jj_options');
	?>
	<select name="iss_jj_options[<?php echo $name; ?>]'>
	<?php foreach( $choices as $item ) { ?>
	<option value="<?php echo $item; ?>"
	<?php selected( $options[$name] == $item ); ?>>
	<?php echo $item; ?></option>;
	<?php } ?>
	</select>
	<?php
}

function iss_jj_display_text_area( $data = array() ) {
	extract ( $data );
	$options = get_option( 'iss_jj_options' );
	?>
	<textarea type="text"
	name="ch3sapi_options[<?php echo $name; ?>]"
	rows="5" cols="30">
	<?php echo esc_html ( $options[$name] ); ?></textarea>
	<?php 
}

function sandbox_radio_element_callback() {
	$options = get_option( 'iss_jj_options' );  
      
    $html = '<input type="radio" id="radio_example_one" name="sandbox_theme_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '/>';  
    $html .= '<label for="radio_example_one">One</label>';  
      
    $html .= '<br /><input type="radio" id="radio_example_two" name="sandbox_theme_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '/>';  
    $html .= '<label for="radio_example_two">Two</label>';  
      
    echo $html;  
}

//Adds the admin page to the setting
function iss_jj_settings_menu() {
	add_options_page( 'My Google Analytics Configuration',
	'My Google Analytics - Settings API', 'manage_options',
	'iss_jj-my-google-analytics',
	'iss_jj_config_page' );
}

//Creates the admin page for the user to see.
function iss_jj_config_page() { ?>
	<div id="iss_jj-general" class="wrap">
	<h2>My Google Analytics – Settings API</h2>
	<form name="iss_jj_options_form_settings_api" method="post"
	action="options.php">
	<?php settings_fields( 'iss_jj_settings' ); ?>
	<?php do_settings_sections( 'iss_jj_settings_section' ); ?>
	<input type="submit" value="Submit" class="button-primary" />
	</form>
	</div>
<?php 
}

?>