<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\UserAccountModel;
use App\Models\UserGroupModel;
use App\Models\LoginAttemptModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $employeeModel;
    protected $userAccountModel;
    protected $userGroupModel;
    protected $loginAttemptModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
        $this->userAccountModel = new UserAccountModel();
        $this->userGroupModel = new UserGroupModel();
        $this->loginAttemptModel = new LoginAttemptModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'get') {
            if ($this->session->get('isLoggedIn')) {
                return redirect()->to('/dashboard');
            }
            return view('auth/login');
        }

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $ipAddress = $this->request->getIPAddress();
        $userAgent = (string)$this->request->getUserAgent();

        $failedAttempts = $this->loginAttemptModel->getFailedAttemptsCount($username);
        if ($failedAttempts >= 5) {
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, false);
            
            $user = $this->userAccountModel->getUserByUsername($username);
            if ($user) {
                $this->userAccountModel->lockAccount($user['id']);
            }
            
            $this->session->setFlashdata('error', 'Too many failed login attempts. Account has been locked.');
            return redirect()->to('/login');
        }

        $user = $this->userAccountModel->getUserByUsername($username);

        if (!$user) {
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, false);
            $this->session->setFlashdata('error', 'Invalid username or password!');
            return redirect()->to('/login');
        }

        if ($user['is_active'] == 0) {
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, false);
            $this->session->setFlashdata('error', 'Your account is deactivated. Please contact administrator.');
            return redirect()->to('/login');
        }

        if ($user['status'] === 'locked' || $user['status'] === 'blocked') {
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, false);
            $this->session->setFlashdata('error', 'Your account is ' . $user['status'] . '. Please contact administrator.');
            return redirect()->to('/login');
        }

        if ($this->userAccountModel->verifyPassword($password, $user['password'])) {
            $this->userAccountModel->updateLastLogin($user['id']);
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, true);

            if ($user['status'] === 'locked') {
                $this->userAccountModel->unlockAccount($user['id']);
            }

            $sessionData = [
                'user_id' => $user['id'],
                'employee_id' => $user['employee_id'],
                'oracleid' => $user['employee_id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'department' => $user['department'],
                'positions' => $user['positions'],
                'group_name' => $user['group_name'],
                'permissions' => $user['permissions'],
                'isLoggedIn' => true
            ];
            $this->session->set($sessionData);

            $this->session->setFlashdata('success', 'Welcome back, ' . $user['full_name'] . '!');
            return redirect()->to('/dashboard');
        } else {
            $this->loginAttemptModel->recordLoginAttempt($username, $ipAddress, $userAgent, false);
            
            $failedAttempts = $this->loginAttemptModel->getFailedAttemptsCount($username);
            if ($failedAttempts >= 5) {
                $this->userAccountModel->lockAccount($user['id']);
                $this->session->setFlashdata('error', 'Too many failed login attempts. Account has been locked.');
            } else {
                $this->session->setFlashdata('error', 'Invalid username or password!');
            }
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        $this->session->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to('/login');
    }

    public function register()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('group_name') !== 'Administrator') {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getMethod() === 'get') {
            $data = [
                'employees' => $this->employeeModel->getAllEmployees(),
                'groups' => $this->userGroupModel->getAllGroups()
            ];
            return view('auth/register', $data);
        }

        $rules = [
            'employee_id' => 'required|is_not_unique[employees.oracleid]',
            'username' => 'required|min_length[3]|max_length[100]|is_unique[user_accounts.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'user_group_id' => 'required|is_not_unique[user_groups.id]'
        ];

        if (!$this->validate($rules)) {
            $data = [
                'employees' => $this->employeeModel->getAllEmployees(),
                'groups' => $this->userGroupModel->getAllGroups(),
                'validation' => $this->validator
            ];
            return view('auth/register', $data);
        }

        $userData = [
            'employee_id' => $this->request->getPost('employee_id'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'user_group_id' => $this->request->getPost('user_group_id'),
            'is_active' => 1,
            'status' => 'active'
        ];

        if ($this->userAccountModel->save($userData)) {
            $this->session->setFlashdata('success', 'User account created successfully!');
            return redirect()->to('/users');
        } else {
            $this->session->setFlashdata('error', 'Failed to create user account!');
            return redirect()->to('/register');
        }
    }

    public function addEmployee()
    {
        if ($this->request->getMethod() === 'get') {
            return view('auth/add_employee');
        }

        $rules = [
            'oracleid' => 'required|is_unique[employees.oracleid]',
            'full_name' => 'required|min_length[2]|max_length[255]',
            'email' => 'required|valid_email|is_unique[employees.email]',
            'department' => 'required',
            'positions' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/add_employee', [
                'validation' => $this->validator
            ]);
        }

        $employeeData = [
            'oracleid' => $this->request->getPost('oracleid'),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'department' => $this->request->getPost('department'),
            'positions' => $this->request->getPost('positions')
        ];

        if ($this->employeeModel->save($employeeData)) {
            $this->session->setFlashdata('success', 'Employee added successfully!');
            return redirect()->to('/employees');
        } else {
            $this->session->setFlashdata('error', 'Failed to add employee!');
            return redirect()->to('/add-employee');
        }
    }
}