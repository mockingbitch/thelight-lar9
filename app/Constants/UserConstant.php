<?php

namespace App\Constants;

class UserConstant
{
    public const COLUMN_ID                  = 'id';
    public const COLUMN_NAME                = 'name';
    public const COLUMN_EMAIL               = 'email';
    public const COLUMN_EMAIL_VERIFIED_AT   = 'email_verified_at';
    public const COLUMN_PASSWORD            = 'password';
    public const COLUMN_REMEMBER_TOKEN      = 'remember_token';
    public const COLUMN_CREATED_AT          = 'created_at';
    public const COLUMN_UPDATED_AT          = 'updated_at';
    public const COLUMN_DELETED_AT          = 'deleted_at';

    public const COLUMN = [
        'id'                => 'id',
        'name'              => 'name',
        'email'             => 'email',
        'email_verified_at' => 'email_verified_at',
        'password'          => 'password',
        'created_at'        => 'created_at',
        'updated_at'        => 'updated_at',
        'deleted_at'        => 'deleted_at'
    ];

    public const ROLE_GUEST     = 'ROLE_GUEST';
    public const ROLE_ADMIN     = 'ROLE_ADMIN';
    public const ROLE_MANAGER   = 'ROLE_MANAGER';
    public const ROLE_WAITER    = 'ROLE_WAITER';

    public const ROLE = [
        'guest'     => self::ROLE_GUEST,
        'admin'     => self::ROLE_ADMIN,
        'manager'   => self::ROLE_MANAGER,
        'waiter'    => self::ROLE_WAITER
    ];

    public const GENDER_MALE    = 'MALE';
    public const GENDER_FEMALE  = 'FEMALE';
    public const GENDER_OTHER   = 'OTHER';

    public const GENDER = [
        'male'      => self::GENDER_MALE,
        'female'    => self::GENDER_FEMALE,
        'other'     => self::GENDER_OTHER
    ];
}