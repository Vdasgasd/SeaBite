<?php


namespace App\Http\Controllers\Kasir;

use App\Models\Invoice;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['pesanan.meja', 'kasir'])->get();
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesanan_id' => 'required|exists:pesanan,pesanan_id',
            'metode_pembayaran' => 'required|in:tunai,kartu_debit,kartu_kredit'
        ]);

        $pesanan = Pesanan::findOrFail($validated['pesanan_id']);

        if ($pesanan->status_pesanan !== 'selesai') {
            return response()->json(['error' => 'Pesanan belum selesai'], 400);
        }

        $invoice = Invoice::create([
            'pesanan_id' => $validated['pesanan_id'],
            'kasir_id' => Auth::id(),
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'total_bayar' => $pesanan->total_harga
        ]);

        // Update status pesanan menjadi dibayar
        $pesanan->update(['status_pesanan' => 'dibayar']);

        return response()->json($invoice->load('pesanan'), 201);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['pesanan.meja', 'pesanan.detailPesanan.menu', 'kasir']);
        return response()->json($invoice);
    }
}
