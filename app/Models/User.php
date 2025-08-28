<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'status',
        'name',
        'email',
        'sex',
        'address',
        'contact_no',
        'password',
        'section_id',
        'mother_name',
        'mother_contact_no',
        'father_name',
        'father_contact_no',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function clubMemberships()
    {
        return $this->hasMany(ClubMembership::class, 'user_id');
    }

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voter_id');
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class, 'user_id');
    }

    public function schoolClass()
    {
        return $this->hasOneThrough(
            SchoolClass::class,
            Section::class,
            'section_id', // Foreign key on sections table
            'class_id',   // Foreign key on classes table
            'section_id', // Local key on users table
            'class_id'    // Local key on sections table
        );
    }

    public function advisedClubs()
    {
        return $this->hasMany(Club::class, 'club_adviser')
            ->select('tbl_clubs.*'); // Explicitly select all columns from the clubs table
    }

    public function joinedClubs()
    {
        return $this->belongsToMany(Club::class, 'tbl_club_membership', 'user_id', 'club_id')
            ->using(ClubMembership::class)
            ->withPivot(['club_role', 'joined_date', 'club_accessibility'])
            ->withTimestamps()
            ->where('tbl_club_membership.club_id', '!=', null) // Add table prefix
            ->select('tbl_clubs.*'); // Explicitly select all columns from the clubs table
    }

    /**
     * Get all clubs IDs that the user is associated with (either as a member or adviser)
     *
     * @return array
     */
    public function getAllAssociatedClubIds()
    {
        // Get clubs where user is a member
        $memberClubIds = $this->joinedClubs()->pluck('tbl_clubs.club_id')->toArray();

        // Get clubs where user is an adviser
        $adviserClubIds = $this->advisedClubs()->pluck('tbl_clubs.club_id')->toArray();

        // Combine both lists and remove duplicates
        return array_unique(array_merge($memberClubIds, $adviserClubIds));
    }

    public function clubJoinRequests()
    {
        return $this->hasMany(ClubJoinRequest::class, 'user_id');
    }
}
