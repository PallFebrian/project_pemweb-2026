<?php

use App\Models\KategoriLayanan;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Filament\Admin\Resources\PermintaanLayananResource;
use App\Models\PermintaanLayanan;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/ 

Route::view('/buat-permintaan', 'layanan.permintaan')
    ->name('buat-permintaan');

Route::get('/', function () {
    return view('landing', [
        'kategoriLayanan' => KategoriLayanan::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get(),
    ]);
})->name('landing');


Route::get('/admin/notifikasi/{notification}/buka', function (string $notification) {
    $userId = Auth::id();

    abort_unless($userId, 403);

    $dataNotifikasi = DB::table('notifications')
        ->where('id', $notification)
        ->where('notifiable_type', User::class)
        ->where('notifiable_id', $userId)
        ->first();

    abort_unless($dataNotifikasi, 404);

    $data = json_decode($dataNotifikasi->data ?? '{}', true) ?: [];

    DB::table('notifications')
        ->where('id', $notification)
        ->update([
            'read_at' => now(),
            'updated_at' => now(),
        ]);

    return redirect($data['target_url'] ?? url('/admin'));
})
    ->middleware('auth')
    ->name('admin.notifikasi.buka');

    
Route::get('/admin/notifikasi-order/buka', function () {
    PermintaanLayanan::query()
        ->where('status', 'baru')
        ->whereNull('dibaca_admin_pada')
        ->update([
            'dibaca_admin_pada' => now(),
            'updated_at' => now(),
        ]);

    return redirect(
        PermintaanLayananResource::getUrl('index', panel: 'admin')
    );
})
    ->middleware('auth')
    ->name('admin.notifikasi-order.buka');