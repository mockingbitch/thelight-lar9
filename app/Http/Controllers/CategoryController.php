<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CategoryRequest;
use App\Repositories\Contracts\Interface\CategoryRepositoryInterface;
use App\Repositories\Contracts\Interface\ProductRepositoryInterface;
use App\Constants\CategoryConstant;
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

        return view('dashboard.category.list', [
            'categoryErrCode' => session()->get('categoryErrCode') ?? '',
            'categoryErrMsg' => session()->get('categoryErrMsg') ?? '',
            'categories' => $categories,
            'breadcrumb' => $this->breadcrumb
        ]);
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
                ->route('dashboard.category.create')
                ->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => Constant::ERR_MSG['create_fail']
                ]);
        endif;

        return redirect()
            ->route('dashboard.category.create')
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
        $categoryId = $request->query('id');

        if (! $this->categoryRepository->find($categoryId)) :
            return redirect()
                ->route('dashboard.category.list')
                ->with('msg', CategoryConstant::ERR_MSG_NOT_FOUND);
        endif;

        if (! $this->categoryRepository->update($categoryId, $request->toArray())) :
            return redirect()
                ->route('dashboard.category.update')
                ->with([
                    'categoryErrCode' => Constant::ERR_CODE['fail'],
                    'categoryErrMsg' => Constant::ERR_MSG['update_fail']
                ]);
        endif;

        return redirect()
            ->route('dashboard.category.update')
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
                ->route('dashboard.category.list')
                ->with('msg', CategoryConstant::ERR_MSG_NOT_FOUND);
        endif;

        // if (! $this->categoryRepository->delete($categoryId)) :
        //     return $this->errorResponse('Failed to delete category');
        // endif;

        // return $this->successResponse('Ok');
    }
}
