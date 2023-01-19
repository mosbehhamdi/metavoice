<?php

namespace App\Http\Controllers;

use App\Models\Instructions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\taskResponce;
use App\Models\Task;

class InstructionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    

    public function sendResolvingDescription(Request $request)
    {
        $resp = taskResponce::findOrFail($request->respId);
        $task = Task::findOrFail($resp->task_id);

        Instructions::create([
            'admin_id' => $task ->admin_id,
            'worker_id' =>  $task->worker_id,
            'task_id' => $task->id,
            'resp_id' => $resp->id,
            'description' => $request->respDescription,


        ]);


        $task->update([
            'status' => 'A corrigé',
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructions  $instructions
     * @return \Illuminate\Http\Response
     */
    public function show(Instructions $instructions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructions  $instructions
     * @return \Illuminate\Http\Response
     */
    public function edit(Instructions $instructions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instructions  $instructions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructions $instructions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructions  $instructions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructions $instructions)
    {
        //
    }
}
