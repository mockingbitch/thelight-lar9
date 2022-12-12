<?php

namespace App\Constants;

class BillDetailConstant
{
    public const BREADCRUMB = 'billdetail';
    public const ERR_MSG_NOT_FOUND = 'Không tìm thấy chi tiết hóa đơn';

    public const COLUMN_ID = 'id';
    public const COLUMN_BILL_ID = 'bill_id';
    public const COLUMN_PRODUCT_NAME = 'product_name';
    public const COLUMN_QUANTITY = 'quantity';
    public const COLUMN_PRICE = 'price';
    public const COLUMN_TOTAL = 'total';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
    public const COLUMN_DELETED_AT = 'deleted_at';

    public const COLUMN = [
        'id' => self::COLUMN_ID,
        'bill_id' => self::COLUMN_BILL_ID,
        'product_name' => self::COLUMN_PRODUCT_NAME,
        'quantity' => self::COLUMN_QUANTITY,
        'price' => self::COLUMN_PRICE,
        'total' => self::COLUMN_TOTAL,
        'created_at' => self::COLUMN_CREATED_AT,
        'updated_at' => self::COLUMN_UPDATED_AT,
        'deleted_at' => self::COLUMN_DELETED_AT,
    ];
}