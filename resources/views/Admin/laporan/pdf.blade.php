<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .summary-item {
            text-align: center;
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            margin: 0 5px;
            border-radius: 5px;
        }

        .summary-item h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .period-label {
            background-color: #e5e7eb;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <div class="period-label">
            @if ($period == 'daily')
                Laporan Harian
            @elseif($period == 'weekly')
                Laporan Mingguan
            @else
                Laporan Bulanan
            @endif
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h3>Jumlah Transaksi</h3>
            <div class="value">{{ number_format($jumlahTransaksi) }}</div>
        </div>
        <div class="summary-item">
            <h3>Rata-rata per Transaksi</h3>
            <div class="value">Rp
                {{ $jumlahTransaksi > 0 ? number_format($totalPendapatan / $jumlahTransaksi, 0, ',', '.') : '0' }}</div>
        </div>
    </div>

    <div class="section">
        <h2>Analisis Penjualan Per Periode</h2>
        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th class="text-right">Jumlah Transaksi</th>
                    <th class="text-right">Total Pendapatan</th>
                    <th class="text-right">Rata-rata per Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chartData as $data)
                    <tr>
                        <td>{{ $data['label'] }}</td>
                        <td class="text-right">{{ number_format($data['transaksi']) }}</td>
                        <td class="text-right">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp
                            {{ $data['transaksi'] > 0 ? number_format($data['pendapatan'] / $data['transaksi'], 0, ',', '.') : '0' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Detail Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Invoice</th>
                    <th>Waktu Pembayaran</th>
                    <th>Kasir</th>
                    <th>Metode Pembayaran</th>
                    <th class="text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_id }}</td>
                        <td>{{ $invoice->waktu_pembayaran->format('d M Y, H:i') }}</td>
                        <td>{{ $invoice->kasir->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->metode_pembayaran }}</td>
                        <td class="text-right">Rp {{ number_format($invoice->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data untuk rentang tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate pada {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
        <p>© {{ date('Y') }} - Sistem Laporan Penjualan</p>
    </div>
</body>

</html>
