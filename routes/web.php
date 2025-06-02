<?php
// routes/web.php
use App\Http\Controllers\{ProfileController, DashboardController, CustomerController, OrderController, ItemController, SaleController, GoldPurchaseController, SalesReportController, InventoryReportController, FinancialReportController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer routes
    Route::resource('customers', CustomerController::class);
    Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    // Item routes
    Route::resource('items', ItemController::class);
    Route::get('/api/items/search', [ItemController::class, 'search'])->name('items.search');


    // Order routes (Custom Orders/Estimates)
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/mark-ready', [OrderController::class, 'markReady'])->name('orders.mark-ready');
    Route::get('/orders/{order}/convert-to-sale', [OrderController::class, 'convertToSale'])->name('orders.convert-to-sale');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'generateReceipt'])->name('orders.receipt');


    // Sale routes
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');

    // Gold Purchase routes
    Route::resource('gold-purchases', GoldPurchaseController::class);

    Route::prefix('reports')->name('reports.')->group(function () {

        // Sales Reports
        Route::get('/sales', [SalesReportController::class, 'index'])->name('sales.index');
        Route::get('/sales/daily', [SalesReportController::class, 'daily'])->name('sales.daily');
        Route::get('/sales/monthly', [SalesReportController::class, 'monthly'])->name('sales.monthly');
        Route::get('/sales/customer', [SalesReportController::class, 'customer'])->name('sales.customer');
        Route::get('/sales/export', [SalesReportController::class, 'export'])->name('sales.export');

        // Inventory Reports
        Route::get('/inventory', [InventoryReportController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/valuation', [InventoryReportController::class, 'valuation'])->name('inventory.valuation');
        Route::get('/inventory/movement', [InventoryReportController::class, 'movement'])->name('inventory.movement');
        Route::get('/inventory/low-stock', [InventoryReportController::class, 'lowStock'])->name('inventory.low-stock');
        Route::get('/inventory/export', [InventoryReportController::class, 'export'])->name('inventory.export');

        // Financial Reports
        Route::get('/financial', [FinancialReportController::class, 'index'])->name('financial.index');
        Route::get('/financial/profit-loss', [FinancialReportController::class, 'profitLoss'])->name('financial.profit-loss');
        Route::get('/financial/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('financial.cash-flow');
        Route::get('/financial/receivables', [FinancialReportController::class, 'receivables'])->name('financial.receivables');
        Route::get('/financial/export', [FinancialReportController::class, 'export'])->name('financial.export');
    });





    // Additional API routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('/gold-rates', function () {
            // This could be connected to a real gold rate API
            return response()->json([
                '24k' => 180000, // PKR per tola
                '22k' => 165000,
                '21k' => 157500,
                '18k' => 135000
            ]);
        })->name('api.gold-rates');
    });
});

require __DIR__ . '/auth.php';
