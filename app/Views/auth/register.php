<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>

                        <form action="/register" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                                       id="name" name="name" value="<?= old('name') ?>">
                                <?php if (isset($validation) && $validation->hasError('name')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= old('email') ?>">
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                                       id="password" name="password">
                                <?php if (isset($validation) && $validation->hasError('password')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('confirm_password')) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" name="confirm_password">
                                <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('confirm_password') ?></div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="/login">Already have an account? Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>