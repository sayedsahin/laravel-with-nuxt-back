<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReadNotificationsResource;
use App\Http\Resources\UnreadNotificationsResource;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $unread = $request->user()->unreadNotifications;
        $read = $request->user()->readNotifications()
            ->take(10)
            ->skip(($request->get('page') - 1) * 10)
            ->latest()
            ->get();

        $unread_coll = UnreadNotificationsResource::collection($unread);
        $read_coll = ReadNotificationsResource::collection($read);

        $unread->markAsRead();
        return $notifications = $unread_coll->merge($read_coll);
    }

    public function notificationCount(Request $request)
    {
        return $request->user()->unreadNotifications->count();
    }
}
