<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Sale;
use App\Models\GoldPurchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_items' => Item::where('status', 'in_stock')->count(),
            'todays_sales' => Sale::whereDate('sale_date', today())->count(),
            'monthly_revenue' => Sale::whereMonth('sale_date', now()->month)
                                   ->whereYear('sale_date', now()->year)
                                   ->sum('net_bill'),
            'recent_sales' => Sale::with(['customer'])
                                 ->latest()
                                 ->limit(5)
                                 ->get(),
            'low_stock_items' => Item::where('quantity', '<=', 1)
                                   ->where('status', 'in_stock')
                                   ->count(),
            'todays_purchases' => GoldPurchase::whereDate('date', today())
                                            ->sum('amount')
        ];

        return view('dashboard', compact('stats'));
    }
}