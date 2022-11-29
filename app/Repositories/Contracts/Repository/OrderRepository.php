<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\Order;
use App\Repositories\Contracts\Interface\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Constants\UserConstant;

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
        $orderTable = $this->model->where('table_id', $table_id)->first(); //check if exist order table 

        if (null !== $orderTable && ! empty($orderTable)) :
            return $orderTable;
        endif;

        $data = [
            'waiter_id' => $user->role !== UserConstant::ROLE['guest'] ? $user->id : null,
            'guest_id' => $user->role === UserConstant::ROLE['guest'] ? $user->id : null,
            'table_id' => array_key_first($order),
            'total' => 0
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
        return $this->model->where('table_id', $table_id)->first();
    }
}