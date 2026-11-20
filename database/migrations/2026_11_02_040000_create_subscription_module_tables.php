<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('subscription_types')) {
            Schema::create('subscription_types', function (Blueprint $table) {
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
                $table->unsignedBigInteger('legacy_card_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('subscription_number')->nullable()->unique();
                $table->foreignId('subscription_type_id')->nullable()->constrained('subscription_types')->nullOnDelete();
                $table->unsignedBigInteger('subscriber_id')->nullable()->index();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status')->default('pending')->index();
                $table->string('phone_number')->nullable()->index();
                $table->text('address')->nullable();
                $table->string('invoice_number')->nullable();
                $table->string('source')->nullable();
                $table->unsignedBigInteger('legacy_profile_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('subscription_members')) {
            Schema::create('subscription_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
                $table->string('name');
                $table->string('relation')->default('self');
                $table->string('birthdate')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
                $table->string('type')->nullable();
                $table->string('path');
                $table->string('original_name')->nullable();
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('renewal_history')) {
            Schema::create('renewal_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
                $table->date('renewed_at')->nullable();
                $table->date('previous_end_date')->nullable();
                $table->date('new_end_date')->nullable();
                $table->decimal('amount', 12, 2)->nullable();
                $table->unsignedBigInteger('renewed_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('renewal_history');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('subscription_members');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_types');
    }
};
