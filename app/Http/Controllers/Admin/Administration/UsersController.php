<?php

namespace App\Http\Controllers\Admin\Administration;

use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Services\Warehouse\WarehouseService;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    protected $userService;
    protected $roleService;
    protected $warehouseService;

    /**
     * __construct
     *
     * @param  mixed $userService
     * @param  mixed $roleService
     * @return void
     */
    public function __construct(
        UserService $userService,
        RoleService $roleService,
        WarehouseService $warehouseService
    )
    {
        $this->userService  = $userService;
        $this->roleService  = $roleService;
        $this->warehouseService  = $warehouseService;

        $this->middleware(['permission:List User'])->only(['index']);
        $this->middleware(['permission:Add User'])->only(['create']);
        $this->middleware(['permission:Edit User'])->only(['edit']);
        $this->middleware(['permission:Delete User'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(UserDataTable $dataTable)
    {
        set_page_meta(__('custom.users'));
        return $dataTable->render('admin.administration.users.index');
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {

        $roles = $this->roleService->get();
        $warehouses = $this->warehouseService->get();
        set_page_meta(__('custom.add_user'));
        return view('admin.administration.users.create', compact('roles','warehouses'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        // dd($request->all());
        if ($this->userService->createOrUpdate($data)) {
            flash(__('custom.user_create_successful'))->success();
        } else {
            flash(__('custom.user_create_failed'))->error();
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $user = $this->userService->get($id);
        $roles = $this->roleService->get();
        $warehouses = $this->warehouseService->get();

        set_page_meta(__('custom.edit_user'));
        return view('admin.administration.users.edit', compact('user', 'roles', 'warehouses'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->userService->createOrUpdate($data, $id)) {
            flash(__('custom.user_updated_successful'))->success();
        } else {
            flash(__('custom.user_updated_failed'))->error();
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        // Check self delete
        if ($id == Auth::id()) {
            flash(__('custom.you_cant_delete_your_self'))->warning();
            return redirect()->back();
        }

        $user = $this->userService->get($id);

        if ($user->email == 'admin@app.com'){
            $checkAdminUser = User::query()->where('email', '!=', 'admin@app.com')
                ->whereHas('roles', function ($q){
                $q->where('name', 'Admin');
            })->first();

            if (!$checkAdminUser){
                flash(__('custom.you_cant_delete_app_admin_user'))->warning();
                return redirect()->back();
            }
        }

        // At least one user remains
        if ($user->count() <= 1) {
            flash(__('custom.you_cant_delete_last_user'))->warning();
            return redirect()->back();
        }

        if ($this->userService->delete($id)) {
            flash(__('custom.user_deleted_successful'))->success();
        } else {
            flash(__('custom.user_deleted_failed'))->error();
        }
        return redirect()->back();
    }


    /**
     * profile
     *
     * @return void
     */
    public function profile()
    {
        $user = $this->userService->get(Auth::id());

        set_page_meta(__('custom.edit_profile'));
        return view('admin.administration.users.profile', compact('user'));
    }

    /**
     * updateProfile
     *
     * @param  mixed $request
     * @param  mixed $profile
     * @return void
     */
    public function updateProfile(ProfileRequest $request, $profile)
    {
        $data = $request->validated();

        if ($this->userService->updateProfile($data, Auth::id())) {
            flash(__('custom.profile_update_successful'))->success();
        } else {
            flash(__('custom.profile_update_failed'))->error();
        }

        return redirect()->route('admin.dashboard');
    }
}
