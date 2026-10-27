
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\FinanzaController;
use App\Http\Controllers\InspeccionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ReservaPublicaController;

// Inicio
// Route::get('/', function () {
//     return redirect()->route(auth()->check() ? 'dashboard' : 'login');
// });

Route::get('/', [CatalogoController::class, 'index'])->name('catalogo');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

// Vehículos - Todos los roles
Route::middleware('rol:ADMINISTRADOR,VENDEDOR,INSPECTOR')->group(function () {

    Route::get('/vehiculos', [VehiculoController::class, 'index'])
        ->name('vehiculos.index');

    // Registro de vehículos - Solo ADMINISTRADOR
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        Route::get('/vehiculos/create', [VehiculoController::class, 'create'])
            ->name('vehiculos.create');

        Route::post('/vehiculos', [VehiculoController::class, 'store'])
            ->name('vehiculos.store');
    });

});
// Inventario - Todos los roles
Route::middleware('rol:ADMINISTRADOR,VENDEDOR,INSPECTOR')->group(function () {

    Route::get('/inventario', [InventarioController::class, 'index'])
        ->name('inventario.index');

});


// Clientes - ADMINISTRADOR y VENDEDOR
Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {

    // Listado de clientes
    Route::get('/clientes', [ClienteController::class, 'index'])
        ->name('clientes.index');

    // Registrar clientes individuales
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

    // Formulario para editar cliente
    Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])
        ->name('clientes.edit');

    // Actualizar información del cliente
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])
        ->name('clientes.update');

    // Consultar detalles del cliente
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])
        ->name('clientes.show');

        
// Activar o desactivar cliente
Route::patch('/clientes/{id}/estado', [ClienteController::class, 'toggleEstado'])
    ->name('clientes.estado');


});

// Usuarios - Solo ADMINISTRADOR
Route::middleware('rol:ADMINISTRADOR')->group(function () {

    // Listado
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index');

    // Formulario de registro
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])
        ->name('usuarios.create');

    // Guardar usuario
    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->name('usuarios.store');

    // Formulario de edición
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit');

    // Actualizar usuario
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->name('usuarios.update');

    // Activar o desactivar usuario
    Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'toggleEstado'])
        ->name('usuarios.estado');

    // Consultar detalles
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])
        ->name('usuarios.show');

});

// Reservas

Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {
Route::get('/reservas', [ReservaController::class, 'index'])
    ->name('reservas.index');

    Route::post('/reservas/checkout', [ReservaController::class, 'checkout'])
    ->name('reservas.checkout');

Route::post('/reservas', [ReservaController::class, 'store'])
    ->name('reservas.store');

Route::post('/reservas/fifo', [ReservaController::class, 'storeFIFO'])
    ->name('reservas.fifo');

Route::post('/reservas/{id}/enganche', [ReservaController::class, 'registrarEnganche'])
->name('reservas.enganche');

Route::post('/reservas/{id}/devolucion', [ReservaController::class, 'registrarDevolucion'])
    ->name('reservas.devolucion');

Route::get('/reservas/{id}', [ReservaController::class, 'show'])
    ->name('reservas.show');

    }); // Fin de permisos de Reservas

// Ventas

Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {
Route::get('/ventas', [VentaController::class, 'index'])
    ->name('ventas.index');

Route::post('/ventas', [VentaController::class, 'store'])
    ->name('ventas.store');

Route::get('/ventas/{id}', [VentaController::class, 'show'])
    ->name('ventas.show');
}); // Fin de permisos de Ventas

// Finanzas
Route::middleware('rol:ADMINISTRADOR')->group(function () {

Route::get('/finanzas', [FinanzaController::class, 'index'])
    ->name('finanzas.index');

Route::get('/finanzas/{id}/detalles', [FinanzaController::class, 'detalles'])
    ->name('finanzas.detalles');

Route::get('/finanzas/{id}', [FinanzaController::class, 'show'])
    ->name('finanzas.show');

}); // Fin de permisos de Finanzas

 // Inspecciones - ADMINISTRADOR e INSPECTOR
Route::middleware('rol:ADMINISTRADOR,INSPECTOR')->group(function () {

    Route::get('/inspecciones', [InspeccionController::class, 'index'])
        ->name('inspecciones.index');

    Route::post('/inspecciones', [InspeccionController::class, 'store'])
        ->name('inspecciones.store');

    Route::patch('/inspecciones/{id}/finalizar', [InspeccionController::class, 'finalizar'])
        ->name('inspecciones.finalizar');

    Route::post('/inspecciones/avaluos', [InspeccionController::class, 'storeAvaluo'])
        ->name('inspecciones.avaluos.store');


Route::patch('/inspecciones/avaluos/{id}/decidir', [InspeccionController::class, 'decidirAvaluo'])->middleware('rol:ADMINISTRADOR')->name('inspecciones.avaluos.decidir');

Route::patch('/vehiculos/{id}/precio', [VehiculoController::class, 'actualizarPrecio'])->middleware('rol:ADMINISTRADOR')->name('vehiculos.precio.actualizar');

    Route::get('/inspecciones/{id}', [InspeccionController::class, 'show'])
        ->name('inspecciones.show');

}); // Fin de permisos de Inspecciones

// Reportes - Solo ADMINISTRADOR
Route::middleware('rol:ADMINISTRADOR')->group(function () {

    Route::get('/reportes', [ReporteController::class, 'index'])
        ->name('reportes.index');

    // Nuevos reportes integrados
    Route::get('/reportes/inventario', [ReporteController::class, 'inventario'])
        ->name('reportes.inventario');

    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])
        ->name('reportes.ventas');

    Route::get('/reportes/fifo', function () {
    return redirect()->route('reportes.inventario', [
        'vista' => 'fifo'
    ]);
})->name('reportes.fifo');

    Route::get('/reportes/conciliacion', [ReporteController::class, 'conciliacionFinanciera'])
        ->name('reportes.conciliacion');

    Route::get('/reportes/antiguedad', function () {
    return redirect()->route('reportes.inventario', [
        'vista' => 'antiguedad'
    ]);
})->name('reportes.antiguedad');

Route::get('/reportes/clientes', [ReporteController::class, 'clientes'])
    ->name('reportes.clientes');

}); // Fin de permisos de Reportes

}); // Fin de rutas protegidas

    // Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::get('/catalogo', [CatalogoController::class, 'index'])
    ->name('catalogo.index');

Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])
    ->whereNumber('id')
    ->name('catalogo.show');

Route::post('/catalogo/{id}/cliente', [ReservaPublicaController::class, 'registrarCliente'])
    ->whereNumber('id')
    ->middleware('throttle:5,10')
    ->name('catalogo.reserva.cliente');

Route::post('/catalogo/{id}/identificar', [ReservaPublicaController::class, 'identificarCliente'])
    ->whereNumber('id')->middleware('throttle:5,10')->name('catalogo.reserva.identificar');

Route::post('/catalogo/{id}/confirmar', [ReservaPublicaController::class, 'confirmar'])
    ->whereNumber('id')->middleware('throttle:3,10')->name('catalogo.reserva.confirmar');

Route::get('/reserva-publica/confirmada/{id}', [ReservaPublicaController::class, 'confirmada'])
    ->whereNumber('id')
    ->name('catalogo.reserva.confirmada');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

   
