<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginAttemptModel extends Model
{
    protected $table = 'login_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'ip_address', 'user_agent', 'attempt_time', 'success'];
    protected $useTimestamps = false;

    public function recordLoginAttempt($username, $ipAddress, $userAgent, $success)
    {
        $data = [
            'username' => $username,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'success' => $success ? 1 : 0,
            'attempt_time' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    public function getFailedAttemptsCount($username, $minutes = 15)
    {
        $time = date('Y-m-d H:i:s', strtotime("-$minutes minutes"));
        
        return $this->where('username', $username)
                    ->where('success', 0)
                    ->where('attempt_time >=', $time)
                    ->countAllResults();
    }

    public function getRecentAttempts($limit = 10)
    {
        return $this->orderBy('attempt_time', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function cleanupOldAttempts($days = 30)
    {
        $time = date('Y-m-d H:i:s', strtotime("-$days days"));
        return $this->where('attempt_time <', $time)->delete();
    }
}