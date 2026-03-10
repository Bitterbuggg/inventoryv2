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

        // Silently deactivate the current session record
        if ($user !== null) {
            try {
                $sessionManager = new SessionManager();
                $sessionId = $sessionManager->getCurrentSessionId();
                if ($sessionId !== null) {
                    $sessionManager->deactivateSession($sessionId);
                }
            } catch (\Throwable) {
                // Silent fail - session tracking is optional
            }
        }

        // Always perform full logout
        RepositoryServices::authenticationService()->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
