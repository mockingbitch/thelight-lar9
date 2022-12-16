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
use App\Constants\ProductConstant;

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
        $this->orderRepository          = $orderRepository;
        $this->orderDetailRepository    = $orderDetailRepository;
        $this->productRepository        = $productRepository;
        $this->tableRepository          = $tableRepository;
        $this->orderService             = $orderService;
        $this->billRepository           = $billRepository;
        $this->billDetailRepository     = $billDetailRepository;
    }

    public function index()
    {
        $tables = $this->tableRepository->getAll();
        $data = [];
        foreach ($tables as $table) :
            $order  = $this->orderRepository->calculatePercentStatus($table->id);
            $data[] = [
                'id' => $table->id,
                'name' => $table->name,
                'percent' => (int) $order['percent'],
                'order' => $order['order']
            ];
        endforeach;

        return view('home.orderlist', [
            'data' => $data,
            'breadcrumb' => 'home'
        ]);
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

        if (null === $table || null === $product || $product->status == ProductConstant::STATUS_OUT_OF_STOCK) :
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

        $orderTable = $this->orderRepository->createOrder($table->id, $user, $order);   //create order
        if (null !== $orderTable) $total = $this->orderDetailRepository->createOrderDetail($orderTable['order']->id, $order); //createOrderDetail and get total
        if (null !== $total) $updateTotal = $this->orderRepository->update($orderTable['order']->id, ['total' => $total]); //update total order

        if (null !== $orderTable && $orderTable['existOrder'] === true) :
            $this->tableRepository->update($table->id, [TableConstant::COLUMN_STATUS => TableConstant::STATUS['on_delivery']]);
        else :
            $this->tableRepository->update($table->id, [TableConstant::COLUMN_STATUS => TableConstant::STATUS['pending']]);
        endif;

        session()->forget('order');


        return redirect()
            ->route(RouteConstant::HOME['table_detail'], ['id' => $table->id]);
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
        $dataUpdate = [];
        $orderDetail = $this->orderDetailRepository->find($request->query(OrderDetailConstant::COLUMN_ID));
        $order = $this->orderRepository->find($orderDetail->order_id);
        $quantity = $request->query(OrderDetailConstant::COLUMN_QUANTITY);

        if (null === $orderDetail) :
            return false;
        endif;

        if ($request->query(OrderDetailConstant::COLUMN_STATUS) == OrderDetailConstant::STATUS_CANCEL) :
            if (! $this->orderDetailRepository->delete($orderDetail->id)) :
                return false;
            endif;

            return true;
        endif;

        if ((int) $quantity > 0) :   //Case isset quantity update
            $dataUpdate = [
                OrderDetailConstant::COLUMN_QUANTITY => $request->query(OrderDetailConstant::COLUMN_QUANTITY),
                OrderDetailConstant::COLUMN_TOTAL => $request->query(OrderDetailConstant::COLUMN_QUANTITY) * $orderDetail->price,
                OrderDetailConstant::COLUMN_STATUS => $request->query(OrderDetailConstant::COLUMN_STATUS)
            ];

            if (! $this->orderDetailRepository->update($orderDetail->id, $dataUpdate)
            || ! $this->orderRepository->updateTotal($orderDetail->order_id)
            ) :
                return false;
            endif;
        else :
            $dataUpdate = [                                                      //Case ! isset quantity update
                OrderDetailConstant::COLUMN_STATUS => $request->query(OrderDetailConstant::COLUMN_STATUS)
            ];

            if (! $this->orderDetailRepository->update($orderDetail->id, $dataUpdate)) :
                return false;
            endif;
        endif;

        $orderDetails = $order->orderDetails;   //get list order details to check table status
        $check = true;

        foreach ($orderDetails as $orderDetail) :
            if ($orderDetail->status !== OrderDetailConstant::STATUS['delivered']) :
                $check = false;
                break;
            endif;
        endforeach;

        if ($check) $this->tableRepository->update($order->table_id, [TableConstant::COLUMN_STATUS => TableConstant::STATUS['delivered']]);

        return true;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function deleteOrder(Request $request) : bool
    {
        $check = $this->orderDetailRepository->checkDeleteAvailability($request->query('order'));

        if (! $check) return false;

        if (! $this->orderRepository->delete($request->query('order'))) :
            return false;
        endif;

        if (! $this->tableRepository->update($request->query('table'), [TableConstant::COLUMN_STATUS => TableConstant::STATUS['empty']])) return false;

        return true;
    }
}
