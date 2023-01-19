<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {

        if (auth()->user()->type == 'user') {
            $user_id = Auth::user()->id;
            $existedSkill = false;
            $skills = Skill::get();

            return Inertia::render('TELEWORKER/skills', compact('skills', 'user_id', 'existedSkill'));
        }
    }

    public function skillWorkers($skillId)
    {

        $teleworkers = DB::table('users')
            ->join('skills', function ($join) {
                $join->on('users.id', '=', 'skills.worker_id')->orOn('users.id', '=', 'skills.preceiver_id');
            })
            ->where('skills.id', '=', $skillId)
            ->where('users.type', '=', 'user')

            ->get();
        return $teleworkers;
    }

    public function workerSkills($id)
    {

        return Skill::where('preceiver_id', '=', $id)
        ->orWhere([
            ['worker_id', '=', $id],
            ['activated', '=', true],  
        ])
        ->get();

    }

    public function skillAdd()
    {

        $user_id = Auth::user()->id;
        $skills = Skill::where("activated", "=", true)->get();

        if (auth()->user()->type == 'user') {
            $user_id = Auth::user()->id;
            return Inertia::render('TELEWORKER/addSkill', compact('skills', 'user_id'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [

            'label' => ['required'],

        ])->validate();
        $user_id = Auth::user()->id;
        $skills = Skill::get();

        $skill = Skill::where('label', '=', $request->label)->first();
        if ($skill == null) {
            Skill::create([
                'label' => $request->label,
                'worker_id' => $user_id,
                'checked' => true,
                'activated' => true,
                'worker_id' => $user_id,


            ]);
        } else {
            $existedSkill = true;
            return Inertia::render('TELEWORKER/skills', compact('skills', 'user_id', 'existedSkill'));

        }

    }

    public function validateSkills(Request $request)
    {
        $skill = Skill::find($request->skill_id);

        if ($request->checked) {
            $skill->update([
                'checked' => true,

            ]);

        } else {
            $skill->update([
                'checked' => false,

            ]);

        }

        return $skill->checked;

    }

    public function destroy($id)
    {
        $skill = Skill::find($id);
        $skill->update([
            'activated' => false,

        ]);

    }

    public function deleteSkillForever($id)
    {
        $skill = Skill::find($id);
        $skill->delete();

    }

    public function enableSkill(Request $request)
    {$user_id = Auth::user()->id;
        $skill = Skill::find($request->skillId);
        if ($user_id === $skill->worker_id) {
            $skill->update([
                'activated' => true,
    
            ]);
        }
        else {
            $skill->update([
                'preceiver_id' => $user_id,
    
            ]);
        }
    

    }

    public function disableSkill(Request $request)
    {
        $user_id = Auth::user()->id;
        $skill = Skill::find($request->skillId);

        if ($user_id === $skill->worker_id) {
            $skill->update([
                'activated' => false,
    
            ]);
        }
        else {
            $skill->update([
                'preceiver_id' => null,
    
            ]);
        }
        

    }
}
