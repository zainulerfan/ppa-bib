<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_applications', function (Blueprint $table) {
            $table->id();
            $table->string('sequence_number')->nullable();
            $table->string('letter_number')->nullable();
            $table->string('name');
            $table->string('position');
            $table->string('department');
            $table->string('candidate_note')->nullable();
            $table->date('application_date')->nullable();
            $table->date('test_date')->nullable();
            $table->string('nik')->nullable();
            $table->string('assignment_note')->nullable();
            $table->string('gender');
            $table->string('application_type');
            $table->string('secondary_letter_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('visitor_permit_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_applications');
    }
};
