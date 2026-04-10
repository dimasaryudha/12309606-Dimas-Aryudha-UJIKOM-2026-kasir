<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\PembelianExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index()
    {
        $data = Pembelian::latest()->get();

        foreach ($data as $d) {
            $items = json_decode($d->detail_produk, true) ?? [];
            $d->items_detail = $items;
        }

        return view('pembelian.index', compact('data'));
    }

    public function create()
    {
        $products = Product::all();
        return view('pembelian.create', compact('products'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $products = collect($request->products)
                ->filter(fn($item) => isset($item['jumlah']) && $item['jumlah'] > 0);

            if ($products->isEmpty()) {
                return redirect()->route('pembelian.create')
                    ->with('error', 'Pilih minimal 1 produk');
            }

            // HITUNG TOTAL BELANJA SEBELUM POIN
            $total = 0;
            foreach ($products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($item['jumlah'] > $product->stock) {
                    return redirect()->route('pembelian.create')
                        ->with('error', 'Stok tidak cukup untuk ' . $product->name);
                }

                $total += $item['jumlah'] * $product->price;
            }

            $status = $request->status_member;
            $poinDipakai = 0;
            $poinBaru = 0;
            $totalPoin = 0;

            if ($status == 'member') {
                $lastPembelian = Pembelian::where('no_hp', $request->no_hp)->latest()->first();
                $poinMember = $lastPembelian->poin ?? 0;

                // BATAS POIN DIPAKAI MAKSIMAL 10% DARI TOTAL
                $maxPoin = floor($total * 0.1);

                // POIN DIPAKAI SESUAI INPUT, DIBATASI MEMBER & MAX 10%
                $poinDipakai = min($request->poin ?? 0, $poinMember, $maxPoin);

                // PRICE = TOTAL - POIN DIPAKAI
                $totalFinal = $total - $poinDipakai;

                // POIN BARU = 1% DARI TOTAL BELANJA SEBELUM POIN DIPAKAI
                $poinBaru = floor($total * 0.01);

                // TOTAL POIN AKHIR MEMBER
                $totalPoin = ($poinMember - $poinDipakai) + $poinBaru;
            } else {
                $totalFinal = $total;
                $poinDipakai = 0;
                $poinBaru = 0;
                $totalPoin = 0;
            }

            // VALIDASI BAYAR
            if ($request->bayar < $totalFinal) {
                return back()->with('error', 'Uang bayar kurang');
            }

            // SIMPAN PEMBELIAN
            $pembelian = Pembelian::create([
                'name' => $status == 'member' ? $request->nama : null,
                'tanggal' => now(),
                'price' => $totalFinal,          // price = total - poinDipakai
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $totalFinal,
                'status_member' => $status,
                'no_hp' => $status == 'member' ? $request->no_hp : null,
                'poin' => $totalPoin,
                'poin_dipakai' => $poinDipakai,
                'poin_didapat' => $poinBaru,
                'detail_produk' => $products->map(fn($item) =>
                    (Product::find($item['id'])->name ?? 'Produk') . ' x ' . $item['jumlah']
                )->implode(', '),
            ]);

            // KURANGI STOK PRODUK
            foreach ($products as $item) {
                Product::find($item['id'])->decrement('stock', $item['jumlah']);
            }

            DB::commit();

            return redirect()->route('pembelian.struk', $pembelian->id);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pembelian.create')
                ->with('error', $e->getMessage());
        }
    }


}
