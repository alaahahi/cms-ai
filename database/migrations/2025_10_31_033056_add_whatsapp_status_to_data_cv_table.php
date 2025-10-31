<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_cv', function (Blueprint $table) {
            $table->integer('whatsapp_status')->nullable()->after('address')->comment('1 = موجود على واتساب, 0 = غير موجود, 2 = غير موجود, 3 = منقول, null = لم يتم التحقق');
            $table->timestamp('whatsapp_checked_at')->nullable()->after('whatsapp_status')->comment('تاريخ آخر تحقق من الواتساب');
        });
    }

    public function down()
    {
        Schema::table('data_cv', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_status', 'whatsapp_checked_at']);
        });
    }
};
