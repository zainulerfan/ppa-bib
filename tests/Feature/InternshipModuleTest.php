<?php

namespace Tests\Feature;

use App\Models\InternshipApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InternshipModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_internship_pages_require_authentication(): void
    {
        $this->get(route('internships.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_internship_kanban_pipeline(): void
    {
        $this->actingAs(User::factory()->create());

        InternshipApplication::create([
            'name' => 'Peserta Demo Kanban',
            'birth_date' => '2002-01-01',
            'nik' => '6302000000000001',
            'domicile' => 'Batulicin',
            'institution' => 'Universitas Lambung Mangkurat',
            'screening_status' => 'terkirim',
        ]);

        $this->get(route('internships.index'))
            ->assertOk()
            ->assertSee('Web Magang (Internship)')
            ->assertSee('Pipeline Visual (Kanban Board)')
            ->assertSee('Peserta Demo Kanban');
    }

    public function test_authenticated_user_can_register_new_internship_participant(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('internships.store'), [
            'name' => 'Ahmad Fauzi',
            'birth_date' => '2003-04-10',
            'nik' => '6302011004030009',
            'domicile' => 'Banjarmasin',
            'institution' => 'Politeknik Negeri Banjarmasin',
            'major' => 'Teknik Mesin',
            'phone_number' => '08123456789',
            'email' => 'ahmad.fauzi@example.test',
            'screening_status' => 'terkirim',
        ]);

        $this->assertDatabaseHas('internship_applications', [
            'name' => 'Ahmad Fauzi',
            'nik' => '6302011004030009',
            'current_stage' => 'A',
        ]);

        $internship = InternshipApplication::where('nik', '6302011004030009')->firstOrFail();
        $response->assertRedirect(route('internships.show', $internship));
    }

    public function test_authenticated_user_can_update_pipeline_stages_and_upload_documents(): void
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->create());

        $internship = InternshipApplication::create([
            'name' => 'Budi Santoso',
            'birth_date' => '2002-05-15',
            'nik' => '6302011505020008',
            'domicile' => 'Tanah Bumbu',
            'screening_status' => 'done',
        ]);

        // 1. Update Tahap B: Briefing
        $this->put(route('internships.stage.update', ['internship' => $internship, 'stage' => 'B']), [
            'briefing_date' => '2026-11-20',
            'briefing_status' => 'done',
            'briefing_notes' => 'Briefing selesai dihadiri peserta.',
        ])->assertRedirect(route('internships.show', $internship));

        $internship->refresh();
        $this->assertEquals('done', $internship->briefing_status);
        $this->assertEquals('C', $internship->current_stage);

        // 2. Update Tahap C: MCU
        $this->put(route('internships.stage.update', ['internship' => $internship, 'stage' => 'C']), [
            'mcu_date' => '2026-11-22',
            'mcu_location' => 'Klinik Tirta Batulicin',
            'mcu_status' => 'fit_to_work',
        ])->assertRedirect(route('internships.show', $internship));

        $internship->refresh();
        $this->assertEquals('fit_to_work', $internship->mcu_status);
        $this->assertEquals('D', $internship->current_stage);

        // 3. Update Tahap D: Unggah Dokumen Privat
        $ktpFile = UploadedFile::fake()->create('ktp.pdf', 500, 'application/pdf');
        $cvFile = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');
        $kkFile = UploadedFile::fake()->create('kk.pdf', 500, 'application/pdf');
        $spdkFile = UploadedFile::fake()->create('spdk.pdf', 500, 'application/pdf');

        $this->put(route('internships.stage.update', ['internship' => $internship, 'stage' => 'D']), [
            'ktp_file' => $ktpFile,
            'cv_file' => $cvFile,
            'kk_file' => $kkFile,
            'spdk_file' => $spdkFile,
        ])->assertRedirect(route('internships.show', $internship));

        $internship->refresh();
        $this->assertEquals('done_upload', $internship->registration_status);
        $this->assertNotNull($internship->ktp_path);
        $this->assertEquals('E', $internship->current_stage);
        Storage::disk('local')->assertExists($internship->ktp_path);

        // 4. Unduh Berkas Privat
        $this->get(route('internships.document.download', ['internship' => $internship, 'type' => 'ktp']))
            ->assertOk();

        // 5. Update Tahap E: Mine Permit
        $this->put(route('internships.stage.update', ['internship' => $internship, 'stage' => 'E']), [
            'mine_permit_status' => 'released',
            'mine_permit_number' => 'MP-BIB-2026-099',
            'mine_permit_date' => '2026-11-25',
        ])->assertRedirect(route('internships.show', $internship));

        $internship->refresh();
        $this->assertEquals('released', $internship->mine_permit_status);
        $this->assertEquals('F', $internship->current_stage);

        // 6. Update Tahap F: Kedatangan Onsite
        $this->put(route('internships.stage.update', ['internship' => $internship, 'stage' => 'F']), [
            'is_local' => true,
            'departure_date' => '2026-11-28',
            'arrival_information' => 'Tiba di Mess BIB km 30',
            'onsite_status' => 'arrived',
        ])->assertRedirect(route('internships.show', $internship));

        $internship->refresh();
        $this->assertTrue($internship->is_local);
        $this->assertEquals('arrived', $internship->onsite_status);
    }

    public function test_authenticated_user_can_download_spdk_blank_template(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('internships.spdk.template'));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('FORMAT_SPDK_MAGANG_PPA_SITE_BIB.pdf', (string) $response->headers->get('content-disposition'));
    }
}
