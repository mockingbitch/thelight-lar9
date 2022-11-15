<?php

namespace App\Constants;

class Constant
{
    public const ERR_CODE_FAIL = 0;
    public const ERR_CODE_SUCCESS = 1;

    public const ERR_CODE = [
        'fail'    => self::ERR_CODE_FAIL,
        'success' => self::ERR_CODE_SUCCESS
    ];

    public const ERR_MSG_CREATE_FAIL = 'Thêm mới thất bại';
    public const ERR_MSG_CREATE_SUCCESS = 'Thêm mới thành công';
    public const ERR_MSG_UPDATE_FAIL = 'Cập nhật thất bại';
    public const ERR_MSG_UPDATE_SUCCESS = 'Thêm mới thành công';
    public const ERR_MSG_DELETE_FAIL = 'Cập nhật thất bại';
    public const ERR_MSG_DELETE_SUCCESS = 'Thêm mới thành công';
    
    public const ERR_MSG = [
        'create_fail'    => self::ERR_MSG_CREATE_FAIL,
        'create_success' => self::ERR_MSG_CREATE_SUCCESS,
        'update_fail'    => self::ERR_MSG_UPDATE_FAIL,
        'update_success' => self::ERR_MSG_UPDATE_SUCCESS,
        'delete_fail'    => self::ERR_MSG_DELETE_FAIL,
        'delete_success' => self::ERR_MSG_DELETE_SUCCESS
    ];

    public const STATUS_AVAILABLE = 'AVAILABLE';
    public const STATUS_UNAVAILABLE = 'UNAVAILABLE';

    public const STATUS = [
        'available' => self::STATUS_AVAILABLE,
        'unavailable' => self::STATUS_UNAVAILABLE
    ];
}   