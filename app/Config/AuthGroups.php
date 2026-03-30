<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public string $defaultGroup = 'employee';

    public array $groups = [
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Full system access.',
        ],
        'employee' => [
            'title'       => 'Employee',
            'description' => 'Operational user with limited access.',
        ],
        'it_staff' => [
            'title'       => 'IT Dev/Staff',
            'description' => 'Technical support and read-only operational visibility.',
        ],
    ];

    public array $permissions = [
        'dashboard.view_admin'       => 'Can access admin dashboard',
        'auth.manage_users'          => 'Can manage user roles and assignments',
        'auth.support_users'         => 'Can reset passwords and unlock accounts',
        'procurement.pr.create'      => 'Can create purchase requests',
        'procurement.pr.approve'     => 'Can approve or reject purchase requests',
        'procurement.po.create'      => 'Can generate purchase orders',
        'procurement.por.manage'     => 'Can manage PO request transitions',
        'procurement.view'           => 'Can view purchase requests and orders',
        'receiving.convert'          => 'Can convert approved PO requests to receiving records',
        'receiving.view'             => 'Can view receiving records',
        'inventory.view'             => 'Can view inventory quantities, movements, and issuance records',
        'inventory.quantity.update'  => 'Can post receiving quantities to inventory stocks',
        'inventory.issuance.create'  => 'Can create and submit inventory issuances',
        'inventory.issuance.approve' => 'Can approve, reject, and release issuances',
        'reports.view'               => 'Can view inventory and movement reports',
        'audit.view'                 => 'Can view workflow and audit logs',
        'workflow.cancel_draft'      => 'Can cancel draft records',
        'system.diagnostics'         => 'Can access system health and diagnostics',
    ];

    public array $matrix = [
        'admin' => [
            'dashboard.view_admin',
            'auth.manage_users',
            'procurement.pr.create',
            'procurement.pr.approve',
            'procurement.po.create',
            'procurement.por.manage',
            'procurement.view',
            'receiving.convert',
            'receiving.view',
            'inventory.view',
            'inventory.quantity.update',
            'inventory.issuance.create',
            'inventory.issuance.approve',
            'reports.view',
            'audit.view',
        ],
        'employee' => [
            'procurement.pr.create',
            'procurement.view',
            'inventory.view',
            'inventory.issuance.create',
        ],
        'it_staff' => [
            'auth.support_users',
            'procurement.view',
            'procurement.pr.approve',
            'receiving.convert',
            'receiving.view',
            'inventory.view',
            'inventory.quantity.update',
            'reports.view',
            'audit.view',
            'workflow.cancel_draft',
            'system.diagnostics',
        ],
    ];
}
