<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Repositories\Contracts\Interface\UserRepositoryInterface;
use App\Constants\UserConstant;
use App\Constants\RouteConstant;
use App\Constants\Constant;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @var breadcrumb
     */
    protected $breadcrumb = UserConstant::BREADCRUMB;

    /**
     * @var userRepository
     */
    protected $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAll();

        return view('dashboard.user.list', [
            'users' => $users,
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
            $user = $this->userRepository->find($id);

            return view('dashboard.user.update', [
                'userErrCode' => session()->get('userErrCode') ?? null,
                'userErrMsg' => session()->get('userErrMsg') ?? null,
                'user' => $user,
                'breadcrumb' => $this->breadcrumb
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route(RouteConstant::DASHBOARD['user_list'])
                ->with([
                    'userErrCode' => Constant::ERR_CODE['fail'],
                    'userErrMsg' => UserConstant::ERR_MSG_NOT_FOUND
                ]);
        }
    }
}
