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
            if (!Schema::hasColumn('extracted_phones', 'name')) {
                $table->string('name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('extracted_phones', 'note')) {
                $table->text('note')->nullable()->after('name');
            }
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
            if (Schema::hasColumn('extracted_phones', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('extracted_phones', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};
