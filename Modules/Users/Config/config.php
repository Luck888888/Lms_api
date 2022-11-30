<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    "users_per_page" => env("USERS_PER_PAGE", 20),

    /*
    |--------------------------------------------------------------------------
    | Roles settings
    |--------------------------------------------------------------------------
    */
    "roles" => [
        "student" => [
            "key"   => "student",
            "label" => __("Student")
        ],
        "teacher" => [
            "key"   => "teacher",
            "label" => __("Teacher")
        ],
        "admin" => [
            "key"   => "administrator",
            "label" => __("Administrator")
        ],
    ],

    "allowed_roles" => [
        "student", "teacher", "administrator"
    ],

    "order_by" => [
        "users" => [
            "full_name", "email", "created_at"
        ],
    ],

    "filters" => [
        'users' => [
            // allowed values true/false/0/1
            'is_archived',
            //Array of roles
            'roles',
            //Array of statuses
            'statuses',
            //Array of curricula
            'curriculums',
            //Array exclude users
            'exclude_users',
            //Array of courses
            'courses',
            //String religion
            'religion',
            //String religion_type
            'religion_type',
            //Array profession
            'professions',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | User sex settings
    |--------------------------------------------------------------------------
    */
    "sex" => [
        "male" => [
            "key"   => "male",
            "label" => __("Male")
        ],
        "female" => [
            "key"   => "female",
            "label" => __("Female")
        ],
    ],

    "allowed_sex" => [
        "male", "female"
    ],

    /*
    |--------------------------------------------------------------------------
    | User statuses
    |--------------------------------------------------------------------------
    */

    "statuses" => [
        "enabled" => [
            "key"   => "enabled",
            "label" => __("Enabled")
        ],
        "disabled" => [
            "key"   => "disabled",
            "label" => __("Disabled")
        ],
        "interested" => [
            "key"   => "interested",
            "label" => __("Interested")
        ],
    ],

    "allowed_statuses" => [
        "enabled", "disabled", "interested"
    ],

    /*
    |--------------------------------------------------------------------------
    | File adapter - path where saving users photo
    |--------------------------------------------------------------------------
    */
    "file_adapter" => "photo",

    /*
    |--------------------------------------------------------------------------
    | Religion
    |--------------------------------------------------------------------------
    */
    "religions" => [
        'judaism', 'christianity', 'islam', 'other'
    ],

    "religion_types" => [
        'orthodox', 'traditional', 'secular', 'other'
    ],

    /*
    |--------------------------------------------------------------------------
    | Export file type
    |--------------------------------------------------------------------------
    */
    "file_format" => [
        'csv', 'xls', ' xlsx', 'ods'
    ],

   /*
   |--------------------------------------------------------------------------
   | Reset password
   |--------------------------------------------------------------------------
   */
    "reset_password" => [
        'expire' => 180
    ]

];
