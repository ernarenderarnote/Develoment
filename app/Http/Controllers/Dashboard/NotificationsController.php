<?php

namespace App\Http\Controllers\Dashboard;

use Gate;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Laravel\Spark\Notification;

class NotificationsController extends Controller
{
    /**
     * Delete file
     */
    public function delete(Request $request, $id)
    {
        $notification = Notification::find($id);
        if (Gate::denies('delete', $notification)) {
            return abort(403, trans('messages.not_authorized_to_access_notification'));
        }

        $result = $notification->delete();
        
        if ( ! $result ) {
            return abort(500, trans('messages.notification_cannot_be_deleted'));
        }

        return response()->api(trans('messages.notification_deleted'));
    }

}
