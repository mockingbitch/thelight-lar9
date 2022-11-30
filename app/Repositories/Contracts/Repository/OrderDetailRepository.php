<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\OrderDetail;
use App\Repositories\Contracts\Interface\OrderDetailRepositoryInterface;
use App\Repositories\BaseRepository;

class OrderDetailRepository extends BaseRepository implements OrderDetailRepositoryInterface
{
    public function getModel()
    {
        return OrderDetail::class;
    }

    /**
     * this function will return subtotal of order
     * @param integer|null $order_id
     * @param array $order
     * 
     * @return void
     */
    public function createOrderDetail(?int $order_id, $order = [])
    {
        $table = array_key_first($order);
        $orderItems = $order[$table]; //remove index table to get list items
        $subTotal = 0;

        foreach ($orderItems as $item) :
            $existedProduct = $this->model                  //check exist product in order
                ->where('product_id', $item['id'])
                ->where('order_id', $order_id)
                ->first();

            if (null !== $existedProduct) :  //increase number of quantity existed product
                $this->update($existedProduct->id, [
                    'total'=> $existedProduct->total + ($item['quantity'] * $item['price']),
                    'quantity' => $existedProduct->quantity + $item['quantity'],
                    'note' => $existedProduct->note . '<br/>' . $item['note']
                ]);
            else :
                $data = [
                    'product_id' => $item['id'],
                    'order_id' => $order_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                    'note' => $item['note'] ?? null
                ];

                if (! $this->create($data)) return 0;
            endif;
        endforeach;

        $arrayOrder = $this->model->where('order_id', $order_id)->get();
        
        foreach ($arrayOrder as $item) :
            $total = (int) $item->price * (int) $item->quantity;
            $subTotal += $total;
        endforeach;

        return $subTotal;
    }
}