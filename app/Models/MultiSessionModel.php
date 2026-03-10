<?php

namespace App\Models;

use CodeIgniter\Model;

class MultiSessionModel extends Model
{
    protected $table            = 'multi_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_name',
        'user_id',
        'session_token',
        'ip_address',
        'user_agent',
        'is_active',
        'last_activity',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id'       => 'required|integer',
        'session_name'  => 'required|string|max_length[255]',
        'session_token' => 'required|string|max_length[255]|is_unique[multi_sessions.session_token]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all active sessions for a user
     */
    public function getActiveSessionsByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('last_activity', 'DESC')
            ->findAll();
    }

    /**
     * Get a session by token
     */
    public function getSessionByToken(string $token): ?array
    {
        return $this->where('session_token', $token)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Deactivate a session
     */
    public function deactivateSession(int $sessionId): bool
    {
        return $this->update($sessionId, [
            'is_active'     => false,
            'last_activity' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Deactivate all sessions for a user
     */
    public function deactivateUserSessions(int $userId): bool
    {
        return $this->where('user_id', $userId)->update(null, [
            'is_active'     => false,
            'last_activity' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update last activity for a session
     */
    public function updateLastActivity(int $sessionId): bool
    {
        return $this->update($sessionId, [
            'last_activity' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Create a new session
     */
    public function createSession(int $userId, string $sessionName, string $sessionToken, ?string $ipAddress = null, ?string $userAgent = null): int|false
    {
        $data = [
            'user_id'       => $userId,
            'session_name'  => $sessionName,
            'session_token' => $sessionToken,
            'ip_address'    => $ipAddress,
            'user_agent'    => $userAgent,
            'is_active'     => true,
            'last_activity' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data) ? $this->getInsertID() : false;
    }

    /**
     * Cleanup old inactive sessions
     */
    public function cleanupOldSessions(int $daysOld = 30): int
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));

        return $this->where('is_active', false)
            ->where('last_activity <', $cutoffDate)
            ->delete();
    }
}
