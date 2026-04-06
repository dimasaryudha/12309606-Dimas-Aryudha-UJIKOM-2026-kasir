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
    public function index(Request $request)
    {
        $query = Pembelian::query();

        // 🔥 FILTER HARI
        if ($request->filled('hari')) {

            $hariMap = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
                'Sabtu' => 6,
                'Minggu' => 7,
            ];

            $hari = $hariMap[$request->hari] ?? null;

            if ($hari) {
                // MySQL: 1 = Minggu, jadi +1
                $query->whereRaw('DAYOFWEEK(tanggal) = ?', [$hari + 1]);
            }
        }

        // 🔥 FILTER 1 HARI SPESIFIK
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // 🔥 FILTER RANGE TANGGAL (from - to)
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }

        // 🔥 kalau hanya from
        if ($request->filled('from') && !$request->filled('to')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        // 🔥 kalau hanya to
        if ($request->filled('to') && !$request->filled('from')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $data = $query->latest()->get();

        // 🔥 decode detail produk
        foreach ($data as $d) {
            $d->items_detail = json_decode($d->detail_produk, true) ?? [];
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
                $poinDipakai = (int) ($request->poin ?? 0);

                if ($poinDipakai > $total) {
                    $poinDipakai = $total;
                }

                $totalFinal = $total - $poinDipakai;
                $poinBaru = floor($total * 0.01);
                $totalPoin = $poinBaru;
            } else {
                $totalFinal = $total;
                $poinDipakai = 0;
                $poinBaru = 0;
                $totalPoin = 0;
            }

            if ($totalFinal > 10000000) {
                return back()
                    ->withInput()
                    ->with('error', 'Total bayar tidak boleh lebih dari Rp 10.000.000');
            }

            $bayar = str_replace('.', '', $request->bayar);

            if (strlen($bayar) > 11) {
                return back()
                    ->withInput()
                    ->with('error', 'Total bayar tidak boleh lebih dari 11 digit');
            }

            if ($bayar < $totalFinal) {
                return back()
                    ->withInput()
                    ->with('error', 'Uang bayar kurang');
            }

            $pembelian = Pembelian::create([
                'name' => $status == 'member' ? $request->nama : null,
                'tanggal' => now(),
                'price' => $totalFinal,
                'bayar' => $bayar,
                'kembalian' => $bayar - $totalFinal,
                'status_member' => $status,
                'no_hp' => $status == 'member' ? $request->no_hp : null,
                'poin' => $totalPoin,
                'poin_dipakai' => $poinDipakai,
                'poin_didapat' => $poinBaru,
                'detail_produk' => $products->map(fn($item) =>
                    (Product::find($item['id'])->name ?? 'Produk') . ' x ' . $item['jumlah']
                )->implode(', '),
            ]);

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

    public function dataPembelian(Request $request)
    {
        $products = collect($request->products)->filter(fn($item) => isset($item['jumlah']) && $item['jumlah'] > 0);

        if ($products->isEmpty()) {
            return back()->with('error', 'Pilih minimal 1 produk');
        }

        $result = [];
        $total = 0;

        foreach ($products as $item) {
            $product = Product::findOrFail($item['id']);
            $subtotal = $item['jumlah'] * $product->price;
            $total += $subtotal;

            $result[] = [
                'id' => $product->id,
                'nama' => $product->name,
                'harga' => $product->price,
                'jumlah' => $item['jumlah'],
                'subtotal' => $subtotal
            ];
        }

        $members = Pembelian::whereNotNull('no_hp')->select('name','no_hp')->distinct()->get();

        return view('pembelian.dataPembelian', compact('result', 'total', 'members'));
    }

    public function struk($id)
    {
        $data = Pembelian::findOrFail($id);
        $items = json_decode($data->detail_produk, true) ?? [];
        $data->items_detail = $items;

        return view('pembelian.struk', compact('data'));
    }

    public function downloadPdf($id)
    {
        $data = Pembelian::findOrFail($id);
        $items = explode(', ', $data->detail_produk);
        $data->items_detail = $items;

        $pdf = Pdf::loadView('pembelian.struk_pdf', compact('data'));

        return $pdf->download('struk-pembelian-'.$data->id.'.pdf');
    }

    public function export()
    {
        return Excel::download(new PembelianExport, 'data_pembelian.xlsx');
    }

}
