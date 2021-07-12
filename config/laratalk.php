<?php

return [

    /**
     *--------------------------------------------------------------------------
     * App Name
     * --------------------------------------------------------------------------
     * 
     * Customize the name of the application to be displayed or leave it by
     * default
     * 
     */
    'name' => env('LARATALK_NAME', 'Laratalk'),

    /**
     * --------------------------------------------------------------------------
     * Base Route
     * --------------------------------------------------------------------------
     * 
     * Laratalk can be accessed with laratalk routing, you can adjust to your
     * favorite routing or leave it by default
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
     * Remember! leave the auth middleware still there, if it is removed
     * Laratalk doesn't work properly
     * 
     */
    'middleware' => [
        'web',
        'auth',
    ],

    /**
     * --------------------------------------------------------------------------
     * User Profile
     * --------------------------------------------------------------------------
     * 
     * Jadi Anda bisa menyesuaikan isi profile yang akan di tampilkan di bila
     * user profile. Aturan main cukup mudah perhatikan dan amati perintah ini
     * 
     * misalkan Anda ingin menambah field role, cukup lakukan ini
     * 'role' => 'Role User'
     * 
     * key array -> field user
     * value array -> label user
     * 
     */
    'users' => [
        'email' => 'Alamat Email'
    ]
];