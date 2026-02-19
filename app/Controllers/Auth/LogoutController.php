<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;

class LogoutController extends BaseController
{
    public function destroy(): RedirectResponse
    {
        RepositoryServices::authenticationService()->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}

