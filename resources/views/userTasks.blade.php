<!DOCTYPE html>
<html>
<head>
    <title>User Tasks</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>
<body>
<style>
.addTaskIcon{
	float: right;
	line-height: 23px;
    border: 1px solid;
    float: right;
    padding: 7px;
    border-radius: 22px;
    font-size: 29px;
	cursor: pointer;
}
#userTasksTable tbody tr td:first-child{
	width:80%;
	border-right: none !important;
}
#userTasksTable tbody tr td:nth-child(2){
	border-left: none !important;
	text-align: right;
}
.shareIcon.shared svg{
	fill:blue;
}
.usersharesvg{
	fill: black;
	height: 25px;
	cursor: pointer;
}
.deleteTask{
	font-weight: bold;
	font-size:25px;
	cursor: pointer;
}
.logoutnav{
	text-align: end;
}
.taskTD label.done{
	text-decoration: line-through;
}
tr.loadingUsersData{
	display: none;
}
#usersList.loadingData tr.loadingUsersData{
	display: block;
}
#usersList.loadingData tr:not(.loadingUsersData){
	display: none;
}
#usersList .loadingUsersData td{
	border: none;
}
</style>

<input type="hidden" id="current_user" value="{{ $user_id }}">
<div id="userIconOrig" class="d-none"><svg class="usersharesvg" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 268.733 268.733" style="enable-background:new 0 0 268.733 268.733;" xml:space="preserve">
<g>
	<path d="M0,141.467v36.37c0,5.69,4.613,10.304,10.304,10.304h33.425v-39.175c0-13.63,3.309-26.493,9.135-37.864
		c-12.09-0.718-23.009-5.835-31.187-13.77C8.495,107.539,0,123.506,0,141.467z"/>
	<path d="M78.616,81.218c-5.225-8.579-8.236-18.646-8.236-29.403c0-6.551,1.13-12.839,3.183-18.697
		c-5.172-3.171-11.254-5.001-17.765-5.001c-18.8,0-34.041,15.239-34.041,34.04c0,18.8,15.241,34.041,34.041,34.041
		c2.589,0,5.107-0.299,7.531-0.847C67.813,90.029,72.951,85.282,78.616,81.218z"/>
	<path d="M171.078,150.335c5.518,0,10.918,1.226,15.834,3.515l8.482-6.204c-0.432-22.684-11.904-42.655-29.279-54.77
		c-10.175,9.679-23.919,15.639-39.037,15.639c-15.118,0-28.862-5.96-39.038-15.638c-17.712,12.35-29.312,32.86-29.312,56.091v44.552
		c0,6.971,5.651,12.622,12.622,12.622h66.796c-2.988-5.393-4.696-11.589-4.696-18.178
		C133.45,167.214,150.33,150.335,171.078,150.335z"/>
	<circle cx="127.078" cy="51.815" r="41.698"/>
	<path d="M247.104,215.36c-3.436,0-6.672,0.822-9.558,2.248l-40.529-29.645l40.234-29.431c2.957,1.518,6.301,2.391,9.852,2.391
		c11.279,0,20.53-8.636,21.529-19.652c1.163-12.944-9.064-23.603-21.529-23.603c-11.944,0-21.628,9.683-21.628,21.628
		c0,0.99,0.09,1.957,0.219,2.911l-40.359,29.521c-3.96-3.473-9.025-5.393-14.258-5.393c-11.944,0-21.628,9.683-21.628,21.628
		c0,11.944,9.684,21.628,21.628,21.628c5.273,0,10.329-1.941,14.258-5.394l40.408,29.557c-0.159,1.058-0.268,2.132-0.268,3.234
		c0,11.944,9.684,21.628,21.628,21.628c11.912,0,21.629-9.655,21.629-21.628C268.733,225.079,259.078,215.36,247.104,215.36z"/>
</g>

</svg></div>
<nav class="container logoutnav navbar shadow-sm">
Hello {{ $username->name }}
<span class="float-right">
    <a href="http://localhost:8000/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
</span>
</nav>
<form id="logout-form" action="http://localhost:8000/logout" method="POST" style="display: none;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
<div class="container">
    <h1>YOUR TASKS</h1>

    <div class="container">
		<table class="table table-bordered" id="userTasksTable">
			<thead>
				<tr>
					<th colspan="2">TASKS <span class="addTaskIcon">+</span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Retrieving data...</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<div id="taskCounting" class="text-muted text-right">
			<small>
				Total tasks: <span class="totalTasks"></span> -
				Tasks done: <span class="doneTasks"></span> -
				Tasks to do: <span class="undoneTasks"></span>
			</small>
		</div>
		<div class="text-center">
		    <input type="button" class="btn btn-primary" id="markTaskAsDone" disabled value="Mark selected tasks as done">
		</div>
	</div>

