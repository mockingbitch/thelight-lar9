<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\Contracts\Interface\BillDetailRepositoryInterface;
use App\Constants\BillConstant;
use App\Constants\RouteConstant;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BillController extends Controller
{
    /**
     * @var billRepository
     */
    protected $billRepository;

    /**
     * @var billDetailRepository
     */
    protected $billDetailRepository;

    protected $breadcrumb = BillConstant::BREADCRUMB;

    /**
     * @param BillRepository $billRepository
     * @param BillDetailRepository $billDetailRepository
     */
    public function __construct(
        BillRepositoryInterface $billRepository,
        BillDetailRepositoryInterface $billDetailRepository
        )
    {
        $this->billRepository = $billRepository;
        $this->billDetailRepository = $billDetailRepository;
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request) : View
    {
        $bills = $this->billRepository->getAll();

        return view(RouteConstant::DASHBOARD['bill_list'], [
            'billErrCode' => session()->get('billErrCode') ?? null,
            'billErrMsg' => session()->get('billErrMsg') ?? null,
            'bills' => $bills,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @param integer|null $id
     *
     * @return View|RedirectResponse
     */
    public function viewDetail(?int $id) : View|RedirectResponse
    {
        $bill = $this->billRepository->find($id);

        if (null === $bill) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['bill_list'])
                ->with([
                    'billErrCode' => Constants::ERR_CODE_FAIL,
                    'billErrMsg' => BillConstant::ERR_MSG_NOT_FOUND
                ]);
        endif;

        return view('dashboard.bill.detail', [
            'billErrCode' => session()->get('billErrCode') ?? null,
            'billErrMsg' => session()->get('billErrMsg') ?? null,
            'bill' => $bill,
            'billDetails' => $bill->billDetails,
            'breadcrumb' => $this->breadcrumb
        ]);
    }
}
