<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * @param string $event
     * @param string $log
     * @param int $param
     * @return mixed
     */
    public function newLog(string $event, string $log, int $param)
    {
        $newLog = new Log();
        $newLog->date = date('Y-m-d H:i:s');
        $newLog->event = $event;
        $newLog->log = $log;
        $newLog->param = $param;
        $newLog->user_id = Auth::id();
        $newLog->save();
        return $newLog->id;
    }
}
