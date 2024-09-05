<?php

use Illuminate\Support\Facades\Route;
use Webzow\License\Http\Controllers\LicenseController;

Route::get('/check-license', [LicenseController::class, 'validate']);
