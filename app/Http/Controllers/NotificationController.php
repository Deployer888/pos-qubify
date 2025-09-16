<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserWalletHistory;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $notification = auth()->user()->unreadNotifications->find($notificationId);

        if ($notification) {
            $notification->markAsRead();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
    public function markAsReadAll(Request $request)
    {
        $user = auth()->user();

        // Fetch notifications with optional date filtering
        $query = $user->notifications();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            // Adjust to_date to include the full day
            $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();

            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Paginate notifications (10 per page)
        $notifications = $query->paginate(10);

        // Return view with the paginated notifications
        return view('admin.notification.notification', compact('notifications'));
    }
    public function customerMarkAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $notification = auth()->guard('customer')->user()->unreadNotifications->find($notificationId);

        if ($notification) {
            $notification->markAsRead();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
    public function CustomermarkAsReadAll(Request $request)
    {
        $user = auth()->guard('customer')->user();

        // Fetch notifications with optional date filtering
        $query = $user->notifications();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            // Adjust to_date to include the full day
            $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();

            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Paginate notifications (10 per page)
        $notifications = $query->paginate(10);

        // Return view with the paginated notifications
        return view('customer.notification.notification', compact('notifications'));
    }
    public function allWalletAmount(Request $request)
    {

        // Fetch notifications with optional date filtering
        $query = UserWalletHistory::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            // Adjust to_date to include the full day
            $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Paginate notifications (10 per page)
        $wallets = $query->where('customer_id', auth()->guard('customer')->user()->id)->paginate(10);

        // Return view with the paginated notifications
        return view('customer.wallet.wallet', compact('wallets'));
    }
}
