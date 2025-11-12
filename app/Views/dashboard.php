<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-users me-2"></i>Employee System
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i>
                    <?= $user['name'] ?> (<?= $user['group'] ?>)
                </span>
                <a class="nav-link" href="/logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <?php if (session()->get('group_name') === 'Administrator'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/employees">
                                <i class="fas fa-users me-2"></i>Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/users">
                                <i class="fas fa-user-cog me-2"></i>User Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/add-employee">
                                <i class="fas fa-user-plus me-2"></i>Add Employee
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">
                                <i class="fas fa-plus-circle me-2"></i>Create User
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>User Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Name:</th>
                                        <td><?= $user['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?= $user['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td><?= $user['department'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Position:</th>
                                        <td><?= $user['positions'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td><span class="badge bg-primary"><?= $user['group'] ?></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-shield-alt me-2"></i>Recent Login Attempts
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($recentAttempts)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>IP</th>
                                                    <th>Status</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentAttempts as $attempt): ?>
                                                    <tr>
                                                        <td><?= $attempt['username'] ?></td>
                                                        <td><code><?= $attempt['ip_address'] ?></code></td>
                                                        <td>
                                                            <span class="badge bg-<?= $attempt['success'] ? 'success' : 'danger' ?>">
                                                                <?= $attempt['success'] ? 'Success' : 'Failed' ?>
                                                            </span>
                                                        </td>
                                                        <td><?= date('M j, H:i', strtotime($attempt['attempt_time'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">No recent login attempts</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>