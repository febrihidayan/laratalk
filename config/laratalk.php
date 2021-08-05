<?php

return [

    /**
     *--------------------------------------------------------------------------
     * App Name
     * --------------------------------------------------------------------------
     * 
     * Customize the name of the application to be displayed or leave it by
     * default.
     * 
     */
    'name' => env('LARATALK_NAME', 'Laratalk'),

    /**
     * --------------------------------------------------------------------------
     * Base Route
     * --------------------------------------------------------------------------
     * 
     * Laratalk can be accessed with laratalk routing, you can adjust to your
     * favorite routing or leave it by default.
     * 
     */
    'path' => env('LARATALK_PATH', 'laratalk'),

    /**
     * --------------------------------------------------------------------------
     * Route Middleware
     * --------------------------------------------------------------------------
     * 
     * This middleware will be used in all Laratalk routing, you can change any
     * of them or add middleware that you have created.
     * 
     * Remember! leave the auth middleware still there, if it is removed.
     * Laratalk doesn't work properly.
     * 
     */
    'middleware' => [
        'web',
        'auth',
    ],

    /**
     * --------------------------------------------------------------------------
     * Laratalk User Configuration
     * --------------------------------------------------------------------------
     * 
     * Set user configuration from Gravatar avatar, model, table name,
     * to migration to add column to users table, and provide relationship
     * to all laratalk tables.
     * 
     */
    'users' => [
        /**
         * By default it will use the avatar from Gravatar, it will
         * ignore the avatar field from the user table.
         */
        'gravatar' => env('LARATALK_USERS_GRAVATAR', true),

        'model' => \App\Models\User::class,

        'migration' => [
            'table' => 'users',

            'columns' => [
                'avatar' => 'avatar',
                'bio' => 'bio',
                'dark_mode' => 'dark_mode',
                'locale' => 'locale',
            ],

            /**
             * Please set this configuration according to the primary key
             * of your users table. This will be used in all Laratalk tables
             * for relationships between tables.
             */
            'foreign_key' => [
                'type' => 'unsignedBigInteger',
                'length' => null
            ]
        ],
    ],

    /**
     * --------------------------------------------------------------------------
     * Laratalk Tables
     * --------------------------------------------------------------------------
     * 
     * These are all tables used by Laratalk to store all authorization data.
     * 
     */
    'tables' => [
        'groups' => 'groups',

        'group_user' => 'group_user',

        'messages' => 'messages',

        'message_recipient' => 'message_recipient',
    ],

    /**
     * --------------------------------------------------------------------------
     * Storage
     * --------------------------------------------------------------------------
     * 
     * Please note the file format does not need to add an image format.
     * Because it will be added automatically, don't duplicate it.
     * 
     */
    'storage' => [
        'disk' => env('LARATALK_STORAGE_DISK', 'local'),

        'path' => env('LARATALK_STORAGE_PATH', 'public/laratalk'),

        'images' => [
            'format' => [
                'gif', 'jpeg', 'jpg', 'png'
            ],
            'size' => 1024
        ],

        'files' => [
            'format' => [
                'docs', 'mp4', 'pdf', 'rar', 'txt', 'zip',
            ],
            'size' => 30720
        ],
    ],

    /**
     * --------------------------------------------------------------------------
     * Group
     * --------------------------------------------------------------------------
     * 
     * Set group configuration both from max participants and group features
     * 
     */
    'group' => [
        'enabled' => env('LARATALK_GROUP_ENABLED', false),
        'participant' => env('LARATALK_GROUP_PARTICIPANT', 300)
    ]
];