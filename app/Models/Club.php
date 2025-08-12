<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Club extends Model
{
    use HasFactory;

    protected $table = 'tbl_clubs';
    protected $primaryKey = 'club_id';

    protected $fillable = [
        'club_name',
        'club_adviser',
        'club_description',
        'club_logo',
        'club_banner',
        'is_club_hunting_day',
        'category',
        'requires_approval',
    ];

    public function getRouteKey()
    {
        return app(Hashids::class)->encode($this->getKey());
    }

    public function adviser()
    {
        return $this->belongsTo(User::class, 'club_adviser');
    }

    public function memberships()
    {
        return $this->hasMany(ClubMembership::class, 'club_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'club_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'club_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'tbl_club_membership', 'club_id', 'user_id')
            ->using(ClubMembership::class)
            ->withPivot(['club_role', 'club_position', 'joined_date', 'club_accessibility'])
            ->withTimestamps();
        // ->wherePivot('user_id', auth()->id()); // Uncomment kapag gusto mo i-filter ang members sa authenticated user
    }

    public function authMember()
    {
        return $this->belongsToMany(User::class, 'tbl_club_membership', 'club_id', 'user_id')
            ->where('user_id', auth()->id());
    }

    public function joinRequests()
    {
        return $this->hasMany(ClubJoinRequest::class, 'club_id');
    }

    public function pendingJoinRequests()
    {
        return $this->hasMany(ClubJoinRequest::class, 'club_id')->where('status', 'pending');
    }
}
