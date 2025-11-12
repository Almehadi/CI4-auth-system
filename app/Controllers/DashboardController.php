<?php

namespace App\Controllers;

use App\Models\LoginAttemptModel;
use App\Models\UserAccountModel;
use App\Models\EmployeeModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    protected $loginAttemptModel;
    protected $userAccountModel;
    protected $employeeModel;

    public function __construct()
    {
        $this->loginAttemptModel = new LoginAttemptModel();
        $this->userAccountModel = new UserAccountModel();
        $this->employeeModel = new EmployeeModel();
    }

    public function index()
    {
        $recentAttempts = $this->loginAttemptModel->getRecentAttempts(10);

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => session()->get('full_name'),
                'email' => session()->get('email'),
                'department' => session()->get('department'),
                'positions' => session()->get('positions'),
                'group' => session()->get('group_name')
            ],
            'recentAttempts' => $recentAttempts
        ];
        return view('dashboard', $data);
    }

    public function employees()
    {
        $data = [
            'title' => 'Employees',
            'employees' => $this->employeeModel->getAllEmployees()
        ];
        return view('employees/list', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'User Accounts',
            'users' => $this->userAccountModel->getAllUsersWithInfo()
        ];
        return view('users/list', $data);
    }

    public function toggleUserStatus($userId)
    {
        $user = $this->userAccountModel->find($userId);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $this->userAccountModel->update($userId, ['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $this->session->setFlashdata('success', "User account {$statusText} successfully!");
        } else {
            $this->session->setFlashdata('error', 'User not found!');
        }
        return redirect()->to('/users');
    }

    public function unlockUser($userId)
    {
        if ($this->userAccountModel->unlockAccount($userId)) {
            $this->session->setFlashdata('success', 'User account unlocked successfully!');
        } else {
            $this->session->setFlashdata('error', 'Failed to unlock user account!');
        }
        return redirect()->to('/users');
    }
}