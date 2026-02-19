<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.55rem; text-align: left; }
        th { background: #f5f5f5; }
        .error { color: #b00020; margin-bottom: 0.75rem; }
        .message { color: #0a7a2a; margin-bottom: 0.75rem; }
    </style>
</head>
<body>
    <h1>Manage Users</h1>
    <p><a href="<?= site_url('admin/dashboard') ?>">Back to Dashboard</a></p>

    <?php if (session('message')): ?>
        <div class="message"><?= esc((string) session('message')) ?></div>
    <?php endif ?>

    <?php if (session('errors')): ?>
        <div class="error">
            <?php foreach ((array) session('errors') as $error): ?>
                <div><?= esc((string) $error) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Groups</th>
            <th>Assign Group</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= esc((string) $user->id) ?></td>
                <td><?= esc((string) ($user->username ?? '')) ?></td>
                <td><?= esc((string) ($user->email ?? '')) ?></td>
                <td><?= esc(implode(', ', $user->getGroups() ?? [])) ?></td>
                <td>
                    <form method="post" action="<?= site_url('admin/users/' . $user->id . '/role') ?>">
                        <?= csrf_field() ?>
                        <select name="role">
                            <option value="admin">admin</option>
                            <option value="employee">employee</option>
                            <option value="it_staff">it_staff</option>
                        </select>
                        <button type="submit">Assign</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>

