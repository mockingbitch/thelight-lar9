<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\Bill;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Constants\BillConstant;
use App\Constants\Constant;

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
            BillConstant::COLUMN['waiter_id']   => $order->waiter_id,
            BillConstant::COLUMN['guest_id']    => $order->guest_id,
            BillConstant::COLUMN['table_id']    => $order->table_id,
            BillConstant::COLUMN['total']       => $order->total,
            BillConstant::COLUMN['note']        => $order->note,
            BillConstant::COLUMN['status']      => $order->status,
        ];

        if (! $bill = $this->create($data)) return null;

        return $bill;
    }

    /**
     * @return array
     */
    public function getLastTenDays() : array
    {
        $bills      = [];
        $arrDays    = [];

        for ($i = 9; $i >= 0; $i--) :
            $day        = date(Constant::DATE_FORMAT_YMD, strtotime('now - '. $i . 'day'));
            $bills[]    = $this->model->whereDate(BillConstant::COLUMN_CREATED_AT, $day)->sum(BillConstant::COLUMN_TOTAL);
            $arrDays[]  = 'N' . date('d', strtotime('now - '. $i . 'day'));
        endfor;

        return [
            'days'  => $arrDays,
            'total' => $bills
        ];
    }

    /**
     * @return array
     */
    public function getLastTwelveMonth() : array
    {
        $bills      = [];
        $arrMonth   = [];

        for ($i = 9; $i >= 0; $i--) :
            $month      = date('m', strtotime('now - '. $i . 'month'));
            $bills[]    = $this->model->whereMonth(BillConstant::COLUMN_CREATED_AT, $month)->sum(BillConstant::COLUMN_TOTAL);
            $arrMonth[] = 'T' . date('m', strtotime('now - '. $i . 'month'));
        endfor;

        return [
            'months'    => $arrMonth,
            'total'     => $bills
        ];
    }

    /**
     * @return array
     */
    public function getTenDaysLastMonth() : array
    {
        $bills      = [];
        $arrDays    = [];
        $month      = date(Constant::DATE_FORMAT_YMD, strtotime('now - 1 month'));
        for ($i = 9; $i >= 0; $i--) :
            $day        = date(Constant::DATE_FORMAT_YMD, strtotime($month. '- '. $i . 'day'));
            $bills[]    = $this->model->whereDate(BillConstant::COLUMN_CREATED_AT, $day)->sum(BillConstant::COLUMN_TOTAL);
            $arrDays[]  = 'N' . date('d', strtotime($month. '- '. $i . 'day'));
        endfor;

        return [
            'days'  => $arrDays,
            'total' => $bills
        ];
    }

    /**
     * @return array
     */
    public function getCurrentDay() : array
    {
        $day        = date(Constant::DATE_FORMAT_YMD, strtotime('now'));
        $lastDay    = date(Constant::DATE_FORMAT_YMD, strtotime('now - 1 day'));
        $bills       = $this->model->whereDate(BillConstant::COLUMN_CREATED_AT, $day);
        $oldBills    = $this->model->whereDate(BillConstant::COLUMN_CREATED_AT, $lastDay);

        $incomeDifferentialPercent  = ($bills->sum(BillConstant::COLUMN_TOTAL) !== $oldBills->sum(BillConstant::COLUMN_TOTAL)) ? (($bills->sum(BillConstant::COLUMN_TOTAL) - $oldBills->sum(BillConstant::COLUMN_TOTAL)) / $oldBills->sum(BillConstant::COLUMN_TOTAL)) * 100 : 0;
        $billCountDifferential      = (count($bills->get()) !== count($oldBills->get())) ? ((count($bills->get()) - count($oldBills->get())) / count($oldBills->get())) * 100 : 0;

        // foreach ($bills)

        return [
            'income'    => [
                'today'     => $bills->sum(BillConstant::COLUMN_TOTAL),
                'lastDay'   => $oldBills->sum(BillConstant::COLUMN_TOTAL)
            ],
            'count'     => [
                'today'     => count($bills->get()),
                'lastDay'   => count($oldBills->get())
            ],
            'percent'   => [
                'income'    => (int) $incomeDifferentialPercent,
                'bill'      => (int) $billCountDifferential
            ]
        ];
    }
}