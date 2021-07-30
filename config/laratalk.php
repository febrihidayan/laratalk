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
     * Set attachments
     * --------------------------------------------------------------------------
     * 
     * Add file format or change directory to your liking
     * 
     * Please note the file format does not need to add an image format.
     * Because it will be added automatically, don't duplicate it
     * 
     */
    'attachment' => [
        'folder' => 'attachments',
        'size' => 2048,
        'images' => [
            'gif', 'jpeg', 'jpg', 'png'
        ],
        'files' => [
            'docs', 'mp4', 'pdf', 'rar', 'txt', 'zip'
        ],
    ],

    /**
     * --------------------------------------------------------------------------
     * Group features
     * --------------------------------------------------------------------------
     * 
     * Set group configuration both from max participants and group features
     * 
     */
    'group' => [
        'avatar' => '',
        'feature' => false,
        'max_recipient' => 300
    ]
];