<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{get_page_meta()}} {{ config('site_title') ?? config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="Install" name="description"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!--Author-->
    <meta name="author" content="ITClanBD"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ favicon() }}">

    <link rel='stylesheet' type='text/css'
          href="{{ static_asset('install/bootstrap/css/bootstrap.min.css') }}"/>
    <link rel='stylesheet' type='text/css'
          href="{{ static_asset('install/js/font-awesome/css/font-awesome.min.css')}}"/>

    <link rel='stylesheet' type='text/css' href="{{ static_asset('install/css/install.css')}}"/>
</head>
<body>
@php
    $s_token = Str::random(21);
    session()->put('hash_token',$s_token);

    $required_min_php_version   = "7.3";
    $required_max_php_version   = "9.0";
    $current_php_version        = phpversion();

    $php_version_success        = (floatval($current_php_version) >= $required_min_php_version && floatval($current_php_version) < $required_max_php_version) ? true : false;

    $data['mysqli_connect']     = [
        'name'      => 'MySQLi',
        'success'   => function_exists("mysqli_connect") ? true : false,
        'message'   => 'MySQLi extension is required to connect with database.'
    ];
    $data['curl_version']       = [
        'name'      => 'cURL',
        'success'   => function_exists("curl_version") ? true : false,
        'message'   => 'cURL extension is required to connect with external API.'
    ];
    $data['gd_info']            = [
        'name'      => 'GD Library',
        'success'   => (extension_loaded('gd') && function_exists('gd_info')) ? true : false,
        'message'   => 'GD Library extension is required to resize image.'
    ];
    $data['allow_url_fopen']    = [
        'name'      => 'allow_url_fopen',
        'success'   => ini_get('allow_url_fopen') ? true : false,
        'message'   => 'allow_url_fopen is required to connect with external API.'
    ];
    $data['date_timezone']      = [
        'name'      => 'date.timezone',
        'success'   => ini_get('date.timezone') ? true : true,
        'message'   => 'date.timezone is required to set timezone.'
    ];
    $data['zip'] =              [
        'name'      => 'Zip',
        'success'   => extension_loaded('zip') ? true : false,
        'message'   => 'ZipArchive extension is required to extract zip file.'
    ];
    $data['zlip'] =              [
        'name'      => 'zlip',
        'success'   => extension_loaded('zlib') ? true : false,
        'message'   => 'zlip extension is required.'
    ];
    $data['open_ssl'] =              [
        'name'      => 'OpenSSL PHP Extension',
        'success'   => (OPENSSL_VERSION_NUMBER > 0x009080bf) ? true : false,
        'message'   => 'Open SSL extension is required.'
    ];
    $data['pdo'] =              [
        'name'      => 'PDO PHP Extension',
        'success'   =>PDO::getAvailableDrivers() ? true : false,
        'message'   => 'PDO extension is required.'
    ];
    $data['bc_math'] =              [
        'name'      => 'BC Math PHP Extension',
        'success'   => extension_loaded('bcmath') ? true : false,
        'message'   => 'BC Math extension is required.'
    ];
    $data['ctype'] =              [
        'name'      => 'Ctype PHP Extension',
        'success'   => extension_loaded('ctype') ? true : false,
        'message'   => 'Ctype extension is required.'
    ];
    $data['file_info'] =              [
        'name'      => 'Fileinfo PHP Extension',
        'success'   => extension_loaded('fileinfo') ? true : false,
        'message'   => 'Fileinfo extension is required.'
    ];
    $data['mbstring'] =              [
        'name'      => 'Mbstring PHP Extension',
        'success'   => extension_loaded('mbstring') ? true : false,
        'message'   => 'Mbstring extension is required.'
    ];
    $data['tokenizer'] =              [
        'name'      => 'Tokenizer PHP Extension',
        'success'   => extension_loaded('tokenizer') ? true : false,
        'message'   => 'Tokenizer extension is required.'
    ];
    $data['xml'] =              [
        'name'      => 'XML PHP Extension',
        'success'   => extension_loaded('xml') ? true : false,
        'message'   => 'XML extension is required.'
    ];
    $data['json'] =              [
        'name'      => 'JSON PHP Extension',
        'success'   => extension_loaded('json') ? true : false,
        'message'   => 'JSON extension is required.'
    ];



    //check if all requirement is success
    foreach ($data as $key => $value){
        if($value['success'] == false){
            $all_requirement_success = false;
            break;
        }else{
            $all_requirement_success = true;
        }
    }

    if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) :

        $writeable_directories = array(
            "../public",
            "../storage",
            "../.env",
            "../bootstrap/cache",
        );
    else :
        $writeable_directories = array(
            "./public",
            "./storage",
            ".env",
            "./bootstrap/cache",
        );
    endif;
    foreach ($writeable_directories as $value) :
        if (!is_writeable($value)) :
            $all_requirement_success = false;
        endif;
    endforeach;
