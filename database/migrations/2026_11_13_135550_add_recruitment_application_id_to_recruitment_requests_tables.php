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
        Schema::table('recruitment_ppe_requests', function (Blueprint $table) {
            $table->foreignId('recruitment_application_id')
                ->nullable()
                ->after('id')
                ->constrained('recruitment_applications')
                ->nullOnDelete();
        });

        Schema::table('recruitment_uniform_requests', function (Blueprint $table) {
            $table->foreignId('recruitment_application_id')
                ->nullable()
                ->after('id')
                ->constrained('recruitment_applications')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitment_uniform_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recruitment_application_id');
        });

        Schema::table('recruitment_ppe_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recruitment_application_id');
        });
    }
};
