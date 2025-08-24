<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Nama aplikasi, digunakan pada notifikasi, judul halaman, dsb.
    |
    */
    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | Environment aplikasi: local, production, staging, dll.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | Jika true akan tampilkan error detail. Jika false, tampilkan error generic.
    |
    */
    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | URL dasar aplikasi. Digunakan untuk generate link via artisan & notifikasi.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),

    // login admin 
     'admin_prefix' => env('ADMIN_PREFIX', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Default timezone aplikasi. Bisa diubah di .env (APP_TIMEZONE).
    |
    */
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | Default locale aplikasi. Bisa diubah di .env (APP_LOCALE).
    | Fallback locale digunakan jika terjemahan utama tidak ditemukan.
    |
    */
    'locale' => env('APP_LOCALE', 'id'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | Kunci enkripsi Laravel. Harus 32 karakter random. Set di .env (APP_KEY).
    |
    */
    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Driver untuk maintenance mode: file atau cache.
    |
    */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
