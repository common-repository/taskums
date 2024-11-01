<ul class="projects">
	
	<?php if(!empty($projects)){ foreach($projects as $project): ?>

	<li>
		<span class="project_title"><?php echo $project['Project']['name']; ?></span><br />
		<span class="project_detail">Tasks: <em><?php echo $project['Project']['task_count']; ?></em></span> 
		<span class="project_detail">Progress: <em><?php echo $project['Project']['progress']; ?>%</em></span> 
		<span class="project_detail">Days: <em><?php echo $project['Project']['actual_time']; ?></em></span> 
		<?php if(is_admin()){ ?>
		<span class="project_detail">Project ID: <em><?php echo $project['Project']['id']; ?></em></span> 
		<?php } ?>
	</li>

	<?php endforeach; }else{ ?>
	
	<li><em>Sorry, no projects found.</em></li>
	
	<?php } ?>
	
</ul>