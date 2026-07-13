<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class AgendaSuratController extends Controller
{
    /**
     * Daftar direktori & jenis surat yang dikumpulkan.
     */
    private array $sources = [
        'nota'        => ['dir' => 'nota-pesanan',   'label' => 'Nota Pesanan',    'icon' => 'fa-file-invoice',       'color' => 'blue'],
        'pemeriksaan' => ['dir' => 'bap-pemeriksaan','label' => 'BAP Pemeriksaan', 'icon' => 'fa-clipboard-check',    'color' => 'amber'],
        'penerimaan'  => ['dir' => 'bap-penerimaan', 'label' => 'BASTB',           'icon' => 'fa-handshake',          'color' => 'indigo'],
        'kwitansi'    => ['dir' => 'kwitansi',       'label' => 'Kwitansi',        'icon' => 'fa-receipt',            'color' => 'emerald'],
    ];

    /**
     * Ambil semua surat dari semua sumber dan kembalikan sebagai array flat.
     */
    private function collectAll(): array
    {
        $disk   = Storage::disk('local');
        $userId = Auth::id();
        $all    = [];

        foreach ($this->sources as $type => $meta) {
            $dir   = "users/{$userId}/{$meta['dir']}";
            $files = $disk->exists($dir) ? $disk->files($dir) : [];

            foreach ($files as $file) {
                if (! str_ends_with($file, '.json')) continue;

                $data = json_decode($disk->get($file), true) ?: [];

                // Normalisasi field nomor & total berdasarkan tipe
                $nomor  = $this->resolveNomor($type, $data);
                $total  = $this->resolveTotal($type, $data);
                $uraian = $this->resolveUraian($type, $data);

                if (! $nomor) continue;

                $all[] = [
                    'id'      => basename($file, '.json'),
                    'type'    => $type,
                    'label'   => $meta['label'],
                    'icon'    => $meta['icon'],
                    'color'   => $meta['color'],
                    'nomor'   => $nomor,
                    'tanggal' => $data['tanggal'] ?? '',
                    'uraian'  => $uraian,
                    'total'   => $total,
                    'route_show' => $this->resolveRoute($type, basename($file, '.json')),
                ];
            }
        }

        return $all;
    }

    private function resolveNomor(string $type, array $data): string
    {
        return match ($type) {
            'kwitansi' => $data['nomor_kwt'] ?? ($data['nomor'] ?? ''),
            default    => $data['nomor'] ?? '',
        };
    }

    private function resolveTotal(string $type, array $data): int
    {
        return match ($type) {
            'nota'    => (int)($data['grand_total'] ?? $data['total'] ?? 0),
            default   => (int)($data['total'] ?? 0),
        };
    }

    private function resolveUraian(string $type, array $data): string
    {
        return match ($type) {
            'nota'        => 'Belanja ' . ($data['belanja'] ?? ''),
            'pemeriksaan' => 'BAP ' . ($data['nota']['belanja'] ?? ($data['belanja'] ?? '')),
            'penerimaan'  => 'BASTB – Ref: ' . ($data['pemeriksaan_nomor'] ?? ''),
            'kwitansi'    => $data['uraian'] ?? ($data['perihal'] ?? 'Kwitansi Pembayaran'),
            default       => '',
        };
    }

    private function resolveRoute(string $type, string $id): string
    {
        return match ($type) {
            'nota'        => route('reports.nota.show', $id),
            'pemeriksaan' => route('reports.pemeriksaan.show', $id),
            'penerimaan'  => route('reports.penerimaan.show', $id),
            'kwitansi'    => route('reports.kwitansi.show', $id),
            default       => '#',
        };
    }

    /**
     * Tampilkan daftar agenda surat.
     */
    public function index(Request $request): View
    {
        $all    = $this->collectAll();
        $search = $request->input('search', '');
        $filter = $request->input('type', '');

        // Filter
        if ($search) {
            $kw = strtolower($search);
            $all = array_filter($all, fn($r) =>
                str_contains(strtolower($r['nomor']), $kw) ||
                str_contains(strtolower($r['uraian']), $kw)
            );
        }
        if ($filter && isset($this->sources[$filter])) {
            $all = array_filter($all, fn($r) => $r['type'] === $filter);
        }

        $all = array_values($all);

        // Urut: tanggal DESC, lalu nomor DESC
        usort($all, function ($a, $b) {
            $tCmp = strcmp($b['tanggal'], $a['tanggal']);
            return $tCmp !== 0 ? $tCmp : strnatcmp($b['nomor'], $a['nomor']);
        });

        // Statistik
        $totalDokumen = count($all);
        $totalNominal = array_sum(array_column($all, 'total'));

        // Pagination
        $page    = (int) $request->input('page', 1);
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;
        $items   = new LengthAwarePaginator(
            array_slice($all, $offset, $perPage),
            $totalDokumen,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $sources = $this->sources;

        return view('agenda_surat.index', compact(
            'items', 'sources', 'search', 'filter',
            'totalDokumen', 'totalNominal'
        ));
    }
}
