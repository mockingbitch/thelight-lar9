<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\Bill;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Constants\BillConstant;

class BillRepository extends BaseRepository implements BillRepositoryInterface
{
    public function getModel()
    {
        return Bill::class;
    }

    /**
     * @param object|null $order
     *
     * @return Bill|null
     */
    public function createBill(? object $order) : ?Bill
    {
        $data = [
            BillConstant::COLUMN['waiter_id'] => $order->waiter_id,
            BillConstant::COLUMN['guest_id'] => $order->guest_id,
            BillConstant::COLUMN['table_id'] => $order->table_id,
            BillConstant::COLUMN['total'] => $order->total,
            BillConstant::COLUMN['note'] => $order->note,
            BillConstant::COLUMN['status'] => $order->status,
        ];

        if (! $bill = $this->create($data)) return null;

        return $bill;
    }

    /**
     * @return array
     */
    public function getLastTenDays() : array
    {
        $bills = [];
        $arrDays = [];

        for ($i = 9; $i >= 0; $i--) :
            $day = date('Y-m-d', strtotime('now - '. $i . 'day'));
            $bills[] = $this->model->whereDate('created_at', $day)->sum('total');
            $arrDays[] = 'N' . date('d', strtotime('now - '. $i . 'day'));
        endfor;

        return [
            'days' => $arrDays,
            'total' => $bills
        ];
    }

    /**
     * @return array
     */
    public function getLastTwelveMonth() : array
    {
        $bills = [];
        $arrMonth = [];

        for ($i = 9; $i >= 0; $i--) :
            $month = date('m', strtotime('now - '. $i . 'month'));
            $bills[] = $this->model->whereMonth('created_at', $month)->sum('total');
            $arrMonth[] = 'T' . date('m', strtotime('now - '. $i . 'month'));
        endfor;

        return [
            'months' => $arrMonth,
            'total' => $bills
        ];
    }

    /**
     * @return array
     */
    public function getTenDaysLastMonth() : array
    {
        $bills = [];
        $arrDays = [];
        $month = date('Y-m-d', strtotime('now - 1 month'));
        for ($i = 9; $i >= 0; $i--) :
            $day = date('Y-m-d', strtotime($month. '- '. $i . 'day'));
            $bills[] = $this->model->whereDate('created_at', $day)->sum('total');
            $arrDays[] = 'N' . date('d', strtotime($month. '- '. $i . 'day'));
        endfor;

        return [
            'days' => $arrDays,
            'total' => $bills
        ];
    }
}