<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('inicio');
});

Route::get('/explorar', function () {
    return view('explorar');
});

Route::get('/experiencia/{id}', [App\Http\Controllers\ExperienciaController::class, 'show'])->name('experiencia.show');

// Rutas de administrador
Route::get('/panel/administrador', [App\Http\Controllers\AdministradorController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/api/admin/prestadores-pendientes', [App\Http\Controllers\AdministradorController::class, 'obtenerPrestadoresPendientes'])->name('admin.prestadores.pendientes');
Route::post('/api/admin/validar-prestador/{id}', [App\Http\Controllers\AdministradorController::class, 'validarPrestador'])->name('admin.validar.prestador');
Route::get('/api/admin/contenido-reportado', [App\Http\Controllers\AdministradorController::class, 'obtenerContenidoReportado'])->name('admin.contenido.reportado');
Route::post('/api/admin/moderar-contenido/{id}', [App\Http\Controllers\AdministradorController::class, 'moderarContenido'])->name('admin.moderar.contenido');
Route::get('/api/admin/usuarios', [App\Http\Controllers\AdministradorController::class, 'obtenerUsuarios'])->name('admin.usuarios');
Route::post('/api/admin/gestionar-usuario/{id}', [App\Http\Controllers\AdministradorController::class, 'gestionarUsuario'])->name('admin.gestionar.usuario');
Route::get('/api/admin/publicaciones', [App\Http\Controllers\AdministradorController::class, 'obtenerPublicaciones'])->name('admin.publicaciones');
Route::post('/api/admin/gestionar-publicacion/{id}', [App\Http\Controllers\AdministradorController::class, 'gestionarPublicacion'])->name('admin.gestionar.publicacion');
Route::get('/api/admin/reportes', [App\Http\Controllers\AdministradorController::class, 'generarReportes'])->name('admin.reportes');
Route::post('/api/admin/registrar-administrador', [App\Http\Controllers\AdministradorController::class, 'registrarAdministrador'])->name('admin.registrar.admin');

// Rutas de paneles
Route::get('/panel/turista', [App\Http\Controllers\TuristaController::class, 'index'])->name('panel.turista');
Route::get('/panel/prestador', [App\Http\Controllers\PrestadorController::class, 'index'])->name('panel.prestador');

// Rutas API para turista
Route::prefix('api/turista')->middleware('web')->group(function () {
    Route::get('/explorar', [App\Http\Controllers\TuristaController::class, 'explorar'])->name('turista.explorar');
    Route::post('/favoritos', [App\Http\Controllers\TuristaController::class, 'agregarFavorito'])->name('turista.favoritos.agregar');
    Route::delete('/favoritos/{id}', [App\Http\Controllers\TuristaController::class, 'eliminarFavorito'])->name('turista.favoritos.eliminar');
    Route::get('/favoritos', [App\Http\Controllers\TuristaController::class, 'favoritos'])->name('turista.favoritos');
    Route::get('/reseñas', [App\Http\Controllers\TuristaController::class, 'reseñas'])->name('turista.reseñas');
});

// Rutas API para prestador
Route::prefix('api/prestador')->middleware('web')->group(function () {
    Route::get('/estadisticas', [App\Http\Controllers\PrestadorController::class, 'estadisticas'])->name('prestador.estadisticas');
    Route::get('/publicaciones', [App\Http\Controllers\PrestadorController::class, 'publicaciones'])->name('prestador.publicaciones');
    Route::get('/metricas', [App\Http\Controllers\PrestadorController::class, 'metricas'])->name('prestador.metricas');
});

// Rutas API para publicaciones
Route::prefix('api/publicaciones')->middleware('web')->group(function () {
    Route::get('/', [App\Http\Controllers\PublicacionesController::class, 'index'])->name('publicaciones.index');
    Route::get('/{id}', [App\Http\Controllers\PublicacionesController::class, 'show'])->name('publicaciones.show');
    Route::post('/', [App\Http\Controllers\PublicacionesController::class, 'store'])->name('publicaciones.store');
    Route::put('/{id}', [App\Http\Controllers\PublicacionesController::class, 'update'])->name('publicaciones.update');
    Route::delete('/{id}', [App\Http\Controllers\PublicacionesController::class, 'destroy'])->name('publicaciones.destroy');
    Route::get('/prestador/{id}', [App\Http\Controllers\PublicacionesController::class, 'getByPrestador'])->name('publicaciones.by-prestador');
});

// Rutas API para reservas
Route::prefix('api/reservas')->middleware('web')->group(function () {
    Route::get('/', [App\Http\Controllers\ReservasController::class, 'index'])->name('reservas.index');
    Route::post('/', [App\Http\Controllers\ReservasController::class, 'store'])->name('reservas.store');
    Route::put('/{id}', [App\Http\Controllers\ReservasController::class, 'update'])->name('reservas.update');
    Route::post('/{id}/cancelar', [App\Http\Controllers\ReservasController::class, 'cancelar'])->name('reservas.cancelar');
    Route::get('/estadisticas', [App\Http\Controllers\ReservasController::class, 'estadisticas'])->name('reservas.estadisticas');
});

// Rutas de autenticación
Route::post('/login', [App\Http\Controllers\AutenticacionController::class, 'iniciarSesion'])->name('login');
Route::post('/registro', [App\Http\Controllers\AutenticacionController::class, 'registrar'])->name('registro');
Route::get('/logout', [App\Http\Controllers\AutenticacionController::class, 'cerrarSesion'])->name('logout');
Route::post('/logout', [App\Http\Controllers\AutenticacionController::class, 'cerrarSesion'])->name('logout.post');