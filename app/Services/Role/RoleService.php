<?php

namespace App\Services\Role;

use App\Models\Permission;
use App\Services\BaseService;
use Spatie\Permission\Models\Role;

/**
 * RoleService
 */
class RoleService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * getParentPermissions
     *
     * @return void
     */
    public function getParentPermissions()
    {
        return Permission::with('childs')
            ->where('parent_id', null)
            ->get();
    }

    /**
     * createOrUpdate
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function createOrUpdate(array $data, $id = null)
    {
        try {
            if ($id) {
                // Update
                $role = $this->get($id);
            } else {
                // Create
                $role = new $this->model();
            }

            $role->name = $data['name'];
            $role->guard_name = 'web';
            $role->save();

            // Assign permission
            $role->syncPermissions(array_map(function($permission) {
                return (int)$permission;
            },$data['permissions']));
            return $role;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function all()
    {
        return $this->model->where('name', '<>', 'Admin')->paginate(10);
    }
}
