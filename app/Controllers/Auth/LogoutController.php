<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\SessionManager;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use function auth;

class LogoutController extends BaseController
{
    public function destroy(): RedirectResponse
    {
        $user = auth()->user();

        RepositoryServices::analyticsService()->trackCurrentUser(
            'auth.logout',
            'auth',
            'user',
            $user === null ? null : (int) ($user->id ?? 0),
        );

        // Deactivate the current session from multi-session management
        if ($user !== null) {
            $sessionManager = new SessionManager();
            $sessionId = $sessionManager->getCurrentSessionId();
            
            if ($sessionId !== null) {
                $sessionManager->deactivateSession($sessionId);
                
                // Get remaining active sessions
                $remaining = $sessionManager->getUserActiveSessions($user->id);
                
                if (count($remaining) > 0) {
                    // Switch to another session instead of logging out
                    $sessionManager->switchSession($remaining[0]['id']);
                    return redirect()->to('/')->with('message', 'Session logged out. Switched to another active session.');
                }
            }
        }

        // If no more sessions, perform full logout
        RepositoryServices::authenticationService()->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
