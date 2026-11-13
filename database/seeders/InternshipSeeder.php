<?php

namespace Database\Seeders;

use App\Models\InternshipApplication;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    public function run(): void
    {
        $participants = [
            [
                'name' => 'Fajar Nurhadi',
                'birth_date' => '2003-05-14',
                'nik' => '6302011405030001',
                'domicile' => 'Banjarmasin',
                'institution' => 'Politeknik Negeri Banjarmasin',
                'major' => 'Teknik Mesin Otomotif',
                'phone_number' => '081255443322',
                'email' => 'fajar.nurhadi@example.test',
                'screening_status' => 'terkirim',
            ],
            [
                'name' => 'Dwi Lestari',
                'birth_date' => '2002-11-20',
                'nik' => '6302012011020002',
                'domicile' => 'Banjarbaru',
                'institution' => 'Universitas Lambung Mangkurat',
                'major' => 'Teknik Pertambangan',
                'phone_number' => '081399887766',
                'email' => 'dwi.lestari@example.test',
                'screening_status' => 'done',
                'briefing_date' => '2026-11-18',
                'briefing_status' => 'scheduled',
                'briefing_notes' => 'Briefing via Zoom Meeting ID: 882 1923 1120 pukul 09.00 WITA.',
            ],
            [
                'name' => 'Bambang Prasetyo',
                'birth_date' => '2001-08-09',
                'nik' => '6302010908010003',
                'domicile' => 'Tanah Bumbu',
                'institution' => 'Politeknik Negeri Tanah Laut',
                'major' => 'Teknik Alat Berat',
                'phone_number' => '082155667788',
                'email' => 'bambang.p@example.test',
                'screening_status' => 'done',
                'briefing_date' => '2026-11-10',
                'briefing_status' => 'done',
                'mcu_date' => '2026-11-15',
                'mcu_location' => 'Klinik Tirta Medical Batulicin',
                'mcu_terms' => 'Puasa 10-12 jam sebelum pengambilan darah.',
                'mcu_status' => 'fit_to_work',
                'registration_status' => 'pending',
            ],
            [
                'name' => 'Aditya Pratama',
                'birth_date' => '2002-03-25',
                'nik' => '6302012503020004',
                'domicile' => 'Kotabaru',
                'institution' => 'Universitas Lambung Mangkurat',
                'major' => 'Kesehatan Masyarakat (K3)',
                'phone_number' => '085244332211',
                'email' => 'aditya.pratama@example.test',
                'screening_status' => 'done',
                'briefing_date' => '2026-11-05',
                'briefing_status' => 'done',
                'mcu_date' => '2026-11-08',
                'mcu_location' => 'RS Ciputra Banjarmasin',
                'mcu_status' => 'fit_to_work',
                'registration_status' => 'done_upload',
                'mine_permit_status' => 'diajukan',
                'mine_permit_number' => 'REQ-MP-2026-089',
                'mine_permit_date' => '2026-11-12',
            ],
            [
                'name' => 'Rian Hidayat',
                'birth_date' => '2001-12-12',
                'nik' => '6302011212010005',
                'domicile' => 'Satui, Tanah Bumbu',
                'institution' => 'Politeknik Negeri Samarinda',
                'major' => 'Teknik Pertambangan Batubara',
                'phone_number' => '087811223344',
                'email' => 'rian.hidayat@example.test',
                'screening_status' => 'done',
                'briefing_status' => 'done',
                'mcu_status' => 'fit_to_work',
                'registration_status' => 'done_upload',
                'mine_permit_status' => 'released',
                'mine_permit_number' => 'MP-BIB-2026-1102',
                'mine_permit_date' => '2026-11-10',
                'is_local' => true,
                'departure_date' => '2026-11-16',
                'arrival_information' => 'Tiba di Mess Karyawan Site BIB km 30, penjemputan PIC Driver Pak Joko.',
                'onsite_status' => 'arrived',
            ],
        ];

        foreach ($participants as $item) {
            InternshipApplication::firstOrCreate(['nik' => $item['nik']], $item);
        }
    }
}

