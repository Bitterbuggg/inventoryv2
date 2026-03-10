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

        // Deactivate the current session
        if ($user !== null) {
            $sessionManager = new SessionManager();
            $sessionId = $sessionManager->getCurrentSessionId();
            
            if ($sessionId !== null) {
                $sessionManager->deactivateSession($sessionId);
                
                // Get remaining active sessions for this user
                $remaining = $sessionManager->getUserActiveSessions($user->id);
                
                if (count($remaining) > 0) {
                    // Switch to another active session
                    $nextSession = $remaining[0];
                    $sessionManager->switchSession($nextSession['id']);
                    
                    // Full logout first to clear Shield state
                    RepositoryServices::authenticationService()->logout();
                    
                    // Then the MultiSessionFilter will restore the next user on next request
                    return redirect()->to('/')->with('message', 'Logged out this account. Switched to another.');
                }
            }
        }

        // No more sessions, perform full logout
        RepositoryServices::authenticationService()->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
