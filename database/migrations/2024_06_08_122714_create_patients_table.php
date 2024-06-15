<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('patient_number')->unique();
            $table->date('birth_date')->nullable();
            $table->date('therapy_for')->nullable();
            $table->string('address')->nullable();
            $table->text('information')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('parents_email')->nullable();
            $table->text('parents_work')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active'); // Hinzufügen des Status
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
