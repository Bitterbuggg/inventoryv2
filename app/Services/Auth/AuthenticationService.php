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

    public function __construct(
        private readonly UserRepositoryInterface $users,
        Auth|AuthenticatorInterface|null $authenticator = null,
    )
    {
        $this->authenticator = $authenticator ?? auth('session');
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

    public function getLastError(): ?string
    {
        return $this->lastError;
    }
}
