<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function getSystemDate(){
        return Setting::first()->system_date?? now()->toDate();
    }
    protected function getSystemSetting(){
        return Setting::first();
    }
}
