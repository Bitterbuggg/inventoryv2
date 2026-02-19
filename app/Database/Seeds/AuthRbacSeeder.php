<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AuthRbacSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsertUser(
            username: 'admin',
            email: 'admin@local.test',
            password: 'Admin@1234',
            group: 'admin',
            permissions: ['dashboard.view_admin', 'auth.manage_users'],
        );

        $this->upsertUser(
            username: 'employee',
            email: 'employee@local.test',
            password: 'Employee@1234',
            group: 'employee',
        );

        $this->upsertUser(
            username: 'itstaff',
            email: 'itstaff@local.test',
            password: 'Itstaff@1234',
            group: 'it_staff',
        );
    }

    /**
     * @param string[] $permissions
     */
    private function upsertUser(
        string $username,
        string $email,
        string $password,
        string $group,
        array $permissions = [],
    ): void {
        /** @var UserModel $users */
        $users = model(UserModel::class);
        $user  = $users->findByCredentials(['email' => strtolower($email)]);

        if (! $user instanceof User) {
            $user = $users->createNewUser([
                'username' => $username,
                'email'    => strtolower($email),
                'password' => $password,
                'active'   => 1,
            ]);
            $users->save($user);
            $user = $users->findByCredentials(['email' => strtolower($email)]);
        }

        if (! $user instanceof User) {
            return;
        }

        if (! $user->inGroup($group)) {
            $user->addGroup($group);
        }

        foreach ($permissions as $permission) {
            if (! $user->hasPermission($permission)) {
                $user->addPermission($permission);
            }
        }
    }
}
