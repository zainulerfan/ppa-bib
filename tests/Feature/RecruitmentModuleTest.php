<?php

namespace Tests\Feature;

use App\Models\RecruitmentApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecruitmentModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruitment_pages_require_an_authenticated_user(): void
    {
        $this->get(route('recruitment.letters'))
            ->assertRedirect(route('login'));
    }

    public function test_existing_user_can_sign_in_to_the_internal_portal(): void
    {
        User::factory()->create([
            'email' => 'hc@example.test',
            'password' => 'secure-password',
        ]);

        $this->post(route('login.store'), [
            'email' => 'hc@example.test',
            'password' => 'secure-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_login_page_has_an_accessible_password_visibility_toggle(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('aria-label="Tampilkan kata sandi"', false)
            ->assertSee('login-password', false);
    }

    public function test_authenticated_user_can_create_an_application_with_a_private_pdf(): void
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->create());

        $this->post(route('recruitment.applications.store'), [
            'sequence_number' => '001',
            'letter_number' => 'HC/REC/001',
            'name' => 'Karyawan Baru',
            'position' => 'OPERATOR',
            'department' => 'PRODUKSI',
            'candidate_note' => 'Kedatangan karyawan baru',
            'application_date' => '2026-11-11',
            'test_date' => '2026-11-12',
            'nik' => '31710001',
            'assignment_note' => 'Pos gabungan Trimartani',
            'gender' => 'Laki-laki',
            'application_type' => 'IM',
            'secondary_letter_number' => 'HC/IM/001',
            'phone_number' => '081234567890',
            'visitor_permit' => UploadedFile::fake()->createWithContent('izin.pdf', "%PDF-1.4\nDokumen tes"),
        ])->assertRedirect(route('recruitment.applications.index'));

        $application = RecruitmentApplication::firstOrFail();
        $this->assertDatabaseHas('recruitment_applications', [
            'id' => $application->id,
            'letter_number' => 'HC/REC/001',
            'secondary_letter_number' => 'HC/IM/001',
            'nik' => '31710001',
        ]);
        Storage::disk('local')->assertExists($application->visitor_permit_path);

        $this->get(route('recruitment.applications.index'))
            ->assertOk()
            ->assertSee('Karyawan Baru')
            ->assertSee('<th>Nama</th>', false)
            ->assertSee('<th>NIK</th>', false)
            ->assertSee('<td>31710001</td>', false)
            ->assertSee('aria-label="Cetak pengajuan Karyawan Baru"', false)
            ->assertSee('aria-label="Ubah pengajuan Karyawan Baru"', false)
            ->assertSee('aria-label="Hapus pengajuan Karyawan Baru"', false);
        $this->get(route('recruitment.print', ['type' => 'visitor-im', 'record' => $application->id]))
            ->assertOk()
            ->assertSee('HC/IM/001');
        $this->get(route('recruitment.applications.permit', $application))
            ->assertOk();
    }

    public function test_position_and_department_must_use_the_configured_options(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from(route('recruitment.applications.create'))
            ->post(route('recruitment.applications.store'), [
                'name' => 'Kandidat',
                'position' => 'ADMIN',
                'department' => 'UNKNOWN',
                'gender' => 'Laki-laki',
                'application_type' => 'IM',
            ])
            ->assertSessionHasErrors(['position', 'department']);

        $this->assertDatabaseCount('recruitment_applications', 0);
    }

    public function test_authenticated_user_can_create_ppe_and_uniform_requests(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('recruitment.ppe.store'), [
            'name' => 'Karyawan Baru',
            'position' => 'MECHANIC',
            'shoes' => '42',
            'helmet' => '1',
            'safety_glasses' => '1',
            'ear_plug' => '1',
            'gloves' => '2 pasang',
            'mask' => '1',
            'process_status' => 'Dalam proses',
        ])->assertRedirect(route('recruitment.ppe.index'));

        $this->assertDatabaseHas('recruitment_ppe_requests', [
            'name' => 'Karyawan Baru',
            'shoes' => '42',
            'process_status' => 'Dalam proses',
        ]);
        $this->get(route('recruitment.ppe.index'))
            ->assertOk()
            ->assertSee('aria-label="Cetak APD Karyawan Baru"', false)
            ->assertSee('aria-label="Ubah APD Karyawan Baru"', false)
            ->assertSee('aria-label="Hapus APD Karyawan Baru"', false);

        $this->post(route('recruitment.uniforms.store'), [
            'finger_number' => '31710001',
            'name' => 'Karyawan Baru',
            'department' => 'PLANT',
            'position' => 'MECHANIC',
            'uniform_color' => 'Biru',
            'uniform_size' => 'L',
            'pickup_quantity' => 2,
        ])->assertRedirect(route('recruitment.uniforms.index'));

        $this->assertDatabaseHas('recruitment_uniform_requests', [
            'finger_number' => '31710001',
            'name' => 'Karyawan Baru',
            'pickup_quantity' => 2,
        ]);
        $this->get(route('recruitment.uniforms.index'))
            ->assertOk()
            ->assertSee('aria-label="Cetak seragam Karyawan Baru"', false)
            ->assertSee('aria-label="Ubah seragam Karyawan Baru"', false)
            ->assertSee('aria-label="Hapus seragam Karyawan Baru"', false);
    }

    public function test_print_templates_render_for_each_requested_document_type(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['visitor-im', 'psikotest', 'tes-praktek', 'refreshment', 'ro', 'apd', 'seragam'] as $type) {
            $this->get(route('recruitment.print', ['type' => $type]))
                ->assertOk()
                ->assertSee('PT PUTRA PERKASA ABADI');
        }

        $this->get(route('recruitment.print', ['type' => 'unknown']))
            ->assertNotFound();
    }

    public function test_ppe_and_uniform_requests_can_be_linked_to_recruitment_application(): void
    {
        $this->actingAs(User::factory()->create());

        $application = RecruitmentApplication::create([
            'name' => 'Budi Santoso',
            'position' => 'OPERATOR',
            'department' => 'PRODUKSI',
            'gender' => 'Laki-laki',
            'application_type' => 'INDUKSI',
            'nik' => '6472010101900001',
        ]);

        $this->post(route('recruitment.ppe.store'), [
            'recruitment_application_id' => $application->id,
            'name' => 'Budi Santoso',
            'position' => 'OPERATOR',
            'shoes' => '41',
            'process_status' => 'Dalam proses',
        ])->assertRedirect(route('recruitment.ppe.index'));

        $this->assertDatabaseHas('recruitment_ppe_requests', [
            'recruitment_application_id' => $application->id,
            'name' => 'Budi Santoso',
        ]);

        $this->post(route('recruitment.uniforms.store'), [
            'recruitment_application_id' => $application->id,
            'name' => 'Budi Santoso',
            'department' => 'PRODUKSI',
            'position' => 'OPERATOR',
            'pickup_quantity' => 1,
        ])->assertRedirect(route('recruitment.uniforms.index'));

        $this->assertDatabaseHas('recruitment_uniform_requests', [
            'recruitment_application_id' => $application->id,
            'name' => 'Budi Santoso',
        ]);

        $this->assertCount(1, $application->fresh()->ppeRequests);
        $this->assertCount(1, $application->fresh()->uniformRequests);
    }

    public function test_applications_filter_and_search_by_keyword_and_position(): void
    {
        $this->actingAs(User::factory()->create());

        RecruitmentApplication::create([
            'name' => 'Ahmad Dani',
            'position' => 'OPERATOR',
            'department' => 'PRODUKSI',
            'gender' => 'Laki-laki',
            'application_type' => 'INDUKSI',
            'nik' => '1111222233334444',
        ]);

        RecruitmentApplication::create([
            'name' => 'Siti Aminah',
            'position' => 'MECHANIC',
            'department' => 'PLANT',
            'gender' => 'Perempuan',
            'application_type' => 'TES PRAKTEK',
            'nik' => '5555666677778888',
        ]);

        $this->get(route('recruitment.applications.index', ['search' => 'Ahmad']))
            ->assertOk()
            ->assertSee('Ahmad Dani')
            ->assertDontSee('Siti Aminah');

        $this->get(route('recruitment.applications.index', ['position' => 'MECHANIC']))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertDontSee('Ahmad Dani');
    }

    public function test_authenticated_user_can_export_applications_with_active_filters(): void
    {
        $this->actingAs(User::factory()->create());

        RecruitmentApplication::create([
            'name' => 'Kandidat Diekspor',
            'position' => 'OPERATOR',
            'department' => 'PRODUKSI',
            'gender' => 'Laki-laki',
            'application_type' => 'INDUKSI',
            'nik' => '9999000011112222',
        ]);

        RecruitmentApplication::create([
            'name' => 'Kandidat Lain',
            'position' => 'MECHANIC',
            'department' => 'PLANT',
            'gender' => 'Laki-laki',
            'application_type' => 'RO',
            'nik' => '8888000011112222',
        ]);

        $response = $this->get(route('recruitment.applications.export', ['position' => 'OPERATOR']));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Kandidat Diekspor', $content);
        $this->assertStringNotContainsString('Kandidat Lain', $content);
    }

    public function test_authenticated_user_can_export_ppe_and_uniform_requests(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('recruitment.ppe.store'), [
            'name' => 'Penerima APD',
            'position' => 'OPERATOR',
            'shoes' => '42',
            'process_status' => 'Dalam proses',
        ]);

        $this->post(route('recruitment.uniforms.store'), [
            'name' => 'Penerima Seragam',
            'department' => 'PRODUKSI',
            'position' => 'OPERATOR',
            'pickup_quantity' => 2,
        ]);

        $responsePpe = $this->get(route('recruitment.ppe.export', ['search' => 'Penerima']));
        $responsePpe->assertOk();
        $this->assertStringContainsString('text/csv', (string) $responsePpe->headers->get('content-type'));
        $this->assertStringContainsString('Penerima APD', $responsePpe->streamedContent());

        $responseUniform = $this->get(route('recruitment.uniforms.export', ['department' => 'PRODUKSI']));
        $responseUniform->assertOk();
        $this->assertStringContainsString('text/csv', (string) $responseUniform->headers->get('content-type'));
        $this->assertStringContainsString('Penerima Seragam', $responseUniform->streamedContent());
    }

    public function test_authenticated_user_can_download_server_generated_pdf_document(): void
    {
        $this->actingAs(User::factory()->create());

        $application = RecruitmentApplication::create([
            'name' => 'Kandidat Uji PDF',
            'position' => 'OPERATOR',
            'department' => 'PRODUKSI',
            'gender' => 'Laki-laki',
            'application_type' => 'IM',
            'letter_number' => 'DOC/REC/BIB/2026/001',
        ]);

        $response = $this->get(route('recruitment.print.pdf', [
            'type' => 'visitor-im',
            'record' => $application->id,
        ]));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('dokumen-visitor-im-'.$application->id.'.pdf', (string) $response->headers->get('content-disposition'));
    }
}
