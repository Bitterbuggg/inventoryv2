<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use function auth;

class Home extends BaseController
{
    public function index(): RedirectResponse
    {
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        if ($user !== null && $user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($user !== null && $user->inGroup('it_staff')) {
            return redirect()->to('/receiving');
        }

        return redirect()->to('/procurement/purchase-requests');
    }
}
