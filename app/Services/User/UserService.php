<?php

namespace App\Services\User;

use App\Notifications\UserCreateNotification;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Contracts\Role;
use Throwable;
use App\Models\User;
use App\Models\UserWarehouse;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

/**
 * UserService
 */
class UserService extends BaseService
{
    /**
     * __construct
     *
     * @param mixed $model
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * createOrUpdate
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function createOrUpdate(array $data, $id = null)
    {
//        try {
            if ($id) {
                // Update
                $user = $this->get($id);

                // Password
                if (isset($data['password']) && $data['password']) {
                    $user->password = Hash::make($data['password']);
                }

                // Avatar
                if (isset($data['avatar']) && $data['avatar'] != null) {
                    logger($data['avatar']);
                    if (strpos($data['avatar'], 'base64') !== false) {
                        $user->avatar = $this->fileUploadService->uploadBase64($data['avatar'], User::FILE_STORE_PATH, $user->avatar);
                    } else {
                        // logger($data['avatar']);
                        // logger(gettype($data['avatar']));
                        $user->avatar = $this->uploadFile($data['avatar'], $user->avatar);
                    }
                }

                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->phone = $data['phone'] ?? $user->phone;
                $user->status = $data['status'];

                // Update user
                $user->save();

                if ($user) {
                    $userWarehouses = UserWarehouse::where('user_id', $id)->get();
                    foreach ($userWarehouses as $userWarehouse) {
                        $userWarehouse->delete();
                    }
                    if (isset($data['warehouses']) && $data['warehouses'] != null && count($data['warehouses']) > 0){
                        foreach ($data['warehouses'] as $warehouse) {
                            $userWarehouse = new UserWarehouse();
                            $userWarehouse->user_id = $id;
                            $userWarehouse->warehouse_id = $warehouse;
                            $userWarehouse->save();
                        }
                    }
                }
                // Give user role
                return $user->syncRoles(intval($data['role']));
            } else {
                // Create
                $data['password'] = Hash::make($data['password']);
                if (isset($data['avatar']) && $data['avatar'] != null) {
                    if (strpos($data['avatar'], 'base64') !== false) {
                        $data['avatar'] = $this->fileUploadService->uploadBase64($data['avatar'], User::FILE_STORE_PATH);
                    } else {
                        $data['avatar'] = $this->uploadFile($data['avatar']);
                    }

                    //
                    //                $file = $this->uploadFile($data['avatar']);
                    //                $data['avatar'] = $file;
                }
                // dd($data['avatar']);

                // Store user
                $user = $this->model::create($data);
                if ($user && isset($data['warehouses']) && $data['warehouses'] != null && count($data['warehouses']) > 0) {
                    // Store user warehouses
                    foreach ($data['warehouses'] as $warehouse) {
                        $userWarehouse = new UserWarehouse();
                        $userWarehouse->user_id = $user->id;
                        $userWarehouse->warehouse_id = $warehouse;
                        $userWarehouse->save();
                    }
                }

//                $this->sendUserNotification($data);

                // Give user role
                return $user->syncRoles(intval($data['role']));
            }
//        } catch (Throwable $e) {
//            Log::info($e->getMessage());
//        }
    }

    public function sendUserNotification($data)
    {
        try {

            $appName = (config('site_title') ?? config('app.name'));
            $itclan = "<a href='https://www.itclanbd.com/'>ITclan BD</a>";
            $reset_url = route('admin.auth.reset-password') . '?email=' . $data['email'] . '&token=' . config('app.key');
            $content = [

                'subject'           => 'Welcome to ' . $appName,
                'greeting'          => "Greetings from $itclan,",
                'content2'          => __('custom.main_content', ['attribute' => $appName]),
                'content_bn'        => '', //__('custom.main_content_bn', ['attribute' => $appName]),
                'reset_button_name' => __('custom.click_here'),
                'reset_url'         => $reset_url,
                'support_content'   => __('custom.support_content'),
                'support_content_bn'   => '', //__('custom.support_content_bn'),

            ];
            Notification::route('mail', $data['email'])
                ->notify(new UserCreateNotification($content));
        } catch (\Exception $e) {

            Log::info($e->getMessage());
        }
    }

    /**
     * delete
     *
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $user = $this->model::findOrFail($id);
            // Delete avatar
            try {
                Storage::disk(config('filesystems.default'))->delete($this->model::FILE_STORE_PATH . '/' . $user->avatar);
            } catch (Throwable $th) {
                throw $th;
            }
            // Delete user
            return $user->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function updateProfile(array $data, $id)
    {
        try {
            // Update
            $user = $this->get($id);

            // Password
            if (isset($data['password']) && $data['password']) {
                $user->password = Hash::make($data['password']);
            }

            // Avatar
            if (isset($data['avatar']) && $data['avatar'] != null) {
                $user->avatar = $this->uploadFile($data['avatar'], $user->avatar);
            }

            $user->name = $data['name'];
            $user->email = $data['email'];

            // Update user
            return $user->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function all()
    {
        return $this->model->where('email', '<>', 'clanadmin@app.com')->orderBy('avatar', 'asc')->paginate(10);
    }
}
