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
        Schema::create('wheel_results', function (Blueprint $table) {
            $table->id();
    
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wheel_item_id')->constrained()->onDelete('cascade');
    
            $table->boolean('is_claimed')->default(false); // هل تم صرف الهدية
            $table->timestamp('claimed_at')->nullable(); // وقت صرف الهدية إن وُجد
    
            $table->string('note')->nullable(); // ملاحظات إن لزم
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_results');
    }
};
