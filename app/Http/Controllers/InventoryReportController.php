<?php
// app/Http/Controllers/InventoryReportController.php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->get('category_id');
        $status = $request->get('status');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Build query
        $query = Item::with(['category', 'stones']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $items = $query->orderBy($sortBy, $sortOrder)->paginate(20);

        // Summary statistics
        $totalItems = Item::count();
        $inStockItems = Item::where('status', 'in_stock')->count();
        $soldItems = Item::where('status', 'sold')->count();
        $onOrderItems = Item::where('status', 'on_order')->count();

        // Inventory value
        $totalInventoryValue = Item::where('status', 'in_stock')->sum('total_price');
        $averageItemValue = $inStockItems > 0 ? $totalInventoryValue / $inStockItems : 0;

        // Category breakdown
        $categoryBreakdown = ItemCategory::withCount(['items' => function($query) {
            $query->where('status', 'in_stock');
        }])->withSum(['items' => function($query) {
            $query->where('status', 'in_stock');
        }], 'total_price')->get();

        // Recent additions
        $recentItems = Item::where('status', 'in_stock')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Low stock items (quantity <= 1)
        $lowStockItems = Item::where('status', 'in_stock')
            ->where('quantity', '<=', 1)
            ->count();

        // Weight analysis
        $totalWeight = Item::where('status', 'in_stock')->sum('weight');
        $averageWeight = $inStockItems > 0 ? $totalWeight / $inStockItems : 0;

        $categories = ItemCategory::all();

        return view('reports.inventory.index', compact(
            'items', 'totalItems', 'inStockItems', 'soldItems', 'onOrderItems',
            'totalInventoryValue', 'averageItemValue', 'categoryBreakdown',
            'recentItems', 'lowStockItems', 'totalWeight', 'averageWeight',
            'categories', 'categoryId', 'status', 'sortBy', 'sortOrder'
        ));
    }

    public function valuation(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->format('Y-m-d'));
        
        // Get items that were in stock as of the specified date
        $items = Item::where('created_at', '<=', $asOfDate)
            ->where(function($query) use ($asOfDate) {
                $query->where('status', 'in_stock')
                    ->orWhere(function($q) use ($asOfDate) {
                        $q->where('status', 'sold')
                          ->whereHas('saleItems.sale', function($sq) use ($asOfDate) {
                              $sq->where('sale_date', '>', $asOfDate);
                          });
                    });
            })
            ->with(['category', 'stones'])
            ->get();

        // Valuation by category
        $categoryValuation = $items->groupBy('category.name')->map(function($categoryItems) {
            return [
                'count' => $categoryItems->count(),
                'total_value' => $categoryItems->sum('total_price'),
                'total_weight' => $categoryItems->sum('weight'),
                'avg_value' => $categoryItems->avg('total_price'),
                'making_cost' => $categoryItems->sum('making_cost'),
                'stone_value' => $categoryItems->sum('stone_price'),
            ];
        });

        // Valuation by karat
        $karatValuation = $items->groupBy('karat')->map(function($karatItems) {
            return [
                'count' => $karatItems->count(),
                'total_value' => $karatItems->sum('total_price'),
                'total_weight' => $karatItems->sum('weight'),
                'avg_value' => $karatItems->avg('total_price'),
            ];
        });

        // Total valuation summary
        $totalValuation = [
            'total_items' => $items->count(),
            'total_value' => $items->sum('total_price'),
            'total_weight' => $items->sum('weight'),
            'total_making_cost' => $items->sum('making_cost'),
            'total_stone_value' => $items->sum('stone_price'),
            'total_pure_weight' => $items->sum('pure_weight'),
        ];

        // Top valuable items
        $topValueItems = $items->sortByDesc('total_price')->take(10);

        // Age analysis
        $ageAnalysis = $items->groupBy(function($item) {
            $months = $item->created_at->diffInMonths(now());
            if ($months < 1) return '0-1 months';
            if ($months < 3) return '1-3 months';
            if ($months < 6) return '3-6 months';
            if ($months < 12) return '6-12 months';
            return '12+ months';
        })->map(function($ageGroup) {
            return [
                'count' => $ageGroup->count(),
                'value' => $ageGroup->sum('total_price'),
            ];
        });

        return view('reports.inventory.valuation', compact(
            'categoryValuation', 'karatValuation', 'totalValuation', 
            'topValueItems', 'ageAnalysis', 'asOfDate'
        ));
    }

    public function movement(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Items added to inventory
        $itemsAdded = Item::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        // Items sold
        $itemsSold = SaleItem::whereHas('sale', function($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('sale_date', [$dateFrom, $dateTo]);
        })->with(['item.category', 'sale.customer'])->get();

        // Movement summary
        $movementSummary = [
            'items_added' => $itemsAdded->count(),
            'items_sold' => $itemsSold->count(),
            'value_added' => $itemsAdded->sum('total_price'),
            'value_sold' => $itemsSold->sum('net_price'),
            'weight_added' => $itemsAdded->sum('weight'),
            'weight_sold' => $itemsSold->sum('weight'),
        ];

        // Daily movement
        $dailyMovement = [];
        $period = new \DatePeriod(
            Carbon::parse($dateFrom), 
            new \DateInterval('P1D'), 
            Carbon::parse($dateTo)->addDay()
        );

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            $addedToday = $itemsAdded->filter(function($item) use ($dateStr) {
                return $item->created_at->format('Y-m-d') === $dateStr;
            });
            
            $soldToday = $itemsSold->filter(function($saleItem) use ($dateStr) {
                return $saleItem->sale->sale_date->format('Y-m-d') === $dateStr;
            });

            $dailyMovement[] = [
                'date' => $dateStr,
                'added_count' => $addedToday->count(),
                'sold_count' => $soldToday->count(),
                'added_value' => $addedToday->sum('total_price'),
                'sold_value' => $soldToday->sum('net_price'),
            ];
        }

        // Category movement
        $categoryMovement = ItemCategory::withCount([
            'items as added_count' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
        ])->withSum([
            'items as added_value' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
        ], 'total_price')->get();

        // Fast moving items (frequently sold)
        $fastMovingItems = Item::withCount(['saleItems' => function($query) use ($dateFrom, $dateTo) {
            $query->whereHas('sale', function($sq) use ($dateFrom, $dateTo) {
                $sq->whereBetween('sale_date', [$dateFrom, $dateTo]);
            });
        }])->having('sale_items_count', '>', 0)
          ->orderBy('sale_items_count', 'desc')
          ->with('category')
          ->limit(10)
          ->get();

        // Slow moving items (old stock)
        $slowMovingItems = Item::where('status', 'in_stock')
            ->where('created_at', '<', now()->subMonths(6))
            ->whereDoesntHave('saleItems', function($query) use ($dateFrom, $dateTo) {
                $query->whereHas('sale', function($sq) use ($dateFrom, $dateTo) {
                    $sq->whereBetween('sale_date', [$dateFrom, $dateTo]);
                });
            })
            ->with('category')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return view('reports.inventory.movement', compact(
            'itemsAdded', 'itemsSold', 'movementSummary', 'dailyMovement',
            'categoryMovement', 'fastMovingItems', 'slowMovingItems',
            'dateFrom', 'dateTo'
        ));
    }

    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 1);
        
        // Low stock items
        $lowStockItems = Item::where('status', 'in_stock')
            ->where('quantity', '<=', $threshold)
            ->with(['category', 'stones'])
            ->orderBy('quantity', 'asc')
            ->get();

        // Out of stock items (quantity = 0)
        $outOfStockItems = Item::where('status', 'in_stock')
            ->where('quantity', 0)
            ->with('category')
            ->get();

        // Zero inventory categories
        $zeroInventoryCategories = ItemCategory::whereDoesntHave('items', function($query) {
            $query->where('status', 'in_stock')->where('quantity', '>', 0);
        })->get();

        // Items that haven't moved in 6 months
        $staleItems = Item::where('status', 'in_stock')
            ->where('created_at', '<', now()->subMonths(6))
            ->whereDoesntHave('saleItems', function($query) {
                $query->whereHas('sale', function($sq) {
                    $sq->where('sale_date', '>=', now()->subMonths(6));
                });
            })
            ->with('category')
            ->orderBy('created_at', 'asc')
            ->get();

        // Summary statistics
        $summary = [
            'low_stock_count' => $lowStockItems->count(),
            'low_stock_value' => $lowStockItems->sum('total_price'),
            'out_of_stock_count' => $outOfStockItems->count(),
            'stale_items_count' => $staleItems->count(),
            'stale_items_value' => $staleItems->sum('total_price'),
        ];

        // Category-wise low stock
        $categoryLowStock = $lowStockItems->groupBy('category.name')->map(function($items) {
            return [
                'count' => $items->count(),
                'value' => $items->sum('total_price'),
                'avg_age_days' => $items->avg(function($item) {
                    return $item->created_at->diffInDays(now());
                }),
            ];
        });

        return view('reports.inventory.low-stock', compact(
            'lowStockItems', 'outOfStockItems', 'zeroInventoryCategories',
            'staleItems', 'summary', 'categoryLowStock', 'threshold'
        ));
    }

    public function export(Request $request)
    {
        $reportType = $request->get('type', 'current');
        $format = $request->get('format', 'csv');

        switch ($reportType) {
            case 'current':
                $items = Item::where('status', 'in_stock')->with(['category', 'stones'])->get();
                break;
            case 'all':
                $items = Item::with(['category', 'stones'])->get();
                break;
            case 'sold':
                $items = Item::where('status', 'sold')->with(['category', 'stones'])->get();
                break;
            case 'low_stock':
                $items = Item::where('status', 'in_stock')->where('quantity', '<=', 1)->with(['category', 'stones'])->get();
                break;
            default:
                $items = Item::where('status', 'in_stock')->with(['category', 'stones'])->get();
        }

        if ($format === 'csv') {
            return $this->exportCsv($items, $reportType);
        }

        return redirect()->back()->with('error', 'Invalid export format');
    }

    private function exportCsv($items, $reportType)
    {
        $filename = "inventory_{$reportType}_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Tag Number', 'Category', 'Group Item', 'Sub Group', 'Status',
                'Weight (g)', 'Karat', 'Pure Weight', 'Quantity', 'Pieces',
                'Making Cost', 'Stone Price', 'Total Price', 'Worker Name',
                'Design No', 'Description', 'Date Created', 'Stone Count', 'Total Stone Value'
            ]);

            // CSV data
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->tag_number,
                    $item->category->name,
                    $item->group_item,
                    $item->sub_group_item,
                    ucfirst(str_replace('_', ' ', $item->status)),
                    $item->weight,
                    $item->karat,
                    $item->pure_weight,
                    $item->quantity,
                    $item->pieces,
                    $item->making_cost,
                    $item->stone_price,
                    $item->total_price,
                    $item->worker_name,
                    $item->design_no,
                    $item->description,
                    $item->created_at->format('Y-m-d'),
                    $item->stones->count(),
                    $item->stones->sum('price')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}