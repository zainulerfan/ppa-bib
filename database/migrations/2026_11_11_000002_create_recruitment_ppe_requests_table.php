<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_ppe_requests', function (Blueprint $table) {
            $table->id();
            $table->date('created_date')->nullable();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('shoes')->nullable();
            $table->string('helmet')->nullable();
            $table->string('safety_glasses')->nullable();
            $table->text('note')->nullable();
            $table->string('ear_plug')->nullable();
            $table->string('gloves')->nullable();
            $table->string('mask')->nullable();
            $table->date('preparation_date')->nullable();
            $table->string('nrp')->nullable();
            $table->string('process_status')->default('Dalam proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_ppe_requests');
    }
};
