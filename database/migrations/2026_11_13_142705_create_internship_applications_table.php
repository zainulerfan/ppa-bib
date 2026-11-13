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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();

            // Tahap A: Data Dasar & Screening
            $table->string('name');
            $table->date('birth_date');
            $table->string('nik', 30)->index();
            $table->string('domicile');
            $table->string('institution')->nullable(); // Universitas / Politeknik / Sekolah
            $table->string('major')->nullable(); // Program Studi / Jurusan
            $table->string('phone_number', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('screening_status')->default('terkirim'); // terkirim, on_proses, done

            // Tahap B: Briefing
            $table->date('briefing_date')->nullable();
            $table->string('briefing_status')->nullable(); // scheduled, done
            $table->text('briefing_notes')->nullable();

            // Tahap C: Medical Check-Up (MCU)
            $table->date('mcu_date')->nullable();
            $table->string('mcu_location')->nullable();
            $table->text('mcu_terms')->nullable(); // Ketentuan / syarat MCU
            $table->string('mcu_status')->nullable(); // scheduled, review, follow_up, fit_to_work

            // Tahap D: Registrasi & Dokumen
            $table->string('ktp_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('kk_path')->nullable();
            $table->string('spdk_path')->nullable(); // Surat Pernyataan Diri & Komitmen
            $table->string('registration_status')->default('pending'); // pending, done_upload

            // Tahap E: Mine Permit
            $table->string('mine_permit_status')->nullable(); // diajukan, released
            $table->string('mine_permit_number')->nullable();
            $table->date('mine_permit_date')->nullable();

            // Tahap F: Kedatangan Onsite (Site BIB)
            $table->boolean('is_local')->default(false);
            $table->date('departure_date')->nullable();
            $table->text('arrival_information')->nullable();
            $table->string('onsite_status')->default('pending'); // pending, arrived, active, completed

            // Tracking Pipeline Stage
            $table->string('current_stage')->default('A'); // A, B, C, D, E, F, completed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
