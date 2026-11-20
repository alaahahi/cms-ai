<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('sales_reps')) {
            Schema::create('sales_reps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->decimal('commission_rate', 8, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('code')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sales_rep_id')->constrained('sales_reps')->cascadeOnDelete();
                $table->unsignedBigInteger('subscription_id')->nullable()->index();
                $table->unsignedBigInteger('legacy_profile_id')->nullable()->index();
                $table->unsignedBigInteger('subscription_type_id')->nullable()->index();
                $table->decimal('amount', 12, 2)->default(0);
                $table->unsignedInteger('cards_count')->default(1);
                $table->date('sale_date')->nullable();
                $table->string('status')->default('completed')->index();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('legacy_transaction_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sales_rep_id')->constrained('sales_reps')->cascadeOnDelete();
                $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
                $table->decimal('rate', 8, 2)->default(0);
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('status')->default('pending')->index();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('targets')) {
            Schema::create('targets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sales_rep_id')->constrained('sales_reps')->cascadeOnDelete();
                $table->unsignedSmallInteger('year');
                $table->unsignedTinyInteger('month');
                $table->decimal('target_amount', 12, 2)->default(0);
                $table->unsignedInteger('target_cards')->default(0);
                $table->decimal('achieved_amount', 12, 2)->default(0);
                $table->unsignedInteger('achieved_cards')->default(0);
                $table->timestamps();
                $table->unique(['sales_rep_id', 'year', 'month']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('targets');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sales_reps');
    }
};
