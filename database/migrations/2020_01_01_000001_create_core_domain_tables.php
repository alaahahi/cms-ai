<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Core domain tables that historically existed outside committed migrations.
 * Safe for fresh test DBs; skipped when tables already exist in production.
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('user_type')) {
            Schema::create('user_type', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('card')) {
            Schema::create('card', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar')->nullable();
                $table->string('name_en')->nullable();
                $table->integer('day')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->string('currency', 10)->nullable();
                $table->date('expir_date')->nullable();
                $table->boolean('show_on_app')->default(true);
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('profile')) {
            Schema::create('profile', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('no')->nullable();
                $table->string('name')->nullable();
                $table->string('birthdate')->nullable();
                $table->string('certification')->nullable();
                $table->string('job')->nullable();
                $table->text('address')->nullable();
                $table->string('image')->nullable();
                $table->integer('results')->nullable();
                $table->text('family_name')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('invoice_number')->nullable();
                $table->string('card_number')->unique()->nullable();
                $table->unsignedBigInteger('card_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('user_add')->nullable();
                $table->unsignedBigInteger('cardHolder_id')->nullable();
                $table->unsignedBigInteger('user_doctor')->nullable();
                $table->unsignedBigInteger('user_accepted')->nullable();
                $table->string('created')->nullable();
                $table->string('source')->nullable();
                $table->string('cloud_image')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('appointment')) {
            Schema::create('appointment', function (Blueprint $table) {
                $table->id();
                $table->string('card_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('service_provider_id')->nullable();
                $table->dateTime('start')->nullable();
                $table->dateTime('end')->nullable();
                $table->text('note')->nullable();
                $table->integer('is_come')->default(0);
                $table->string('source')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->decimal('balance', 14, 2)->default(0);
                $table->integer('card')->default(0);
                $table->integer('card_total')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('wallet_id')->nullable();
                $table->decimal('amount', 14, 2)->default(0);
                $table->string('type')->nullable();
                $table->unsignedBigInteger('morphed_id')->nullable();
                $table->string('morphed_type')->nullable();
                $table->text('description')->nullable();
                $table->integer('is_pay')->default(0);
                $table->string('created')->nullable();
                $table->integer('card')->default(0);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('card_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('appointment');
        Schema::dropIfExists('profile');
        Schema::dropIfExists('card');
        Schema::dropIfExists('user_type');
    }
};
