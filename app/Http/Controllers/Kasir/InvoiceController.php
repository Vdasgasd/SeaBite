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
        $invoices = Invoice::with(['pesanan.meja', 'kasir'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kasir.invoice.index', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesanan_id' => 'required|exists:pesanan,pesanan_id',
            'metode_pembayaran' => 'required|in:tunai,kartu_debit,kartu_kredit'
        ]);

        $pesanan = Pesanan::findOrFail($validated['pesanan_id']);

        if ($pesanan->status_pesanan !== 'selesai') {
            return redirect()->back()
                ->with('error', 'Pesanan belum selesai');
        }

        $invoice = Invoice::create([
            'pesanan_id' => $validated['pesanan_id'],
            'kasir_id' => Auth::id(),
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'total_bayar' => $pesanan->total_harga
        ]);


        $pesanan->update(['status_pesanan' => 'dibayar']);
        $pesanan->meja->update(['status' => 'tersedia']);


        return redirect()->route('kasir.invoice.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['pesanan.meja', 'pesanan.detailPesanan.menu', 'kasir']);
        return view('kasir.invoice.show', compact('invoice'));
    }
}
