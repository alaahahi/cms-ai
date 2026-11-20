<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('ticket_categories')) {
            Schema::create('ticket_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar');
                $table->string('name_en')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->text('description')->nullable();
                $table->string('priority')->default('medium'); // low,medium,high
                $table->string('status')->default('new')->index(); // new,in_progress,resolved,closed
                $table->unsignedBigInteger('category_id')->nullable()->index();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->unsignedBigInteger('assigned_to')->nullable()->index();
                $table->unsignedBigInteger('subscription_id')->nullable()->index();
                $table->unsignedBigInteger('legacy_pending_request_id')->nullable()->unique();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ticket_messages')) {
            Schema::create('ticket_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('message');
                $table->boolean('is_internal')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->unsignedBigInteger('subscription_id')->nullable()->index();
                $table->unsignedBigInteger('legacy_order_id')->nullable()->unique();
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('IQD');
                $table->date('due_date')->nullable();
                $table->string('status')->default('draft')->index(); // draft,sent,paid,overdue,cancelled
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
                $table->string('payment_method')->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('transaction_id')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('received_by')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('provider_dues')) {
            Schema::create('provider_dues', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('provider_id')->index();
                $table->unsignedBigInteger('visit_id')->nullable()->index();
                $table->decimal('amount', 12, 2)->default(0);
                $table->date('period_start')->nullable();
                $table->date('period_end')->nullable();
                $table->string('status')->default('pending')->index(); // pending,paid
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('analytics_snapshots')) {
            Schema::create('analytics_snapshots', function (Blueprint $table) {
                $table->id();
                $table->string('metric_key');
                $table->decimal('metric_value', 14, 2)->default(0);
                $table->json('meta')->nullable();
                $table->date('snapshot_date')->nullable();
                $table->timestamps();
                $table->index(['metric_key', 'snapshot_date']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('analytics_snapshots');
        Schema::dropIfExists('provider_dues');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_categories');
    }
};
