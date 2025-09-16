<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstallRequest;
use App\Models\SystemSettings;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use URL;
use Session;
use DB;


class InstallerController extends Controller
{
    public function index(){
        return view('install.index');
    }
    public function getInstall(InstallRequest $request){
        if (!Hash::check(session()->get('hash_token'),$request->random_token))
        {
            return response()->json([
                'error'   => __('Something went wrong. Please try again.'),
            ]);
        }
        envWrite('APP_URL', URL::to('/'));

        ini_set('max_execution_time', 900); //900 seconds
        $host           = $request->host;
        $db_user        = $request->db_user;
        $db_name        = $request->db_name;
        $db_password    = $request->db_password;

        $purchase_code  = $request->purchase_code;

        //backup old database
        try {
            DB::connection()->getPdo();
            Artisan::call("backup:run --only-db --disable-notifications");
            Log::info("DB backup successful");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        //check for valid database connection
        try{
            $mysqli = @new \mysqli($host, $db_user, $db_password, $db_name);
        }catch (\Throwable $th){
            return response()->json([
                'error'   => __('Please input valid database information.'),
            ]);
        }
        if (mysqli_connect_errno()) {
            return response()->json([
                'error'   => __('Please input valid database information.'),
            ]);
        }
        $mysqli->close();
        $data['DB_HOST']        = $host;
        $data['DB_DATABASE']    = $db_name;
        $data['DB_USERNAME']    = $db_user;
        $data['DB_PASSWORD']    = $db_password;

        session()->put('email', $request->email);
        session()->put('name', $request->name);
        session()->put('login_password', $request->password);
        session()->put('purchase_code', $purchase_code);

        try{
            $verification = verify_purchase_code($purchase_code);
        }catch (\Throwable $e){
            return response()->json([
                'error'   => $e->getMessage(),
            ]);
        }

        if ($verification === true) :
            envWrite('DB_HOST', $data['DB_HOST']);
            envWrite('DB_DATABASE', $data['DB_DATABASE']);
            envWrite('DB_USERNAME', $data['DB_USERNAME']);
            envWrite('DB_PASSWORD', $data['DB_PASSWORD']);
            sleep(3);

            return response()->json([
                'route'     => route('install.finalize'),
                'success'   => true,
            ]);
        else:
            return response()->json([
                'success'   => false,
                'error'   => $verification,
            ]);
        endif;
    }

    public function final(){

        \Artisan::call('db:wipe --drop-views --force');
        \Artisan::call('key:generate');
        \Artisan::call('optimize:clear');
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
        envWrite('APP_DEBUG', false);
        envWrite('IS_DEMO_MODE', false);
        envWrite('MIX_ASSET_URL', URL::to('/').'/public');
        envWrite('MIX_ENV_MODE', 'production');
        try{
            \Artisan::call('storage:link --force');
            envWrite('FILESYSTEM_DRIVER', 'public');
        }catch (\Throwable $t){
            envWrite('FILESYSTEM_DRIVER', 'public_path');
        }
        try{
            \Artisan::call('jwt:secret');
        }catch (\Throwable $t){
            Log::error($t->getMessage());
        }

        $user                = User::find(1);
        $user->email         = Session::get('email');
        $user->name          = Session::get('name');
        $user->password      = bcrypt(Session::get('login_password'));
        $user->save();

        storePurchaseCode(Session::get('purchase_code'));
        Storage::put('installed.txt', session()->get('purchase_code'));

        envWrite('APP_PURCHASE_CODE', session()->get('purchase_code'));
        envWrite('APP_INSTALLED', true);

        return response()->json([
            'success'   => true,
            'message'   => "Installation is Successful",
            'route'     => route('admin.auth.login'),
        ]);
    }
}
