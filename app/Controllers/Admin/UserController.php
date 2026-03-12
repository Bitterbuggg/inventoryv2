<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserModel;
use Config\RepositoryServices;
use DomainException;

class UserController extends BaseController
{
    public function index(): string|ResponseInterface
    {
        $users = model(UserModel::class)->withGroups()->findAll();

        if ($this->shouldExportCsv()) {
            $rows = [];
            foreach ($users as $user) {
                $groups = $user->getGroups() ?? [];
                $currentRole = 'employee';

                foreach (['admin', 'it_staff', 'employee'] as $role) {
                    if (in_array($role, $groups, true)) {
                        $currentRole = $role;
                        break;
                    }
                }

                $rows[] = [
                    (string) ($user->id ?? ''),
                    (string) ($user->username ?? ''),
                    (string) ($user->email ?? ''),
                    implode(', ', $groups),
                    $currentRole,
                ];
            }

            return $this->csvResponse(
                'admin_users_' . date('Ymd_His') . '.csv',
                ['ID', 'Username', 'Email', 'Groups', 'Current Role'],
                $rows,
            );
        }

        return view('admin/users', ['users' => $users]);
    }

    public function create(): string
    {
        return view('admin/create_user');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[users.username]',
            'email'            => 'required|valid_email|max_length[254]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
            'role'             => 'required|in_list[admin,employee,it_staff]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        if (RepositoryServices::userRepository()->findByIdentifier($email) !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['email' => 'Email already exists.']);
        }

        $service = RepositoryServices::authenticationService();

        try {
            $userId = $service->register([
                'username' => (string) $this->request->getPost('username'),
                'email'    => (string) $this->request->getPost('email'),
                'password' => (string) $this->request->getPost('password'),
            ]);

            RepositoryServices::userRepository()->assignGroup(
                $userId,
                (string) $this->request->getPost('role'),
            );

            $user = auth()->user();
            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.create_user',
                'admin',
                'user',
                $user === null ? null : (int) ($user->id ?? 0),
            );

            return redirect()->to('/admin/users')->with('message', 'User account created successfully.');
        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(int $userId): string|RedirectResponse
    {
        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        return view('admin/edit_user', ['user' => $user]);
    }

    public function update(int $userId): RedirectResponse
    {
        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $rules = [
            'email' => 'required|valid_email|max_length[254]',
            'username' => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',
        ];

        if ((string) $user->email !== $this->request->getPost('email')) {
            $newEmail = strtolower(trim((string) $this->request->getPost('email')));
            $existing = RepositoryServices::userRepository()->findByIdentifier($newEmail);

            if ($existing !== null && (int) ($existing->id ?? 0) !== (int) ($user->id ?? 0)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', ['email' => 'Email already exists.']);
            }
        }

        if ((string) $user->username !== $this->request->getPost('username')) {
            $rules['username'] .= '|is_unique[users.username]';
        }

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $user->email = (string) $this->request->getPost('email');
        $user->username = (string) $this->request->getPost('username');

        model(UserModel::class)->save($user);

        $currentUser = auth()->user();
        RepositoryServices::analyticsService()->trackCurrentUser(
            'admin.update_user',
            'admin',
            'user',
            $currentUser === null ? null : (int) ($currentUser->id ?? 0),
        );

        return redirect()->to('/admin/users')->with('message', 'User account updated successfully.');
    }

    public function delete(int $userId): RedirectResponse
    {
        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $userGroups = $user->getGroups() ?? [];
        if (in_array('admin', $userGroups, true)) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete admin users.');
        }

        $currentUser = auth()->user();
        if ($currentUser !== null && (int) $currentUser->id === $userId) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account.');
        }

        model(UserModel::class)->delete($userId);

        RepositoryServices::analyticsService()->trackCurrentUser(
            'admin.delete_user',
            'admin',
            'user',
            $currentUser === null ? null : (int) ($currentUser->id ?? 0),
        );

        return redirect()->to('/admin/users')->with('message', 'User account deleted successfully.');
    }

    public function role(int $userId): RedirectResponse
    {
        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $newRole = (string) $this->request->getPost('role');
        $validRoles = ['admin', 'employee', 'it_staff'];

        if (! in_array($newRole, $validRoles, true)) {
            return redirect()
                ->back()
                ->with('error', 'Invalid role selected.');
        }

        // Assign new role (using syncGroups which replaces existing roles)
        RepositoryServices::userRepository()->assignGroup($userId, $newRole);

        $currentUser = auth()->user();
        RepositoryServices::analyticsService()->trackCurrentUser(
            'admin.assign_user_role',
            'admin',
            'user',
            $currentUser === null ? null : (int) ($currentUser->id ?? 0),
        );

        return redirect()->to('/admin/users')->with('message', "User role updated to {$newRole}.");
    }

    public function poCreatePermission(int $userId): RedirectResponse
    {
        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $rules = [
            'action' => 'required|in_list[grant,revoke]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $action = (string) $this->request->getPost('action');
        $permission = 'procurement.po.create';

        if ($action === 'grant') {
            if (! $user->hasPermission($permission)) {
                $user->addPermission($permission);
            }
            $message = 'PO creation delegation granted.';
            $event = 'admin.po_create_permission_granted';
        } else {
            if ($user->hasPermission($permission)) {
                $user->removePermission($permission);
            }
            $message = 'PO creation delegation revoked.';
            $event = 'admin.po_create_permission_revoked';
        }

        $currentUser = auth()->user();
        RepositoryServices::analyticsService()->trackCurrentUser(
            $event,
            'admin',
            'user',
            $currentUser === null ? null : (int) ($currentUser->id ?? 0),
            [
                'target_user_id' => $userId,
                'permission' => $permission,
            ],
        );

        return redirect()->to('/admin/users')->with('message', $message);
    }
}

