<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function team()
    {
        $teleworkers = User::where("type", "=", "user")
            ->where("email_verified_at", "!=", null)
            ->get();

        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/Team', compact('teleworkers'));

        }

    }

    public function skillsValidate(Request $request)
    {

        $worker = User::findOrFail($request->id);

        $worker->update([
            'profile' => $request->status,

        ]);
        $teleworkers = User::where("type", "=", "user")
            ->where("email_verified_at", "!=", null)
            ->get();
        return Inertia::render('Admin/Team', compact('teleworkers'));

    }

       
     
    public function workerUpdate(Request $request)
    {

        $worker = User::findOrFail($request->id);

        $worker->update([
            'profile' => $request->status,

        ]);
        $teleworkers = User::where("type", "=", "user")
            ->where("email_verified_at", "!=", null)
            ->get();
        return Inertia::render('Admin/Team', compact('teleworkers'));

    }



}
