<ul class="latest_completed_tasks">

	<?php if(!empty($tasks)){ foreach($tasks as $task): ?>

	<li>
		<span class="task_title">Task <?php echo $task['Task']['name']; ?></span><br />
		<span class="task_detail">Started: <em><?php echo $task['Task']['started_date']; ?></em></span> 
		<span class="task_detail">Completed: <em><?php echo $task['Task']['completed_date']; ?></em></span> 
		<span class="task_detail">Project: <em><?php echo $task['Task']['project']; ?></em></span> 
	</li>

	<?php endforeach; }else{ ?>
	
	<li><em>Sorry, no tasks found.</em></li>
	
	<?php } ?>
	
</ul>