<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Models\UserModel;
use Config\RepositoryServices;

class UserController extends BaseController
{
    public function index(): string
    {
        $users = model(UserModel::class)->withGroups()->findAll();

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

