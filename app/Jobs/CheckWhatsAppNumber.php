<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ExtractedPhone;
use App\Models\DataCv;
use App\Models\SystemConfig;

class CheckWhatsAppNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber;
    protected $phoneId;
    protected $tableName;
    protected $apiKey;
    protected $baseUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber, $phoneId, $tableName = 'extracted_phones')
    {
        $this->phoneNumber = $phoneNumber;
        $this->phoneId = $phoneId;
        $this->tableName = $tableName;
        
        $config = SystemConfig::first();
        $this->apiKey = $config->api_key ?? '';
        $this->baseUrl = env('WHATSAPP_API_URL', 'https://api.textmebot.com/send.php');
    }

    /**
     * الحصول على رسالة ترويجية عشوائية
     */
    protected function getPromotionalMessage()
    {
        $messages = [
            'شركة الهدف المباشر ستقوم بالتواصل معك لتقدم عرض بطاقة جديدة مخصص لك ولعائلتك',
            'عروض حصرية على البطاقات! شركة الهدف المباشر تقدم لك خصومات مميزة',
            'فرصة ذهبية! احصل على بطاقة مخصصة لك ولعائلتك بأسعار تنافسية',
            'شركة الهدف المباشر: نقدم لك أفضل عروض البطاقات الصحية',
            'عرض خاص! احصل على بطاقة جديدة مخصصة لك مع خصومات حصرية',
            'شركة الهدف المباشر تقدم لك أفضل الخدمات والعروض على البطاقات',
            'لا تفوت فرصتك! عروض محدودة على البطاقات المخصصة',
            'شركة الهدف المباشر: خدمات صحية متميزة وبطاقات تناسب جميع العائلات',
            'عروض خاصة لجميع العائلات! احصل على بطاقة مخصصة الآن',
            'شركة الهدف المباشر تقدم أفضل العروض والخدمات الصحية لك',
        ];
        
        return $messages[array_rand($messages)];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('جارٍ التحقق من رقم الواتساب: ' . $this->phoneNumber);
            
            // إرسال رسالة ترويجية واحدة فقط للتحقق من وجود الرقم
            // هذه طريقة آمنة لتجنب الحظر - رسالة ترويجية عشوائية من 10 نماذج
            $promotionalMessage = $this->getPromotionalMessage();
            
            Log::info('إرسال رسالة ترويجية للتحقق إلى: ' . $this->phoneNumber);
            
            $response = Http::timeout(10)->get($this->baseUrl, [
                'recipient' => $this->phoneNumber,
                'apikey' => $this->apiKey,
                'text' => $promotionalMessage, // رسالة ترويجية عشوائية للتحقق
                'json' => 'yes',
            ]);
            
            // تحديث حالة الرد في قاعدة البيانات
            if ($this->tableName === 'data_cv') {
                $phone = \App\Models\DataCv::find($this->phoneId);
            } else {
                $phone = ExtractedPhone::find($this->phoneId);
            }
            
            if (!$phone) {
                Log::error('Phone not found: ' . $this->phoneId . ' in table: ' . $this->tableName);
                return;
            }
            
            if ($response->successful()) {
                $responseBody = $response->json();
                
                // فحص الاستجابة لمعرفة إذا كان الرقم موجود على واتساب
                if (isset($responseBody['status']) && $responseBody['status'] == 'success') {
                    // الرقم موجود على واتساب
                    $phone->whatsapp_status = 1;
                    Log::info('✅ الرقم ' . $this->phoneNumber . ' موجود على واتساب');
                } else {
                    // الرقم غير موجود أو فشل الإرسال
                    $phone->whatsapp_status = 0;
                    Log::info('❌ الرقم ' . $this->phoneNumber . ' غير موجود على واتساب');
                }
            } else {
                // فشل الإرسال - الرقم غير موجود على واتساب
                $phone->whatsapp_status = 0;
                Log::warning('فشل التحقق من الرقم: ' . $this->phoneNumber);
            }
            
            $phone->whatsapp_checked_at = now();
            $phone->save();
            
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من رقم الواتساب: ' . $e->getMessage(), [
                'phone_number' => $this->phoneNumber,
                'phone_id' => $this->phoneId,
                'table' => $this->tableName
            ]);
            
            // في حالة الخطأ، نعتبر الرقم غير متأكد
            if ($this->tableName === 'data_cv') {
                $phone = \App\Models\DataCv::find($this->phoneId);
            } else {
                $phone = ExtractedPhone::find($this->phoneId);
            }
            
            if ($phone) {
                $phone->whatsapp_status = null;
                $phone->whatsapp_checked_at = now();
                $phone->save();
            }
        }
    }
}
