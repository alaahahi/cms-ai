<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Legacy tables historically outside migrations (needed for sync/tests)
        if (! Schema::hasTable('results')) {
            Schema::create('results', function (Blueprint $table) {
                $table->id();
                $table->string('husband_b')->nullable();
                $table->string('husband_hb')->nullable();
                $table->string('husband_mcv')->nullable();
                $table->string('husband_mch')->nullable();
                $table->string('husband_hemoglobin_a')->nullable();
                $table->string('husband_hemoglobin_f')->nullable();
                $table->string('husband_hemoglobin_a2')->nullable();
                $table->string('husband_hbs')->nullable();
                $table->string('husband_hcv')->nullable();
                $table->string('husband_hiv')->nullable();
                $table->string('husband_tb')->nullable();
                $table->string('husband_syphilis')->nullable();
                $table->string('husband_tpha')->nullable();
                $table->string('wife_b')->nullable();
                $table->string('wife_hb')->nullable();
                $table->string('wife_mcv')->nullable();
                $table->string('wife_mch')->nullable();
                $table->string('wife_hemoglobin_a')->nullable();
                $table->string('wife_hemoglobin_f')->nullable();
                $table->string('wife_hemoglobin_a2')->nullable();
                $table->string('wife_hbs')->nullable();
                $table->string('wife_hcv')->nullable();
                $table->string('wife_hiv')->nullable();
                $table->string('wife_tb')->nullable();
                $table->string('wife_syphilis')->nullable();
                $table->string('wife_tpha')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('profile_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('doctor_reslts')) {
            Schema::create('doctor_reslts', function (Blueprint $table) {
                $table->id();
                $table->string('husband_talasyma')->nullable();
                $table->string('husband_faqar')->nullable();
                $table->string('husband_himofilya')->nullable();
                $table->string('husband_al')->nullable();
                $table->string('husband_dam')->nullable();
                $table->text('husband_note')->nullable();
                $table->string('husband_results')->nullable();
                $table->string('wife_talasyma')->nullable();
                $table->string('wife_faqar')->nullable();
                $table->string('wife_himofilya')->nullable();
                $table->string('wife_al')->nullable();
                $table->string('wife_dam')->nullable();
                $table->text('wife_note')->nullable();
                $table->string('wife_results')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('profile_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('service_categories')) {
            Schema::create('service_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar')->nullable();
                $table->string('name_en')->nullable();
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->decimal('discount', 8, 2)->default(0);
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->unsignedBigInteger('subscription_type_id')->nullable()->index();
                $table->unsignedBigInteger('legacy_category_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('providers')) {
            Schema::create('providers', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar')->nullable();
                $table->string('name_en')->nullable();
                $table->string('specialty_ar')->nullable();
                $table->string('specialty_en')->nullable();
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->integer('review_rate')->default(5);
                $table->integer('ex_year')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('legacy_card_service_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('provider_services')) {
            Schema::create('provider_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
                $table->unsignedBigInteger('service_category_id')->nullable()->index();
                $table->unsignedBigInteger('subscription_type_id')->nullable()->index();
                $table->decimal('price', 12, 2)->nullable();
                $table->string('currency', 10)->nullable();
                $table->string('working_days')->nullable();
                $table->string('working_hours')->nullable();
                $table->integer('appointments_per_day')->default(5);
                $table->date('expir_date')->nullable();
                $table->boolean('show_on_app')->default(true);
                $table->boolean('is_popular')->default(false);
                $table->unsignedBigInteger('legacy_card_service_id')->nullable()->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status')->default('active');
                $table->text('terms')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('visits')) {
            Schema::create('visits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subscription_id')->nullable()->index();
                $table->unsignedBigInteger('legacy_profile_id')->nullable()->index();
                $table->unsignedBigInteger('provider_id')->nullable()->index();
                $table->unsignedBigInteger('provider_service_id')->nullable()->index();
                $table->string('visit_type'); // lab|doctor
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->json('payload')->nullable();
                $table->timestamp('visited_at')->nullable();
                $table->unsignedBigInteger('legacy_results_id')->nullable()->unique();
                $table->unsignedBigInteger('legacy_doctor_results_id')->nullable()->unique();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('visits');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('provider_services');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('service_categories');
        // Do not drop legacy results/doctor_reslts in down if they may exist in production
    }
};
