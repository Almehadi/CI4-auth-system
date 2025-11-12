<?php

namespace Config;

// ...

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('register', 'AuthController::register', ['filter' => 'auth']);
$routes->post('register', 'AuthController::register', ['filter' => 'auth']);
$routes->get('add-employee', 'AuthController::addEmployee', ['filter' => 'auth']);
$routes->post('add-employee', 'AuthController::addEmployee', ['filter' => 'auth']);

// Dashboard Routes
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('employees', 'DashboardController::employees', ['filter' => 'auth']);
$routes->get('users', 'DashboardController::users', ['filter' => 'auth']);
$routes->get('users/toggle/(:num)', 'DashboardController::toggleUserStatus/$1', ['filter' => 'auth']);
$routes->get('users/unlock/(:num)', 'DashboardController::unlockUser/$1', ['filter' => 'auth']);

// Default route
$routes->get('/', function() {
    return redirect()->to('/dashboard');
});