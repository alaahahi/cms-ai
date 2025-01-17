<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
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
    
        // إذا كان النوع صورة، أضف رابط URL كامل
        if ($setting->type === 'image') {
            $baseUrl = env('APP_IMAGE_URL', url('storage')); // افتراضياً يستخدم التخزين المحلي إذا لم يتم تحديد البيئة
            $setting->value = $baseUrl . '/' . $setting->value;
        }
    
        // إذا كان النوع JSON، قم بفك الترميز
        if ($setting->type === 'json') {
            $setting->value = json_decode($setting->value);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $setting,
        ], 200);
    }
    
}