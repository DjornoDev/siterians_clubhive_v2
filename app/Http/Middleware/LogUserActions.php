<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class LogUserActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and successful responses
        if (Auth::check() && $response->getStatusCode() < 400) {
            $this->logAction($request, $response);
        }

        return $response;
    }

    private function logAction(Request $request, Response $response)
    {
        $user = Auth::user();
        $method = $request->method();
        $route = $request->route();

        if (!$route) {
            return;
        }

        $routeName = $route->getName();
        $uri = $request->getRequestUri();

        // Skip logging for certain routes
        $skipRoutes = [
            'admin.action-logs.index',
            'admin.action-logs.show',
            'admin.action-logs.user-suggestions',
            'admin.action-logs.action-suggestions',
            // Skip routes that have manual logging in controllers
            'admin.users.store',
            'admin.users.update',
            'admin.users.destroy',
            'admin.clubs.store',
            'admin.clubs.update',
            'admin.clubs.destroy',
            'clubs.events.store',
            'clubs.events.update',
            'clubs.events.delete',
            'clubs.posts.store',
            'clubs.posts.update',
            'clubs.posts.delete',
            'voting.store',
            'voting.submit',
            'voting.save-candidate',
            'voting.toggle-published',
            'profile.update',
            'profile.destroy',
            // Navigation and read-only routes
            'dashboard',
            'home.index',
            'clubs.index',
            'clubs.show',
            'events.index',
        ];

        if (in_array($routeName, $skipRoutes)) {
            return;
        }

        // Determine action category and type based on route
        $actionData = $this->determineActionData($method, $routeName, $uri);

        if ($actionData) {
            ActionLog::create_log(
                $actionData['category'],
                $actionData['type'],
                $actionData['description'],
                [
                    'route' => $routeName,
                    'uri' => $uri,
                    'method' => $method,
                    'parameters' => $request->route()->parameters(),
                    'response_code' => $response->getStatusCode()
                ],
                $response->getStatusCode() < 400 ? 'success' : 'failed'
            );
        }
    }

    private function determineActionData($method, $routeName, $uri)
    {
        // Authentication actions
        if (str_contains($routeName, 'login')) {
            return [
                'category' => 'authentication',
                'type' => 'login',
                'description' => 'User logged in'
            ];
        }

        if (str_contains($routeName, 'logout')) {
            return [
                'category' => 'authentication',
                'type' => 'logout',
                'description' => 'User logged out'
            ];
        }

        // User management actions
        if (str_contains($routeName, 'users')) {
            $category = 'user_management';

            if ($method === 'POST') {
                return [
                    'category' => $category,
                    'type' => 'created',
                    'description' => 'Created new user account'
                ];
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                return [
                    'category' => $category,
                    'type' => 'updated',
                    'description' => 'Updated user account'
                ];
            } elseif ($method === 'DELETE') {
                return [
                    'category' => $category,
                    'type' => 'deleted',
                    'description' => 'Deleted user account'
                ];
            }
        }

        // Club management actions
        if (str_contains($routeName, 'clubs')) {
            $category = 'club_management';

            if ($method === 'POST') {
                return [
                    'category' => $category,
                    'type' => 'created',
                    'description' => 'Created new club'
                ];
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                return [
                    'category' => $category,
                    'type' => 'updated',
                    'description' => 'Updated club information'
                ];
            } elseif ($method === 'DELETE') {
                return [
                    'category' => $category,
                    'type' => 'deleted',
                    'description' => 'Deleted club'
                ];
            }
        }

        // Event management actions
        if (str_contains($routeName, 'events')) {
            $category = 'event_management';

            if ($method === 'POST') {
                return [
                    'category' => $category,
                    'type' => 'created',
                    'description' => 'Created new event'
                ];
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                return [
                    'category' => $category,
                    'type' => 'updated',
                    'description' => 'Updated event information'
                ];
            } elseif ($method === 'DELETE') {
                return [
                    'category' => $category,
                    'type' => 'deleted',
                    'description' => 'Deleted event'
                ];
            }
        }

        // Post management actions
        if (str_contains($routeName, 'posts')) {
            $category = 'post_management';

            if ($method === 'POST') {
                return [
                    'category' => $category,
                    'type' => 'created',
                    'description' => 'Created new post'
                ];
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                return [
                    'category' => $category,
                    'type' => 'updated',
                    'description' => 'Updated post'
                ];
            } elseif ($method === 'DELETE') {
                return [
                    'category' => $category,
                    'type' => 'deleted',
                    'description' => 'Deleted post'
                ];
            }
        }

        // Voting management actions
        if (str_contains($routeName, 'voting')) {
            $category = 'voting_management';

            if ($method === 'POST') {
                return [
                    'category' => $category,
                    'type' => 'created',
                    'description' => 'Created voting event'
                ];
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                return [
                    'category' => $category,
                    'type' => 'updated',
                    'description' => 'Updated voting event'
                ];
            } elseif ($method === 'DELETE') {
                return [
                    'category' => $category,
                    'type' => 'deleted',
                    'description' => 'Deleted voting event'
                ];
            }
        }

        return null;
    }
}
