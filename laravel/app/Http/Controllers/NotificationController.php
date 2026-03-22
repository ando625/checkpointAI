<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Comment;


class NotificationController extends Controller
{
    //未読件数を返す（ヘッダーのバッジ数）
    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    //通知一覧を返す（ドロップダウン用）
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->with(['comment.user', 'comment', 'report'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    //１けんだけを既読にする　報告IDを返す
    public function markAsRead(Request $request, Notification $notification)
    {
        //自分の通知以外は操作させない
        if($notification->user_id !== $request->user()->id){
            return response()->json(['message' => "権限がありません"],403);
        }

        $notification->update(['is_read' => true]); //1件だけ既読に更新

        //フロントでこのreport_idのページに飛ぶために返す
        return response()->json([
            'report_id' => $notification->report_id
        ]);
    }


}
