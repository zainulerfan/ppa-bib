<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\Recruitment\RecruitmentApplicationController;
use App\Http\Controllers\Recruitment\RecruitmentDocumentController;
use App\Http\Controllers\Recruitment\RecruitmentPpeController;
use App\Http\Controllers\Recruitment\RecruitmentUniformController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('recruitment')->name('recruitment.')->group(function () {
    Route::view('/', 'recruitment.index')->name('home');
    Route::view('/surat-rekrutmen', 'recruitment.letters.index')->name('letters');

    Route::get('/surat-rekrutmen/pengajuan/{application}/izin-visitor', [RecruitmentDocumentController::class, 'downloadPermit'])
        ->name('applications.permit');
    Route::get('/surat-rekrutmen/pengajuan/export', [RecruitmentApplicationController::class, 'export'])
        ->name('applications.export');
    Route::resource('/surat-rekrutmen/pengajuan', RecruitmentApplicationController::class)
        ->except('show')
        ->names('applications')
        ->parameters(['pengajuan' => 'application']);

    Route::get('/surat-rekrutmen/input-apd/export', [RecruitmentPpeController::class, 'export'])
        ->name('ppe.export');
    Route::resource('/surat-rekrutmen/input-apd', RecruitmentPpeController::class)
        ->except('show')
        ->names('ppe')
        ->parameters(['input-apd' => 'ppeRequest']);

    Route::get('/surat-rekrutmen/input-seragam/export', [RecruitmentUniformController::class, 'export'])
        ->name('uniforms.export');
    Route::resource('/surat-rekrutmen/input-seragam', RecruitmentUniformController::class)
        ->except('show')
        ->names('uniforms')
        ->parameters(['input-seragam' => 'uniformRequest']);

    Route::get('/surat-rekrutmen/print/{type}/{record?}', [RecruitmentDocumentController::class, 'show'])
        ->where('record', '[0-9]+')
        ->name('print');
    Route::get('/surat-rekrutmen/print/{type}/{record?}/pdf', [RecruitmentDocumentController::class, 'downloadPdf'])
        ->where('record', '[0-9]+')
        ->name('print.pdf');
});

Route::middleware('auth')->prefix('internships')->name('internships.')->group(function () {
    Route::get('/spdk-template', [InternshipController::class, 'downloadSpdkTemplate'])->name('spdk.template');
    Route::get('/{internship}/document/{type}', [InternshipController::class, 'downloadDocument'])->name('document.download');
    Route::put('/{internship}/stage/{stage}', [InternshipController::class, 'updateStage'])->name('stage.update');
    Route::resource('/', InternshipController::class)->parameters(['' => 'internship']);
});

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
