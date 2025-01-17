<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\AppSettings;

class AppSettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات.
     */
    public function index()
    {
        $settings = DB::table('app_settings')->get();
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);

    
    }

    /**
     * حفظ أو تحديث إعداد.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // صورة شريط التمرير
            'description' => 'nullable|string',
        ]);

        // إذا كان الإعداد يحتوي على صورة
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('settings', 'public');
            $validated['value'] = $path;
        }

        // تحديث الإعدادات باستخدام النموذج
        if ($request->hasFile('image')) {
            // رفع الصورة وحفظ المسار
            $path = $request->file('image')->store('settings', 'public');
            $validated['value'] = $path;
        }
        
        // تحديث أو إدخال البيانات باستخدام Eloquent
        AppSettings::updateOrCreate(
            ['key' => $validated['key']], // شرط البحث
            [
                'value' => $validated['value'],
                'description' => $validated['description'],
                'type' => $request->hasFile('image') ? 'image' : 'string',
            ]
        );
        
        return redirect()->back()->with('success', 'Setting updated successfully.');
    }
}
