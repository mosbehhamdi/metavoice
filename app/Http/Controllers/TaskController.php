<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Ladumor\OneSignal\OneSignal;
use App\Models\User;
use App\Models\taskResponce;

class TaskController extends Controller
{

    public function indexTask()
    {$skills = Skill::groupBy('label')
            ->where("checked", "=", true)
            ->get();
        $tasks = Task::all();
        $id = auth()->user()->id;
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/Tasks', compact('tasks', 'skills'));

        }
        if (auth()->user()->type == 'user') {
            return Inertia::render('TELEWORKER/pending', compact('id'));

        }
    }


    public function indexAdminRespondedTask()
    {
        $tasks = Task::where("status", "=", "Repondu")->get();
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/Responded', compact('tasks'));

        }
        
    }


    public function indexAdminValidTask()
    {
        $tasks = Task::where("status", "=", "Validé")->get();
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/validTasks', compact('tasks'));

        }
        
    }

    
    public function indexAdminToResolveTask()
    {
        $tasks = Task::where("status", "=", "A corrigé")->get();
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/toResolve', compact('tasks'));

        }
        
    }



    public function indexWorkerTasks()
    {
        $id = auth()->user()->id;
        $workerTasks = Task::where("worker_id", "=", $id)->get();

        return Inertia::render('TELEWORKER/pending', compact('id', 'workerTasks'));

    }


    public function indexWorkerRespondedTask()
    {
        $id = auth()->user()->id;

        $tasks = Task::where("status", "=", "Repondu")
        ->where("worker_id", "=", $id)
        ->get();
            return Inertia::render('TELEWORKER/respondedTasks', compact('tasks'));

        
        
    }

    public function indexWorkerToResolveTask()
    {
        $id = auth()->user()->id;

        $tasks = Task::where("status", "=", "A corrigé")
        ->where("worker_id", "=", $id)
        ->get();
            return Inertia::render('TELEWORKER/toResolveTasks', compact('tasks'));

        
        
    }

    public function indexWorkerValidTask()
    {
        $id = auth()->user()->id;

        $tasks = Task::where("status", "=", "Validé")
        ->where("worker_id", "=", $id)
        ->get();
            return Inertia::render('TELEWORKER/validTasks', compact('tasks'));

        
        
    }


    public function test()
    {
    }


    public function tagAsValidTask($respId)
    {
        $resp = taskResponce::findOrFail($respId);
        $task = Task::findOrFail($resp->task_id);
        
        $task->update([
            'status' => 'Validé',
        ]);

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
        $admin = Auth::user();
        $worker = DB::table('users')->where('email', $request->emailWorker)->first();

        $message = 'hey ' . $worker->name . ' !! Vous avez un nouveau tache de ' . Auth::user()->name;

        if (auth()->user()->type == 'admin') {

            Validator::make($request->all(), [
                'title' => ['required'],
                'description' => ['required'],
            ])->validate();

            if ($request->file === null) {
                Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'admin_id' => $admin->id,
                    'worker_id' => $worker->id,
                    'name' => 'noFile',
                    'hasFile' => false,
                    'adminName' =>$admin->name,
                    'adminPhoto' =>  $admin->profilePicture,
                    'workerName' => $worker->name,
                    'workerPhoto' => $worker->profilePicture,
                ]);

                $fields = [
                    "include_external_user_ids" => [strval($worker->id)],
                    "channel_for_external_user_ids" => "push",
                ];
                OneSignal::sendPush($fields, $message);

            } else {
                $fileName = time() . '.' . $request->file->extension();
                $request->file->move(public_path('uploads'), $fileName);

                Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'name' => $fileName,
                    'admin_id' => $admin->id,
                    'worker_id' => $worker->id,
                    'hasFile' => true,
                    'adminName' =>$admin->name,
                    'adminPhoto' =>  $admin->profilePicture,
                    'workerName' => $worker->name,
                    'workerPhoto' => $worker->profilePicture,

                ]);
            }

            $fields = [
                "include_external_user_ids" => [strval($worker->id)],
                "channel_for_external_user_ids" => "push",
            ];
            OneSignal::sendPush($fields, $message);


            $user = User::where('email', '=', $request->emailWorker)->first();
            $user->available = false;
            $user->save();


        }


    
        
      

    }

    public function destroyTask($id)
    {
        $file = Task::find($id);
        $file->delete();

    }

}
