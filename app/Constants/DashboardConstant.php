<?php

namespace App\Constants;

class DashboardConstant
{
    public const BREADCRUMB_DASHBOARD   = 'adminhome';
    public const BREADCRUMB_CATEGORY    = 'category';
    public const BREADCRUMB_TABLE       = 'table';
    public const BREADCRUMB_PRODUCT     = 'product';
    public const BREADCRUMB_BILL        = 'bill';

    public const BREADCRUMB = [
        'home'      => self::BREADCRUMB_DASHBOARD,
        'category'  => self::BREADCRUMB_CATEGORY,
        'table'     => self::BREADCRUMB_TABLE,
        'product'   => self::BREADCRUMB_PRODUCT,
        'bill'      => self::BREADCRUMB_BILL
    ];
}