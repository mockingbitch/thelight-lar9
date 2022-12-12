<?php

namespace App\Repositories\Contracts\Repository;

use App\Models\OrderDetail;
use App\Repositories\Contracts\Interface\OrderDetailRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Constants\OrderConstant;

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
            $existedPendingProduct = null;
            $existedProduct = $this->model                  //check exist product in order
                ->where('product_id', $item['id'])
                ->where('order_id', $order_id)
                ->get();

            if ( null !== $existedProduct) :
                foreach ($existedProduct as $itemProduct) :
                    if (null !== $itemProduct && $itemProduct->status === OrderConstant::STATUS['pending']) :  //check last pending product
                        $existedPendingProduct = $itemProduct;
                    endif;
                endforeach;
            endif;

            if (null !== $existedPendingProduct and $existedPendingProduct->status === OrderConstant::STATUS['pending']) :  //increase number of quantity existed pending product
                $this->update($existedPendingProduct->id, [
                    'total'=> $existedPendingProduct->total + ($item['quantity'] * $item['price']),
                    'quantity' => $existedPendingProduct->quantity + $item['quantity'],
                    'note' => $existedPendingProduct->note ? $existedPendingProduct->note . '<br/>' . $item['note'] : $item['note']
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

    /**
     * @param integer|null $order_id
     * 
     * @return boolean
     */
    public function deleteByOrderId(?int $order_id) : bool
    {
        $result = $this->model->where('order_id', $order_id)->get();
        
        foreach ($result as $item) :
            if($item){
                if (! $item->delete()) return false;
            }    
        endforeach;

        return true;
    }
}