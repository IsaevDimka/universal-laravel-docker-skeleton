<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\User;
use App\Traits\PhoneNumberFormattingTrait;
use Browser;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class DebugController extends Controller
{
    use PhoneNumberFormattingTrait;
    protected $app;

    function __construct(Application $application)
    {
        $this->app = $application;
    }

    public function renderView(Request $request)
    {
        $name = $request->name ?? 'welcome';

        if (! View::exists($name)) {
            abort(404);
        }
        return view($name);
    }

    public function index(Request $request)
    {
//        \BeyondCode\ServerTiming\Facades\ServerTiming::start('debug');

        \App\Services\DebugService::start();

        $result = [];
        $result['environment'] = $this->app->environment();
        $result['version'] = $this->app->version();
        $result['timestamp'] = now()->timestamp;
        $result['locale'] = $this->app->getLocale();
        $result['isDownForMaintenance'] = $this->app->isDownForMaintenance();
        $result['request'] = $request->all();
        $result['method'] = $request->method();
//        $result['user'] = $request->user();
        $result['ip'] = $request->ip();
        $result['UA'] = $request->userAgent();
        $result['secure'] = $request->secure();
        $result['fullUrl'] = $request->fullUrl();
        $result['session'] = $request->session()->all();
        $result['route'] = $request->route();

        $f = $request->get('f');

        if (method_exists($this, $method = Str::studly($f))) {
            $result[(string)$f] = $this->{$method}();
        }

//        \BeyondCode\ServerTiming\Facades\ServerTiming::addMetric('User: '.$request->user()->id);
//        \BeyondCode\ServerTiming\Facades\ServerTiming::stop('debug');
//        $result['ServerTiming'] = round(\BeyondCode\ServerTiming\Facades\ServerTiming::getDuration('debug')) .' ms';

        $result['meta'] = \App\Services\DebugService::result();
        return response()->json($result);
    }

    private function view($template = null)
    {
        return view($template);
    }

    private function phpinfo()
    {
        return phpinfo();
    }

    private function test()
    {
        return \App\Models\Locale::getTableName();
    }

    private function ddd()
    {
        ddd(__METHOD__);
    }

    private function geoip()
    {
        $result['IP'] = '118.69.35.47';
        $geoip = geoip()->getLocation($result['IP']);
        $result['GeoIP'] = $geoip->toArray();
        return $result;
    }

    private function testLogToTelegram()
    {
        $data = [
            'first' => 'test',
        ];
        $type = null; # without param type or clear or message
        logger()->channel('telegram')->error("test message", [
            'type' => $type,
            'data' => $data,
        ]);
    }

    private function testLogToMongoDB()
    {
        $payload = \request()->toArray();
        logger()->channel('mongodb')->error('Test message', [
            'collection' => 'TestCollection',
            'payload'    => $payload,
        ]);
    }

    private function sentry()
    {
        throw new \Exception('My first Sentry error!');
    }

    private function stats()
    {
        $stats['users_is_active'] = User::query()->count();
        $stats['roles'] = Role::all()->pluck('name')->toArray();
        $stats['permissions'] = Permission::all()->pluck('name')->toArray();
        $stats['migrations'] = DB::table(config('database.migrations'))->select('migration')->get()->pluck('migration')->toArray();

        return $stats;
    }

    private function phoneNumber()
    {
        $phones_vn = ['792674228', '0909994816', '+84909994816'];
        $phones_id = ['+628099994861', '628909994861'];
        $country_iso_code = 'id';
        $phone = $phones_id[1];
        return $this->phoneNumberFormatting($phone, $country_iso_code);
    }

    private function runTestJob($payload = [])
    {
        $payload = [
            'environment' => app()->environment(),
            'time'        => now()->format('d-m-Y H:i:s'),
            'message'     => 'Running TestJob',
        ];

        if (app()->environment('production', 'staging')) {
            dispatch(new \App\Jobs\TestJob($payload));
        } else {
            dispatch_now(new \App\Jobs\TestJob($payload));
        }
    }

    private function timezone()
    {
        $timezones = \App\Models\Timezone::all();
        return response()->json($timezones);
    }

    private function browserDetect()
    {
      return Browser::detect();
    }

    private function clickhouse()
    {
        $clickhouse = "clickhouse";
    }

    private function datatables()
    {
        $data = Language::query();
        return DataTables::eloquent($data)->toJson();
    }

}
