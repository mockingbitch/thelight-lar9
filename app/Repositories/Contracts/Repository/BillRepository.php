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
}