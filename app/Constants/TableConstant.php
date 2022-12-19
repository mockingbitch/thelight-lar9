<?php

namespace App\Constants;

class TableConstant
{
    public const BREADCRUMB         = 'table';
    public const ERR_MSG_NOT_FOUND  = 'Không tìm thấy bàn';

    public const COLUMN_ID          = 'id';
    public const COLUMN_NAME        = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_STATUS      = 'status';
    public const COLUMN_CREATED_AT  = 'created_at';
    public const COLUMN_UPDATED_AT  = 'updated_at';
    public const COLUMN_DELETED_AT  = 'deleted_at';

    public const COLUMN = [
        'id'            => self::COLUMN_ID,
        'name'          => self::COLUMN_NAME,
        'description'   => self::COLUMN_DESCRIPTION,
        'status'        => self::COLUMN_STATUS,
        'created_at'    => self::COLUMN_CREATED_AT,
        'updated_at'    => self::COLUMN_UPDATED_AT,
        'deleted_at'    => self::COLUMN_DELETED_AT,
    ];

    public const STATUS_EMPTY       = '0';
    public const STATUS_PENDING     = '1';
    public const STATUS_ON_DELIVERY = '2';
    public const STATUS_DELIVERED   = '3';

    public const STATUS = [
        'pending'       => self::STATUS_PENDING,
        'on_delivery'   => self::STATUS_ON_DELIVERY,
        'delivered'     => self::STATUS_DELIVERED,
        'empty'         => self::STATUS_EMPTY,
    ];
}