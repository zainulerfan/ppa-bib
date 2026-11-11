<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_uniform_requests', function (Blueprint $table) {
            $table->id();
            $table->date('created_date')->nullable();
            $table->string('finger_number')->nullable();
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('uniform_color')->nullable();
            $table->string('uniform_size')->nullable();
            $table->text('note')->nullable();
            $table->date('request_date')->nullable();
            $table->unsignedInteger('pickup_quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_uniform_requests');
    }
};
