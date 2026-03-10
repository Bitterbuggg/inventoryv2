<?php
/**
 * View for displaying and managing user sessions
 */
?>
<?= $this->extend('common') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">My Sessions</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars(session('message')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars(session('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <p class="text-muted">Manage your active login sessions below:</p>

                    <?php if (empty($sessions)): ?>
                        <div class="alert alert-info">
                            You don't have any active sessions.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Session Name</th>
                                        <th>IP Address</th>
                                        <th>Last Activity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sessions as $session): ?>
                                        <tr <?= $session['id'] === $currentSessionId ? 'class="table-active"' : '' ?>>
                                            <td>
                                                <strong><?= htmlspecialchars($session['session_name']) ?></strong>
                                                <?php if ($session['id'] === $currentSessionId): ?>
                                                    <span class="badge bg-success">Current</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($session['ip_address'] ?? 'Unknown') ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php
                                                    $lastActivity = $session['last_activity'] ?? $session['created_at'];
                                                    $time = new DateTime($lastActivity);
                                                    echo $time->format('M d, Y H:i:s');
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <?php if ($session['id'] !== $currentSessionId): ?>
                                                    <form method="POST" action="/auth/sessions/switch/<?= $session['id'] ?>" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-primary">Switch</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form method="POST" action="/auth/sessions/logout/<?= $session['id'] ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Logout this session?')">Logout</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="/auth/sessions/add-new" class="btn btn-success">+ Add New Session</a>
                            <form method="POST" action="/auth/sessions/logout-all" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Logout all sessions? You will be redirected to login.')">Logout All Sessions</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Session Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Current User:</strong> <?= htmlspecialchars($user->username ?? 'Unknown') ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user->email ?? 'Unknown') ?></p>
                    <p><small class="text-muted">
                        You can log in to multiple accounts simultaneously. Each login creates a new session that you can switch between or logout independently.
                    </small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
