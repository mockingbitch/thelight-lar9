<?php

namespace App\Constants;

class OrderConstant
{
    public const BREADCRUMB         = 'order';

    public const COLUMN_ID          = 'id';
    public const COLUMN_WAITER_ID   = 'waiter_id';
    public const COLUMN_GUEST_ID    = 'guest_id';
    public const COLUMN_TABLE_ID    = 'table_id';
    public const COLUMN_TOTAL       = 'total';
    public const COLUMN_NOTE        = 'note';
    public const COLUMN_STATUS      ='status';
    public const COLUMN_CREATED_AT  = 'created_at';
    public const COLUMN_UPDATED_AT  = 'updated_at';
    public const COLUMN_DELETED_AT  = 'deleted_at';
    
    public const COLUMN = [
        'id'            => self::COLUMN_ID,
        'waiter_id'     => self::COLUMN_WAITER_ID,
        'guest_id'      => self::COLUMN_GUEST_ID,
        'table_id'      => self::COLUMN_TABLE_ID,
        'total'         => self::COLUMN_TOTAL,
        'note'          => self::COLUMN_NOTE,
        'status'        => self::COLUMN_STATUS,
        'created_at'    => self::COLUMN_CREATED_AT,
        'updated_at'    => self::COLUMN_UPDATED_AT,
        'deleted_at'    => self::COLUMN_DELETED_AT
    ];

    public const STATUS_PENDING     = 0;
    public const STATUS_DONE        = 1;
    public const STATUS_DELIVERED   = 2;
    public const STATUS_CANCEL      = 3;

    public const STATUS = [
        'pending'   => self::STATUS_PENDING,
        'done'      => self::STATUS_DONE,
        'delivered' => self::STATUS_DELIVERED,
        'cancel'    => self::STATUS_CANCEL  
    ];
}