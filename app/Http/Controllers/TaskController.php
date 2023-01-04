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

    public function indexWorkerTasks()
    {
        $id = auth()->user()->id;
        $workerTasks = Task::where("worker_id", "=", $id)->get();

        return Inertia::render('TELEWORKER/pending', compact('id', 'workerTasks'));

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

      

        $admin_id = Auth::user()->id;
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
                    'admin_id' => $admin_id,
                    'worker_id' => $worker->id,
                    'name' => 'noFile',
                    'hasFile' => false,
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
                    'admin_id' => $admin_id,
                    'worker_id' => $worker->id,
                    'hasFile' => true,

                ]);
            }

            $fields = [
                "include_external_user_ids" => [strval($worker->id)],
                "channel_for_external_user_ids" => "push",
            ];
            OneSignal::sendPush($fields, $message);

        }


    
        
        $worker->update([
            'available' => false,
        ]);


    }

    public function destroyTask($id)
    {
        $file = Task::find($id);
        $file->delete();

    }

}
