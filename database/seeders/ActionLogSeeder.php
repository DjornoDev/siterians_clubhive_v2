<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionLog;
use App\Models\User;
use Carbon\Carbon;

class ActionLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for testing
        $users = User::limit(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create some users first.');
            return;
        }

        $categories = [
            'authentication' => ['login', 'logout', 'password_reset', 'password_changed'],
            'user_management' => ['created', 'updated', 'deleted'],
            'club_management' => ['created', 'updated', 'deleted'],
            'club_membership' => ['joined', 'member_added', 'member_removed'],
            'event_management' => ['created', 'updated', 'deleted'],
            'post_management' => ['created', 'updated', 'deleted'],
            'voting_management' => ['created', 'updated', 'deleted']
        ];

        $statuses = ['success', 'failed'];
        $descriptions = [
            'authentication' => [
                'login' => 'User logged in successfully',
                'logout' => 'User logged out',
                'password_reset' => 'Password reset requested',
                'password_changed' => 'Password changed successfully'
            ],
            'user_management' => [
                'created' => 'New user account created',
                'updated' => 'User account information updated',
                'deleted' => 'User account deleted'
            ],
            'club_management' => [
                'created' => 'New club created',
                'updated' => 'Club information updated',
                'deleted' => 'Club deleted'
            ],
            'club_membership' => [
                'joined' => 'User joined a club',
                'member_added' => 'Member added to club',
                'member_removed' => 'Member removed from club'
            ],
            'event_management' => [
                'created' => 'New event created',
                'updated' => 'Event information updated',
                'deleted' => 'Event deleted'
            ],
            'post_management' => [
                'created' => 'New post created',
                'updated' => 'Post information updated',
                'deleted' => 'Post deleted'
            ],
            'voting_management' => [
                'created' => 'New voting event created',
                'updated' => 'Voting event updated',
                'deleted' => 'Voting event deleted'
            ]
        ];

        // Create 50 sample logs
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $category = array_rand($categories);
            $actionType = $categories[$category][array_rand($categories[$category])];
            $status = $statuses[array_rand($statuses)];

            // Create log with random date in the last 45 days
            $createdAt = Carbon::now()->subDays(rand(0, 45));

            ActionLog::create([
                'user_id' => $user->user_id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'action_category' => $category,
                'action_type' => $actionType,
                'action_description' => $descriptions[$category][$actionType] ?? "Performed {$actionType} action",
                'action_details' => [
                    'example_detail' => 'Sample detail ' . ($i + 1),
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'timestamp' => $createdAt->toISOString()
                ],
                'status' => $status,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Created 50 sample action logs');
    }
}
