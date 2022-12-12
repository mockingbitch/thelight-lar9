<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\Interface\OrderRepositoryInterface;
use App\Repositories\Contracts\Interface\OrderDetailRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Repositories\Contracts\Interface\TableRepositoryInterface;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\Contracts\Interface\BillDetailRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use App\Constants\UserConstant;
use App\Constants\TableConstant;
use App\Constants\OrderDetailConstant;
use App\Constants\RouteConstant;

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
     * @var billRepository
     */
    protected $billRepository;

    /**
     * @var billDetailRepository
     */
    protected $billDetailRepository;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderDetailRepositoryInterface $orderDetailRepository
     * @param ProductRepositoryInterface $productRepository
     * @param TableRepositoryInterface $tableRepository
     * @param OrderService $orderService
     * @param BillRepositoryInterface $billRepository
     * @param BillDetailRepositoryInterface $billDetailRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository, 
        OrderDetailRepositoryInterface $orderDetailRepository,
        ProductRepositoryInterface $productRepository,
        TableRepositoryInterface $tableRepository,
        OrderService $orderService,
        BillRepositoryInterface $billRepository,
        BillDetailRepositoryInterface $billDetailRepository
        )
    {
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository =$orderDetailRepository;
        $this->productRepository = $productRepository;
        $this->tableRepository = $tableRepository;
        $this->orderService = $orderService;
        $this->billRepository = $billRepository;
        $this->billDetailRepository = $billDetailRepository;
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
        // if ! isset order of table->id ?? forget all session order
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

    /**
     * @return RedirectResponse
     */
    public function submitOrder() : RedirectResponse
    {
        $order = session()->get('order');
        $user = auth()->guard('user')->user();
        $table = null !== $order ? $this->tableRepository->find(array_key_first($order)) : null;

        if (null === $order 
            || ! isset($order) 
            || null === $user 
            || null === $table
            || (  $user->role !== UserConstant::ROLE['admin'] 
             && $user->role !== UserConstant::ROLE['manager'] 
             && $user->role !== UserConstant::ROLE['waiter'] 
             )
            ) :
            return redirect()
                ->back()
                ->with([
                    'orderErrCode' => 1,
                    'orderErrMsg' => 'Vui lòng thêm sản phẩm vào order'
                ]);
        endif;

        $orderTable = $this->orderRepository->createOrder($table->id, $user, $order);
        if (null !== $orderTable) $total = $this->orderDetailRepository->createOrderDetail($orderTable->id, $order);
        if (null !== $total) $updateTotal = $this->orderRepository->update($orderTable->id, ['total' => $total]);
        if (null !== $updateTotal) session()->forget('order');

        return redirect()
            ->route(RouteConstant::HOME['table_detail'], ['id' => array_key_first($order)]);
    }

    public function remove()
    {
        session()->forget('order');
    }

    /**
     * @param integer|null $id
     * 
     * @return boolean
     */
    public function checkOut(?int $id) : bool
    {
        $order = $this->orderRepository->getTableOrder($id);

        if (null === $order || empty($order)) return false;

        if (! $bill = $this->billRepository->createBill($order)) return false;

        $billDetail = $this->billDetailRepository->createBillDetail($bill, $order);

        if ( $billDetail == false) return false;
       
        if (! $this->orderDetailRepository->deleteByOrderId($order->id)
            || ! $this->tableRepository->update($id, [TableConstant::COLUMN_STATUS => TableConstant::STATUS['empty']])  // update status of table
            || ! $this->orderRepository->delete($order->id)
            )
        {
            return false;
        }

        session()->forget('order');        

        return true;
    }

    /**
     * @param Request $request
     * 
     * @return boolean
     */
    public function updateOrder(Request $request) : bool
    {
        if ($request->query(OrderDetailConstant::COLUMN_QUANTITY) == 0) :
            if (! $this->deleteOrder($request->query(OrderDetailConstant::COLUMN_ID))) :
                return false;
            endif;

            return true;
        endif;

        $orderDetail = $this->orderDetailRepository->find($request->query(OrderDetailConstant::COLUMN_ID));

        if (null === $orderDetail) :
            return false;
        endif;

        $dataUpdate = [
            OrderDetailConstant::COLUMN_QUANTITY => $request->query(OrderDetailConstant::COLUMN_QUANTITY),
            OrderDetailConstant::COLUMN_TOTAL => $request->query(OrderDetailConstant::COLUMN_QUANTITY) * $orderDetail->price,
        ];
        
        if (! $this->orderDetailRepository->update($orderDetail->id, $dataUpdate)
            || ! $this->orderRepository->updateTotal($orderDetail->order_id)
        ) :
            return false;
        endif;

        return true;
    }

    /**
     * @param integer|null $id
     * 
     * @return boolean
     */
    public function deleteOrder(?int $id) : bool
    {
        if (! $this->orderDetailRepository->delete($id)) :
            return false;
        endif;

        return true;
    }
}
