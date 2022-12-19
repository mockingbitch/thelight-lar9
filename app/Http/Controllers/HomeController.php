<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Repositories\Contracts\Interface\TableRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Constants\RouteConstant;
use App\Constants\ProductConstant;

class HomeController extends Controller
{
    protected $tableRepository;
    protected $productRepository;

    public function __construct(
        TableRepositoryInterface $tableRepository, 
        ProductRepositoryInterface $productRepository
        )
    {
        $this->tableRepository = $tableRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        // return view('home.index');
        if (auth()->guard('user')->user()) {
            return redirect()->route(RouteConstant::HOME['table_list']);
        }

        return \redirect()->route(RouteConstant::LOGIN);
    }

    /**
     * @return View
     */
    public function catchError() : View
    {
        return view(RouteConstant::ERROR);
    }

    public function getProductsScreenOrder(Request $request)
    {
        $table_id = $request->query('table');
        $keySearch = $request->query('key');

        $table = $this->tableRepository->find((int) $table_id);

        // if (null === $table) :
        //     return redirect()->route(RouteConstant::HOME['table_list']);
        // endif;

        if (null !== $keySearch) :
            $products = $this->productRepository->search(ProductConstant::COLUMN_NAME ,$keySearch);
        else :
            $products = $this->productRepository->getAll();
        endif;

        return view('home.order', [
            'products' => $products,
            'table' => $table,
            'breadcrumb' => 'products_order',
            'orderErrCode' => session()->get('orderErrCode') ?? null,
            'orderErrMsg' => session()->get('orderErrMsg') ?? null
        ]);
    }
}
