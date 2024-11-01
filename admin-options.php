<?php
if (!current_user_can('manage_options')) wp_die( __('You do not have sufficient permissions to access this page.') );
	
if(isset($_POST['taskums_api_key'])){
	
	$taskums_api_key = $_POST['taskums_api_key'];
	update_option( 'taskums_api_key', $taskums_api_key );
	
	?>
	<div class="updated"><p><strong><?php _e('Settings saved.', 'taskums_options' ); ?></strong></p></div>
	<?php
}else{

	//Retrieve API Key from WP Options
	$taskums_api_key = get_option( 'taskums_api_key' );

}

if($taskums_api_key == ''){ ?>
	<div class="updated"><p><strong><?php _e('Please update your api key.', 'taskums_options' ); ?></strong></p></div>
<?php } ?>
<style>
	pre {padding:5px; background:#eee; border:1px solid #ddd;}
	ul.projects {padding:10px; border:1px solid #ddd; background:#FF9; width:500px;}
	ul.projects li {margin:10px;}
	span.project_title {font-size:18px; color:#333; font-family:Helvetica, Arial, sans-serif;}
	span.project_detail {font-size:12px; color:#666; margin-right:10px; }
	span.project_detail em{color:#333; }
</style>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e('Taskums Settings'); ?></h2>
	<form name="form" action="" method="post">
		<p><?php _e('Your Taskums API Key can be found on Taskums.com by logging in and visiting the My Account page. Only account owners will have an API Key. If you change your API Key, please remember to update it here.'); ?></p>
		
		<table class="form-table">
		<tbody><tr>
			<th><label for="taskums_api_key"><?php _e("API Key:", 'taskums_options' ); ?></label></th>
			<td><input type="text" name="taskums_api_key" id="taskums_api_key" value="<?php echo $taskums_api_key; ?>" size="20" class="regular-text code"><br />
			<a href="http://taskums.com" target="_blank">taskums.com</a> | <a href="http://taskums.com/login" target="_blank">Login</a> | <a href="http://taskums.com/pricing" target="_blank">Pricing & Signup</a> | <a href="http://taskums.com/blog" target="_blank">Blog</a> | <a href="http://taskums.com/api" target="_blank">API</a></td>
		</tr>
		</tbody></table>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
	</form>
	
	<?php if($taskums_api_key){ //only show usage info if the user has an api key, no need to overwhelm ?>
	
	<h2><?php _e('My Projects'); ?></h2>
	<p><?php _e('Here is a list of your projects in Taskums using the Quick API - List of Projects function and our custom css.'); ?></p>
	<div>
		<?php taskums_project_list(); ?>
	</div>
	
	<h2><?php _e('Plugin Usage'); ?></h2>
	<p><?php _e('We offer the Full API for developers who want complete control over the output of this plugin, and a Quick API to provide short hand usage for common needs.'); ?></p>
	
	<h3><?php _e('Quick API'); ?></h3>
	<p><?php _e('In your WordPress theme templates, enter the following code inside php tags to retrieve information from your Taskums account. You can edit the output by editing the template file listed under each function.'); ?></p>
	
	<table class="form-table">
	<tbody><tr>
		<th><?php _e('List of Projects'); ?></th>
		<td><pre>taskums_project_list();</pre>Template: /wp-content/plugins/taskums/api-project-list.php</td>
	</tr><tr>
		<th><?php _e('Latest Completed Tasks'); ?></th>
		<td><pre>taskums_latest_completed( 5 );</pre>Template: /wp-content/plugins/taskums/api-latest-completed.php</td>
	</tr>
	</tbody></table>
	
	<h3><?php _e('Full API'); ?></h3>
	<p><?php _e('This plugin allows you full control over your Taskums account. It contains direct access to the API and is a very powerful tool, yet may require some basic programming knowledge to use effectively. Consult your nearest programmer for advice if this looks overwhelming.'); ?></p>

	<h4><?php _e('Complete Example'); ?></h4>
	<p><?php _e('To get started, we will show a complete example of how to retrieve a list of all your account\'s projects. It outputs the name and project id number.'); ?></p>
	<pre>
$response = taskums('project', 'list'); 
if(!empty($response)){
	foreach($response as $index => $value):
		echo ' Project: '.$value.' (project_id:'.$index.') ';
	endforeach;
}else{ echo '<em>Sorry, no projects found</em>'; }
</pre>
	
	<h4><?php _e('API Format'); ?></h4>
	<pre>taskums( <em>object</em>, <em>function</em>, <em>parameters</em> ); </pre>
	<p>See the <a href="http://taskums.com/api">Taskums API Documentation</a> for the available object, function and parameter options.</p>
	
	<h4><?php _e('Examples'); ?></h4>
	<table class="form-table">
	<tbody><tr>
		<th><?php _e('Get List of Projects'); ?></th>
		<td><pre>taskums( 'project', 'list' );</pre></td>
	</tr><tr>
		<th><?php _e('Get List of Tasks for Project'); ?></th>
		<td><pre>taskums( 'project', 'list_tasks', 'project_id:###' );</pre><em><?php _e('replace ### with your project id number'); ?></em></td>
	</tr>
	</tbody></table>
	
	<p><?php _e('Note: The plugin caches calls to the API for 6 hours.'); ?></p>
	
	<?php } //end api key check ?>
	<br />
</div>