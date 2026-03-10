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
            RepositoryServices::analyticsService()->trackHttp(
                'auth.login_validation_failed',
                'auth',
                null,
                null,
                null,
                ['errors_count' => count($this->validator->getErrors())],
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $identifier = (string) $this->request->getPost('identifier');

        $service = RepositoryServices::authenticationService();
        $success = $service->login(
            $identifier,
            (string) $this->request->getPost('password'),
        );

        if (! $success) {
            RepositoryServices::analyticsService()->trackHttp(
                'auth.login_failed',
                'auth',
                null,
                null,
                null,
                ['identifier_type' => $this->identifierType($identifier)],
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $service->getLastError() ?? 'Invalid credentials.');
        }

        $user = auth()->user();
        RepositoryServices::analyticsService()->trackCurrentUser(
            'auth.login_success',
            'auth',
            'user',
            $user === null ? null : (int) ($user->id ?? 0),
        );

        // Silently create a session record for multi-account tracking
        if ($user !== null) {
            try {
                $sessionManager = $service->getSessionManager();
                $sessionManager->createSession(
                    $user->id,
                    "{$user->username} ({$user->email})",
                    service('request')->getIPAddress(),
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                );
            } catch (\Throwable) {
                // Silent fail - session tracking is optional
            }
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

    private function identifierType(string $identifier): string
    {
        return str_contains($identifier, '@') ? 'email' : 'username';
    }
}
