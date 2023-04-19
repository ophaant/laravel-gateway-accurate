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
            'dashboard' => 'r',
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'permissions' => 'c,r,u,d',
            'access' => 'c,r,u,d',
            'journal_vouher_uploads' => 'c,r,d',
            'customers' => 'c,r,u',
            'employees' => 'c,r,u',
            'items' => 'c,r,u',
            'sales_invoices' => 'c,r,u',
            'banks' => 'c,r,u,d',
            'category_banks' => 'c,r,u,d',
            'databases' => 'c,r,u',
            'sessions' => 'c,r,u',
        ],
        'office' => [
            'dashboard' => 'r',
            'journal_vouher_uploads' => 'c,r,d',
            'customers' => 'c,r,u',
            'employees' => 'c,r,u',
            'items' => 'c,r,u',
            'sales_invoices' => 'c,r,u',
            'banks' => 'c,r,u,d',
            'category_banks' => 'c,r,u,d',
            'databases' => 'c,r,u',
            'sessions' => 'c,r,u',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
