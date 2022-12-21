<?php

namespace App\Repositories\Contracts\Interface;

use App\Repositories\BaseRepositoryInterface;

interface BillRepositoryInterface extends BaseRepositoryInterface
{
    public function createBill(? object $order);
    public function getLastTenDays();
    public function getTenDaysLastMonth();
    public function getLastTwelveMonth();
    public function getCurrentDay();
}