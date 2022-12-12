<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ladumor\OneSignal\OneSignal;

class NotificationController extends Controller
{

    public function push(Request $request)
    {
        $user = Auth::user();
        $fields = [
            "include_external_user_ids" => ["2"],
            "channel_for_external_user_ids" => "push",
        ];
        $message = 'hey!! this is a test notification';
        OneSignal::sendPush($fields, $message);

        return $user->id;
        //return redirect()->route('file.upload');
    }
}
