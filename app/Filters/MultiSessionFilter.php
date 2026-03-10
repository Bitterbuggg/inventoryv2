<?php

namespace App\Filters;

use App\Services\Auth\SessionManager;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserModel;

/**
 * MultiSessionFilter manages simultaneous logins for different accounts.
 * Validates session tokens and ensures the correct user is authenticated.
 */
class MultiSessionFilter implements FilterInterface
{
    /** @var SessionManager */
    private $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip validation for auth routes
        $currentPath = $request->getPath();
        $publicRoutes = ['/login', '/signup', '/', '/logout', '/auth/'];

        foreach ($publicRoutes as $route) {
            if (strpos($currentPath, $route) === 0) {
                return;
            }
        }

        $token = $this->sessionManager->getCurrentSessionToken();

        if ($token === null) {
            // No multi-session token, use Shield's auth
            return;
        }

        // Validate the session token is still active
        if (! $this->sessionManager->validateCurrentSession()) {
            // Token is invalid/expired
            session()->remove('multi_session_token');
            session()->remove('multi_session_id');

            // If user is still logged in via Shield, logout
            if (auth()->loggedIn()) {
                auth()->logout();
                return redirect()->to('/login')->with('error', 'Your session has expired. Please log in again.');
            }

            return;
        }

        // Session is valid - ensure the correct user is authenticated
        $sessionId = $this->sessionManager->getCurrentSessionId();
        if ($sessionId !== null) {
            $sessionData = $this->sessionManager->getSessionDetails($sessionId);

            if ($sessionData !== null) {
                $currentUser = auth()->user();
                $sessionUserId = (int) ($sessionData['user_id'] ?? 0);

                // If wrong user is logged in, we need to restore the right one
                // This happens after a logout() call switched sessions
                if ($currentUser === null || (int) $currentUser->id !== $sessionUserId) {
                    $user = model(UserModel::class)->withGroups()->findById($sessionUserId);

                    if ($user !== null) {
                        // Restore by updating session variables Shield reads
                        // Shield uses 'login_hash' in session to identify the user
                        $identityModel = model('CodeIgniter\\Shield\\Models\\IdentityModel');
                        $identity = $identityModel
                            ->where('user_id', $sessionUserId)
                            ->where('type', 'email_password')
                            ->first();

                        if ($identity !== null) {
                            // Update Shield's session to this user
                            session()->set('login_hash', $identity->hash);
                        }
                    }
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}
