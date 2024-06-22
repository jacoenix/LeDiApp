<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('temp_school_postcode')->nullable();
        });

        // Copy data from `school_postcode` to `temp_school_postcode`
        DB::statement('UPDATE patients SET temp_school_postcode = school_postcode');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('temp_school_postcode');
        });
    }
};
