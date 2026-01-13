<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'dashboard' => 'm',
            'user-management' => 'm',
            'admin-user' => 'm,c,r,u,d,change_status',
            'general-user' => 'm,c,r,u,d,change_status',
            'roles' => 'c,r,u,d,m,change_permission',
            'permissions' => 'c,r,u,d,m',
            'role-permission' => 'm',
            'tour-management' => 'm',
            'tour' => 'm,c,r,u,d,change_status',
            'tour-schedule' => 'm,c,r,u,d',
            'member-management' => 'm,c,r,u,d',
            'financial-management' => 'm',
            'financial' => 'm',
            'expense' => 'm,c,r,u,d',
            'expense-category' => 'm,c,r,u,d',
            'payment' => 'm,c,r,u,d',
            'settings' => 'm',
        ],
        'user' => [
            'dashboard' => 'm',
            'profile' => 'm,u',
            'tours' => 'm',
            'payment' => 'm,c',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'a' => 'attach',
        'm' => 'menu',
        'f' => 'filter',
        'approve' => 'approve',
        'reject' => 'reject',
        'change_permission' => 'change-permission',
        'change_status' => 'change-status',
    ],
];
