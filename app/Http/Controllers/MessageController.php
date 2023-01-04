<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Ladumor\OneSignal\OneSignal;
class MessageController extends Controller
{
    public function index()
    {
        $receiver_id = Auth::user()->id;
        $Messages = Message::where("receiver_id", "=", $receiver_id)->get();
        $teleworkers = User::where("type", "=", "user")
            ->where("email_verified_at", "!=", null)
            ->get();
        if (auth()->user()->type == 'admin') {
            return Inertia::render('Admin/adminTeam', compact('Tasks', 'user_id', 'teleworkers'));

        } else {
            return Inertia::render('TELEWORKER/skills', compact('Tasks', 'user_id'));

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
        
            'content' => ['required'],
           

        ])->validate();
        $sender_id = Auth::user()->id;
        Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,

        ]);

    
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
        $messages = Message::all();

        return Redirect::route('messages');
    }

}
