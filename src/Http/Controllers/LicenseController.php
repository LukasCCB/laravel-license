<?php

namespace Webzow\License\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LicenseController extends BaseController
{
    protected string $endpointUrl = "https://license.webzow.com/api/check-license";
    protected string $appLicense;

    public function __construct()
    {
        $this->appLicense = config('webzow-license.key');
    }

    public function getAppData (): array
    {
        $app = config('app');

        // is a valid UUID
        if (!preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){3}-[a-f\d]{12}$/i', $this->appLicense)) {
            abort(401, 'Unauthorized');
        }

        return [
            'key' =>  $this->appLicense,
            'app' => [
                'name' => $app['name'],
                'url' => $app['url'],
                'hash' => $app['key'],
                'timezone' => $app['timezone'],
                'locale' => $app['locale'],
                'is_debug' => $app['debug']
            ],
        ];
    }

    public function validate()
    {
        $cacheKey = 'license_validation_result';

        return Cache::remember($cacheKey, 60, function () {
            $locale = config('app.locale') ?? 'en';
            $response = Http::post($this->endpointUrl.'/'.$this->appLicense.'/'.$locale , self::getAppData());

            if ($response->status() === 200) {
                return true;
            }

            abort(401, 'Unauthorized');
        });
    }
}
