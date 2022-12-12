<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\Order;
use App\Repositories\Contracts\Interface\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Constants\UserConstant;
use App\Constants\OrderConstant;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return Order::class;
    }

    /**
     * @param [type] $user
     * @param array $order
     * 
     * @return object
     */
    public function createOrder($table_id, $user, $order = []) : object
    {
        $orderTable = $this->model->where(OrderConstant::COLUMN_TABLE_ID, $table_id)->first(); //check if exist order table 

        if (null !== $orderTable && ! empty($orderTable)) :
            return $orderTable;
        endif;

        $data = [
            OrderConstant::COLUMN_WAITER_ID => $user->role !== UserConstant::ROLE['guest'] ? $user->id : null,
            OrderConstant::COLUMN_GUEST_ID => $user->role === UserConstant::ROLE['guest'] ? $user->id : null,
            OrderConstant::COLUMN_TABLE_ID => array_key_first($order),
            OrderConstant::COLUMN_TOTAL => 0
        ];

        $orderTable = $this->create($data);

        return $orderTable;
    }

    /**
     * @param integer|null $table_id
     * 
     * @return ?object
     */
    public function getTableOrder(?int $table_id) : ?object
    {
        return $this->model->where(OrderConstant::COLUMN_TABLE_ID, $table_id)->first();
    }

    /**
     * @param integer|null $id
     * 
     * @return boolean
     */
    public function updateTotal(?int $id) : bool
    {
        $order = $this->find($id);
        $orderDetails = $order->orderDetails; //query by relationship
        $total = 0;

        foreach ($orderDetails as $item) :
            $total += $item->total;
        endforeach;

        if (! $this->update($order->id, [OrderConstant::COLUMN_TOTAL => $total])) :
            return false;
        endif;

        return true;
    }
}