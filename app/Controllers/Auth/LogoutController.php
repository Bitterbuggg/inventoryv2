<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
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

        RepositoryServices::authenticationService()->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
