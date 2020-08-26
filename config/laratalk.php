<?php

return [

    /**
     * --------------------------------------------------------------------------
     * App Name
     * --------------------------------------------------------------------------
     * 
     * Sesuaikan nama aplikasi yang akan ditampilkan atau biarkan secara default
     * 
     */
    'name' => env('LARATALK_NAME', 'Laratalk'),

    /**
     * --------------------------------------------------------------------------
     * Base Route
     * --------------------------------------------------------------------------
     * 
     * Laratalk dapat di akses dengan routing laratalk, Anda bisa menyesuaikan
     * dengan routing kesukaan Anda atau biarkan ini secara default
     * 
     */
    'path' => env('LARATALK_PATH', 'laratalk'),

    /**
     * --------------------------------------------------------------------------
     * Route Middleware
     * --------------------------------------------------------------------------
     * 
     * Middleware ini akan digunakan di semua routing Laratalk, Anda bisa mengubah
     * salah satunya atau menambahkan middleware yang telah Anda buat.
     * 
     * Ingat! biarkan middleware auth tetap ada, jika di hapus Laratalk tidak
     * berkerja dengan baik
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