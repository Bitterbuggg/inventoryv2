<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use CodeIgniter\Shield\Authentication\AuthenticatorInterface;
use CodeIgniter\Shield\Auth;
use DomainException;
use InvalidArgumentException;
use function auth;

class AuthenticationService
{
    private ?string $lastError = null;
    private readonly Auth|AuthenticatorInterface $authenticator;
    private ?SessionManager $sessionManager = null;

    public function __construct(
        private readonly UserRepositoryInterface $users,
        Auth|AuthenticatorInterface|null $authenticator = null,
        ?SessionManager $sessionManager = null,
    )
    {
        $this->authenticator    = $authenticator ?? auth('session');
        $this->sessionManager   = $sessionManager ?? new SessionManager();
    }

    /**
     * @param array{username: string, email: string, password: string} $data
     */
    public function register(array $data): int
    {
        $username = trim($data['username'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('username, email, and password are required.');
        }

        if ($this->users->findByIdentifier($username) !== null) {
            throw new DomainException('Username already exists.');
        }

        if ($this->users->findByIdentifier($email) !== null) {
            throw new DomainException('Email already exists.');
        }

        $userId = $this->users->createUser([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ]);

        $this->users->assignGroup($userId, 'employee');

        return $userId;
    }

    public function login(string $identifier, string $password): bool
    {
        $identifier = trim($identifier);

        if ($identifier === '' || $password === '') {
            $this->lastError = 'Identifier and password are required.';

            return false;
        }

        $credentialField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $result          = $this->authenticator->attempt([
            $credentialField => $identifier,
            'password'       => $password,
        ]);

        if (! $result->isOK()) {
            $this->lastError = $result->reason() ?? 'Login failed.';

            return false;
        }

        $this->lastError = null;

        return true;
    }

    public function logout(): void
    {
        $this->authenticator->logout();
    }

    /**
     * Add a session without logging out the current user
     * This allows multiple concurrent login sessions
     */
    public function addSession(string $identifier, string $password, string $sessionName = ''): bool
    {
        $identifier = trim($identifier);

        if ($identifier === '' || $password === '') {
            $this->lastError = 'Identifier and password are required.';

            return false;
        }

        // Store the current session state
        $currentUser = auth()->user();

        // Attempt to login with the new credentials
        $credentialField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $result          = $this->authenticator->attempt([
            $credentialField => $identifier,
            'password'       => $password,
        ]);

        if (! $result->isOK()) {
            $this->lastError = $result->reason() ?? 'Login failed.';

            return false;
        }

        // Get the newly logged-in user
        $newUser = auth()->user();

        if ($newUser === null) {
            $this->lastError = 'Failed to retrieve user after login.';

            return false;
        }

        // Create a session record in the database
        if ($sessionName === '') {
            $sessionName = "{$newUser->username} ({$newUser->email})";
        }

        $sessionToken = $this->sessionManager->createSession(
            $newUser->id,
            $sessionName,
            service('request')->getIPAddress(),
            $_SERVER['HTTP_USER_AGENT'] ?? null
        );

        if ($sessionToken === false) {
            $this->lastError = 'Failed to create session record.';

            return false;
        }

        $this->lastError = null;

        return true;
    }

    /**
     * Get the session manager instance
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }
}
