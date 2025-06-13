<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{

    public function penjualan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly

        $query = Invoice::query()->with('kasir', 'pesanan');
        $query->whereBetween('waktu_pembayaran', [$startDate, Carbon::parse($endDate)->endOfDay()]);

        $invoices = $query->latest('waktu_pembayaran')->paginate(15);
        $totalPendapatan = $query->sum('total_bayar');
        $jumlahTransaksi = $query->count();

        // Data untuk grafik berdasarkan periode
        $chartData = $this->getChartData($startDate, $endDate, $period);

        return view('admin.laporan.index', compact(
            'invoices',
            'totalPendapatan',
            'jumlahTransaksi',
            'startDate',
            'endDate',
            'period',
            'chartData'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $period = $request->input('period', 'monthly');

        $query = Invoice::query()->with('kasir', 'pesanan');
        $query->whereBetween('waktu_pembayaran', [$startDate, Carbon::parse($endDate)->endOfDay()]);

        $invoices = $query->latest('waktu_pembayaran')->get();
        $totalPendapatan = $query->sum('total_bayar');
        $jumlahTransaksi = $query->count();

        // Data untuk grafik
        $chartData = $this->getChartData($startDate, $endDate, $period);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'invoices',
            'totalPendapatan',
            'jumlahTransaksi',
            'startDate',
            'endDate',
            'period',
            'chartData'
        ));

        $filename = 'laporan-penjualan-' . $startDate . '-to-' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    private function getChartData($startDate, $endDate, $period)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $data = [];

        switch ($period) {
            case 'daily':
                $data = $this->getDailyData($start, $end);
                break;
            case 'weekly':
                $data = $this->getWeeklyData($start, $end);
                break;
            case 'monthly':
                $data = $this->getMonthlyData($start, $end);
                break;
        }

        return $data;
    }

    private function getDailyData($start, $end)
    {
        $data = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();

            $pendapatan = Invoice::whereBetween('waktu_pembayaran', [$dayStart, $dayEnd])
                ->sum('total_bayar');

            $transaksi = Invoice::whereBetween('waktu_pembayaran', [$dayStart, $dayEnd])
                ->count();

            $data[] = [
                'label' => $current->format('d M Y'),
                'pendapatan' => $pendapatan,
                'transaksi' => $transaksi,
                'date' => $current->format('Y-m-d')
            ];

            $current->addDay();
        }

        return $data;
    }

    private function getWeeklyData($start, $end)
    {
        $data = [];
        $current = $start->copy()->startOfWeek();

        while ($current->lte($end)) {
            $weekStart = $current->copy()->startOfWeek();
            $weekEnd = $current->copy()->endOfWeek();

            // Batasi minggu terakhir sampai tanggal akhir
            if ($weekEnd->gt($end)) {
                $weekEnd = $end->copy()->endOfDay();
            }

            $pendapatan = Invoice::whereBetween('waktu_pembayaran', [$weekStart, $weekEnd])
                ->sum('total_bayar');

            $transaksi = Invoice::whereBetween('waktu_pembayaran', [$weekStart, $weekEnd])
                ->count();

            $data[] = [
                'label' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y'),
                'pendapatan' => $pendapatan,
                'transaksi' => $transaksi,
                'date' => $weekStart->format('Y-m-d')
            ];

            $current->addWeek();
        }

        return $data;
    }

    private function getMonthlyData($start, $end)
    {
        $data = [];
        $current = $start->copy()->startOfMonth();

        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            // Batasi bulan terakhir sampai tanggal akhir
            if ($monthEnd->gt($end)) {
                $monthEnd = $end->copy()->endOfDay();
            }

            $pendapatan = Invoice::whereBetween('waktu_pembayaran', [$monthStart, $monthEnd])
                ->sum('total_bayar');

            $transaksi = Invoice::whereBetween('waktu_pembayaran', [$monthStart, $monthEnd])
                ->count();

            $data[] = [
                'label' => $current->format('M Y'),
                'pendapatan' => $pendapatan,
                'transaksi' => $transaksi,
                'date' => $current->format('Y-m-d')
            ];

            $current->addMonth();
        }

        return $data;
    }
}
