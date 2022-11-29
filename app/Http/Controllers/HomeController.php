<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Repositories\Contracts\Interface\TableRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;

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
            return redirect()->route('home.table');
        }

        return \redirect()->route('login');
    }

    /**
     * @return View
     */
    public function catchError() : View
    {
        return view('home.error');
    }

    public function getProductsScreenOrder(Request $request)
    {
        $table_id = $request->query('table');
        $table = $this->tableRepository->find((int) $table_id);

        if (null === $table) :
            return redirect()->route('home.table');
        endif;
 
        $products = $this->productRepository->getAll();

        return view('home.order', [
            'products' => $products,
            'table' => $table,
            'orderErrCode' => session()->get('orderErrCode') ?? null,
            'orderErrMsg' => session()->get('orderErrMsg') ?? null
        ]);
    }
}
