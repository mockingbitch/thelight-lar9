<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\Interface\OrderRepositoryInterface;
use App\Repositories\Contracts\Interface\OrderDetailRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Repositories\Contracts\Interface\TableRepositoryInterface;
use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * @var orderRepository
     */
    protected $orderRepository;

    /**
     * @var orderDetailRepository
     */
    protected $orderDetailRepository;

    /**
     * @var productRepository
     */
    protected $productRepository;

    /**
     * @var tableRepository
     */
    protected $tableRepository;

    /**
     * @var orderService
     */
    protected $orderService;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderDetailRepositoryInterface $orderDetailRepository
     * @param ProductRepositoryInterface $productRepository
     * @param TableRepositoryInterface $tableRepository
     * @param OrderService $orderService
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository, 
        OrderDetailRepositoryInterface $orderDetailRepository,
        ProductRepositoryInterface $productRepository,
        TableRepositoryInterface $tableRepository,
        OrderService $orderService
        )
    {
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository =$orderDetailRepository;
        $this->productRepository = $productRepository;
        $this->tableRepository = $tableRepository;
        $this->orderService = $orderService;
    }

    /**
     * @param Request $request
     * 
     * @return void
     */
    public function createSessionOrder(Request $request)
    {
        $table   = $this->tableRepository->find($request->query('table'));
        $product = $this->productRepository->find($request->query('product'));

        if (null === $table || null === $product) :
            return false;
        endif;

        $order = session()->get('order');
        //if ! isset order of table->id ?? forget all session order
        if (! isset($order[$table->id])) :
            session()->forget('order');
        endif;

        $this->orderService->createSessionOrder($table, $product, $request->toArray());

        return true;
    }

    public function updateSessionOrder(Request $request)
    {

    }

    public function deleteSessionOrder(Request $request)
    {

    }

    public function submitOrder()
    {
        $order = session()->get('order');
        
        if (null === $order || ! isset($order)) :
            return redirect()->back()->with([
                'errCode' => 1,
                'errMsg' => 'Vui lòng thêm sản phẩm vào order'
            ]);
        endif;
    }
}
