<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\DashboardConstant;
use Illuminate\View\View;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\Contracts\Interface\BillDetailRepositoryInterface;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * @var breadcrumb
     */
    protected $breadcrumb = DashboardConstant::BREADCRUMB_DASHBOARD;

    /**
     * @var billRepository
     */
    protected $billRepository;

    /**
     * @var billDetailRepository
     */
    protected $billDetailRepository;

    /**
     * @param BillRepositoryInterface $billRepository
     * @param BillDetailRepositoryInterface $billDetailRepository
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
     * @return View
     */
    public function index() : View
    {
        $bills      = $this->billRepository->getCurrentDay();
        $topProduct = $this->billDetailRepository->getTopProduct();

        return view('dashboard.index', [
            'breadcrumb'        => $this->breadcrumb,
            'income'            => $bills['income'],
            'count'             => $bills['count'],
            'percent'           => $bills['percent']
        ]);
    }

    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function getDataChart() : JsonResponse
    {
        $bills      = $this->billRepository->getLastTenDays();
        $oldBills   = $this->billRepository->getTenDaysLastMonth();
        $getMonthly = $this->billRepository->getLastTwelveMonth();

        return response()->json([
            'current'   => $bills,
            'old'       => $oldBills,
            'month'     => $getMonthly
        ], 200);
    }
}
