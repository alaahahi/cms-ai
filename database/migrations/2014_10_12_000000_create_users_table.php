<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->decimal('percentage', 8, 2)->nullable();
            $table->string('device')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('verification_date')->nullable();
            $table->string('verification_user_type')->nullable();
            $table->text('family_members_names')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('gender')->nullable();
            $table->string('token')->nullable();
            $table->string('network')->nullable();
            $table->string('fcm_token')->nullable();
            $table->boolean('can_lots')->nullable();
            $table->boolean('is_band')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('morphed_id')->nullable();
            $table->string('morphed_type')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
