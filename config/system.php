<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Username
    |--------------------------------------------------------------------------
    |
    | The username for the system user. This is used when seeding the database
    | with a default system user. Ensure this is set in your environment file
    | before running the seeder, especially in non-production environments.
    |
    */

    'name' => env('SYSTEM_USERNAME', 'System'),

    /*
    |--------------------------------------------------------------------------
    | System Email
    |--------------------------------------------------------------------------
    |
    | The email address for the system user. This will be used as the unique
    | identifier for the system user in the database. Ensure this email is set
    | correctly in your environment file.
    |
    */

    'email' => env('SYSTEM_EMAIL', 'System@null.dev'),

    /*
    |--------------------------------------------------------------------------
    | System Password
    |--------------------------------------------------------------------------
    |
    | The password for the system user. This is used when seeding the database
    | with a default system user. Ensure this is set in your environment file
    | to securely seed the user.
    |
    */

    'password' => env('SYSTEM_PASSWORD'),

];
