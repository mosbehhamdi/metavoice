<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Ladumor\OneSignal\OneSignal;

class FileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $files = File::latest()->get();
        if (auth()->user()->type == 'manager') {
            return Inertia::render('DashboardManager', compact('files'));
        } else if (auth()->user()->type == 'admin') {
            return Inertia::render('DashboardAdmin', compact('files'));
        } else {
            return Inertia::render('Dashboard', compact('files'));
        }
    }





    public function update(Request $request)
    {
        $file = File::findOrFail($request->id);

        $file->update([
            'title' => $request->title,
            'description' => $request->description,

        ]);
        return redirect()->route('dashboard');

    }

  

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'file' => ['required'],
            'description' => ['required'],
        ])->validate();

        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);

        File::create([
            'title' => $request->title,
            'description' => $request->description,
            'name' => $fileName,
        ]);

        $fields = [
            "include_external_user_ids" => ["2"],
            "channel_for_external_user_ids" => "push",
        ];
        $message = 'hey!! someone add an image to repair';
        OneSignal::sendPush($fields, $message);

        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $file = File::find($id);
        $file->delete();
        $files = File::all();

        return Redirect::route('dashboard');
    }
}
