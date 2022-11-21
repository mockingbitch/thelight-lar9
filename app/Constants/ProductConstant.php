<?php

namespace App\Constants;

class ProductConstant
{
    public const BREADCRUMB = 'product';
    public const ERR_MSG_NOT_FOUND = 'Không tìm thấy sản phẩm';
    public const ERR_MSG_CATEGORY_NOT_FOUND = 'Chưa có danh mục';
    public const ERR_MSG_CANT_PROCESS_IMAGE = 'Chưa có danh mục';
    public const IMAGE_FOLDER = 'products';

    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_STATUS = 'status';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
    public const COLUMN_DELETED_AT = 'deleted_at';

    public const COLUMN = [
        'id' => self::COLUMN_ID,
        'name' => self::COLUMN_NAME,
        'description' => self::COLUMN_DESCRIPTION,
        'status' => self::COLUMN_STATUS,
        'created_at' => self::COLUMN_CREATED_AT,
        'updated_at' => self::COLUMN_UPDATED_AT,
        'deleted_at' => self::COLUMN_DELETED_AT,
    ];
}