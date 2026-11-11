<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');

Route::view('/recruitment', 'divisions.show', [
    'division' => [
        'name' => 'Recruitment',
        'short_name' => 'Recruitment',
        'icon' => 'users',
        'description' => 'Kelola proses pemenuhan tenaga kerja, mulai dari kebutuhan hingga kandidat bergabung.',
        'scope' => [
            ['title' => 'Perencanaan tenaga kerja', 'description' => 'Ruang untuk mencatat dan meninjau kebutuhan tenaga kerja.'],
            ['title' => 'Seleksi kandidat', 'description' => 'Ruang untuk mengelola tahapan seleksi dan informasi kandidat.'],
            ['title' => 'Administrasi onboarding', 'description' => 'Ruang untuk mempersiapkan proses awal karyawan bergabung.'],
        ],
    ],
])->name('recruitment');

Route::view('/people-development', 'divisions.show', [
    'division' => [
        'name' => 'People Development',
        'short_name' => 'People Development',
        'icon' => 'graduation',
        'description' => 'Dukung pengembangan kompetensi dan pertumbuhan karyawan di setiap tahap karier.',
        'scope' => [
            ['title' => 'Pelatihan & kompetensi', 'description' => 'Ruang untuk merencanakan pengembangan kompetensi karyawan.'],
            ['title' => 'Performa & pengembangan', 'description' => 'Ruang untuk mendukung proses evaluasi dan rencana pengembangan.'],
            ['title' => 'Karier & talenta', 'description' => 'Ruang untuk meninjau potensi dan arah pengembangan talenta.'],
        ],
    ],
])->name('people-development');

Route::view('/compensation-benefit', 'divisions.show', [
    'division' => [
        'name' => 'Compensation & Benefit',
        'short_name' => 'Comben',
        'icon' => 'wallet',
        'description' => 'Dukung pengelolaan kompensasi dan benefit karyawan secara terstruktur.',
        'scope' => [
            ['title' => 'Kompensasi', 'description' => 'Ruang untuk meninjau informasi dan administrasi kompensasi.'],
            ['title' => 'Benefit karyawan', 'description' => 'Ruang untuk mengelola informasi benefit yang tersedia.'],
            ['title' => 'Administrasi payroll', 'description' => 'Ruang untuk mendukung administrasi data payroll.'],
        ],
    ],
])->name('compensation-benefit');

Route::view('/industrial-relation', 'divisions.show', [
    'division' => [
        'name' => 'Industrial Relation',
        'short_name' => 'Industrial Relation',
        'icon' => 'handshake',
        'description' => 'Bangun hubungan industrial yang harmonis melalui pengelolaan administrasi dan komunikasi.',
        'scope' => [
            ['title' => 'Hubungan industrial', 'description' => 'Ruang untuk mendukung administrasi hubungan industrial.'],
            ['title' => 'Kebijakan & kepatuhan', 'description' => 'Ruang untuk mengelola informasi kebijakan dan kepatuhan.'],
            ['title' => 'Komunikasi karyawan', 'description' => 'Ruang untuk mendukung komunikasi antara perusahaan dan karyawan.'],
        ],
    ],
])->name('industrial-relation');
