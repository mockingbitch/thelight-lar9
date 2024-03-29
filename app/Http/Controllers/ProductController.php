<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\RedirectResponse;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Repositories\Contracts\Interface\CategoryRepositoryInterface;
use App\Services\ImageService;
use Illuminate\View\View;
use App\Constants\ProductConstant;
use App\Constants\Constant;
use App\Constants\RouteConstant;

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
     * @var imageService
     */
    protected $imageService;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ImageService $imageService
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ImageService $imageService
        )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->imageService = $imageService;
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request) : View
    {
        if (null !== $request->query('category')) :
            $products = $this->productRepository->getProductsByCategory($request->query('category'));
        else :
            $products = $this->productRepository->getAll();
        endif;

        return view('dashboard.product.list', [
            'productErrCode' => session()->get('productErrCode') ?? '',
            'productErrMsg' => session()->get('productErrMsg') ?? '',
            'products' => $products ?? null,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @return View|RedirectResponse
     */
    public function viewCreate() : View|RedirectResponse
    {
        $categories = $this->categoryRepository->getAll();

        if  (! $categories || null === $categories || count($categories) == 0) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_list'])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => ProductConstant::ERR_MSG_CATEGORY_NOT_FOUND
                ]);
        endif;

        return view('dashboard.product.create', [
            'productErrCode' => session()->get('productErrCode') ?? null,
            'productErrMsg' => session()->get('productErrMsg') ?? null,
            'categories' => $categories,
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
            $product = $this->productRepository->find($id);
            $categories = $this->categoryRepository->getAll();

            if  (! $categories || null === $categories || count($categories) == 0 || null == $product) :
                return redirect()
                    ->route(RouteConstant::DASHBOARD['product_list'])
                    ->with([
                        'productErrCode' => Constant::ERR_CODE['fail'],
                        'productErrMsg' => ProductConstant::ERR_MSG_NOT_FOUND
                    ]);
            endif;

            return view('dashboard.product.update', [
                'productErrCode' => session()->get('productErrCode') ?? null,
                'productErrMsg' => session()->get('productErrMsg') ?? null,
                'product' => $product,
                'categories' => $categories,
                'breadcrumb' => $this->breadcrumb
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_list'])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => ProductConstant::ERR_MSG_NOT_FOUND
                ]);
        }
    }

    /**
     * @param ProductRequest $request
     * @return View|RedirectResponse
     */
    public function create(ProductRequest $request) : View|RedirectResponse
    {
        $category_id = $request->category_id;

        if (! $this->categoryRepository->find($category_id)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_create'])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => ProductConstant::ERR_MSG_CATEGORY_NOT_FOUND
                ]);
        endif;

        $data = $request->toArray();

        if (null !== $request->image) :
            $data['image'] = $this->imageService->create($request->image, ProductConstant::IMAGE_FOLDER);

            if (null == $data['image'] || $data['image'] == '') :
                return redirect()
                    ->route(RouteConstant::DASHBOARD['product_create'])
                    ->with([
                        'productErrCode' => Constant::ERR_CODE['fail'],
                        'productErrMsg' => ProductConstant::ERR_MSG_CANT_PROCESS_IMAGE
                    ]);
            endif;
        endif;

        if (! $this->productRepository->create($data)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_create'])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => Constant::ERR_MSG['create_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['product_create'])
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
        $data        = $request->toArray();
        $category_id = $request->category_id;

        if (! $this->categoryRepository->find($category_id)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_update'], ['id' => $productId])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => ProductConstant::ERR_MSG_CATEGORY_NOT_FOUND
                ]);
        endif;

        if (! $product = $this->productRepository->find($productId)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_list'])
                ->with('msg', ProductConstant::ERR_MSG_NOT_FOUND);
        endif;


        if (null !== $request->image) :
            $data['image'] = $this->imageService->create($request->image, ProductConstant::IMAGE_FOLDER);

            if (null == $data['image'] || $data['image'] == '') :
                return redirect()
                    ->route(RouteConstant::DASHBOARD['product_update'], ['id' => $productId])
                    ->with([
                        'productErrCode' => Constant::ERR_CODE['fail'],
                        'productErrMsg' => ProductConstant::ERR_MSG_CANT_PROCESS_IMAGE
                    ]);
            endif;

            $this->imageService->delete($product->image, 'products');
        endif;

        if (! $this->productRepository->update($productId, $data)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['product_update'], ['id' => $productId])
                ->with([
                    'productErrCode' => Constant::ERR_CODE['fail'],
                    'productErrMsg' => Constant::ERR_MSG['update_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['product_update'], ['id' => $productId])
            ->with([
                'productErrCode' => Constant::ERR_CODE['success'],
                'productErrMsg' => Constant::ERR_MSG['update_success']
            ]);
    }

    /**
     * @param Request $request
     *
     * @return boolean
     */
    public function delete(Request $request) : bool
    {
        $product_id = $request->query('id');

        if (! $this->productRepository->delete($product_id)) :
            return false;
        endif;

        return true;
    }
}
