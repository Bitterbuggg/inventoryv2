<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use function auth;

class LoginController extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'identifier' => 'required|min_length[3]|max_length[254]',
            'password'   => 'required|min_length[8]|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $service = RepositoryServices::authenticationService();
        $success = $service->login(
            (string) $this->request->getPost('identifier'),
            (string) $this->request->getPost('password'),
        );

        if (! $success) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $service->getLastError() ?? 'Invalid credentials.');
        }

        return $this->redirectByRole();
    }

    private function redirectByRole(): RedirectResponse
    {
        $user = auth()->user();

        if ($user !== null && $user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/');
    }
}

