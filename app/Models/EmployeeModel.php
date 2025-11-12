<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'oracleid';
    protected $allowedFields = ['oracleid', 'full_name', 'email', 'phone_number', 'department', 'positions'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getEmployeeByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getEmployeeByOracleId($oracleId)
    {
        return $this->where('oracleid', $oracleId)->first();
    }

    public function getAllEmployees()
    {
        return $this->findAll();
    }
}