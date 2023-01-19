<?php

namespace App\Http\Controllers;

use App\Models\taskResponce;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskResponceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */






     public function index($id)
     {
        $workerId = auth()->user()->id;

      return  $responses = taskResponce::where("worker_id", "=", $workerId)
      ->where("task_id","=",$id)
      ->get();
       
     }



     public function indexAdmin($id)
     {
       

      return  $responses = taskResponce::where("task_id","=",$id)->get();
       
     }











    public function store(Request $request)
    {

        $fileName = time() . '.' . $request->file->extension();
       $request->file->move(public_path('uploads'), $fileName);
     
       $task = Task::findOrFail($request->taskid);
       $admin= User::findOrFail($task->admin_id);

      taskResponce::create([
            'description' => $request->description,
            'admin_id' => $task->admin_id,
            'worker_id' => $task->worker_id,
            'task_id' => $task->id,
            'name' => $fileName,
            'andminName'=>$admin->name,
            'workerName'=>Auth::user()->name,
            'andminPhoto'=>$admin->profilePicture,
            'workerPhoto'=>Auth::user()->profilePicture,

            'taskTitle'=>$task->title,
        ]);

        $task->update([
            'status' => 'Repondu',
           

        ]);



        
        
        User::where('id', Auth::user()->id)->update([
            "available" => false,
        ]);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\taskResponce  $taskResponce
     * @return \Illuminate\Http\Response
     */
    public function show(taskResponce $taskResponce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\taskResponce  $taskResponce
     * @return \Illuminate\Http\Response
     */
    public function edit(taskResponce $taskResponce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\taskResponce  $taskResponce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, taskResponce $taskResponce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\taskResponce  $taskResponce
     * @return \Illuminate\Http\Response
     */
    public function destroy(taskResponce $taskResponce)
    {
        //
    }
}
