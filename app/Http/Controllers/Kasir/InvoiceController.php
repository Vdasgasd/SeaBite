<?php

namespace App\Http\Controllers\Kasir;

use App\Models\Invoice;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
            'metode_pembayaran' => [
                'required',
                Rule::in(['tunai', 'midtrans']),
            ],
        ]);

        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($validated['pesanan_id']);

        if ($pesanan->status_pesanan !== 'selesai') {
            return response()->json(['error' => 'Pesanan belum selesai'], 422);
        }


        if ($validated['metode_pembayaran'] == 'tunai') {
            $invoice = Invoice::create([
                'pesanan_id' => $validated['pesanan_id'],
                'kasir_id' => Auth::id(),
                'metode_pembayaran' => 'tunai',
                'total_bayar' => $pesanan->total_harga
            ]);

            $pesanan->update(['status_pesanan' => 'dibayar']);
            $pesanan->meja->update(['status' => 'tersedia']);


            return response()->json([
                'status' => 'success',
                'redirect_url' => route('kasir.invoice.show', $invoice),
                'message' => 'Invoice berhasil dibuat secara tunai.'
            ]);
        }



        if ($validated['metode_pembayaran'] == 'midtrans') {

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;


            $orderId = 'INV-' . $pesanan->pesanan_id . '-' . time();

            $item_details = [];
            foreach ($pesanan->detailPesanan as $detail) {
                $item_details[] = [
                    'id' => $detail->detail_pesanan_id,
                    'price' => $detail->subtotal / ($detail->jumlah ?? $detail->berat_gram),
                    'quantity' => $detail->jumlah ?? $detail->berat_gram,
                    'name' => $detail->menu->nama_menu,
                ];
            }


            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $pesanan->total_harga,
                ],
                'customer_details' => [
                    'first_name' => 'Pelanggan Meja ' . $pesanan->meja->nomor_meja,
                    'email' => 'pelanggan@example.com',
                    'phone' => '08123456789',
                ],
                'item_details' => $item_details,
                 'enabled_payments' => ['qris', 'gopay', 'shopeepay', 'bca_va', 'bni_va', 'bri_va'],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                return response()->json(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }


    public function show(Invoice $invoice)
    {
        $invoice->load(['pesanan.meja', 'pesanan.detailPesanan.menu', 'kasir']);
        return view('kasir.invoice.show', compact('invoice'));
    }


    public function notificationHandler(Request $request)
    {

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification object.'], 500);
        }


        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;


        $parts = explode('-', $order_id);
        if (count($parts) < 2 || $parts[0] !== 'INV') {
            return response()->json(['error' => 'Invalid order ID format.'], 400);
        }
        $pesanan_id = $parts[1];

        $pesanan = Pesanan::find($pesanan_id);
        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan not found.'], 404);
        }


        if ($pesanan->status_pesanan === 'dibayar') {
             return response()->json(['message' => 'Payment already processed.'], 200);
        }


        if ($transaction == 'capture' || $transaction == 'settlement') {
            if ($fraud == 'challenge') {

            } else if ($fraud == 'accept') {

                DB::transaction(function () use ($pesanan, $notif, $type) {

                    Invoice::create([
                        'invoice_id' => $notif->order_id,
                        'pesanan_id' => $pesanan->pesanan_id,
                        'kasir_id' => Auth::id() ?? 1,
                        'metode_pembayaran' => $type,
                        'total_bayar' => $notif->gross_amount
                    ]);


                    $pesanan->update(['status_pesanan' => 'dibayar']);

                    $pesanan->meja->update(['status' => 'tersedia']);
                });
            }
        } else if ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {

        } else if ($transaction == 'pending') {

        }

        return response()->json(['message' => 'Notification processed successfully.'], 200);
    }
}
