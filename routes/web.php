<?php
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminSupirController;
use App\Http\Controllers\admin\AdminDanaController;
use App\Http\Controllers\admin\AdminLaporanKerusakanController;
use App\Http\Controllers\admin\AdminLaporanKeuanganController;
use App\Http\Controllers\admin\AdminHargaController;
use App\Http\Controllers\admin\AdminFeeController;
use App\Http\Controllers\admin\AdminCallCenterController;
use App\Http\Controllers\admin\AdminDaruratController;
use App\Http\Controllers\admin\AdminTransaksiController;
use App\Http\Controllers\admin\AdminPenggunaController;
use App\Http\Controllers\admin\AdminMasterDataController;
use App\Http\Controllers\admin\AdminTipeController;
use App\Http\Controllers\admin\AlasanPembatalanController;
use App\Http\Controllers\admin\KotaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/get_image', [AdminController::class, 'getImage']);
Route::get('/', [AdminController::class, 'startPage'])->middleware('guest');
Route::get('/export', [AdminController::class, 'testExport']);

Route::prefix('/admin')->name('admin.')->group(function(){
    //All the admin routes will be defined here...
    // Route::get('/login', [AdminAuthController::class, 'getLogin'])->name('login');
    // Route::post('/login', [AdminAuthController::class, 'postLogin']);
    // Route::get('/logout', [AdminAuthController::class, 'postLogout']);
    Route::get('/', [AdminController::class, 'index'])->name('home');

    Route::prefix('/supir')->group(function() {
        Route::get('/', [AdminSupirController::class, 'index'])->name('supir');
        Route::get('/locations', [AdminSupirController::class, 'locations']);
        Route::get('/export/{id}', [AdminSupirController::class, 'detail']);
        Route::get('/detail/{id}', [AdminSupirController::class, 'detail']);
        Route::get('/create', [AdminSupirController::class, 'showAddView']);
        Route::get('/edit-form/{id}', [AdminSupirController::class, 'showEditView']);
        Route::get('/histori', [AdminSupirController::class, 'history']);
        Route::post('/add', [AdminSupirController::class, 'add'])->name('supir.add');
        Route::post('/edit/{id}', [AdminSupirController::class, 'edit'])->name('supir.edit');
        Route::post('/suspend/{id}', [AdminSupirController::class, 'suspend'])->name('supir.suspend');
        Route::delete('/delete/{id}', [AdminSupirController::class, 'destroy'])->name('supir.delete');
    });

    Route::prefix('/dana')->group(function() {
        Route::get('/', [AdminDanaController::class, 'index'])->name('dana');
        Route::post('/konfirmasi', [AdminDanaController::class, 'verify']);
    });

    Route::prefix('/laporan-kerusakan')->group(function() {
        Route::get('/', [AdminLaporanKerusakanController::class, 'index'])->name('laporan-kerusakan');
        Route::post('/solve', [AdminLaporanKerusakanController::class, 'solve']);
    });

    Route::prefix('/harga')->group(function() {
        Route::get('/', [AdminHargaController::class, 'index'])->name('harga');
        Route::post('/', [AdminHargaController::class, 'update']);
    });

    Route::prefix('/fee')->group(function() {
        Route::get('/', [AdminFeeController::class, 'index'])->name('fee');
        Route::post('/', [AdminFeeController::class, 'update']);
    });

    Route::prefix('/laporan-keuangan')->group(function() {
        Route::get('/', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan');
        Route::post('/', [AdminLaporanKeuanganController::class, 'export']);
    });

    Route::prefix('/call-center')->group(function() {
        Route::get('/', [AdminCallCenterController::class, 'index'])->name('call-center');
        Route::post('/', [AdminCallCenterController::class, 'update']);
    });

    Route::prefix('/tipemobil')->group(function() {
        Route::get('/', [AdminTipeController::class, 'index'])->name('tipe');
        Route::get('/create', [AdminTipeController::class, 'showAddView']);
        Route::post('/create', [AdminTipeController::class, 'add'])->name('tipe.add');
        Route::delete('/delete/{id}', [AdminTipeController::class, 'destroy'])->name('tipe.delete');
    });

    Route::prefix('/kota')->group(function() {
        Route::get('/', [KotaController::class, 'index'])->name('kota');
        Route::get('/create', [KotaController::class, 'create']);
        Route::post('/create', [KotaController::class, 'store'])->name('kota.add');
        Route::post('/delete', [KotaController::class, 'destroy'])->name('kota.delete');
    });

    Route::prefix('/cancels')->group(function() {
        Route::get('/', [AlasanPembatalanController::class, 'index'])->name('cancels');
        Route::get('/create', [AlasanPembatalanController::class, 'create']);
        Route::post('/create', [AlasanPembatalanController::class, 'store'])->name('cancels.add');
        Route::delete('/delete', [AlasanPembatalanController::class, 'destroy'])->name('cancels.delete');
    });

    Route::prefix('/darurat')->group(function() {
        Route::get('/', [AdminDaruratController::class, 'index'])->name('darurat');
        Route::post('/konfirmasi', [AdminDaruratController::class, 'verify']);
    });

    Route::prefix('/transaksi')->group(function() {
        Route::get('/', [AdminTransaksiController::class, 'index']);
        Route::get('/detail/{id}', [AdminTransaksiController::class, 'detail']);
        Route::get('/detail/print-preview/{id}', [AdminTransaksiController::class, 'detailPrintPreview']);
    });

    Route::prefix('/pengguna')->group(function() {
        Route::get('/', [AdminPenggunaController::class, 'index']);
        Route::get('/detail/{id}', [AdminPenggunaController::class, 'detail']);
    });

    Route::prefix('/masterdata')->group(function() {
        Route::get('/kebijakan', [AdminMasterDataController::class, 'kebijakan']);
        Route::get('/create-kebijakan', [AdminMasterDataController::class, 'showAddKebijakan']);
        Route::get('/edit-kebijakan', [AdminMasterDataController::class, 'showEditKebijakan']);
        Route::get('/syarat', [AdminMasterDataController::class, 'syarat']);
        Route::get('/create-syarat', [AdminMasterDataController::class, 'showAddSyarat']);
        Route::get('/edit-syarat', [AdminMasterDataController::class, 'showEditSyarat']);
        Route::get('/tarif', [AdminMasterDataController::class, 'tarif']);
        Route::get('/create-tarif', [AdminMasterDataController::class, 'showAddTarif']);
        Route::get('/edit-tarif', [AdminMasterDataController::class, 'showEditTarif']);
    });
});

Route::get('pay/{snapToken}', 'PaySnapTransactionController')->name('snap_url')->middleware('signed');
Route::post('payment-notifications-webhook', 'PaymentNotificationController')->name('payment_notification_webhook')->middleware('validate.midtrans');

Route::fallback('NotFoundController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/logout', 'LoginController@logout');
