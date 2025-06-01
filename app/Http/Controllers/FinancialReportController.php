<?php
// app/Http/Controllers/FinancialReportController.php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\GoldPurchase;
use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Revenue & Sales
        $sales = Sale::whereBetween('sale_date', [$dateFrom, $dateTo])->get();
        $totalRevenue = $sales->sum('net_bill');
        $totalReceived = $sales->sum('total_received');
        $pendingReceivables = $sales->where('cash_balance', '>', 0)->sum('cash_balance');

        // Gold Purchases (Expenses)
        $goldPurchases = GoldPurchase::whereBetween('date', [$dateFrom, $dateTo])->get();
        $totalGoldPurchases = $goldPurchases->sum('amount');
        $goldPurchaseCash = $goldPurchases->sum('cash_payment');

        // Inventory Investment
        $inventoryAdded = Item::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_price');
        $currentInventoryValue = Item::where('status', 'in_stock')->sum('total_price');

        // Cost of Goods Sold
        $soldItems = Sale::with('saleItems.item')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->get()
            ->flatMap(function($sale) {
                return $sale->saleItems;
            });
        $cogs = $soldItems->sum(function($saleItem) {
            return $saleItem->item->making_cost + $saleItem->item->stone_price + 
                   ($saleItem->item->pure_weight * 150000); // Approximate gold cost
        });

        // Gross Profit
        $grossProfit = $totalRevenue - $cogs;
        $grossProfitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Payment Method Breakdown
        $paymentBreakdown = [
            'cash' => $sales->sum('cash_received'),
            'credit_card' => $sales->sum('credit_card_amount'),
            'check' => $sales->sum('check_amount'),
            'used_gold' => $sales->sum('used_gold_amount'),
            'pure_gold' => $sales->sum('pure_gold_amount'),
        ];

        // Daily Financial Summary
        $dailyFinancials = [];
        $period = new \DatePeriod(
            Carbon::parse($dateFrom), 
            new \DateInterval('P1D'), 
            Carbon::parse($dateTo)->addDay()
        );

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            $daySales = $sales->filter(function($sale) use ($dateStr) {
                return $sale->sale_date->format('Y-m-d') === $dateStr;
            });
            
            $dayPurchases = $goldPurchases->filter(function($purchase) use ($dateStr) {
                return $purchase->date->format('Y-m-d') === $dateStr;
            });

            $dailyFinancials[] = [
                'date' => $dateStr,
                'revenue' => $daySales->sum('net_bill'),
                'received' => $daySales->sum('total_received'),
                'purchases' => $dayPurchases->sum('amount'),
                'net_cash_flow' => $daySales->sum('total_received') - $dayPurchases->sum('cash_payment'),
            ];
        }

        // Outstanding Receivables by Customer
        $outstandingReceivables = Customer::whereHas('sales', function($query) {
            $query->where('cash_balance', '>', 0);
        })->withSum(['sales as outstanding_amount' => function($query) {
            $query->where('cash_balance', '>', 0);
        }], 'cash_balance')
        ->orderBy('outstanding_amount', 'desc')
        ->limit(10)
        ->get();

        // Monthly Comparison
        $previousMonth = [
            'from' => Carbon::parse($dateFrom)->subMonth()->startOfMonth()->format('Y-m-d'),
            'to' => Carbon::parse($dateFrom)->subMonth()->endOfMonth()->format('Y-m-d')
        ];
        
        $previousMonthSales = Sale::whereBetween('sale_date', [$previousMonth['from'], $previousMonth['to']])->get();
        $previousRevenue = $previousMonthSales->sum('net_bill');
        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        return view('reports.financial.index', compact(
            'totalRevenue', 'totalReceived', 'pendingReceivables', 'totalGoldPurchases',
            'goldPurchaseCash', 'inventoryAdded', 'currentInventoryValue', 'cogs',
            'grossProfit', 'grossProfitMargin', 'paymentBreakdown', 'dailyFinancials',
            'outstandingReceivables', 'revenueGrowth', 'dateFrom', 'dateTo'
        ));
    }

    public function profitLoss(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Revenue
        $sales = Sale::whereBetween('sale_date', [$dateFrom, $dateTo])->get();
        $totalSalesRevenue = $sales->sum('net_bill');
        $totalMakingCharges = $sales->sum('total_making');
        $totalStoneCharges = $sales->sum('total_stone_charges');
        $totalOtherCharges = $sales->sum('total_other_charges');

        // Cost of Goods Sold
        $soldItems = Sale::with('saleItems.item')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->get()
            ->flatMap(function($sale) {
                return $sale->saleItems;
            });

        $goldCost = $soldItems->sum(function($saleItem) {
            return $saleItem->item->pure_weight * 150000; // Average gold cost
        });
        
        $stoneCost = $soldItems->sum('stone_price');
        $makingCost = $soldItems->sum(function($saleItem) {
            return $saleItem->item->making_cost;
        });

        $totalCOGS = $goldCost + $stoneCost + $makingCost;
        $grossProfit = $totalSalesRevenue - $totalCOGS;

        // Operating Expenses (estimated)
        $operatingExpenses = [
            'rent' => 50000, // Monthly rent
            'utilities' => 15000,
            'salaries' => 100000,
            'insurance' => 10000,
            'miscellaneous' => 25000,
        ];
        $totalOperatingExpenses = array_sum($operatingExpenses);

        // Gold Purchase Costs
        $goldPurchases = GoldPurchase::whereBetween('date', [$dateFrom, $dateTo])->sum('amount');

        // Net Profit
        $netProfit = $grossProfit - $totalOperatingExpenses;
        $netProfitMargin = $totalSalesRevenue > 0 ? ($netProfit / $totalSalesRevenue) * 100 : 0;

        // Comparison with previous period
        $previousPeriod = $this->getPreviousPeriodData($dateFrom, $dateTo);

        return view('reports.financial.profit-loss', compact(
            'totalSalesRevenue', 'totalMakingCharges', 'totalStoneCharges', 'totalOtherCharges',
            'goldCost', 'stoneCost', 'makingCost', 'totalCOGS', 'grossProfit',
            'operatingExpenses', 'totalOperatingExpenses', 'goldPurchases', 'netProfit',
            'netProfitMargin', 'previousPeriod', 'dateFrom', 'dateTo'
        ));
    }

    public function cashFlow(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Cash Inflows
        $sales = Sale::whereBetween('sale_date', [$dateFrom, $dateTo])->get();
        $cashFromSales = $sales->sum('cash_received');
        $creditCardReceipts = $sales->sum('credit_card_amount');
        $checkReceipts = $sales->sum('check_amount');
        $goldReceipts = $sales->sum('used_gold_amount') + $sales->sum('pure_gold_amount');

        $totalCashInflows = $cashFromSales + $creditCardReceipts + $checkReceipts + $goldReceipts;

        // Cash Outflows
        $goldPurchases = GoldPurchase::whereBetween('date', [$dateFrom, $dateTo])->sum('cash_payment');
        $inventoryInvestment = Item::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_price');
        
        // Operating expenses (monthly estimates)
        $operatingCashOutflows = [
            'rent' => 50000,
            'utilities' => 15000,
            'salaries' => 100000,
            'supplies' => 10000,
            'marketing' => 5000,
            'insurance' => 10000,
            'miscellaneous' => 15000,
        ];
        $totalOperatingOutflows = array_sum($operatingCashOutflows);

        $totalCashOutflows = $goldPurchases + $totalOperatingOutflows;
        $netCashFlow = $totalCashInflows - $totalCashOutflows;

        // Daily cash flow
        $dailyCashFlow = [];
        $period = new \DatePeriod(
            Carbon::parse($dateFrom), 
            new \DateInterval('P1D'), 
            Carbon::parse($dateTo)->addDay()
        );

        $runningBalance = 500000; // Starting cash balance
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            $dayInflows = $sales->filter(function($sale) use ($dateStr) {
                return $sale->sale_date->format('Y-m-d') === $dateStr;
            })->sum('total_received');
            
            $dayOutflows = GoldPurchase::whereDate('date', $dateStr)->sum('cash_payment');
            
            $dayNetFlow = $dayInflows - $dayOutflows;
            $runningBalance += $dayNetFlow;

            $dailyCashFlow[] = [
                'date' => $dateStr,
                'inflows' => $dayInflows,
                'outflows' => $dayOutflows,
                'net_flow' => $dayNetFlow,
                'running_balance' => $runningBalance,
            ];
        }

        // Cash flow categories
        $inflowCategories = [
            'Sales (Cash)' => $cashFromSales,
            'Sales (Credit Card)' => $creditCardReceipts,
            'Sales (Check)' => $checkReceipts,
            'Gold Exchange' => $goldReceipts,
        ];

        $outflowCategories = [
            'Gold Purchases' => $goldPurchases,
            'Operating Expenses' => $totalOperatingOutflows,
        ];

        return view('reports.financial.cash-flow', compact(
            'totalCashInflows', 'totalCashOutflows', 'netCashFlow', 'dailyCashFlow',
            'inflowCategories', 'outflowCategories', 'operatingCashOutflows',
            'dateFrom', 'dateTo'
        ));
    }

    public function receivables(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->format('Y-m-d'));

        // Outstanding receivables
        $outstandingReceivables = Sale::where('cash_balance', '>', 0)
            ->where('sale_date', '<=', $asOfDate)
            ->with(['customer', 'saleItems.item'])
            ->orderBy('sale_date', 'asc')
            ->get();

        // Aging analysis
        $agingBuckets = [
            '0-30 days' => 0,
            '31-60 days' => 0,
            '61-90 days' => 0,
            '90+ days' => 0,
        ];

        foreach ($outstandingReceivables as $sale) {
            $daysPastDue = $sale->sale_date->diffInDays(Carbon::parse($asOfDate));
            
            if ($daysPastDue <= 30) {
                $agingBuckets['0-30 days'] += $sale->cash_balance;
            } elseif ($daysPastDue <= 60) {
                $agingBuckets['31-60 days'] += $sale->cash_balance;
            } elseif ($daysPastDue <= 90) {
                $agingBuckets['61-90 days'] += $sale->cash_balance;
            } else {
                $agingBuckets['90+ days'] += $sale->cash_balance;
            }
        }

        // Customer-wise receivables
        $customerReceivables = Customer::whereHas('sales', function($query) use ($asOfDate) {
            $query->where('cash_balance', '>', 0)->where('sale_date', '<=', $asOfDate);
        })->withSum(['sales as total_outstanding' => function($query) use ($asOfDate) {
            $query->where('cash_balance', '>', 0)->where('sale_date', '<=', $asOfDate);
        }], 'cash_balance')
        ->withCount(['sales as outstanding_invoices' => function($query) use ($asOfDate) {
            $query->where('cash_balance', '>', 0)->where('sale_date', '<=', $asOfDate);
        }])
        ->orderBy('total_outstanding', 'desc')
        ->get();

        // Summary statistics
        $summary = [
            'total_outstanding' => $outstandingReceivables->sum('cash_balance'),
            'number_of_invoices' => $outstandingReceivables->count(),
            'number_of_customers' => $customerReceivables->count(),
            'average_invoice_amount' => $outstandingReceivables->count() > 0 ? 
                $outstandingReceivables->avg('cash_balance') : 0,
            'oldest_invoice_days' => $outstandingReceivables->count() > 0 ? 
                $outstandingReceivables->min('sale_date')->diffInDays(Carbon::parse($asOfDate)) : 0,
        ];

        // Payment collection trend (last 6 months)
        $collectionTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::parse($asOfDate)->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::parse($asOfDate)->subMonths($i)->endOfMonth();
            
            $monthCollections = Sale::whereBetween('sale_date', [$monthStart, $monthEnd])
                ->sum('total_received');
                
            $collectionTrend[] = [
                'month' => $monthStart->format('M Y'),
                'collections' => $monthCollections,
            ];
        }

        return view('reports.financial.receivables', compact(
            'outstandingReceivables', 'agingBuckets', 'customerReceivables',
            'summary', 'collectionTrend', 'asOfDate'
        ));
    }

    public function export(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $format = $request->get('format', 'csv');

        switch ($reportType) {
            case 'profit_loss':
                return $this->exportProfitLoss($dateFrom, $dateTo, $format);
            case 'cash_flow':
                return $this->exportCashFlow($dateFrom, $dateTo, $format);
            case 'receivables':
                return $this->exportReceivables($dateTo, $format);
            default:
                return $this->exportSummary($dateFrom, $dateTo, $format);
        }
    }

    private function getPreviousPeriodData($dateFrom, $dateTo)
    {
        $periodLength = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        $previousFrom = Carbon::parse($dateFrom)->subDays($periodLength + 1)->format('Y-m-d');
        $previousTo = Carbon::parse($dateFrom)->subDay()->format('Y-m-d');

        $previousSales = Sale::whereBetween('sale_date', [$previousFrom, $previousTo])->get();
        
        return [
            'revenue' => $previousSales->sum('net_bill'),
            'dates' => $previousFrom . ' to ' . $previousTo,
        ];
    }

    private function exportSummary($dateFrom, $dateTo, $format)
    {
        $filename = "financial_summary_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Golden Jewellers - Financial Summary']);
            fputcsv($file, ['Period: ' . $dateFrom . ' to ' . $dateTo]);
            fputcsv($file, []);
            
            // Get financial data
            $sales = Sale::whereBetween('sale_date', [$dateFrom, $dateTo])->get();
            $goldPurchases = GoldPurchase::whereBetween('date', [$dateFrom, $dateTo])->get();
            
            fputcsv($file, ['REVENUE']);
            fputcsv($file, ['Total Sales Revenue', number_format($sales->sum('net_bill'), 2)]);
            fputcsv($file, ['Total Received', number_format($sales->sum('total_received'), 2)]);
            fputcsv($file, ['Pending Receivables', number_format($sales->where('cash_balance', '>', 0)->sum('cash_balance'), 2)]);
            fputcsv($file, []);
            
            fputcsv($file, ['EXPENSES']);
            fputcsv($file, ['Gold Purchases', number_format($goldPurchases->sum('amount'), 2)]);
            fputcsv($file, ['Cash Paid for Gold', number_format($goldPurchases->sum('cash_payment'), 2)]);
            fputcsv($file, []);
            
            fputcsv($file, ['PAYMENT METHODS']);
            fputcsv($file, ['Cash', number_format($sales->sum('cash_received'), 2)]);
            fputcsv($file, ['Credit Card', number_format($sales->sum('credit_card_amount'), 2)]);
            fputcsv($file, ['Check', number_format($sales->sum('check_amount'), 2)]);
            fputcsv($file, ['Used Gold', number_format($sales->sum('used_gold_amount'), 2)]);
            fputcsv($file, ['Pure Gold', number_format($sales->sum('pure_gold_amount'), 2)]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportProfitLoss($dateFrom, $dateTo, $format)
    {
        // Similar implementation for P&L export
        return $this->exportSummary($dateFrom, $dateTo, $format);
    }

    private function exportCashFlow($dateFrom, $dateTo, $format)
    {
        // Similar implementation for cash flow export
        return $this->exportSummary($dateFrom, $dateTo, $format);
    }

    private function exportReceivables($asOfDate, $format)
    {
        // Similar implementation for receivables export
        return $this->exportSummary(now()->startOfMonth()->format('Y-m-d'), $asOfDate, $format);
    }
}