<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', 'today');

        // Revenue data
        $revenueQuery = Order::where('payment_status', 'paid');

        if ($period === 'today') {
            $revenueQuery->whereDate('created_at', today());
        } elseif ($period === 'monthly') {
            $revenueQuery->whereYear('created_at', now()->year)
                         ->whereMonth('created_at', now()->month);
        } elseif ($period === 'yearly') {
            $revenueQuery->whereYear('created_at', now()->year);
        }

        $totalRevenue = (clone $revenueQuery)->sum('total');
        $totalOrders = (clone $revenueQuery)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top selling products
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('order', function ($q) use ($period) {
                $q->where('payment_status', 'paid');
                $this->applyPeriodFilter($q, $period);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Top categories
        $topCategoriesQuery = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('categories.name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->where('orders.payment_status', 'paid');
        $this->applyPeriodFilter($topCategoriesQuery, $period, 'orders.created_at');
        $topCategories = $topCategoriesQuery->groupBy('categories.name')
            ->orderByDesc('total_qty')
            ->get();

        // Recent orders
        $recentOrdersQuery = Order::with('user')
            ->where('payment_status', 'paid');
        $this->applyPeriodFilter($recentOrdersQuery, $period);
        $recentOrders = $recentOrdersQuery->latest()->limit(10)->get();

        // Low stock products
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'topProducts',
            'topCategories',
            'recentOrders',
            'lowStockProducts',
            'period'
        ));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $period = $request->get('period', 'today');
        $periodLabel = match ($period) {
            'today' => 'Hari Ini (' . now()->format('d M Y') . ')',
            'monthly' => 'Bulan Ini (' . now()->format('F Y') . ')',
            'yearly' => 'Tahun Ini (' . now()->year . ')',
            default => 'Hari Ini',
        };

        // Revenue data
        $revenueQuery = Order::where('payment_status', 'paid');
        $this->applyPeriodFilter($revenueQuery, $period);
        $totalRevenue = (clone $revenueQuery)->sum('total');
        $totalOrders = (clone $revenueQuery)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top selling products
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('order', function ($q) use ($period) {
                $q->where('payment_status', 'paid');
                $this->applyPeriodFilter($q, $period);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // All orders in period
        $ordersQuery = Order::with('user')->where('payment_status', 'paid');
        $this->applyPeriodFilter($ordersQuery, $period);
        $orders = $ordersQuery->latest()->get();

        // Build Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Header
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->setCellValue('A2', 'Periode: ' . $periodLabel);
        $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('d M Y H:i'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true);

        // Summary
        $sheet->setCellValue('A5', 'RINGKASAN');
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
        $sheet->setCellValue('A6', 'Total Pendapatan');
        $sheet->setCellValue('B6', $totalRevenue);
        $sheet->setCellValue('A7', 'Total Pesanan');
        $sheet->setCellValue('B7', $totalOrders);
        $sheet->setCellValue('A8', 'Rata-rata Nilai Order');
        $sheet->setCellValue('B8', $avgOrderValue);
        $sheet->getStyle('B6:B8')->getNumberFormat()->setFormatCode('#,##0');

        // Top Products
        $sheet->setCellValue('A10', 'PRODUK TERLARIS');
        $sheet->getStyle('A10')->getFont()->setBold(true)->setSize(12);
        $sheet->setCellValue('A11', 'No');
        $sheet->setCellValue('B11', 'Produk');
        $sheet->setCellValue('C11', 'Qty Terjual');
        $sheet->setCellValue('D11', 'Pendapatan');
        $sheet->getStyle('A11:D11')->getFont()->setBold(true);

        $row = 12;
        foreach ($topProducts as $i => $item) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $item->product_name);
            $sheet->setCellValue('C' . $row, $item->total_qty);
            $sheet->setCellValue('D' . $row, $item->total_revenue);
            $row++;
        }

        // Detail Orders
        $row += 2;
        $sheet->setCellValue('A' . $row, 'DETAIL PESANAN');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Order Number');
        $sheet->setCellValue('C' . $row, 'Pelanggan');
        $sheet->setCellValue('D' . $row, 'Total');
        $sheet->setCellValue('E' . $row, 'Tanggal');
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        $row++;

        foreach ($orders as $i => $order) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->user->name ?? '-');
            $sheet->setCellValue('D' . $row, $order->total);
            $sheet->setCellValue('E' . $row, $order->created_at->format('d M Y H:i'));
            $row++;
        }

        // Auto-size columns
        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Format currency columns
        $sheet->getStyle('D12:D' . max(12, 11 + $topProducts->count()))->getNumberFormat()->setFormatCode('#,##0');

        $fileName = 'Laporan_Penjualan_' . $period . '_' . now()->format('Y-m-d') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function applyPeriodFilter($query, string $period, string $column = 'created_at'): void
    {
        if ($period === 'today') {
            $query->whereDate($column, today());
        } elseif ($period === 'monthly') {
            $query->whereYear($column, now()->year)
                  ->whereMonth($column, now()->month);
        } elseif ($period === 'yearly') {
            $query->whereYear($column, now()->year);
        }
    }
}
