<?php

namespace App\Http\Controllers\Admin\Administration;

use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
use App\Services\Role\RoleService;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    protected $roleService;

    /**
     * __construct
     *
     * @param  mixed $roleService
     * @return void
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService  = $roleService;

        $this->middleware(['permission:List Role'])->only(['index']);
        $this->middleware(['permission:Add Role'])->only(['create']);
        $this->middleware(['permission:Edit Role'])->only(['edit']);
        $this->middleware(['permission:Show Role'])->only(['show']);
        $this->middleware(['permission:Delete Role'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(RoleDataTable $dataTable)
    {
        set_page_meta(__('custom.roles'));
        return $dataTable->render('admin.administration.roles.index');
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        $permissions = $this->roleService->getParentPermissions();

        set_page_meta(__('custom.add_role'));
        return view('admin.administration.roles.create', compact('permissions'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        if ($this->roleService->createOrUpdate($data)) {
            flash(__('custom.role_create_successful'))->success();
        } else {
            flash(__('custom.role_create_failed'))->error();
        }

        return redirect()->route('admin.roles.index');
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $role = $this->roleService->get($id);
        return view('admin.administration.roles.show', compact('role'));
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $role = $this->roleService->get($id);
        $permissions = $this->roleService->getParentPermissions();

        $parents_id = [];
        $role_permission = [];

        if ($role->permissions) {
            foreach ($role->permissions as $key => $value) {
                array_push($role_permission, $value->id);
                array_push($parents_id, $value->parent_id);
            }
        }


        set_page_meta(__('custom.edit_role'));
        return view('admin.administration.roles.edit', compact('role', 'permissions', 'parents_id', 'role_permission'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(RoleRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->roleService->createOrUpdate($data, $id)) {
            flash(__('custom.role_updated_successful'))->success();
        } else {
            flash(__('custom.role_updated_failed'))->error();
        }

        return redirect()->route('admin.roles.index');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        if ($this->roleService->delete($id)) {
            flash(__('custom.role_deleted_successful'))->success();
        } else {
            flash(__('custom.role_deleted_failed'))->error();
        }

        return redirect()->route('admin.roles.index');
    }
}
