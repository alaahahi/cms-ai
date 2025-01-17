<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppSettingsController extends Controller
{

    public function indexPage()
    {
        $config = SystemConfig::first();
        return Inertia::render('FormRegistrationSaved', ['url'=>$this->ur,'config'=>$config]);
    }

    /**
     * استرجاع جميع الإعدادات.
     */
    public function index()
    {
        // جلب جميع الإعدادات
        $settings = DB::table('app_settings')->get();

        // تحويل القيم التي تكون بصيغة JSON
        $settings = $settings->map(function ($setting) {
            if ($setting->type === 'json') {
                $setting->value = json_decode($setting->value);
            }
            return $setting;
        });

        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ], 200);
    }

    /**
     * استرجاع إعداد معين باستخدام المفتاح.
     */
    public function show($key)
    {
        $setting = DB::table('app_settings')->where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Setting not found.',
            ], 404);
        }

        if ($setting->type === 'json') {
            $setting->value = json_decode($setting->value);
        }

        return response()->json([
            'status' => 'success',
            'data' => $setting,
        ], 200);
    }
}