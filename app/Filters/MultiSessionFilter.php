<?php

namespace App\Filters;

use App\Services\Auth\SessionManager;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * MultiSessionFilter validates that the current session is still active
 * If not, it logs the user out and redirects to login
 */
class MultiSessionFilter implements FilterInterface
{
    private SessionManager $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip validation for certain routes
        $currentPath = $request->getPath();
        
        // Routes that don't require session validation
        $publicRoutes = ['/login', '/signup', '/'];
        
        foreach ($publicRoutes as $route) {
            if (strpos($currentPath, $route) === 0) {
                return;
            }
        }

        // If user is logged in, validate their session
        if (auth()->loggedIn()) {
            if (!$this->sessionManager->validateCurrentSession()) {
                // Session is no longer valid, logout the user
                auth()->logout();
                session()->destroy();
                
                return redirect()->to('/login')->with('error', 'Your session has expired. Please log in again.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}
