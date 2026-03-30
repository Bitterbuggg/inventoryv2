<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\UserManagementService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\RepositoryServices;
use DomainException;

class UserController extends BaseController
{
    private ?UserManagementService $userManagement = null;

    public function index(): string|ResponseInterface
    {
        $users = $this->userManagement()->listUsers();

        if ($this->shouldExportCsv()) {
            $rows = [];

            foreach ($users as $user) {
                $groups = $user->getGroups() ?? [];
                $currentRole = 'employee';

                foreach ($this->userManagement()->assignableRoles() as $role) {
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

        return view('admin/users', [
            'users' => $users,
            'modulePermsMap' => $this->userManagement()->moduleBadgePermissions(),
        ]);
    }

    public function create(): string
    {
        return view('admin/create_user', [
            'permissionStructure' => $this->userManagement()->permissionStructure(),
            'rolePresets' => $this->userManagement()->rolePresets(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[users.username]',
            'email' => 'required|valid_email|max_length[254]',
            'password' => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[' . implode(',', $this->userManagement()->roleSelections()) . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $userId = $this->userManagement()->createUser(
                [
                    'username' => (string) $this->request->getPost('username'),
                    'email' => (string) $this->request->getPost('email'),
                    'password' => (string) $this->request->getPost('password'),
                ],
                (string) $this->request->getPost('role'),
                $this->requestPermissions(),
            );

            $user = auth()->user();
            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.create_user',
                'admin',
                'user',
                $user === null ? null : (int) ($user->id ?? 0),
                ['target_user_id' => $userId],
            );

            return redirect()->to('/admin/users')->with('message', 'User account created successfully.');
        } catch (DomainException $e) {
            return $this->redirectForDomainError($e);
        }
    }

    public function edit(int $userId): string|RedirectResponse
    {
        $user = $this->userManagement()->findUser($userId);

        if ($user === null) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        return view('admin/edit_user', [
            'user' => $user,
            'permissionStructure' => $this->userManagement()->permissionStructure(),
        ]);
    }

    public function update(int $userId): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email|max_length[254]',
            'username' => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $this->userManagement()->updateUser(
                $userId,
                (string) $this->request->getPost('username'),
                (string) $this->request->getPost('email'),
                $this->requestPermissions(),
            );

            $currentUser = auth()->user();
            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.update_user',
                'admin',
                'user',
                $currentUser === null ? null : (int) ($currentUser->id ?? 0),
                ['target_user_id' => $userId],
            );

            return redirect()->to('/admin/users')->with('message', 'User account updated successfully.');
        } catch (DomainException $e) {
            return $this->redirectForDomainError($e);
        }
    }

    public function delete(int $userId): RedirectResponse
    {
        $currentUser = auth()->user();

        try {
            $this->userManagement()->deleteUser(
                $userId,
                $currentUser === null ? null : (int) ($currentUser->id ?? 0),
            );

            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.delete_user',
                'admin',
                'user',
                $currentUser === null ? null : (int) ($currentUser->id ?? 0),
                ['target_user_id' => $userId],
            );

            return redirect()->to('/admin/users')->with('message', 'User account deleted successfully.');
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function role(int $userId): RedirectResponse
    {
        $rules = [
            'role' => 'required|in_list[' . implode(',', $this->userManagement()->assignableRoles()) . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $newRole = (string) $this->request->getPost('role');

        try {
            $this->userManagement()->assignRole($userId, $newRole);

            $currentUser = auth()->user();
            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.assign_user_role',
                'admin',
                'user',
                $currentUser === null ? null : (int) ($currentUser->id ?? 0),
                [
                    'target_user_id' => $userId,
                    'role' => $newRole,
                ],
            );

            return redirect()->to('/admin/users')->with('message', "User role updated to {$newRole}.");
        } catch (DomainException $e) {
            return redirect()->to('/admin/users')->with('error', $e->getMessage());
        }
    }

    public function modulePermission(int $userId): RedirectResponse
    {
        $rules = [
            'module' => 'required|in_list[' . implode(',', array_keys($this->userManagement()->modulePermissions())) . ']',
            'action' => 'required|in_list[grant,revoke]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $module = (string) $this->request->getPost('module');
        $action = (string) $this->request->getPost('action');

        try {
            $this->userManagement()->updateModulePermission($userId, $module, $action);

            $currentUser = auth()->user();
            RepositoryServices::analyticsService()->trackCurrentUser(
                'admin.module_permission_changed',
                'admin',
                'user',
                $currentUser === null ? null : (int) ($currentUser->id ?? 0),
                [
                    'target_user_id' => $userId,
                    'module' => $module,
                    'action' => $action,
                ],
            );

            $message = "Module '" . ucfirst($module) . "' access " . ($action === 'grant' ? 'granted' : 'revoked') . '.';

            return redirect()->to('/admin/users')->with('message', $message);
        } catch (DomainException $e) {
            return redirect()->to('/admin/users')->with('error', $e->getMessage());
        }
    }

    private function userManagement(): UserManagementService
    {
        return $this->userManagement ??= RepositoryServices::userManagementService();
    }

    /**
     * @return string[]
     */
    private function requestPermissions(): array
    {
        $permissions = $this->request->getPost('permissions');

        return is_array($permissions) ? array_values($permissions) : [];
    }

    private function redirectForDomainError(DomainException $e): RedirectResponse
    {
        $message = $e->getMessage();

        if ($message === 'User not found.') {
            return redirect()->to('/admin/users')->with('error', $message);
        }

        if ($message === 'Email already exists.') {
            return redirect()->back()->withInput()->with('errors', ['email' => $message]);
        }

        if ($message === 'Username already exists.') {
            return redirect()->back()->withInput()->with('errors', ['username' => $message]);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }
}
