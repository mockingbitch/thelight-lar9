<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Repositories\Contracts\Interface\CategoryRepositoryInterface;
use Illuminate\View\View;
use App\Constants\ProductConstant;
use App\Constants\Constant;

class ProductController extends Controller
{
    /**
     * @var string
     */
    protected $breadcrumb = ProductConstant::BREADCRUMB;

    /**
     * @var productRepository
     */
    protected $productRepository;

     /**
     * @var categoryRepository
     */
    protected $categoryRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
        )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Request $request
     * 
     * @return View
     */
    public function index(Request $request) : View
    {
        $products = $this->productRepository->getAll();

        return view('dashboard.product.list', [
            'productErrCode' => session()->get('productErrCode') ?? '',
            'productErrMsg' => session()->get('productErrMsg') ?? '',
            'products' => $products,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @param ProductRequest $request
     * 
     * @return View
     */
    public function create(ProductRequest $request) : View
    {
        $category_id = $request->category_id;

        if (! $this->categoryRepository->find($category_id)) :
            return redirect()
                ->route('dashboard.product.create')
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => ProductConstant::ERR_MSG['category_not_found']
                ]);
        endif;

        if (! $this->productRepository->create($request->toArray())) :
            return redirect()
                ->route('dashboard.product.create')
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => Constant::ERR_MSG['create_fail']
                ]);
        endif;

        return redirect()
            ->route('dashboard.product.create')
            ->with([
                'productErrCode' => Constant::ERR_CODE['success'],
                'productErrMsg' => Constant::ERR_MSG['create_success']
            ]);
    }

    /**
     * @param integer|null $productId
     * @param ProductRequest $request
     * 
     * @return RedirectResponse
     */
    public function update(?int $productId, ProductRequest $request) : RedirectResponse
    {
        if (! $this->productRepository->find($productId)) :
            return redirect()
                ->route('dashboard.product.list')
                ->with('msg', ProductConstant::ERR_MSG_NOT_FOUND);
        endif;

        if (! $this->productRepository->update($productId, $request->toArray())) :
            return redirect()
                ->route('dashboard.product.update')
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => Constant::ERR_MSG['update_fail']
                ]);
        endif;

        return redirect()
            ->route('dashboard.product.update')
            ->with([
                'productErrCode' => Constant::ERR_CODE['success'],
                'productErrMsg' => Constant::ERR_MSG['update_success']
            ]);
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function delete(Request $request) : JsonResponse
    {
        try {
            $product_id = $request->query('id');

            if (! $this->productRepository->find($product_id)) :
                return $this->errorResponse('Resource not found');
            endif;

            if (! $this->productRepository->delete($product_id)) :
                return $this->errorResponse('Failed to delete product');
            endif;

            return $this->successResponse('Ok');
        } catch (\Throwable $th) {
            return $this->catchErrorResponse();
        }
    }
}
