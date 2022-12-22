<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\DashboardConstant;
use Illuminate\View\View;
use App\Repositories\Contracts\Interface\BillRepositoryInterface;
use App\Repositories\Contracts\Interface\BillDetailRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Constants\Constant;

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
        $bills          = $this->billRepository->getDifferent('1', Constant::DATE_FORMAT_YMD, Constant::DATE_DAY);
        $billsLastMonth = $this->billRepository->getDifferent('1', Constant::DATE_FORMAT_M, Constant::DATE_MONTH);

        return view('dashboard.index', [
            'breadcrumb'        => $this->breadcrumb,
            'income'            => $bills['income'],
            'count'             => $bills['count'],
            'percent'           => $bills['percent'],
            'percentLastMonth'  => $billsLastMonth['percent'],
        ]);
    }

    /**
     * Return response json for Dashboard's Chart
     *
     * @return JsonResponse
     */
    public function getDataChart() : JsonResponse
    {
        $bills      = $this->billRepository
                    ->getLastBills(
                        'N',
                        Constant::DATE_FORMAT_D,
                        Constant::DATE_NOW,
                        Constant::DATE_DAY,
                        Constant::CONDITION_TENDAYSFROMNOW
                    );
        $oldBills   = $this->billRepository
                    ->getLastBills(
                        'N',
                        Constant::DATE_FORMAT_D,
                        date(Constant::DATE_FORMAT_YMD, strtotime('now - 1 month')),
                        Constant::DATE_DAY,
                        Constant::CONDITION_TENDAYSLASTMONTH
                    );
        $getMonthly = $this->billRepository
                    ->getLastBills(
                        'T',
                        Constant::DATE_FORMAT_M,
                        Constant::DATE_NOW,
                        Constant::DATE_MONTH,
                        Constant::CONDITION_TENMONTHSLASTYEAR
                    );

        return response()->json([
            'current'   => $bills,
            'old'       => $oldBills,
            'month'     => $getMonthly
        ], 200);
    }
}
