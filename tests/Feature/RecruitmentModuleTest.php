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
}
