<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAccountModel extends Model
{
    protected $table = 'user_accounts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['employee_id', 'username', 'password', 'user_group_id', 'is_active', 'status', 'last_login'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }

    public function getUserByUsername($username)
    {
        return $this->select('user_accounts.*, employees.full_name, employees.email, employees.phone_number, employees.department, employees.positions, user_groups.group_name, user_groups.permissions')
                    ->join('employees', 'employees.oracleid = user_accounts.employee_id')
                    ->join('user_groups', 'user_groups.id = user_accounts.user_group_id')
                    ->where('user_accounts.username', $username)
                    ->first();
    }

    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function updateStatus($userId, $status)
    {
        return $this->update($userId, ['status' => $status]);
    }

    public function lockAccount($userId)
    {
        return $this->update($userId, ['status' => 'locked']);
    }

    public function unlockAccount($userId)
    {
        return $this->update($userId, ['status' => 'active']);
    }

    public function getAllUsersWithInfo()
    {
        return $this->select('user_accounts.*, employees.full_name, employees.email, employees.department, user_groups.group_name')
                    ->join('employees', 'employees.oracleid = user_accounts.employee_id')
                    ->join('user_groups', 'user_groups.id = user_accounts.user_group_id')
                    ->findAll();
    }

    public function getUserWithEmployeeInfo($userId)
    {
        return $this->select('user_accounts.*, employees.*, user_groups.group_name, user_groups.permissions')
                    ->join('employees', 'employees.oracleid = user_accounts.employee_id')
                    ->join('user_groups', 'user_groups.id = user_accounts.user_group_id')
                    ->where('user_accounts.id', $userId)
                    ->first();
    }
}