</div>
   
      
<div class="modal fade" id="addTaskModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading">Create task</h4>
            </div>
            <div class="modal-body">
                <form id="taskForm" name="taskForm" class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="taskname" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="saveTaskBtn" value="create" >Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
      
<div class="modal fade" id="usersShareModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading">Share task with</h4>
            </div>
            <div class="modal-body">
			    <form id="userForm" name="userForm" class="form-horizontal">
                   <input type="hidden" name="task_id_share" id="task_id_share">
					<table id="usersList" class="table table-bordered"><tbody></tbody></table>
                    <div class="text-center">
                     <button id="shareWithUsers" type="submit" class="btn btn-primary d-none">Save
                     </button>
                    </div>
				 </form>
            </div>
        </div>
    </div>
</div>
        
	
</body>
    
<script type="text/javascript">
var curr_user_id = {{ $user_id }};
  $(function () {
	    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
		
		$('#addTaskModal').on('shown.bs.modal', function (e) {
			$('#taskForm input').focus();
	    });
     
	 $('#userTasksTable').on('click', '.taskChck', function(){
		 let count = $('#userTasksTable .taskChck').filter(':checked').length;
		 if (count){
			 $('#markTaskAsDone').attr('disabled', false);
		 }
		 else{
			 $('#markTaskAsDone').attr('disabled', true);
		 }
	 });
	 
	 $('#userTasksTable').on('click', '.deleteTask', function(){
			let taskTR = $(this).closest('tr');
			let task_id = $(this).closest('tr').attr('id');
		if (confirm("Are You sure want to delete?")){
        $.ajax({
				  data: {action: 'deleteTask', task_id: task_id},
				  url: "{{ route('usertasks.store') }}",
				  type: "POST",
				  dataType: 'json',
					success: function (data) {
 				      taskTR.fadeTo("slow",0.7, function(){         
					      $(this).remove();   
                          if ( $('#userTasksTable tbody tr').length < 1){
							  $('#userTasksTable tbody').addClass('notask').html(getNoTaskRow());
						  }						  
					      updateTaskCount();
					  });
					},
					error: function (data) {
					  alert('Error: could not delete task');
					  console.log('Error:', data);
					}
        });
			
		}
	 });

	 $('#markTaskAsDone').click(function(){
		 let tasksList = [];
		 $('#userTasksTable .taskChck:checked').each(function(){
			 tasksList.push($(this).val());
		 });
		 let data = {action: 'markAsDone'};
		 data.tasksList = tasksList.join();
		 	$.ajax({
				  data: data,
				  url: "{{ route('usertasks.store') }}",
				  type: "POST",
				  dataType: 'json',
				  success: function (data) {
				      		 $('#userTasksTable .taskChck:checked').each(function(){
								 $(this).attr('checked', false);
								 $(this).attr('disabled', true);
								$(this).closest('tr').find('.taskTD label').addClass('done text-muted');
							});
							$('#markTaskAsDone').attr('disabled', true);
							updateTaskCount();
				  },
				  error: function (data) {
					  alert('Error: could not mark as done');
					  console.log('Error:', data);
				  }
				});	
		 			 
	 });
	 
	/* CLICK ON SHARE USERS ICON*/     
	$('#userTasksTable').on('click', '.shareIcon', function(){
		let task_id = $(this).closest('tr').attr('id');
		$('#task_id_share').val(task_id);
		$('input.userChck').prop('checked', false);
		$('#usersShareModal').modal('show');
		let data = {action: 'getSharedUsers', task_id: task_id};
	    $.ajax({
		   data: data,
		   url: "{{ route('usertasks.store') }}",
		   type: "POST",
		   dataType: 'json',
		   success: function (data) {
                    $.each(data, function(i, row) {
						$('input#checkuser_'+row.user_id).prop('checked', true);
					});
					$('#usersList').removeClass('loadingData');
					$('#shareWithUsers').removeClass('d-none');
		   },
		   error: function (data) {
			  alert('Error');
			  console.log('Error:', data);
		   }
		});
	});
	
	/* SUBMIT SHARE WITH USERS BUTTON */     
	$('#shareWithUsers').click(function(e){
		e.preventDefault();
		 let usersList = [];
		 $('#usersList .userChck:checked').each(function(){
			 usersList.push($(this).val());
		 });
		 let data = {action: 'shareWithUsers'};
		 data.usersList = usersList.join();
		 data.task_id = $('#task_id_share').val();
		 	$.ajax({
				  data: data,
				  url: "{{ route('usertasks.store') }}",
				  type: "POST",
				  dataType: 'json',
				  success: function (data) {
					$('#usersShareModal').modal('hide');
					updateShareIconColor();
				  },
				  error: function (data) {
					  alert('Error: could not share');
					  console.log('Error:', data);
				  }
				});	
		 			 
	 });
	 
	    $('.addTaskIcon').click(function () {
				$('#taskForm').trigger("reset");
				$('#addTaskModal').modal('show');
		});


	
	    $('#saveTaskBtn').click(function (e) {
			if ( $('#taskname').val().trim() != ''){
				e.preventDefault();
				var data = $('#taskForm').serialize() + '&action=createTask';
				$.ajax({
				  data: data,
				  url: "{{ route('usertasks.store') }}",
				  type: "POST",
				  dataType: 'json',
				  success: function (data) {
                      if (data.error == 'exists'){
						  alert('A task with the same name was already created');
					  }
					  else{
						  $('#addTaskModal').modal('hide');
						  let newTaskRow = getNewTaskRow(data.task_id, data.task_name, 0);
						  if (jQuery('#userTasksTable tbody').hasClass('notask')){
							  jQuery('#userTasksTable tbody').removeClass('notask').html('');
						  }
						  jQuery('#userTasksTable tbody').append(newTaskRow);
						  updateTaskCount();
					  }
				 
				  },
				  error: function (data) {
					  console.log('Error:', data);
					  $('#saveBtn').html('Save Changes');
				  }
				});				
			}
        });
	

	

           /*   GET USER TASKS */
	            $.ajax({
                  url: "{{ url('/usertasks') }}",
                  method: 'post',
                  data: {
                     action: 'getUserTasks'
                  },
                  success: function(result){
					 let buffer = '';
                     $.each(result, function(i, item) {
						buffer += getNewTaskRow(item.id, item.name, item.done);
					});
					if ( buffer == ''){
						buffer = getNoTaskRow();
						$('#userTasksTable tbody').addClass('notask');
					}
					$('#userTasksTable tbody').html(buffer);
					updateShareIconColor();
					updateTaskCount();
                  }});

		/*   GET USERS LIST FOR SHARE */
	            $.ajax({
                  url: "{{ url('/usertasks') }}",
                  method: 'post',
                  data: {
                     action: 'getAllOtherUsers'
                  },
                  success: function(result){
					let buffer = '<tr class="loadingUsersData"><td>Loading ...</td></tr>';
					$.each(result, function(i, user) {
						buffer += getUserRow(user.id, user.name);
					});
					$('#usersList').addClass('loadingData');
					$('#usersList tbody').html(buffer);
                                        
                  }});		
  });
  
  function updateShareIconColor(){
	  	          jQuery.ajax({
                  url: "{{ url('/usertasks') }}",
                  method: 'post',
                  data: {
                     action: 'taskShareCount'
                  },
                  success: function(result){
                     
					$.each(result, function(i, item) {
					   const taskid = item.task_id;    	
					   const counts = item.counts;
					   if (counts > 1){
					       $('#userTasksTable tr#' + taskid + ' .shareIcon').addClass('shared');						   
					   }else{
						   $('#userTasksTable tr#' + taskid + ' .shareIcon').removeClass('shared');						   
					   }
					});
                  }});	  
  }
  
  function getNewTaskRow(taskID, taskName, taskDone){
	  let userShareSVG = $('#userIconOrig').html();
	  let taskRow = '<tr id="'+taskID+'"><td class="taskTD"><div class="form-check"><input id="checktask_'+taskID+'" class="taskChck" type="checkbox" value="' +taskID+'"';
	  if (taskDone){
     	  taskRow += ' disabled ';		  
	  }
	  taskRow += '> <label  for="checktask_'+taskID+'" class="form-check-label ';
	  if (taskDone){
     	  taskRow += ' done text-muted ';		  
	  }
	  taskRow += ' ">'+taskName+'</label></div></td><td><span class="shareIcon">'+userShareSVG+'</span> <span class="deleteTask">&times;</span></td></tr>';
	return taskRow;
  }
  
  function getNoTaskRow(){
	  return '<tr><td colspan="2">No tasks found for you</td></tr>';
  }
  
  function getUserRow(id, name){
	  return  '<tr><td><div class="form-check"><input class="form-check-input userChck" type="checkbox" value="'+id+'" id="checkuser_'
	              +id+'"><label class="form-check-label" for="checkuser_'+id+'">'+name+'</label></div></td></tr>';
  }
  
  function updateTaskCount(){
	  if ( $('#userTasksTable tbody').hasClass('notask') ){
		  $('#taskCounting').hide();
	  }
	  else{
		  let totalTask = $('#userTasksTable tbody tr').length;
		  let doneTasks = $('#userTasksTable .taskTD .done').length;
		  $('#taskCounting span.totalTasks').html(totalTask);
		  $('#taskCounting span.doneTasks').html(doneTasks);
		  $('#taskCounting span.undoneTasks').html(totalTask - doneTasks);
		  $('#taskCounting').show();
	  }
							  
  }
</script>
</html>