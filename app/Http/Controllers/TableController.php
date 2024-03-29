<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TableRequest;
use Illuminate\Http\RedirectResponse;
use App\Repositories\Contracts\Interface\TableRepositoryInterface;
use App\Repositories\Contracts\Interface\OrderRepositoryInterface;
use App\Constants\TableConstant;
use App\Constants\Constant;
use App\Constants\RouteConstant;
use Illuminate\View\View;

class TableController extends Controller
{
    /**
     * @var string
     */
    protected $breadcrumb = TableConstant::BREADCRUMB;

    /**
     * @var tableRepository
     */
    protected $tableRepository;

    /**
     * @var orderRepository
     */
    protected $orderRepository;

    /**
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TableRepositoryInterface $tableRepository,
        OrderRepositoryInterface $orderRepository
        )
    {
        $this->tableRepository = $tableRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * 
     * @return View
     */
    public function index(Request $request) : View
    {
        $tables = $this->tableRepository->getAll();

        return view('dashboard.table.list', [
            'tableErrCode' => session()->get('tableErrCode') ?? '',
            'tableErrMsg' => session()->get('tableErrMsg') ?? '',
            'tables' => $tables,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @return View
     */
    public function viewCreate() : View
    {
        return view('dashboard.table.create', [
            'tableErrCode' => session()->get('tableErrCode') ?? null,
            'tableErrMsg' => session()->get('tableErrMsg') ?? null,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @param integer|null $id
     * @return View|RedirectResponse
     */
    public function viewUpdate(?int $id) : View|RedirectResponse
    {
        try {
            $table = $this->tableRepository->find($id);

            return view('dashboard.table.update', [
                'tableErrCode' => session()->get('tableErrCode') ?? null,
                'tableErrMsg' => session()->get('tableErrMsg') ?? null,
                'table' => $table,
                'breadcrumb' => $this->breadcrumb
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route(RouteConstant::DASHBOARD['table_list'])
                ->with([
                    'tableErrCode' => Constant::ERR_CODE['fail'],
                    'tableErrMsg' => TableConstant::ERR_MSG_NOT_FOUND
                ]);
        }
    }

    /**
     * @param TableRequest $request
     * 
     * @return RedirectResponse
     */
    public function create(TableRequest $request) : RedirectResponse
    {
        if (! $this->tableRepository->create($request->toArray())) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['table_create'])
                ->with([
                    'tableErrCode' => Constant::ERR_CODE['fail'],
                    'tableErrMsg' => Constant::ERR_MSG['create_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['table_create'])
            ->with([
                'tableErrCode' => Constant::ERR_CODE['success'],
                'tableErrMsg' => Constant::ERR_MSG['create_success']
            ]);
}

    /**
     * @param TableRequest $request
     * 
     * @return RedirectResponse
     */
    public function update($id, TableRequest $request) : RedirectResponse
    {
        if (! $this->tableRepository->find($id)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['table_list'])
                ->with([
                    'tableErrCode' => Constant::ERR_CODE['fail'],
                    'tableErrMsg' => TableConstant::ERR_MSG_NOT_FOUND
                ]);
        endif;

        if (! $this->tableRepository->update($id, $request->toArray())) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['table_update'], ['id' => $id])
                ->with([
                    'tableErrCode' => Constant::ERR_CODE['fail'],
                    'tableErrMsg' => Constant::ERR_MSG['update_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['table_update'], ['id' => $id])
            ->with([
                'tableErrCode' => Constant::ERR_CODE['success'],
                'tableErrMsg' => Constant::ERR_MSG['update_success']
            ]);
    }

    /**
     * @param Request $request
     * 
     * @return boolean
     */
    public function delete(Request $request) : bool
    {
        $table_id = $request->query('id');
        
        if (! $this->tableRepository->delete($table_id)) :
            return false;
        endif;

        return true;
    }


    //HOME
    /**
     * @param Request $request
     * 
     * @return View
     */
    public function viewIndex(Request $request) : View
    {
        $tables = $this->tableRepository->getAll();

        return view('home.table', [
            'tables' => $tables
        ]);
    }

    /**
     * @param integer|null $id
     * @param Request $request
     * 
     * @return View|RedirectResponse
     */
    public function viewDetail(?int $id, Request $request) : View|RedirectResponse
    {
        $table = $this->tableRepository->find($id);

        if (null !== $table) :
            $order = $this->orderRepository->getTableOrder($table->id);

            return view('home.tabledetail', [
                'table' => $table,
                'order' => $order,
                'orderErrCode' => session()->get('orderErrCode') ?? null,
                'orderErrMsg' => session()->get('orderErrMsg') ?? null
            ]);
        endif;

        return redirect()
            ->route(RouteConstant::HOME['table_list'])
            ->with([
                'tableErrCode' => Constant::ERR_CODE['fail'],
                'tableErrMsg' => TableConstant::ERR_MSG_NOT_FOUND
            ]);
    }
}
