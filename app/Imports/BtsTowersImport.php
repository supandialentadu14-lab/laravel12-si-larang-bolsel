<?php

namespace App\Imports;

use App\Models\BtsTower;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BtsTowersImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2; // skip header row
    }

    public function collection(Collection $rows): void
    {
        $userId = Auth()->id() ?? 1;

        foreach ($rows as $row) {
            $nama_bts = trim($row[0] ?? '');
            $provider = trim($row[1] ?? '');
            $kecamatan = trim($row[2] ?? '');
            $desa = trim($row[3] ?? '');
            $alamat = trim($row[4] ?? '');
            $latitude = (float) ($row[5] ?? 0);
            $longitude = (float) ($row[6] ?? 0);
            $tinggi_tower = is_numeric($row[7] ?? null) ? (float) $row[7] : null;
            $tipe_tower = trim($row[8] ?? '');
            $kondisi = trim($row[9] ?? '');
            $status_operasional = trim($row[10] ?? '');
            $tahun_dibangun = is_numeric($row[11] ?? null) ? (int) $row[11] : null;
            $keterangan = trim($row[12] ?? '');

            if (!$nama_bts || !$provider || !$kecamatan) continue;

            // Validate provider
            if (!in_array($provider, BtsTower::$providerList)) {
                $provider = 'Lainnya';
            }
            // Validate kecamatan
            if (!in_array($kecamatan, BtsTower::$kecamatanList)) {
                continue;
            }

            // Auto-generate kode
            $year = now()->year;
            $count = BtsTower::whereYear('created_at', $year)->count() + 1;
            do {
                $kode = 'BTS-BOLSEL-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                $exists = BtsTower::where('kode_bts', $kode)->exists();
                $count++;
            } while ($exists);

            BtsTower::create([
                'kode_bts' => $kode,
                'nama_bts' => $nama_bts,
                'provider' => $provider,
                'kecamatan' => $kecamatan,
                'desa' => $desa ?: null,
                'alamat' => $alamat ?: null,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tinggi_tower' => $tinggi_tower,
                'tipe_tower' => $tipe_tower ?: null,
                'kondisi' => $kondisi ?: null,
                'status_operasional' => $status_operasional ?: null,
                'tahun_dibangun' => $tahun_dibangun,
                'keterangan' => $keterangan ?: null,
                'user_id' => $userId,
            ]);
        }
    }
}
