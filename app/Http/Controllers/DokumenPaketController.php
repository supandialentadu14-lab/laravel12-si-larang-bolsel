<?php

namespace App\Http\Controllers;

use App\Models\OpdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenPaketController extends Controller
{
    public function show(string $notaId, Request $request)
    {
        $disk = Storage::disk('local');

        $nota = $this->loadById($disk, 'nota-pesanan', $notaId);
        if (!$nota) {
            abort(404);
        }

        $notaNomor = (string)($nota['nomor'] ?? '');

        $pemeriksaan = $this->findLatestWhere($disk, 'bap-pemeriksaan', function (array $doc) use ($notaNomor) {
            return (string)($doc['nota']['nomor'] ?? '') === $notaNomor;
        });

        $penerimaan = $this->findLatestWhere($disk, 'bap-penerimaan', function (array $doc) use ($notaNomor) {
            return (string)($doc['nota']['nomor'] ?? '') === $notaNomor;
        });

        $kwitansi = null;
        if ($penerimaan && isset($penerimaan['data']['nomor'])) {
            $penerimaanNomor = (string)$penerimaan['data']['nomor'];
            $kwitansi = $this->findLatestWhere($disk, 'kwitansi', function (array $doc) use ($penerimaanNomor) {
                return (string)($doc['penerimaan_nomor'] ?? '') === $penerimaanNomor;
            });
        }

        $opd = OpdSetting::where('user_id', Auth::id())->first();

        $view = $request->isMobile() ? 'reports.mobile.dokumen_paket_report' : 'reports.dokumen_paket_report';

        return view($view, [
            'notaId' => $notaId,
            'nota' => $nota,
            'pemeriksaan' => $pemeriksaan['data'] ?? null,
            'penerimaan' => $penerimaan['data'] ?? null,
            'kwitansi' => $kwitansi['data'] ?? null,
            'opd' => $opd,
        ]);
    }

    private function loadById($disk, string $folder, string $id): ?array
    {
        $path = "users/" . Auth::id() . "/{$folder}/{$id}.json";
        if (!$disk->exists($path)) {
            return null;
        }
        $raw = $disk->get($path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    private function findLatestWhere($disk, string $folder, callable $predicate): ?array
    {
        $dir = "users/" . Auth::id() . "/{$folder}";
        if (!$disk->exists($dir)) {
            return null;
        }

        $files = $disk->files($dir);
        $candidates = [];

        foreach ($files as $file) {
            if (!str_ends_with($file, '.json')) {
                continue;
            }
            $raw = $disk->get($file);
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                continue;
            }
            if (!$predicate($data)) {
                continue;
            }
            $candidates[] = [
                'file' => $file,
                'updated' => $disk->lastModified($file),
                'data' => $data,
            ];
        }

        if (!$candidates) {
            return null;
        }

        usort($candidates, function ($a, $b) {
            return ($b['updated'] ?? 0) <=> ($a['updated'] ?? 0);
        });

        $picked = $candidates[0];
        $id = basename($picked['file'], '.json');

        return [
            'id' => $id,
            'updated' => $picked['updated'],
            'data' => $picked['data'],
        ];
    }
}