@endphp
<div class="install-box">

    <div class="panel panel-install">
        <div class="panel-heading text-center">
            <h2>{{__('Installation')}} | Clan Vent</h2>
        </div>
        <div class="panel-body padding-0">
            <div class="tab-container clearfix">
                <div id="pre-installation" class="tab-title col-sm-4 active"><i class="fa fa-circle-o"></i><strong>
                    {{ __('Pre-Installation') }}</strong></div>
                <div id="configuration" class="tab-title col-sm-4"><i class="fa fa-circle-o"></i><strong>
                    {{ __('Configuration')}}</strong></div>
                <div id="finished" class="tab-title col-sm-4"><i class="fa fa-circle-o"></i><strong> {{__('Finished')}}</strong>
                </div>
            </div>
            <div id="alert-container">
                <div id="error_m" class="alert alert-danger hide">
                </div>
                <div id="success_m" class="alert alert-success hide">
                </div>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="pre-installation-tab">
                    <div class="section">
                        <p>{{__('1. Please configure your PHP settings to match following requirements')}}:</p>
                        <hr/>
                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th width="25%">{{__('PHP Settings')}}</th>
                                        <th width="27%">{{__('Current Version')}}</th>
                                        <th>{{__('Required Version')}}</th>
                                        <th class="text-center">{{__('Status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{__('PHP Version')}}</td>
                                    <td>{{ phpversion() }}</td>
                                    <td>{{$required_min_php_version}} {{__('or')}} {{__('Later')}}</td>
                                    <td class="text-center">
                                        @if($php_version_success)
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="section">
                        <p>{{__('2. Please make sure you have set the')}} <strong>{{__('writable')}}</strong>
                            {{__('permission on the following')}} {{__('folders/files')}}:</p>
                        <hr/>
                        <div>
                            <table>
                                <tbody>
                                @foreach ($writeable_directories as $value)
                                    <tr>
                                        <td id="first-td"><?php echo $value; ?></td>
                                        <td class="text-center">
                                            @if (is_writeable($value))
                                                <i class="status fa fa-check-circle-o"></i>
                                            @else
                                                @php
                                                    $all_requirement_success = false;
                                                @endphp
                                                <i class="status fa fa-times-circle-o"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="section">
                        <p>{{__('3. Please make sure the extensions/settings listed below are installed/enabled')}}:</p>
                        <hr/>
                        <div>
                            <table>
                                <thead>
                                <tr>
                                    <th>{{__('Extension/settings')}}</th>
                                    <th>{{__('Current Settings')}}</th>
                                    <th>{{__('Required Settings')}}</th>
                                    <th class="text-center">{{__('Status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td data-toggle="tooltip" data-placement="top" title="{{ $value['message'] }}">{{$value['name']}}</td>
                                        <td>
                                            @if($value['success'])
                                                <i class="status fa fa-check-circle-o"></i>
                                            @else
                                                <i class="status fa fa-times-circle-o"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="status fa fa-check-circle-o"></i>
                                        </td>
                                        <td class="text-center">
                                            @if($value['success'])
                                                <i class="status fa fa-check-circle-o"></i>
                                            @else
                                                <i class="status fa fa-times-circle-o"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button {{ (!$all_requirement_success) ? 'disabled=disabled' : '' }}
                                class="btn btn-info form-next"><i class='fa fa-chevron-right'></i> {{__('Step 2')}}
                        </button>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="configuration-tab">
                    <form name="config-form" id="config-form" action="{{route('install.process')}}" method="post">
                        @csrf
                        <div class="section clearfix">
                            <p>{{ __('1. Please enter your item purchase code.')}}</p>
                            <hr/>
                            <div>
                                <div class="form-group clearfix">
                                    <label for="purchase_code" class="col-md-3">{{__('Envato purchase code')}}</label>
                                    <div class="col-md-9">
                                        <input type="text" value="{{old('purchase_code') ?? ''}}" id="purchase_code"
                                               name="purchase_code" class="form-control"
                                               placeholder="You can find form codecanyon item download section"/>
                                        <strong class="text-danger" id="purchase_code_error"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section clearfix">
                            <p>{{__('2. Please enter your database connection details.')}}</p>
                            <hr/>
                            <div>
                                <input type="hidden" name="random_token" value="{{ bcrypt($s_token) }}">
                                <div class="form-group clearfix">
                                    <label for="host" class=" col-md-3">{{__('Database Hos')}}t</label>
                                    <div class="col-md-9">
                                        <input type="text" value="{{old('host') ?? 'localhost'}}" id="host" name="host"
                                               autofocus
                                               class="form-control" placeholder="Database Host (usually localhost)"/>
                                        <strong class="text-danger" id="host_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_user" class=" col-md-3">Database User</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{old('db_user') ?? ''}}" name="db_user"
                                               class="form-control" autocomplete="off"
                                               placeholder="Database user name"/>
                                        <strong class="text-danger" id="db_user_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_password" class=" col-md-3">Password</label>
                                    <div class=" col-md-9">
                                        <input type="password" value="{{old('db_password') ?? ''}}" name="db_password"
                                               class="form-control" autocomplete="off"
                                               placeholder="Database user password"/>
                                        <strong class="text-danger" id="db_password_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_name" class=" col-md-3">Database Name</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{old('db_name') ?? ''}}" name="db_name"
                                               class="form-control" placeholder="Database Name"/>
                                        <strong class="text-danger" id="db_name_error"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section clearfix">
                            <p>{{__('3. Please enter your account details for administration.')}}</p>
                            <hr/>
                            <div>
                                <div class="form-group clearfix">
                                    <label for="last_name" class=" col-md-3">Name</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{old('name') ?? ''}}" id="name"
                                               name="name" class="form-control" placeholder="Enter your name"/>
                                        <strong class="text-danger" id="name_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="email" class=" col-md-3">Email</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{old('email') ?? ''}}" name="email"
                                               class="form-control" placeholder="Enter your email"/>
                                        <strong class="text-danger" id="email_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="password" class=" col-md-3">{{__('Password')}}</label>
                                    <div class=" col-md-9">
                                        <input type="password" value="{{old('password') ?? ''}}" name="password"
                                               class="form-control" placeholder="Enter login password"/>
                                        <strong class="text-danger" id="password_error"></strong>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-info form-next form_submitter">
                                <span class="loader hide"> {{__('Installing...')}}</span>
                                <span class="button-text"><i class='fa fa-chevron-right'></i> {{__('Finish')}}</span>
                            </button>
                        </div>

                    </form>
                </div>

                <div role="tabpanel" class="tab-pane" id="finished-tab">
                    <div class="section">
                        <div class="clearfix">
                            <i class="status fa fa-check-circle-o pull-left"> </i><span class="pull-left">{{ __('Congratulation!') }} {{ __('You have successfully installed') }} <strong>  Clan Vent</strong></span>
                        </div>

                        <a class="go-to-login-page" href="{{url('login')}}">
                            <div class="text-center">
                                <div class="font"><i class="fa fa-desktop"></i></div>
                                <div>{{ __('Login to Admin Dashboard') }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="{{ static_asset('admin/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ static_asset('install/js/install.js')}}"></script>
</html>




