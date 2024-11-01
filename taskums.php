<?php
/*
Plugin Name: Taskums
Plugin URI: http://taskums.com/blog/2010/09/wordpress-plugin/
Description: Taskums API WP Plugin
Version: 1.0.2
Author: Taskums
Author URI: http://taskums.com
License: GPL2

Copyright 2010 Taskums (email : info@taskums.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//-- Template Functions - Full API
function taskums($object, $function, $parameters, $options){
	if(!in_array($object, array('project','task','email'))){ echo 'Invalid Taskums Object'; return false; }
	
	//Retrieve API Key from WP Options
	$taskums_api_key = get_option( 'taskums_api_key' );
	
	if($taskums_api_key){
		$response = taskums_cache($object.'_'.$function.'_'.$parameters);
		if($response === false){
			$response = unserialize( wp_remote_retrieve_body( wp_remote_get('http://taskums.com/api/'.$object.'/'.$function.'/'.$parameters.'/apikey:'.$taskums_api_key.'/mode:php') ) );
			if(!taskums_error_check($response)) set_transient($object.'_'.$function.'_'.$parameters, $response, taskums_cache_time());
		}
		if(!taskums_error_check($response)){
			return $response;
		}else{ echo $response['Error']; }
	}else{ echo 'Missing API Key. Check WordPress->Settings->Taskums'; }
}

//-- Template Functions - Quick API
function taskums_project_list(){

	//Retrieve API Key from WP Options
	$taskums_api_key = get_option( 'taskums_api_key' );
	
	if($taskums_api_key){
		$projects = taskums_cache('taskums_project_list');
		if($projects === false){
			$projects = unserialize( wp_remote_retrieve_body( wp_remote_get('http://taskums.com/api/project/view_all/apikey:'.$taskums_api_key.'/mode:php') ) );
			if(!taskums_error_check($projects)) set_transient('taskums_project_list', $projects, taskums_cache_time());
		}
		if(!taskums_error_check($projects)){
			include('api-project-list.php');
		}else{ echo $projects['Error']; }
	}else{ echo 'Missing API Key. Check WordPress->Settings->Taskums'; }
}

function taskums_latest_completed( $limit = 5){

	//Retrieve API Key from WP Options
	$taskums_api_key = get_option( 'taskums_api_key' );
	
	if($taskums_api_key){
		$tasks = taskums_cache('taskums_latest_completed');
		if($tasks === false){
			$tasks = unserialize( wp_remote_retrieve_body( wp_remote_get('http://taskums.com/api/task/latest_completed/limit:'.$limit.'/apikey:'.$taskums_api_key.'/mode:php') ) );
			if(!taskums_error_check($tasks)) set_transient('taskums_latest_completed', $tasks, taskums_cache_time());
		}
		if(!taskums_error_check($tasks)){
			include('api-latest-completed.php');
		}else{ echo $tasks['Error']; }
	}else{ echo 'Missing API Key. Check WordPress->Settings->Taskums'; }
}

//-- Taskums Caching
function taskums_cache($transient){
	return get_transient($transient);
}
function taskums_cache_time(){
	return 60*60*6; //6 hours
}

//-- Taskums Error Check
function taskums_error_check($response){
	if(isset($response['Error'])){
		return true; //TRUE = ERROR
	}else{
		return false; //FALSE = NO ERROR
	}
}

//-- Taskums Activation
function taskums_activate() {
	add_option('taskums_api_key', '');
}
register_activation_hook( __FILE__, 'taskums_activate' );

//-- Admin Settings
add_action('admin_menu', 'taskums_plugin_menu');
function taskums_plugin_menu() {
  add_options_page('Taskums Options', 'Taskums', 'manage_options', 'taskums_options', 'taskums_plugin_options');
}
function taskums_plugin_options() {
  include_once('admin-options.php');
}
function taskums_plugin_settings( $links ) {
	$settings_link = '<a href="options-general.php?page=taskums_options">'.__( 'Settings', 'taskums' ).'</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
function taskums_add_plugin_settings($links, $file) {
	if ( $file == basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ) ) {
		$links[] = '<a href="options-general.php?page=taskums_options">' . __( 'Settings', 'taskums' ) . '</a>';
		$links[] = '<a href="http://taskums.com/blog/2010/09/wordpress-plugin/">' . __( 'Support', 'taskums' ) . '</a>';
	}
	return $links;
}
add_action( 'plugin_action_links_'.basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ), 'taskums_plugin_settings', 10, 4 );
add_filter( 'plugin_row_meta', 'taskums_add_plugin_settings', 10, 2 );
?>