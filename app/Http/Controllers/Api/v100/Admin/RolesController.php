<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Services\Role\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleShowResource;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Requests\API\RoleRequest;
use App\Http\Resources\PermissionResource;

class RolesController extends Controller
{
    use ApiReturnFormatTrait;


    protected $roleService;

    public function __construct(RoleService $roleService)
    {

        $this->roleService = $roleService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $suppliers = RoleResource::collection($this->roleService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Role List'), $suppliers);

           } catch(\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

           }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $data = $request->validated();
            $role = $this->roleService->createOrUpdate($data);
            return $this->responseWithSuccess('Role Created',new RoleResource($role));

        } catch(\Exception $e)
        {
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
                $role = $this->roleService->get($id);
                return $this->responseWithSuccess('Role details',new RoleShowResource($role));

                } catch(\Exception $e)
                {
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
    public function update(RoleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $role = $this->roleService->createOrUpdate($data,$id);
            return $this->responseWithSuccess('Role Updated',new RoleResource($role));

        } catch(\Exception $e)
        {
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
            if ($this->roleService->delete($id)) {
                return $this->responseWithSuccess(__('custom.role_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.role_deleted_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function getPermissions()
    {
        try{
            $permissions = PermissionResource::collection($this->roleService->getParentPermissions())->response()->getData(true);
            return $this->responseWithSuccess(__('Permission List'), $permissions);

           } catch(\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

           }

    }
}
