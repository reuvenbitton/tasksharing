<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTasks;
use App\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
	    return view('userTasks',array('hostname' => request()->getHttpHost(),
		                              'user_id'  => Auth::id(), 
									  'username' => Auth::user()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$user_id = Auth::id();
        switch($request->action){
			case 'getUserTasks': 
				$tasks = DB::table('user_tasks')
				    ->leftJoin('tasks', 'user_tasks.task_id', '=', 'tasks.id')
					->select('tasks.id','tasks.name','tasks.done')
					->where('user_id', $user_id)				
				    ->get();
                return response()->json($tasks);
			break;
			case 'getAllOtherUsers':
				$users = DB::table('users')
					->select('id','name')
					->where('id', '<>', $user_id)				
				    ->get();
                return response()->json($users);
			break;
			case 'createTask':
				$taskExists = DB::table('tasks')
					->select('id')
					->where('name', $request->name)				
				    ->count();
				if ( $taskExists ){
					//return response()->json('A task with the same name was already created', 500);
					return response()->json(array('error' => 'exists'));
				}	
				else{
				    //$newTaskID = DB::table('tasks')->insertGetId([['name' => $request->name, 'done' => 0]]);
					$task = new Tasks;
					$task->name = $request->name;
					$task->save();
					$userTask = new userTasks;
					$userTask->user_id = $user_id;
					$userTask->task_id = $task->id;
					$userTask->timestamps = false;
					$userTask->save();					
					return response()->json(array('task_id' => $task->id, 'task_name' => $request->name));				   
				}
			break;
			case 'markAsDone':
				try{
					Tasks::whereIn('id', explode(',', $request->tasksList))->update([ 'done' => 1  ]);
                    return response()->json('Success');
				} catch (\Illuminate\Database\QueryException $e) {
					return response()->json('Error with update', 500);
				}
			break;
			case 'deleteTask':
				return $this->deleteTask($request);
		    break;
			case 'taskShareCount':
				return $this->getTaskShareCount();
		    break;
			case 'shareWithUsers':
				return $this->shareWithUsers($request);
		    break;
			default:
				return '';
			break;
		}
    }

	protected function shareWithUsers($request){
		try{
			$usersList = explode(',', $request->usersList);
			foreach($usersList as $user_id){
					$userTask = new userTasks;
					$userTask->user_id = $user_id;
					$userTask->task_id = $request->task_id;
					$userTask->timestamps = false;
					$userTask->save();					
			}
			return response()->json('Success'); 
		} catch (\Illuminate\Database\QueryException $e) {
			return response()->json('Error with share task', 500);
		}
	}

	protected function deleteTask($request){
		try{
			$task = Tasks::find($request->task_id);
			$task->delete();
			DB::table('user_tasks')->where('task_id', $request->task_id)->delete();
            return response()->json('Success');
		} catch (\Illuminate\Database\QueryException $e) {
			return response()->json('Error with delete task', 500);
		}
	}

    protected function getTaskShareCount(){
		$data = DB::table('user_tasks')
		    //->leftJoin('users', 'user_tasks.user_id', '=', 'users.id')
    	    ->select('task_id', DB::raw("COUNT(*) as counts"))
			//->where('user_tasks.user_id', Auth::id())
     	    ->groupBy('task_id')
	        ->get();
		return $data;
	}
	
	
	
    /**
     * Display the specified resource.
     *
     * @param  \App\UserTasks  $userTasks
     * @return \Illuminate\Http\Response
     */
    public function show(UserTasks $userTasks)
    { 
		//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserTasks  $userTasks
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTasks $userTasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserTasks  $userTasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserTasks $userTasks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserTasks  $userTasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTasks $userTasks)
    {
        //
    }
}
