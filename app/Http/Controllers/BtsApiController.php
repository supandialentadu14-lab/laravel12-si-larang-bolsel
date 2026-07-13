<?php

namespace App\Http\Controllers;

use App\Models\BtsTower;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BtsApiController extends Controller
{
    public function index(): JsonResponse
    {
        $towers = BtsTower::select(
            'id', 'kode_bts', 'nama_bts', 'provider', 'kecamatan', 'desa',
            'latitude', 'longitude', 'tinggi_tower', 'tipe_tower',
            'kondisi', 'status_operasional', 'coverage_radius'
        )
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'count' => $towers->count(),
            'data' => $towers,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $tower = BtsTower::with('notes')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tower,
        ]);
    }

    public function stats(): JsonResponse
    {
        $total = BtsTower::count();
        $byStatus = BtsTower::select('status_operasional', DB::raw('count(*) as total'))
            ->groupBy('status_operasional')->get();
        $byProvider = BtsTower::select('provider', DB::raw('count(*) as total'))
            ->groupBy('provider')->orderByDesc('total')->get();
        $byKecamatan = BtsTower::select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')->orderByDesc('total')->get();

        return response()->json([
            'success' => true,
            'total' => $total,
            'by_status' => $byStatus,
            'by_provider' => $byProvider,
            'by_kecamatan' => $byKecamatan,
        ]);
    }
}
