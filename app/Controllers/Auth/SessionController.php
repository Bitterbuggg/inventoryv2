<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\SessionManager;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;

class SessionController extends BaseController
{
    private SessionManager $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
    }

    /**
     * Show all active sessions for the current user
     */
    public function index(): string|RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $sessions = $this->sessionManager->getCurrentUserActiveSessions();
        $currentSessionId = $this->sessionManager->getCurrentSessionId();

        return view('auth/sessions/index', [
            'user'              => $user,
            'sessions'          => $sessions,
            'currentSessionId'  => $currentSessionId,
        ]);
    }

    /**
     * Switch to a different active session
     */
    public function switch(int $sessionId): RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        if ($this->sessionManager->switchSession($sessionId)) {
            RepositoryServices::analyticsService()->trackCurrentUser(
                'auth.session_switched',
                'auth',
                'session',
                $sessionId,
            );

            return redirect()->back()->with('message', 'Session switched successfully.');
        }

        return redirect()->back()->with('error', 'Failed to switch session.');
    }

    /**
     * Logout a specific session
     */
    public function logout(int $sessionId): RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $currentSessionId = $this->sessionManager->getCurrentSessionId();

        if ($this->sessionManager->logoutSession($sessionId)) {
            RepositoryServices::analyticsService()->trackHttp(
                'auth.session_logged_out',
                'auth',
                null,
                null,
                null,
                ['session_id' => $sessionId],
            );

            // If we logged out the current session, we're already redirected
            if ($currentSessionId === $sessionId) {
                return redirect()->to('/');
            }

            return redirect()->back()->with('message', 'Session logged out successfully.');
        }

        return redirect()->back()->with('error', 'Failed to logout session.');
    }

    /**
     * Logout all sessions
     */
    public function logoutAll(): RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        $this->sessionManager->logoutAllSessions();

        RepositoryServices::analyticsService()->trackCurrentUser(
            'auth.all_sessions_logged_out',
            'auth',
            'user',
            $user === null ? null : (int) ($user->id ?? 0),
        );

        return redirect()->to('/login')->with('message', 'All sessions have been logged out.');
    }

    /**
     * Add a new session (login with another account)
     */
    public function addNew(): string|RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        return view('auth/sessions/add-session');
    }

    /**
     * Store a new session
     */
    public function store(): RedirectResponse
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $rules = [
            'identifier'    => 'required|min_length[3]|max_length[254]',
            'password'      => 'required|min_length[8]|max_length[255]',
            'session_name'  => 'permit_empty|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            RepositoryServices::analyticsService()->trackHttp(
                'auth.add_session_validation_failed',
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
        $sessionName = (string) $this->request->getPost('session_name');

        $service = RepositoryServices::authenticationService();
        $success = $service->addSession(
            $identifier,
            (string) $this->request->getPost('password'),
            $sessionName,
        );

        if (!$success) {
            RepositoryServices::analyticsService()->trackHttp(
                'auth.add_session_failed',
                'auth',
                null,
                null,
                null,
                ['identifier_type' => $this->identifierType($identifier)],
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $service->getLastError() ?? 'Failed to add session.');
        }

        $user = auth()->user();
        RepositoryServices::analyticsService()->trackCurrentUser(
            'auth.session_added',
            'auth',
            'user',
            $user === null ? null : (int) ($user->id ?? 0),
        );

        return redirect()->to('/auth/sessions')->with('message', 'New session added successfully!');
    }

    private function identifierType(string $identifier): string
    {
        return str_contains($identifier, '@') ? 'email' : 'username';
    }
}
