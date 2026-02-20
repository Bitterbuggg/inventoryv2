<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'employee';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     *
     * @var array<string, array<string, string>>
     */
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
            'description' => 'Technical support and controlled administrative access.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     */
    public array $permissions = [
        'dashboard.view_admin'       => 'Can access admin dashboard',
        'auth.manage_users'          => 'Can manage user roles and assignments',
        'procurement.pr.create'      => 'Can create purchase requests',
        'procurement.pr.approve'     => 'Can approve or reject purchase requests',
        'procurement.po.create'      => 'Can generate purchase orders from approved requests',
        'procurement.por.manage'     => 'Can manage PO request transitions',
        'receiving.convert'          => 'Can convert approved PO requests to receiving records',
        'inventory.quantity.update'  => 'Can post receiving quantities to inventory stocks',
        'inventory.issuance.create'  => 'Can create and submit inventory issuances',
        'inventory.issuance.approve' => 'Can approve, reject, and release issuances',
        'reports.view'               => 'Can view inventory and movement reports',
        'audit.view'                 => 'Can view workflow and audit logs',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     */
    public array $matrix = [
        'admin' => [
            'dashboard.view_admin',
            'auth.manage_users',
            'procurement.pr.create',
            'procurement.pr.approve',
            'procurement.po.create',
            'procurement.por.manage',
            'receiving.convert',
            'inventory.quantity.update',
            'inventory.issuance.create',
            'inventory.issuance.approve',
            'reports.view',
            'audit.view',
        ],
        'employee' => [
            'procurement.pr.create',
            'inventory.issuance.create',
        ],
        'it_staff' => [
            'procurement.pr.create',
            'procurement.pr.approve',
            'procurement.po.create',
            'procurement.por.manage',
            'receiving.convert',
            'inventory.quantity.update',
            'inventory.issuance.create',
            'inventory.issuance.approve',
            'reports.view',
            'audit.view',
        ],
    ];
}

