<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }

        if (!empty($arguments)) {
            $userGroup = session()->get('group_name');
            $requiredGroup = $arguments[0];

            if ($userGroup !== $requiredGroup && $userGroup !== 'Administrator') {
                session()->setFlashdata('error', 'Access denied! Insufficient permissions.');
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}