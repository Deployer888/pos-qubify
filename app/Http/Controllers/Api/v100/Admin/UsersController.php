<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Requests\API\UserRequest;
use Auth;

class UsersController extends Controller
{
    use ApiReturnFormatTrait;

    protected $userService;


    /**
     * __construct
     *
     * @param  mixed $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService  = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = UserResource::collection($this->userService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('user List'), $users);
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->createOrUpdate($data);
            return $this->responseWithSuccess('User Created', new UserResource($user));
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->userService->get($id);
            if (!$user)
                return $this->responseWithError('Not found', [], 404);

            return $this->responseWithSuccess('User Details', new UserResource($user));
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->createOrUpdate($data, $id);
            return $this->responseWithSuccess('User Updated', new UserResource($user));
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($id == Auth::id()) {
                return $this->responseWithError(__('custom.you_cant_delete_your_self'), [], 403);
            }

            $user = $this->userService->get($id);

            if ($user->email == 'admin@app.com') {
                $checkAdminUser = User::query()->where('email', '!=', 'admin@app.com')
                    ->whereHas('roles', function ($q) {
                        $q->where('name', 'Admin');
                    })->first();

                if (!$checkAdminUser) {
                    return $this->responseWithError(__('custom.you_cant_delete_app_admin_user'), [], 403);
                }
            }

            // At least one user remains
            if ($user->count() <= 1) {
                return $this->responseWithError(__('custom.you_cant_delete_last_user'), [], 403);
            }

            if ($this->userService->delete($id)) {
                return $this->responseWithSuccess(__('custom.user_deleted_successful'), [], 200);
            } else {
                return $this->responseWithError(__('custom.user_deleted_failed'), [], 500);
            }
        } catch (\Exception $e) {
            return $this->responseWithError(__('Something went wrong'), [], 500);
        }
    }
}
