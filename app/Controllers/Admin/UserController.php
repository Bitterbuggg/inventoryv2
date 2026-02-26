<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserModel;
use Config\RepositoryServices;

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

    public function assignRole(int $userId): RedirectResponse
    {
        $rules = [
            'role' => 'required|in_list[admin,employee,it_staff]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        RepositoryServices::userRepository()->assignGroup(
            $userId,
            (string) $this->request->getPost('role'),
        );

        return redirect()->to('/admin/users')->with('message', 'Role assignment updated.');
    }
}

