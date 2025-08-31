<?php

namespace App\Services;

use App\Models\Club;
use Illuminate\Support\Facades\Log;

class MainClubService
{
    /**
     * Get the main club instance
     *
     * @return Club|null
     */
    public static function getMainClub(): ?Club
    {
        $mainClubId = config('club.main_club_id');

        try {
            $mainClub = Club::find($mainClubId);

            if (!$mainClub) {
                Log::error('Main club not found', [
                    'main_club_id' => $mainClubId,
                    'message' => 'Main club with ID ' . $mainClubId . ' does not exist'
                ]);
                return null;
            }

            return $mainClub;
        } catch (\Exception $e) {
            Log::error('Error retrieving main club', [
                'main_club_id' => $mainClubId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if a user is the main club adviser
     *
     * @param int $userId
     * @return bool
     */
    public static function isMainClubAdviser(int $userId): bool
    {
        $mainClub = self::getMainClub();

        if (!$mainClub) {
            return false;
        }

        return $mainClub->club_adviser === $userId;
    }

    /**
     * Get main club hunting day status
     *
     * @return bool
     */
    public static function isHuntingDayActive(): bool
    {
        $mainClub = self::getMainClub();

        if (!$mainClub) {
            return false;
        }

        return (bool) $mainClub->is_club_hunting_day;
    }

    /**
     * Check if a club is protected (cannot be deleted)
     *
     * @param int $clubId
     * @return bool
     */
    public static function isProtectedClub(int $clubId): bool
    {
        $protectedClubs = config('club.protected_clubs', []);

        return in_array($clubId, $protectedClubs);
    }

    /**
     * Get main club ID
     *
     * @return int
     */
    public static function getMainClubId(): int
    {
        return config('club.main_club_id', 1);
    }

    /**
     * Validate that main club exists and is accessible
     *
     * @return bool
     */
    public static function validateMainClub(): bool
    {
        $mainClub = self::getMainClub();

        if (!$mainClub) {
            Log::critical('Main club validation failed - main club does not exist');
            return false;
        }

        if (!$mainClub->club_adviser) {
            Log::critical('Main club validation failed - no adviser assigned');
            return false;
        }

        return true;
    }
}
