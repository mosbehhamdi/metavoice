<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {

        $user_id = Auth::user()->id;

        $skills = Skill::where('worker_id', '!=', $user_id)
            ->groupBy('label')
            ->get();

        if (auth()->user()->type == 'user') {
            return Inertia::render('TELEWORKER/skills', compact('skills'));
        }
    }

    public function skillWorkers($skillId)
    {

        $teleworkers =  DB::table('users')
            ->join('skills', function ($join) {
                $join->on('users.id', '=', 'skills.worker_id')->orOn('users.id', '=', 'skills.preceiver_id');
            })
            ->where('skills.id','=',$skillId)
            ->get();
        return  $teleworkers;
    }

    public function workerSkills($id)
    {

        return Skill::where("worker_id", "=", $id)
            ->orWhere("activated", "=", true)
            ->groupBy('label')
            ->get();

    }

    public function skillAdd()
    {

        $user_id = Auth::user()->id;
        $skills = Skill::where("worker_id", "=", $user_id)->groupBy('label')->get();

        if (auth()->user()->type == 'user') {
            $user_id = Auth::user()->id;
            return Inertia::render('TELEWORKER/addSkill', compact('skills'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        Validator::make($request->all(), [

            'label' => ['required'],

        ])->validate();

        Skill::create([
            'label' => $request->label,
            'worker_id' => $user_id,
            'checked' => true,

        ]);
        //  return Inertia::render('TELEWORKER/requests', compact('skills', 'user_id'));

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
            'worker_id' => 0,

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
        $skill->update([
            'activated' => true,
            'preceiver_id' => $user_id,

        ]);
        // $user_id = Auth::user()->id;

        /*Skill::create([
    'label' => $skill->label,
    'perceiver_id' => $user_id,
    'checked' => true,
    'activated'=>true,

    ]);*/
    }

    public function disableSkill(Request $request)
    {
        $skill = Skill::find($request->skillId);
        $skill->update([
            'activated' => false,
            'preceiver_id' => null,

        ]);
        // $u

    }
}
