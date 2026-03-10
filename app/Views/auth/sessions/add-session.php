<?php
/**
 * View for adding a new session (logging in with another account)
 */
?>
<?= $this->extend('common') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Add New Session</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Log in to another account to add it as a new session:</p>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars(session('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $field => $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/auth/sessions/add" id="addSessionForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="identifier" class="form-label">Email or Username</label>
                            <input 
                                type="text" 
                                class="form-control <?= session('errors.identifier') ? 'is-invalid' : '' ?>" 
                                id="identifier" 
                                name="identifier" 
                                value="<?= old('identifier') ?>" 
                                required
                                placeholder="admin@local.test or username"
                            >
                            <?php if (session('errors.identifier')): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars(session('errors.identifier')) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input 
                                type="password" 
                                class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                id="password" 
                                name="password" 
                                required
                            >
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars(session('errors.password')) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="session_name" class="form-label">Session Name (Optional)</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="session_name" 
                                name="session_name" 
                                value="<?= old('session_name') ?>" 
                                placeholder="e.g., Admin Account"
                            >
                            <small class="text-muted d-block mt-1">
                                If left blank, the username and email will be used.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Add Session</button>
                            <a href="/auth/sessions" class="btn btn-secondary">Back to Sessions</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <h6 class="alert-heading">About Multi-Session Login</h6>
                <p class="mb-0">
                    You can log in to multiple accounts simultaneously. Each account creates a separate session
                    that you can switch between using the sessions management page. Logging out one session will
                    not affect your other active sessions.
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
