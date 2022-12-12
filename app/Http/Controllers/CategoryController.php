<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CategoryRequest;
use App\Repositories\Contracts\Interface\CategoryRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Constants\CategoryConstant;
use App\Constants\RouteContant;
use App\Constants\Constant;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * @var string
     */
    protected $breadcrumb = CategoryConstant::BREADCRUMB;

    /**
     * @var categoryRepository
     */
    protected $categoryRepository;

    /**
     * @var productRepository
     */
    protected $productRepository;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
        )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     * 
     * @return View
     */
    public function index(Request $request) : View
    {
        $categories = $this->categoryRepository->getAll();

        return view(RouteConstant::DASHBOARD['category_list'], [
            'categoryErrCode' => session()->get('categoryErrCode') ?? null,
            'categoryErrMsg' => session()->get('categoryErrMsg') ?? null,
            'categories' => $categories,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @return View
     */
    public function viewCreate() : View
    {
        return view(RouteConstant::DASHBOARD['category_create'], [
            'categoryErrCode' => session()->get('categoryErrCode') ?? null,
            'categoryErrMsg' => session()->get('categoryErrMsg') ?? null,
            'breadcrumb' => $this->breadcrumb
        ]);
    }

    /**
     * @param integer|null $id
     * 
     * @return View|RedirectResponse
     */
    public function viewUpdate(?int $id) : View|RedirectResponse
    {
        try {
            $category = $this->categoryRepository->find($id);

            if (null == $category || $category == '') :
                return redirect()->with(RouteConstant::DASHBOARD['category_list'])->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => CategoryConstant::ERR_MSG_NOT_FOUND
                ]);
            endif;

            return view(RouteConstant::DASHBOARD['category_update'], [
                'categoryErrCode' => session()->get('categoryErrCode') ?? null,
                'categoryErrMsg' => session()->get('categoryErrMsg') ?? null,
                'category' => $category,
                'breadcrumb' => $this->breadcrumb
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route(RouteConstant::DASHBOARD['category_list'])
                ->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => CategoryConstant::ERR_MSG_NOT_FOUND
                ]);
        }
    }

    /**
     * @param CategoryRequest $request
     * 
     * @return RedirectResponse
     */
    public function create(CategoryRequest $request) : RedirectResponse
    {
        if (! $this->categoryRepository->create($request->toArray())) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['category_create'])
                ->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => Constant::ERR_MSG['create_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['category_create'])
            ->with([
                'categoryErrCode' => Constant::ERR_CODE['success'],
                'categoryErrMsg' => Constant::ERR_MSG['create_success']
            ]);
    }

    /**
     * @param integer|null $categoryId
     * @param CategoryRequest $request
     * 
     * @return RedirectResponse
     */
    public function update(?int $categoryId, CategoryRequest $request) : RedirectResponse
    {
        if (! $this->categoryRepository->find($categoryId)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['category_list'])
                ->with('msg', CategoryConstant::ERR_MSG_NOT_FOUND);
        endif;

        if (! $this->categoryRepository->update($categoryId, $request->toArray())) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['category_update'], ['id' => $categoryId])
                ->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => Constant::ERR_MSG['update_fail']
                ]);
        endif;

        return redirect()
            ->route(RouteConstant::DASHBOARD['category_update'], ['id' => $categoryId])
            ->with([
                'categoryErrCode' => Constant::ERR_CODE['success'],
                'categoryErrMsg' => Constant::ERR_MSG['update_success']
            ]);
    }

    public function delete(Request $request)
    {
        $categoryId = $request->query('id');
        
        if (! $this->categoryRepository->find($categoryId)) :
            return redirect()
                ->route(RouteConstant::DASHBOARD['category_list'])
                ->with('msg', CategoryConstant::ERR_MSG_NOT_FOUND);
        endif;

        // if (! $this->categoryRepository->delete($categoryId)) :
        //     return $this->errorResponse('Failed to delete category');
        // endif;

        // return $this->successResponse('Ok');
    }
}
