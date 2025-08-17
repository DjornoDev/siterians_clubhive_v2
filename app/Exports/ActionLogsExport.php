<?php

namespace App\Exports;

use App\Models\ActionLog;

class ActionLogsExport
{
    public function collection()
    {
        return ActionLog::with('user')->orderBy('created_at', 'desc')->get()->map(function ($actionLog) {
            return [
                'ID' => $actionLog->id,
                'User Name' => $actionLog->user_name,
                'User Role' => ucfirst($actionLog->user_role),
                'Action Category' => $actionLog->action_category,
                'Action Type' => $actionLog->action_type,
                'Action Description' => $actionLog->action_description,
                'Status' => ucfirst($actionLog->status),
                'IP Address' => $actionLog->ip_address,
                'User Agent' => $actionLog->user_agent,
                'Created At' => $actionLog->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
