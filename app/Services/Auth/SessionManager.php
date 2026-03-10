<?php

namespace App\Services\Auth;

use App\Models\MultiSessionModel;
use CodeIgniter\Shield\Models\UserModel;

/**
 * SessionManager handles multiple concurrent sessions for users
 * Allows a user to be logged in with different accounts on different "sessions"
 * and switch between them
 */
class SessionManager
{
    private MultiSessionModel $sessionModel;
    private UserModel $userModel;

    public function __construct()
    {
        $this->sessionModel = new MultiSessionModel();
        $this->userModel    = new UserModel();
    }

    /**
     * Generate a unique session token
     */
    private function generateSessionToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Create a new session for a user
     * Does NOT logout the previous session
     */
    public function createSession(
        int $userId,
        string $sessionName,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): string|false {
        $token = $this->generateSessionToken();

        $sessionId = $this->sessionModel->createSession(
            $userId,
            $sessionName,
            $token,
            $ipAddress,
            $userAgent
        );

        if ($sessionId === false) {
            return false;
        }

        // Store the session token in the PHP session
        session()->set('multi_session_token', $token);
        session()->set('multi_session_id', $sessionId);

        return $token;
    }

    /**
     * Get the current active session token
     */
    public function getCurrentSessionToken(): ?string
    {
        return session()->get('multi_session_token');
    }

    /**
     * Get the current session ID
     */
    public function getCurrentSessionId(): ?int
    {
        return session()->get('multi_session_id');
    }

    /**
     * Switch to a different active session
     */
    public function switchSession(int $sessionId): bool
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null || !$session['is_active']) {
            return false;
        }

        // Verify the session belongs to the current user
        $currentUser = auth()->user();
        if ($currentUser === null || $currentUser->id != $session['user_id']) {
            return false;
        }

        // Switch to the new session
        session()->set('multi_session_token', $session['session_token']);
        session()->set('multi_session_id', $sessionId);

        // Update last activity
        $this->sessionModel->updateLastActivity($sessionId);

        return true;
    }

    /**
     * Get all active sessions for the current user
     */
    public function getUserActiveSessions(int $userId): array
    {
        return $this->sessionModel->getActiveSessionsByUser($userId);
    }

    /**
     * Get all active sessions for the current logged-in user
     */
    public function getCurrentUserActiveSessions(): array
    {
        $user = auth()->user();
        if ($user === null) {
            return [];
        }

        return $this->getUserActiveSessions($user->id);
    }

    /**
     * Logout a specific session
     */
    public function logoutSession(int $sessionId): bool
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return false;
        }

        // Verify ownership
        $currentUser = auth()->user();
        if ($currentUser === null || $currentUser->id != $session['user_id']) {
            return false;
        }

        $result = $this->sessionModel->deactivateSession($sessionId);

        // If this is the current session, redirect to select another or logout
        if ($this->getCurrentSessionId() == $sessionId) {
            // Get remaining active sessions
            $remaining = $this->getUserActiveSessions($currentUser->id);

            if (count($remaining) > 0) {
                // Switch to another session
                $this->switchSession($remaining[0]['id']);
            } else {
                // No more sessions, perform full logout
                auth()->logout();
                session()->destroy();
            }
        }

        return $result;
    }

    /**
     * Logout all sessions for the current user
     */
    public function logoutAllSessions(): void
    {
        $user = auth()->user();
        if ($user === null) {
            return;
        }

        $this->sessionModel->deactivateUserSessions($user->id);
        auth()->logout();
        session()->destroy();
    }

    /**
     * Validate current session is still active
     */
    public function validateCurrentSession(): bool
    {
        $token = $this->getCurrentSessionToken();

        if ($token === null) {
            return false;
        }

        $session = $this->sessionModel->getSessionByToken($token);

        if ($session === null) {
            return false;
        }

        // Update last activity
        $this->sessionModel->updateLastActivity($session['id']);

        return true;
    }

    /**
     * Cleanup sessions for a specific user that haven't been active
     */
    public function cleanupUserInactiveSessions(int $userId, int $daysOld = 7): int
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));

        $model = new MultiSessionModel();

        return $model->where('user_id', $userId)
            ->where('is_active', false)
            ->where('last_activity <', $cutoffDate)
            ->delete();
    }

    /**
     * Get session details
     */
    public function getSessionDetails(int $sessionId): ?array
    {
        return $this->sessionModel->find($sessionId);
    }
}
