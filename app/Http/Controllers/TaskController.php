<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Ladumor\OneSignal\OneSignal;

class TaskController extends Controller
{

    public function indexTask()
    {$skills = Skill::groupBy('label')->get();
        $tasks = Task::all();
         $id=auth()->user()->id;
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/Tasks', compact('tasks', 'skills'));

        }
        if (auth()->user()->type == 'user') {
            return Inertia::render('TELEWORKER/finished',compact('id'));

        }
    }

    public function indexWorkerTasks()
    {
        $id = auth()->user()->id;
        $workerTasks = Task::where("worker_id", "=",  $id)->get();

        return Inertia::render('TELEWORKER/pending',compact('id','workerTasks'));


    }

    public function test()
    {
    }

    public function updateTask(Request $request)
    {
        $file = Task::findOrFail($request->id);

        $file->update([
            'title' => $request->title,
            'description' => $request->description,

        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function storeTask(Request $request)
    {
         
      
        if (auth()->user()->type == 'admin') {
            $admin_id = Auth::user()->id;
            $worker = DB::table('users')->where('email', $request->emailWorker)->first();
    
            Validator::make($request->all(), [
                'title' => ['required'],
                'description' => ['required'],
            ])->validate();

            if ($request->file === null) {
                Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'admin_id' => $admin_id,
                    'worker_id' => $worker->id,
                ]);

                $fields = [
                    "include_external_user_ids" => ["10"],
                    "channel_for_external_user_ids" => "push",
                ];
                $message = 'hey !! Vous avez nouveau tache a determiner';
                OneSignal::sendPush($fields, $message);
                
            } else {
                $fileName = time() . '.' . $request->file->extension();
                $request->file->move(public_path('uploads'), $fileName);

                Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'name' => $fileName,
                    'admin_id' => $admin_id,
                    'worker_id' => $worker->id,

                ]);
            }

            $fields = [
                "include_external_user_ids" => ["10"],
                "channel_for_external_user_ids" => "push",
            ];
            $message = 'hey !! Vous avez nouveau tache a determiner';
            OneSignal::sendPush($fields, $message);

        }
    

    }

    public function destroyTask($id)
    {
        $file = Task::find($id);
        $file->delete();

    }

}
