<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;

class DeleteOldNotifications extends Command
{
    // コマンドの名前（スケジューラーから呼ぶときに使う）
    protected $signature = 'notifications:delete-old';

    // コマンドの説明
    protected $description = '7日以上経過した通知を削除する';

    public function handle()
    {
        $count = Notification::where('created_at', '<', Carbon::now()->subDays(7))
            ->delete(); // 7日より前の通知を全削除

        $this->info("削除した通知: {$count}件");
    }
}
