<?php

namespace Webzow\License;

use Illuminate\Support\ServiceProvider;
use Webzow\License\Http\Controllers\LicenseController;

class LicenseServiceProvider extends ServiceProvider
{
    public function register ()
    {
       //
    }

    public function boot ()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('webzow-license.php'),
        ], 'webzow-license-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        return (new LicenseController)->validate();
    }
}
