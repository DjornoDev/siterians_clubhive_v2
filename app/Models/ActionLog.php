<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action_category',
        'action_type',
        'action_description',
        'action_details',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'action_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Static method to create action log entry
     */
    public static function create_log($category, $type, $description, $details = null, $status = 'success')
    {
        $user = Auth::user();

        return self::create([
            'user_id' => $user ? $user->user_id : null,
            'user_name' => $user ? $user->name : 'System',
            'user_role' => $user ? $user->role : null,
            'action_category' => $category,
            'action_type' => $type,
            'action_description' => $description,
            'action_details' => $details,
            'status' => $status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by user role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('user_role', $role);
    }

    /**
     * Scope for filtering by action category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('action_category', $category);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for searching by user name
     */
    public function scopeUserSearch($query, $search)
    {
        return $query->where('user_name', 'like', '%' . $search . '%');
    }

    /**
     * Scope for searching by action keyword
     */
    public function scopeActionSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('action_type', 'like', '%' . $search . '%')
                ->orWhere('action_description', 'like', '%' . $search . '%');
        });
    }

    /**
     * Clean up old logs (older than 30 days)
     */
    public static function cleanupOldLogs()
    {
        $cutoffDate = Carbon::now()->subDays(30);

        // Get logs to archive before deleting
        $oldLogs = self::where('created_at', '<', $cutoffDate)->get();

        if ($oldLogs->count() > 0) {
            // Archive to file
            $archiveData = $oldLogs->toArray();
            $fileName = 'action_logs_archive_' . date('Y_m_d_H_i_s') . '.json';
            $filePath = storage_path('app/action_logs_archives/' . $fileName);

            // Create directory if it doesn't exist
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            file_put_contents($filePath, json_encode($archiveData, JSON_PRETTY_PRINT));

            // Delete old logs
            self::where('created_at', '<', $cutoffDate)->delete();

            return $oldLogs->count();
        }

        return 0;
    }

    /**
     * Create user-friendly log for user updates
     */
    public static function create_user_update_log($user, $originalData, $updatedData, $updatedBy = null)
    {
        $changes = [];
        $fieldMappings = [
            'name' => 'Name',
            'email' => 'Email Address',
            'role' => 'Role',
            'sex' => 'Gender',
            'address' => 'Address',
            'contact_no' => 'Contact Number',
            'mother_name' => 'Mother\'s Name',
            'mother_contact_no' => 'Mother\'s Contact Number',
            'father_name' => 'Father\'s Name',
            'father_contact_no' => 'Father\'s Contact Number',
            'section_id' => 'Section',
            'password' => 'Password'
        ];

        // Compare old and new data to find actual changes
        foreach ($updatedData as $field => $newValue) {
            if ($field === 'password') {
                // For password, just mention it was changed
                if (!empty($newValue)) {
                    $changes[] = 'Password was updated';
                }
                continue;
            }

            $oldValue = $originalData[$field] ?? null;

            // Skip if values are the same
            if ($oldValue == $newValue) {
                continue;
            }

            $friendlyFieldName = $fieldMappings[$field] ?? ucfirst(str_replace('_', ' ', $field));

            // Handle special cases
            if ($field === 'section_id') {
                $oldSection = $oldValue ? \App\Models\Section::find($oldValue)?->section_name : 'None';
                $newSection = $newValue ? \App\Models\Section::find($newValue)?->section_name : 'None';
                $changes[] = "{$friendlyFieldName} changed from '{$oldSection}' to '{$newSection}'";
            } else {
                $changes[] = "{$friendlyFieldName} changed from '{$oldValue}' to '{$newValue}'";
            }
        }

        // Create the description
        if (empty($changes)) {
            $description = "Viewed user profile for {$user->name} (no changes made)";
        } else {
            $description = "Updated user account for {$user->name}: " . implode(', ', $changes);
        }

        return self::create_log(
            'user_management',
            'updated',
            $description,
            [
                'user_id' => $user->user_id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'changes_made' => count($changes),
                'updated_by' => $updatedBy ?? auth()->user()->name ?? 'System'
            ]
        );
    }

    /**
     * Create user-friendly log for club operations
     */
    public static function create_club_log($action, $club, $details = [])
    {
        $descriptions = [
            'created' => "Created new club: {$club->club_name}",
            'updated' => "Updated club settings for: {$club->club_name}",
            'deleted' => "Deleted club: {$club->club_name}",
            'member_added' => "Added new member to {$club->club_name}",
            'member_removed' => "Removed member from {$club->club_name}",
            'approval_toggled' => "Changed approval requirement for {$club->club_name}",
        ];

        return self::create_log(
            'club_management',
            $action,
            $descriptions[$action] ?? "Performed {$action} on club: {$club->club_name}",
            array_merge([
                'club_id' => $club->club_id,
                'club_name' => $club->club_name,
            ], $details)
        );
    }

    /**
     * Create user-friendly log for event operations
     */
    public static function create_event_log($action, $event, $details = [])
    {
        $descriptions = [
            'created' => "Created new event: {$event->title}",
            'updated' => "Updated event: {$event->title}",
            'deleted' => "Deleted event: {$event->title}",
            'approved' => "Approved event: {$event->title}",
            'rejected' => "Rejected event: {$event->title}",
        ];

        return self::create_log(
            'event_management',
            $action,
            $descriptions[$action] ?? "Performed {$action} on event: {$event->title}",
            array_merge([
                'event_id' => $event->event_id,
                'event_title' => $event->title,
                'event_date' => $event->event_date,
            ], $details)
        );
    }

    /**
     * Create user-friendly log for post operations
     */
    public static function create_post_log($action, $post, $details = [])
    {
        $descriptions = [
            'created' => "Created new post: " . substr($post->content, 0, 50) . (strlen($post->content) > 50 ? '...' : ''),
            'updated' => "Updated post: " . substr($post->content, 0, 50) . (strlen($post->content) > 50 ? '...' : ''),
            'deleted' => "Deleted post: " . substr($post->content, 0, 50) . (strlen($post->content) > 50 ? '...' : ''),
        ];

        return self::create_log(
            'post_management',
            $action,
            $descriptions[$action] ?? "Performed {$action} on post",
            array_merge([
                'post_id' => $post->post_id,
                'post_content_preview' => substr($post->content, 0, 100),
            ], $details)
        );
    }

    /**
     * Get user-friendly formatted details
     */
    public function getFormattedDetailsAttribute()
    {
        if (!$this->action_details || !is_array($this->action_details)) {
            return [];
        }

        $formatted = [];
        $fieldMappings = [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_email' => 'Email Address',
            'club_id' => 'Club ID',
            'club_name' => 'Club Name',
            'event_id' => 'Event ID',
            'event_title' => 'Event Title',
            'event_date' => 'Event Date',
            'post_id' => 'Post ID',
            'post_content_preview' => 'Content Preview',
            'election_id' => 'Election ID',
            'election_title' => 'Election Title',
            'changes_made' => 'Number of Changes',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'status' => 'Status',
            'club_role' => 'Club Role',
            'old_status' => 'Previous Status',
            'new_status' => 'New Status',
            'vote_id' => 'Vote ID',
            'positions_voted' => 'Positions Voted',
        ];

        foreach ($this->action_details as $key => $value) {
            $friendlyKey = $fieldMappings[$key] ?? ucfirst(str_replace('_', ' ', $key));

            // Skip displaying complex arrays or technical fields
            if (is_array($value) && !in_array($key, ['updated_fields'])) {
                continue;
            }

            // Handle special formatting
            if ($key === 'updated_fields' && is_array($value)) {
                $value = implode(', ', $value);
            }

            $formatted[$friendlyKey] = $value;
        }

        return $formatted;
    }
}
