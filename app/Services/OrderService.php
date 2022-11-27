<?php
namespace App\Services;

class OrderService
{
    public function createSessionOrder($table, $product, $request)
    {
        $order = session()->get('order');
        $table_id = $table->id;
        $product_id = $product->id;
        
        if (null !== $order && isset($order[$table_id][$product_id])) :
            $order[$table_id][$product_id]['quantity'] += $request['quantity'] ?? 1;
        else :
            $order[$table_id][$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request['quantity'] ?? 1,
                'image' => $product->image
            ];
        endif;
        
        session()->put('order', $order);
        print_r($order);
    }
}