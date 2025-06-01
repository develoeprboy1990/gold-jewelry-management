<?php
// app/Http/Controllers/SalesReportController.php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));
        $customerId = $request->get('customer_id');
        $status = $request->get('status');

        // Build query
        $query = Sale::with(['customer', 'user'])
            ->whereBetween('sale_date', [$dateFrom, $dateTo]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($status) {
            if ($status === 'paid') {
                $query->where('cash_balance', '<=', 0);
            } elseif ($status === 'pending') {
                $query->where('cash_balance', '>', 0);
            }
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(15);

        // Calculate summary statistics
        $totalSales = $query->count();
        $totalRevenue = $query->sum('net_bill');
        $totalReceived = $query->sum('total_received');
        $totalPending = $query->where('cash_balance', '>', 0)->sum('cash_balance');

        // Daily sales for chart
        $dailySales = Sale::selectRaw('DATE(sale_date) as date, COUNT(*) as count, SUM(net_bill) as revenue')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers
        $topCustomers = Sale::select('customer_id', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(net_bill) as total_spent'))
            ->with('customer')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Sales by payment method
        $paymentMethods = [
            'Cash' => $query->sum('cash_received'),
            'Credit Card' => $query->sum('credit_card_amount'),
            'Check' => $query->sum('check_amount'),
            'Used Gold' => $query->sum('used_gold_amount'),
            'Pure Gold' => $query->sum('pure_gold_amount'),
        ];

        $customers = Customer::select('id', 'name', 'contact_no')->get();

        return view('reports.sales.index', compact(
            'sales', 'totalSales', 'totalRevenue', 'totalReceived', 'totalPending',
            'dailySales', 'topCustomers', 'paymentMethods', 'customers',
            'dateFrom', 'dateTo', 'customerId', 'status'
        ));
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $dailySales = Sale::with(['customer', 'user', 'saleItems.item'])
            ->whereDate('sale_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_sales' => $dailySales->count(),
            'total_revenue' => $dailySales->sum('net_bill'),
            'total_received' => $dailySales->sum('total_received'),
            'total_pending' => $dailySales->where('cash_balance', '>', 0)->sum('cash_balance'),
            'avg_sale_value' => $dailySales->count() > 0 ? $dailySales->avg('net_bill') : 0,
            'total_items_sold' => $dailySales->sum(function($sale) {
                return $sale->saleItems->count();
            }),
        ];

        // Hourly breakdown
        $hourlySales = $dailySales->groupBy(function($sale) {
            return $sale->created_at->format('H');
        })->map(function($sales, $hour) {
            return [
                'hour' => $hour . ':00',
                'count' => $sales->count(),
                'revenue' => $sales->sum('net_bill')
            ];
        })->values();

        return view('reports.sales.daily', compact('dailySales', 'summary', 'hourlySales', 'date'));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // Monthly summary
        $monthlySales = Sale::whereBetween('sale_date', [$startDate, $endDate])->get();
        
        $summary = [
            'total_sales' => $monthlySales->count(),
            'total_revenue' => $monthlySales->sum('net_bill'),
            'total_received' => $monthlySales->sum('total_received'),
            'total_pending' => $monthlySales->where('cash_balance', '>', 0)->sum('cash_balance'),
            'avg_daily_sales' => $monthlySales->count() / $startDate->daysInMonth,
            'avg_sale_value' => $monthlySales->count() > 0 ? $monthlySales->avg('net_bill') : 0,
        ];

        // Daily breakdown for the month
        $dailyBreakdown = Sale::selectRaw('DATE(sale_date) as date, COUNT(*) as count, SUM(net_bill) as revenue')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with zero values
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->addDay());
        $dailyData = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $dailyData[] = [
                'date' => $dateStr,
                'day' => $date->format('d'),
                'count' => $dailyBreakdown->get($dateStr)->count ?? 0,
                'revenue' => $dailyBreakdown->get($dateStr)->revenue ?? 0,
            ];
        }

        // Top performing days
        $topDays = collect($dailyData)->sortByDesc('revenue')->take(5);

        // Week-wise breakdown
        $weeklyData = collect($dailyData)->groupBy(function($item) {
            return 'Week ' . ceil(Carbon::parse($item['date'])->day / 7);
        })->map(function($week) {
            return [
                'count' => $week->sum('count'),
                'revenue' => $week->sum('revenue'),
            ];
        });

        return view('reports.sales.monthly', compact(
            'summary', 'dailyData', 'topDays', 'weeklyData', 'month'
        ));
    }

    public function customer(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Customer sales analysis
        $customerStats = Customer::select('customers.*')
            ->withCount(['sales' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
            }])
            ->withSum(['sales' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
            }], 'net_bill')
            ->withAvg(['sales' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
            }], 'net_bill')
            ->withSum(['sales' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('sale_date', [$dateFrom, $dateTo])->where('cash_balance', '>', 0);
            }], 'cash_balance')
            ->having('sales_count', '>', 0)
            ->orderBy('sales_sum_net_bill', 'desc')
            ->paginate(15);

        // Customer segments
        $segments = [
            'vip' => $customerStats->where('sales_sum_net_bill', '>=', 500000)->count(),
            'regular' => $customerStats->whereBetween('sales_sum_net_bill', [100000, 499999])->count(),
            'occasional' => $customerStats->whereBetween('sales_sum_net_bill', [1, 99999])->count(),
        ];

        // New vs returning customers
        $newCustomers = Customer::whereHas('sales', function($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
        })->whereDoesntHave('sales', function($query) use ($dateFrom) {
            $query->where('sale_date', '<', $dateFrom);
        })->count();

        $returningCustomers = Customer::whereHas('sales', function($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
        })->whereHas('sales', function($query) use ($dateFrom) {
            $query->where('sale_date', '<', $dateFrom);
        })->count();

        return view('reports.sales.customer', compact(
            'customerStats', 'segments', 'newCustomers', 'returningCustomers', 
            'dateFrom', 'dateTo'
        ));
    }

    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));
        $format = $request->get('format', 'csv');

        $sales = Sale::with(['customer', 'user', 'saleItems.item'])
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->orderBy('sale_date', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportCsv($sales, $dateFrom, $dateTo);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($sales, $dateFrom, $dateTo);
        }

        return redirect()->back()->with('error', 'Invalid export format');
    }

    private function exportCsv($sales, $dateFrom, $dateTo)
    {
        $filename = "sales_report_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Sale No', 'Date', 'Customer', 'Contact', 'Items Count',
                'Total Making', 'Total Stone', 'Total Gold', 'Gross Total',
                'Bill Discount', 'Net Bill', 'Cash Received', 'Credit Card',
                'Check', 'Used Gold', 'Pure Gold', 'Total Received', 'Balance', 'Status'
            ]);

            // CSV data
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->sale_no,
                    $sale->sale_date->format('Y-m-d'),
                    $sale->customer->name,
                    $sale->customer->contact_no,
                    $sale->saleItems->count(),
                    $sale->total_making,
                    $sale->total_stone_charges,
                    $sale->total_gold_price,
                    $sale->total_making + $sale->total_stone_charges + $sale->total_other_charges + $sale->total_gold_price,
                    $sale->bill_discount,
                    $sale->net_bill,
                    $sale->cash_received,
                    $sale->credit_card_amount,
                    $sale->check_amount,
                    $sale->used_gold_amount,
                    $sale->pure_gold_amount,
                    $sale->total_received,
                    $sale->cash_balance,
                    $sale->cash_balance <= 0 ? 'Paid' : 'Pending'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($sales, $dateFrom, $dateTo)
    {
        // This would use the PDF library (like DomPDF) to generate PDF
        // For now, returning CSV as PDF generation would need additional setup
        return $this->exportCsv($sales, $dateFrom, $dateTo);
    }
}