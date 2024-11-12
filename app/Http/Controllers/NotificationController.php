<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;

        return response()->json($notifications);
    }

    public function readAll()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications;

        return response()->json($notifications);
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return response()->noContent();
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications->find($id);

        $notification->delete();

        return response()->json(['message' => 'Notification supprimé'], 204);
    }
}
