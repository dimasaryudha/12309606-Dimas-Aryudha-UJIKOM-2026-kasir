<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::all();
        $totalProduk = 0;
        $produkList = [];

        foreach ($pembelian as $p) {
            $items = explode(', ', $p->detail_produk);

            foreach ($items as $item) {
                $nama = trim(substr($item, 0, strrpos($item, 'x')));
                $jumlah = (int) substr(strrchr($item, 'x'), 1);

                $totalProduk += $jumlah;

                if (!isset($produkList[$nama])) {
                    $produkList[$nama] = 0;
                }

                $produkList[$nama] += $jumlah;
            }
        }

        $totalMember = Pembelian::where('status_member', 'member')->count();
        $totalNonMember = Pembelian::where('status_member', 'non_member')->count();
        $dataBulanan = Pembelian::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $dataBulanan[$i] ?? 0;
        }

        $dataHarian = Pembelian::whereDate('tanggal', now()->toDateString())
        ->orderBy('tanggal', 'desc')
        ->get();

        $dataPerHari = [
            'Senin' => 0,
            'Selasa' => 0,
            'Rabu' => 0,
            'Kamis' => 0,
            'Jumat' => 0,
            'Sabtu' => 0,
            'Minggu' => 0,
        ];

        foreach ($pembelian as $p) {

            $tanggal = strtotime($p->tanggal);
            $hariAngka = date('N', $tanggal);

            $namaHari = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            $items = explode(', ', $p->detail_produk);

            foreach ($items as $item) {
                $jumlah = (int) substr(strrchr($item, 'x'), 1);
                $dataPerHari[$namaHari[$hariAngka]] += $jumlah;
            }
        }

        return view('dashboard', compact(
            'totalProduk',
            'totalMember',
            'totalNonMember',
            'chartData',
            'produkList',
            'dataHarian',
            'dataPerHari'
        ));
    }
}
