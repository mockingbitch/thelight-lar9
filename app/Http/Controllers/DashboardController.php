<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\DashboardConstant;
use Illuminate\View\View;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
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
     * @param BillRepositoryInterface $billRepository
     */
    public function __construct(BillRepositoryInterface $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    /**
     * @return View
     */
    public function index() : View
    {
        return view('dashboard.index', [
            'breadcrumb' => $this->breadcrumb,
        ]);
    }

    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function getDataChart() : JsonResponse
    {
        $bills = $this->billRepository->getLastTenDays();
        $oldBills = $this->billRepository->getTenDaysLastMonth();
        $getMonthly = $this->billRepository->getLastTwelveMonth();

        return response()->json([
            'current' => $bills,
            'old' => $oldBills,
            'month' => $getMonthly
        ], 200);
    }
}
