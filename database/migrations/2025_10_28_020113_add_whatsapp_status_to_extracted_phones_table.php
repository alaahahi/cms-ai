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
        Schema::table('extracted_phones', function (Blueprint $table) {
            $table->integer('whatsapp_status')->nullable()->comment('1 = موجود على واتساب, 0 = غير موجود, null = لم يتم التحقق');
            $table->timestamp('whatsapp_checked_at')->nullable()->comment('تاريخ آخر تحقق من الواتساب');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extracted_phones', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_status', 'whatsapp_checked_at']);
        });
    }
};
