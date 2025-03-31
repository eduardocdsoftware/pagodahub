<?php

use Illuminate\Support\Facades\Route;

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





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth'])->name('dashboard');

//require __DIR__.'/auth.php';

//Route::view('/close_cash','close_cash', [CloseCashController::class, 'show']);

Route::get('/test', [App\Http\Controllers\test::class, 'test'])->name('test.list');
Route::middleware(['auth', 'user.data'])->group(function () {
    Route::get('/', function () {
        return view('auth/login');
    });
    Route::post('/close_cash', [App\Http\Controllers\CloseCashController::class, 'show'])->name('close.cash');
    Route::post('/closecash_store', [App\Http\Controllers\CloseCashController::class, 'store'])->name('closecash.store');
    Route::post('/closecash_import', [App\Http\Controllers\CloseCashController::class, 'import'])->name('closecash.import');
    Route::get('/closecash_list', [App\Http\Controllers\CloseCashController::class, 'list'])->name('closecash.list');
    Route::post('/closecash_destroy', [App\Http\Controllers\CloseCashController::class, 'destroy'])->name('closecash.destroy');
    Route::post('/closecash_edit', [App\Http\Controllers\CloseCashController::class, 'edit'])->name('closecash.edit');

    Route::get('/valepagoda', [App\Http\Controllers\ValePagodaController::class, 'index'])->name('valepagoda');
    Route::get('/valepagodacancel', [App\Http\Controllers\ValePagodaController::class, 'index'])->name('valepagodacancel');

    Route::get('/valepagoda_search', [App\Http\Controllers\ValePagodaController::class, 'search'])->name('valepagoda.search');
    Route::get('/valepagoda_store', [App\Http\Controllers\ValePagodaController::class, 'store'])->name('valepagoda.store');
    Route::get('/valepagoda_list', [App\Http\Controllers\ValePagodaController::class, 'list'])->name('valepagoda.list');
    Route::post('/valepagoda_destroy', [App\Http\Controllers\ValePagodaController::class, 'destroy'])->name('valepagoda.destroy');


    Route::post('/valespagodarange', [App\Http\Controllers\ValesPagodaRangeController::class, 'index'])->name('valespagodarange');
    Route::get('/valespagodarange_search', [App\Http\Controllers\ValesPagodaRangeController::class, 'search'])->name('valespagodarange.search');
    Route::post('/valespagodarange_store', [App\Http\Controllers\ValesPagodaRangeController::class, 'store'])->name('valespagodarange.store');
    Route::post('/valespagodarange_list', [App\Http\Controllers\ValesPagodaRangeController::class, 'list'])->name('valespagodarange.list');
    Route::post('/valespagodarange_delete', [App\Http\Controllers\ValesPagodaRangeController::class, 'delete'])->name('valespagodarange.delete');

    Route::get('/loans', [App\Http\Controllers\LoansController::class, 'index'])->name('loans');
    Route::get('/loans_debt', [App\Http\Controllers\LoansController::class, 'indexDebt'])->name('loans_debt');
    Route::get('/loans_debt_search', [App\Http\Controllers\LoansController::class, 'searchDebt'])->name('loans_debt.search');
    Route::get('/loans_search', [App\Http\Controllers\LoansController::class, 'search'])->name('loans.search');
    Route::post('/loans_store', [App\Http\Controllers\LoansController::class, 'store'])->name('loans.store');
    Route::post('/loans_store_new', [App\Http\Controllers\LoansController::class, 'store_new'])->name('loans.store_new');
    Route::post('/loans_newuser', [App\Http\Controllers\LoansController::class, 'newuser'])->name('loans.newuser');
    Route::post('/loans_update', [App\Http\Controllers\LoansController::class, 'update'])->name('loans.update');
    Route::get('/loans_list', [App\Http\Controllers\LoansController::class, 'list'])->name('loans.list');
    Route::post('/loans_list_delete', [App\Http\Controllers\LoansController::class, 'destroy'])->name('loans.destroy');
    Route::post('/loans_list/{id}', [App\Http\Controllers\LoansController::class, 'show'])->name('loans.show');
    Route::post('/loans_list', [App\Http\Controllers\LoansController::class, 'updateLoan'])->name('loans.updateLoan');
    Route::post('/loans', [App\Http\Controllers\LoansController::class, 'updatePayment'])->name('loans.updatePayment');

    Route::get('/facture', [App\Http\Controllers\FactureController::class, 'index'])->name('factures');
    Route::post('/facture/resume', [App\Http\Controllers\FactureController::class, 'resume'])->name('factures.resume');
    Route::post('/marketinvoice', [App\Http\Controllers\FactureController::class, 'store'])->name('factures.store');
    Route::post('/facture', [App\Http\Controllers\FactureController::class, 'update'])->name('factures.update');
    Route::post('/facture_edit', [App\Http\Controllers\FactureController::class, 'edit'])->name('factures.edit');
    Route::delete('/marketinvoice/{id}', [App\Http\Controllers\FactureController::class, 'borrar'])->name('factures.borrar');
    //Route::post('/facture/{id}', [App\Http\Controllers\FactureController::class, 'show'])->name('factures.show');
    Route::post('/factures/{id}', [App\Http\Controllers\FactureController::class, 'show'])->name('factures.show');

    Route::delete('/facture', [App\Http\Controllers\FactureController::class, 'eliminar'])->name('factures.eliminar');
    Route::post('/facture/search_by_provider', [App\Http\Controllers\FactureController::class, 'searchByProvider'])->name('factures.searchByProvider');
    Route::post('/facture/get_credit', [App\Http\Controllers\FactureController::class, 'getAllCredit'])->name('factures.credit');
    Route::get('/facture/get_credit', [App\Http\Controllers\FactureController::class, 'getAllCredit'])->name('factures.get_credit');

    //Route::post('/facture', [App\Http\Controllers\FactureController::class, 'pagar'])->name('factures.pagar');

    //rutas de creacion de presupuesto
    //Route::get('/budget', [App\Http\Controllers\BudgetController::class, 'index'])->name('budget');
    //Route::post('/budget', [App\Http\Controllers\BudgetController::class, 'create'])->name('budget.create');
    Route::get('/budget', [App\Http\Controllers\BudgetController::class, 'index'])->name('budget');
    Route::post('/budget', [App\Http\Controllers\BudgetController::class, 'store'])->name('budget.store');
    Route::delete('budget/{id}', [App\Http\Controllers\BudgetController::class, 'destroy'])->name('budget.delete');
    Route::get('/budget/create', [App\Http\Controllers\BudgetController::class, 'create'])->name('budget.create');
    
    Route::get('/budget/edit/{id}', [App\Http\Controllers\BudgetController::class, 'edit'])->name('budget.edit');
    Route::post('/budget/edit', [App\Http\Controllers\BudgetController::class, 'update'])->name('budget.update');
    //Route::get('/tdc/edit/{id}', [App\Http\Controllers\CardController::class, 'edit'])->name('card.edit');
    //Route::post('/tdc/edit', [App\Http\Controllers\CardController::class, 'update'])->name('card.update');
    

    // Ruta para mostrar la página del mercado (método GET)
    Route::get('/market', [App\Http\Controllers\MarketController::class, 'index'])->name('market');
    // Ruta para procesar el formulario del mercado (método POST)
    Route::post('/market/create', [App\Http\Controllers\MarketController::class, 'store'])->name('market.store');
    Route::post('/market', [App\Http\Controllers\MarketController::class, 'charge'])->name('market.charge');
    Route::post('/market/{id}', [App\Http\Controllers\MarketController::class, 'update'])->name('market.update');
    Route::get('/market/{id}', [App\Http\Controllers\MarketController::class, 'edit'])->name('market.edit');
    Route::get('/marketinvoices', [App\Http\Controllers\MarketController::class, 'show'])->name('marketinvoices');
    Route::get('/marketinvoice', [App\Http\Controllers\MarketController::class, 'shopday'])->name('market.day')->where('day', 'monday');
    


    //rutas de creacion de presupuesto
    Route::get('/startBrink', [App\Http\Controllers\StartBrinkController::class, 'index'])->name('startbrink');
    Route::post('/startBrink', [App\Http\Controllers\StartBrinkController::class, 'create'])->name('startbrink.create');
    
    //rutas de creacion de devolucion gerencia
    Route::get('/devgerency', [App\Http\Controllers\DevolucionGerencyController::class, 'index'])->name('devgerency');
    Route::post('/devgerency', [App\Http\Controllers\DevolucionGerencyController::class, 'create'])->name('devgerency.create');
    
    //rutas de Brick supervisor
    Route::get('/Brink', [App\Http\Controllers\BrinkController::class, 'index'])->name('Brink');
    Route::post('/Brink', [App\Http\Controllers\BrinkController::class, 'import'])->name('Brink.import');
    Route::post('/Brink_save', [App\Http\Controllers\BrinkController::class, 'store'])->name('Brink.store');
    Route::post('/Brink_update', [App\Http\Controllers\BrinkController::class, 'update'])->name('Brink.update');
    Route::post('/Brink_print', [App\Http\Controllers\BrinkController::class, 'imprimir'])->name('Brink.imprimir');

    //rutas de Brick envio
    Route::get('/BrinkSend', [App\Http\Controllers\BrinkController::class, 'indexSend'])->name('BrinkSend');
    Route::post('/BrinkSend', [App\Http\Controllers\BrinkController::class, 'brinkStore'])->name('Brink.brinkStore');
    Route::delete('/BrinkSend', [App\Http\Controllers\BrinkController::class, 'brinkdestroy'])->name('BrinkSend.brinkdestroy');

    //rutas solicitud brick
    Route::get('/requestBrink', [App\Http\Controllers\RequestBrinkController::class, 'index'])->name('requestBrink');
    Route::post('/requestBrink', [App\Http\Controllers\RequestBrinkController::class, 'store'])->name('requestBrink.store');
    Route::get('/requestBrink_edit', [App\Http\Controllers\RequestBrinkController::class, 'edit'])->name('requestBrink.edit');
    Route::post('/requestBrink_edit', [App\Http\Controllers\RequestBrinkController::class, 'update'])->name('requestBrink.update');
    //rutas solicitud gerencia
    Route::get('/requestGerency', [App\Http\Controllers\RequestGerencyController::class, 'index'])->name('requestGerency');
    Route::post('/requestGerency', [App\Http\Controllers\RequestGerencyController::class, 'store'])->name('requestGerency.store');    
    Route::get('/requestGerency_edit', [App\Http\Controllers\RequestGerencyController::class, 'edit'])->name('requestGerency.edit');
    Route::post('/requestGerency_edit', [App\Http\Controllers\RequestGerencyController::class, 'update'])->name('requestGerency.update');

    //rutas facturas
    Route::get('/tdc', [App\Http\Controllers\CardController::class, 'index'])->name('card');
    Route::post('/tdc', [App\Http\Controllers\CardController::class, 'store'])->name('card.store');
    Route::delete('tdc/{id}', [App\Http\Controllers\CardController::class, 'destroy'])->name('card.delete');
    Route::get('/tdc/create', [App\Http\Controllers\CardController::class, 'create'])->name('card.create');
    Route::get('/tdc/edit/{id}', [App\Http\Controllers\CardController::class, 'edit'])->name('card.edit');
    Route::post('/tdc/edit', [App\Http\Controllers\CardController::class, 'update'])->name('card.update');

    Route::get('/pbank', [App\Http\Controllers\PresupuestoBankController::class, 'index'])->name('pbank');
    Route::post('/pbank', [App\Http\Controllers\PresupuestoBankController::class, 'store'])->name('pbank.store');
    Route::delete('pbank/{id}', [App\Http\Controllers\PresupuestoBankController::class, 'destroy'])->name('pbank.delete');
    Route::get('/pbank/create', [App\Http\Controllers\PresupuestoBankController::class, 'create'])->name('pbank.create');
    Route::get('/pbank/edit/{id}', [App\Http\Controllers\PresupuestoBankController::class, 'edit'])->name('pbank.edit');
    Route::post('/pbank/edit', [App\Http\Controllers\PresupuestoBankController::class, 'update'])->name('pbank.update');

    
    //Route::get('/invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice');
    //Route::get('/invoice', [App\Http\Controllers\InvoiceController::class, 'index_viejo'])->name('invoice');
    Route::get('/invoice', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/invoice', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/edit/{id}', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::post('/excel', [App\Http\Controllers\InvoiceController::class, 'getExcel'])->name('invoice.getExcel');    
    Route::get('/summary11', [App\Http\Controllers\InvoiceController::class, 'list'])->name('invoice.show');
    Route::get('/summary', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.index');
    Route::delete('invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoice.delete');
    Route::post('/invoice/edit', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');
    
    Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment');
    Route::post('/payment', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');

    //rutas producto
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product');
    Route::post('/product', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
    Route::delete('product/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('product.delete');
    Route::get('/product/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
    Route::get('/product/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
    Route::get('/product/filter/{session?}', [App\Http\Controllers\ProductController::class, 'filter'])->name('product.filter');
    Route::get('/product/details/{id}', [App\Http\Controllers\ProductController::class, 'details'])->name('product.details');

    //rutas marca
    Route::get('/brand', [App\Http\Controllers\BrandController::class, 'index'])->name('brand');
    Route::post('/brand', [App\Http\Controllers\BrandController::class, 'store'])->name('brand.store');
    Route::delete('brand/{id}', [App\Http\Controllers\BrandController::class, 'destroy'])->name('brand.delete');
    Route::get('/brand/create', [App\Http\Controllers\BrandController::class, 'create'])->name('brand.create');
    Route::get('/brand/edit/{id}', [App\Http\Controllers\BrandController::class, 'edit'])->name('brand.edit');
    Route::post('/brand/edit', [App\Http\Controllers\BrandController::class, 'update'])->name('brand.update');

    //rutas categorias
    Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category');
    Route::post('/category', [App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
    Route::delete('category/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.delete');
    Route::get('/category/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
    Route::get('/category/edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category/edit', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');

    //rutas departamnto
    Route::get('/department', [App\Http\Controllers\DepartmentController::class, 'index'])->name('department');
    Route::post('/department', [App\Http\Controllers\DepartmentController::class, 'store'])->name('department.store');
    Route::delete('department/{id}', [App\Http\Controllers\DepartmentController::class, 'destroy'])->name('department.delete');
    Route::get('/department/create', [App\Http\Controllers\DepartmentController::class, 'create'])->name('department.create');
    Route::get('/department/edit/{id}', [App\Http\Controllers\DepartmentController::class, 'edit'])->name('department.edit');
    Route::post('/department/edit', [App\Http\Controllers\DepartmentController::class, 'update'])->name('department.update');

});
Route::get('/firma1', function () {
    return view('canvas/tablero3');
});
Route::get('/firma2', function () {
    return view('canvas/tablero4');
});
Route::get('/firma3', function () {
    return view('canvas/tablero5');
});


// Ruta para dompdf
Route::get('download-pdf', [App\Http\Controllers\CloseCashController::class, 'downloadPdf'])->name('download-pdf');
Route::post('facture-pdf', [App\Http\Controllers\FactureController::class, 'downloadPdf'])->name('facture-pdf');
Route::post('resume-pdf', [App\Http\Controllers\FactureController::class, 'resumePdf'])->name('resume-pdf');
Route::post('/marketinvoice-pdf', [App\Http\Controllers\MarketController::class, 'print'])->name('market.print');

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('homeredirect');
