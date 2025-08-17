<?php

namespace App\Exports;

use App\Models\Club;

class ClubEventsExport
{
    protected $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function collection()
    {
        return $this->club->events()->with(['organizer'])->get()->map(function ($event) {
            return [
                'Event ID' => $event->event_id,
                'Event Name' => $event->event_name,
                'Description' => strip_tags($event->event_description),
                'Organizer' => $event->organizer ? $event->organizer->name : 'N/A',
                'Event Date' => $event->event_date ? date('Y-m-d', strtotime($event->event_date)) : 'N/A',
                'Event Time' => $event->event_time ?? 'N/A',
                'Location' => $event->event_location ?? 'N/A',
                'Visibility' => ucfirst($event->event_visibility),
                'Approval Status' => ucfirst($event->approval_status),
                'Approved At' => $event->approved_at ? $event->approved_at->format('Y-m-d H:i:s') : 'N/A',
                'Created At' => $event->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
