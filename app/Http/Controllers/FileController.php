<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\File;
 
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
        }
        else if (auth()->user()->type == 'admin') {
            return Inertia::render('DashboardAdmin', compact('files'));

        }
        else {
            return Inertia::render('Dashboard', compact('files'));

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
            'title' => ['required'],
            'file' => ['required'],
        ])->validate();
  
        $fileName = time().'.'.$request->file->extension();  
        $request->file->move(public_path('uploads'), $fileName);
    
        File::create([
            'title' => $request->title,
            'name' => $fileName
        ]);
    
        return redirect()->route('file.upload');
    }
}