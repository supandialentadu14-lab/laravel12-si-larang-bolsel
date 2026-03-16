<?php

namespace App\Http\Controllers;

use App\Models\BapItem;
use App\Models\BapPemeriksaan;
use App\Models\NotaMaster;
use App\Models\OpdSetting;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class PemeriksaanController extends Controller
{
    protected function loadNotaMaster(): array
    {
        $row = NotaMaster::where('user_id', Auth::id())->first();
        if ($row) {
            return [
                'opd' => ['nama' => $row->opd_nama ?? '', 'alamat' => $row->opd_alamat ?? ''],
                'ppk' => ['nama' => $row->ppk_nama ?? '', 'nip' => $row->ppk_nip ?? '', 'alamat' => $row->ppk_alamat ?? ''],
                'penyedia' => ['toko' => $row->penyedia_toko ?? '', 'pemilik' => $row->penyedia_pemilik ?? '', 'alamat' => $row->penyedia_alamat ?? ''],
                'pejabat' => ['nama' => $row->pejabat_nama ?? '', 'nip' => $row->pejabat_nip ?? ''],
                'pptk' => ['nama' => $row->pptk_nama ?? '', 'nip' => $row->pptk_nip ?? ''],
                'pengurus_barang' => ['nama' => $row->pengurus_barang_nama ?? '', 'nip' => $row->pengurus_barang_nip ?? ''],
                'pengurus_pengguna' => ['nama' => $row->pengurus_pengguna_nama ?? '', 'nip' => $row->pengurus_pengguna_nip ?? ''],
                'bendahara' => ['nama' => $row->bendahara_nama ?? '', 'nip' => $row->bendahara_nip ?? ''],
            ];
        }
        return [
            'opd' => ['nama' => '', 'alamat' => ''],
            'ppk' => ['nama' => '', 'nip' => '', 'alamat' => ''],
            'penyedia' => ['toko' => '', 'pemilik' => '', 'alamat' => ''],
            'pejabat' => ['nama' => '', 'nip' => ''],
            'pptk' => ['nama' => '', 'nip' => ''],
            'pengurus_barang' => ['nama' => '', 'nip' => ''],
            'pengurus_pengguna' => ['nama' => '', 'nip' => ''],
            'bendahara' => ['nama' => '', 'nip' => ''],
        ];
    }

    protected function listNotaDocs(): array
    {
        $disk = Storage::disk('local');
        $dir = 'users/'.Auth::id().'/nota-pesanan';
        $files = $disk->exists($dir) ? $disk->files($dir) : [];
        $items = [];
        foreach ($files as $file) {
            if (! str_ends_with($file, '.json')) continue;
            $data = json_decode($disk->get($file), true) ?: [];
            $items[] = [
                'id' => basename($file, '.json'),
                'nomor' => $data['nomor'] ?? '',
                'tanggal' => $data['tanggal'] ?? '',
                'belanja' => $data['belanja'] ?? '',
                'kegiatan' => $data['kegiatan'] ?? '',
                'sub_kegiatan' => $data['sub_kegiatan'] ?? '',
                'penyedia' => $data['penyedia'] ?? [],
                'items' => $data['items'] ?? [],
            ];
        }
        usort($items, fn($a, $b) => ($b['tanggal'] ?? '') <=> ($a['tanggal'] ?? ''));
        return $items;
    }

    protected function listPemeriksaanDocs(): array
    {
        $disk = Storage::disk('local');
        $dir = 'users/'.Auth::id().'/bap-pemeriksaan';
        $files = $disk->exists($dir) ? $disk->files($dir) : [];
        $items = [];
        foreach ($files as $file) {
            if (! str_ends_with($file, '.json')) continue;
            $data = json_decode($disk->get($file), true) ?: [];
            $items[] = [
                'id' => basename($file, '.json'),
                'nomor' => $data['nomor'] ?? '',
                'tanggal' => $data['tanggal'] ?? '',
                'nota_nomor' => $data['nota']['nomor'] ?? '',
                'items' => $data['items'] ?? [],
            ];
        }
        usort($items, fn($a, $b) => ($b['tanggal'] ?? '') <=> ($a['tanggal'] ?? ''));
        return $items;
    }

    protected function findNotaByNomor(?string $nomor): ?array
    {
        if (!$nomor) return null;
        foreach ($this->listNotaDocs() as $doc) {
            if (trim($doc['nomor'] ?? '') === trim($nomor)) return $doc;
        }
        return null;
    }

    public function form(Request $request): View
    {
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $notaDocs = $this->listNotaDocs();
        
        // Cek nomor terakhir untuk auto-increment
        $docs = $this->listPemeriksaanDocs();
        $nextNum = 1;
        if (!empty($docs)) {
            // listPemeriksaanDocs sudah diurutkan descending berdasarkan tanggal
            // Kita coba cari angka terbesar dari nomor-nomor yang ada
            foreach ($docs as $doc) {
                // Ambil bagian angka di awal (misal 001/BAPB/... -> 001)
                if (preg_match('/^(\d+)/', (string)($doc['nomor'] ?? ''), $m)) {
                    $num = (int)$m[1];
                    if ($num >= $nextNum) {
                        $nextNum = $num + 1;
                    }
                }
            }
        }
        $nextNomorRaw = str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $data = session('bap_current') ?? [
            'tanggal' => now()->toDateString(),
            'tempat' => 'Bolaang Uki',
            'nomor' => $nextNomorRaw,
            'nota_nomor' => '',
        ];
        return view('pemeriksaan.create', compact('data', 'opd', 'notaDocs'));
    }

    protected function toWordsId(int $value): string
    {
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($value < 12) return $huruf[$value];
        if ($value < 20) return $this->toWordsId($value - 10) . " belas";
        if ($value < 100) return $this->toWordsId(intval($value / 10)) . " puluh " . $this->toWordsId($value % 10);
        if ($value < 200) return "seratus " . $this->toWordsId($value - 100);
        if ($value < 1000) return $this->toWordsId(intval($value / 100)) . " ratus " . $this->toWordsId($value % 100);
        if ($value < 2000) return "seribu " . $this->toWordsId($value - 1000);
        if ($value < 1000000) return $this->toWordsId(intval($value / 1000)) . " ribu " . $this->toWordsId($value % 1000);
        if ($value < 1000000000) return $this->toWordsId(intval($value / 1000000)) . " juta " . $this->toWordsId($value % 1000000);
        return (string) $value;
    }

    protected function formatRomawi(int $number): string
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function report(Request $request): View
    {
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();
        $payload = $request->all();
        $nota = null;
        if ($nid = $request->input('nota_id')) {
            $nid = trim($nid);
            foreach ($this->listNotaDocs() as $doc) {
                if ($doc['id'] === $nid) { $nota = $doc; break; }
            }
        }
        if (!$nota) {
            $nota = $this->findNotaByNomor($request->input('nota_nomor'));
        }
        $cleanItems = [];
        foreach (($nota['items'] ?? []) as $it) {
            $name = $it['name'] ?? '';
            $qty = (int)($it['qty'] ?? 0);
            $unit = $it['unit'] ?? '';
            $price = (int)($it['price'] ?? 0);
            $total = $qty * $price;
            $cleanItems[] = [
                'nama' => $name,
                'kuantitas' => $qty,
                'satuan' => $unit,
                'harga' => $price,
                'jumlah' => $total,
            ];
        }
        $totalSum = 0;
        foreach ($cleanItems as $row) { $totalSum += (int)($row['jumlah'] ?? 0); }
        $dt = \Carbon\Carbon::parse($payload['tanggal'] ?? now()->toDateString())->locale('id');
        $hari = $dt->translatedFormat('l');
        $bulan = $dt->translatedFormat('F');
        $tanggalKata = ucwords($this->toWordsId((int) $dt->format('d')));
        $tahunKata = ucwords($this->toWordsId((int) $dt->format('Y')));
        
        $tanggalObj = \Carbon\Carbon::parse($payload['tanggal'] ?? now()->toDateString());
        $tahunAnggaran = $tanggalObj->year;
        $inputNomor = trim((string)($payload['nomor'] ?? ''));
        if (!preg_match('/^\d+$/', $inputNomor)) {
            return back()->withErrors(['nomor' => 'Nomor hanya boleh angka'])->withInput();
        }
        $bulanRomawi = $this->formatRomawi($tanggalObj->month);
        $singkatanOpd = strtoupper($opd->singkatan_opd ?? 'DISKOMINFO');
        $nomorFormatted = "{$inputNomor}/BAPB/{$singkatanOpd}/{$bulanRomawi}/{$tahunAnggaran}";

        $data = [
            'nomor' => $nomorFormatted,
            'nomor_raw' => $inputNomor,
            'tanggal' => $payload['tanggal'] ?? now()->toDateString(),
            'tempat' => $payload['tempat'] ?? 'Bolaang Uki',
            'nota' => [
                'id' => $nota['id'] ?? null,
                'nomor' => $nota['nomor'] ?? '',
                'tanggal' => $nota['tanggal'] ?? '',
                'belanja' => $nota['belanja'] ?? '',
                'kegiatan' => $nota['kegiatan'] ?? '',
                'sub_kegiatan' => $nota['sub_kegiatan'] ?? '',
                'penyedia' => [
                    'toko' => $nota['penyedia']['toko'] ?? '',
                    'pemilik' => $nota['penyedia']['pemilik'] ?? '',
                    'alamat' => (function() use ($nota) {
                        $addr = trim($nota['penyedia']['alamat'] ?? '');
                        if ($addr !== '') return $addr;
                        
                        $sid = $nota['supplier_id'] ?? null;
                        if ($sid) {
                            return Supplier::find($sid)->address ?? '';
                        }
                        
                        return Supplier::where('name', $nota['penyedia']['toko'] ?? '')->first()->address ?? '';
                    })(),
                ],
            ],
            'items' => $cleanItems,
            'terbilang' => ucwords($this->toWordsId((int) $totalSum)),
            'total' => $totalSum,
            'ppk' => [
                'nama' => (trim($master['ppk']['nama'] ?? '') ?: ($opd->kepala_nama ?? '')),
                'nip' => (trim($master['ppk']['nip'] ?? '') ?: ($opd->kepala_nip ?? '')),
                'jabatan' => 'Pejabat Pembuat Komitmen',
                'alamat' => (trim($master['ppk']['alamat'] ?? '') ?: ''), 
            ],
            'tanggal_kata' => "Pada hari {$hari} Tanggal {$tanggalKata} Bulan {$bulan} Tahun {$tahunKata}",
        ];
        session(['bap_current' => $data]);
        return view('reports.pemeriksaan_report', compact('data', 'opd'));
    }

    public function save(Request $request): RedirectResponse
    {
        $currentId = session('bap_current_id') ?? $request->input('id');
        $id = $currentId ?: (string) Str::uuid();
        
        // Prefer request data (from form) if 'nomor' is present, otherwise fallback to session (from preview)
        $data = $request->all();
        if ((!isset($data['nomor']) || $data['nomor'] === '') && session('bap_current')) {
            $data = session('bap_current');
        }
        
        $rawNomor = trim((string)($data['nomor_raw'] ?? $data['nomor'] ?? ''));
        if (!preg_match('/^\d+$/', $rawNomor)) {
            return back()->withErrors(['nomor' => 'Nomor hanya boleh angka'])->withInput();
        }
        
        if (!isset($data['nota']) || !isset($data['items'])) {
            $master = $this->loadNotaMaster();
            $opd = OpdSetting::where('user_id', Auth::id())->first();
            $nota = null;
            if ($nid = $request->input('nota_id')) {
                $nid = trim($nid);
                foreach ($this->listNotaDocs() as $doc) {
                    if ($doc['id'] === $nid) { $nota = $doc; break; }
                }
            }
            if (!$nota) {
                $nota = $this->findNotaByNomor($request->input('nota_nomor'));
            }
            if (!$nota) {
                return back()->withErrors(['nota_nomor' => 'Nota pesanan tidak ditemukan'])->withInput();
            }
            $cleanItems = [];
            foreach (($nota['items'] ?? []) as $it) {
                $name = $it['name'] ?? '';
                $qty = (int)($it['qty'] ?? 0);
                $unit = $it['unit'] ?? '';
                $price = (int)($it['price'] ?? 0);
                $total = $qty * $price;
                $cleanItems[] = [
                    'nama' => $name,
                    'kuantitas' => $qty,
                    'satuan' => $unit,
                    'harga' => $price,
                    'jumlah' => $total,
                ];
            }
            $totalSum = 0;
            foreach ($cleanItems as $row) { $totalSum += (int)($row['jumlah'] ?? 0); }
            $dt = \Carbon\Carbon::parse($data['tanggal'] ?? now()->toDateString())->locale('id');
            $hari = $dt->translatedFormat('l');
            $bulan = $dt->translatedFormat('F');
            $tanggalKata = ucwords($this->toWordsId((int) $dt->format('d')));
            $tahunKata = ucwords($this->toWordsId((int) $dt->format('Y')));
            $data = [
                'nomor' => $data['nomor'] ?? '',
                'nomor_raw' => $rawNomor,
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
                'tempat' => $data['tempat'] ?? 'Bolaang Uki',
                'nota' => [
                    'id' => $nota['id'] ?? null,
                    'nomor' => $nota['nomor'] ?? '',
                    'tanggal' => $nota['tanggal'] ?? '',
                    'belanja' => $nota['belanja'] ?? '',
                    'kegiatan' => $nota['kegiatan'] ?? '',
                    'sub_kegiatan' => $nota['sub_kegiatan'] ?? '',
                    'penyedia' => $nota['penyedia'] ?? [],
                ],
                'items' => $cleanItems,
                'terbilang' => ucwords($this->toWordsId((int) $totalSum)),
                'total' => $totalSum,
                'ppk' => [
                    'nama' => ($master['ppk']['nama'] ?? '') ?: ($opd->kepala_nama ?? ''),
                    'nip' => ($master['ppk']['nip'] ?? '') ?: ($opd->kepala_nip ?? ''),
                    'jabatan' => 'Pejabat Pembuat Komitmen',
                    'alamat' => ($master['ppk']['alamat'] ?? '') ?: (($master['opd']['alamat'] ?? '') ?: ($opd->alamat_opd ?? '')),
                ],
                'tanggal_kata' => "Pada hari {$hari} Tanggal {$tanggalKata} Bulan {$bulan} Tahun {$tahunKata}",
            ];
        }
        $tanggalObj = \Carbon\Carbon::parse($data['tanggal'] ?? now()->toDateString());
        $bulanRomawi = $this->formatRomawi($tanggalObj->month);
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $singkatanOpd = strtoupper($opd->singkatan_opd ?? 'DISKOMINFO');
        $nomorFormatted = "{$rawNomor}/BAPB/{$singkatanOpd}/{$bulanRomawi}/{$tanggalObj->year}";
        foreach ($this->listPemeriksaanDocs() as $doc) {
            if (($doc['nomor'] ?? '') === $nomorFormatted && ($doc['id'] ?? '') !== $id) {
                return back()->withErrors(['nomor' => 'Nomor BAP sudah digunakan'])->withInput();
            }
        }
        $data['nomor'] = $nomorFormatted;
        $data['nomor_raw'] = $rawNomor;
        $data['tempat'] = $data['tempat'] ?? 'Bolaang Uki';
        session(['bap_current' => $data, 'bap_current_id' => $id]);
        Storage::disk('local')->put("users/".Auth::id()."/bap-pemeriksaan/{$id}.json", json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        session()->forget('bap_current_id');
        $bap = BapPemeriksaan::updateOrCreate(
            ['user_id' => Auth::id(), 'nomor' => $data['nomor'] ?? ''],
            [
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
                'tempat' => $data['tempat'] ?? '',
                'nota_nomor' => $data['nota']['nomor'] ?? '',
                'nota_tanggal' => $data['nota']['tanggal'] ?? null,
                'belanja' => $data['nota']['belanja'] ?? '',
                'penyedia_toko' => $data['nota']['penyedia']['toko'] ?? '',
                'penyedia_alamat' => $data['nota']['penyedia']['alamat'] ?? '',
                'ppk_nama' => $data['ppk']['nama'] ?? '',
                'ppk_nip' => $data['ppk']['nip'] ?? '',
                'ppk_alamat' => $data['ppk']['alamat'] ?? '',
                'total' => (int)($data['total'] ?? 0),
                'terbilang' => $data['terbilang'] ?? '',
            ]
        );
        if (!empty($data['items']) && $bap) {
            $bap->items()->delete();
            foreach ($data['items'] as $row) {
                BapItem::create([
                    'bap_id' => $bap->id,
                    'nama' => $row['nama'] ?? '',
                    'kuantitas' => (int)($row['kuantitas'] ?? 0),
                    'satuan' => $row['satuan'] ?? '',
                    'harga' => (int)($row['harga'] ?? 0),
                    'jumlah' => (int)($row['jumlah'] ?? 0),
                ]);
            }
        }
        if ($currentId) {
            return redirect()->route('reports.pemeriksaan.list')->with('success', 'Berita acara "' . $data['nomor'] . '" berhasil diperbarui');
        }
        return redirect()->route('reports.pemeriksaan.list')->with('success', 'Berita acara "' . $data['nomor'] . '" berhasil disimpan');
    }

    public function list(Request $request): View
    {
        $disk = Storage::disk('local');
        $dir = 'users/'.Auth::id().'/bap-pemeriksaan';
        $files = $disk->exists($dir) ? $disk->files($dir) : [];
        $items = [];
        $search = $request->input('search');
        foreach ($files as $file) {
            if (! str_ends_with($file, '.json')) continue;
            $data = json_decode($disk->get($file), true) ?: [];

            if ($search) {
                $searchLower = strtolower($search);
                $nomor = strtolower($data['nomor'] ?? '');
                $notaNomor = strtolower($data['nota']['nomor'] ?? '');
                if (!str_contains($nomor, $searchLower) && !str_contains($notaNomor, $searchLower)) {
                    continue;
                }
            }

            $total = 0;
            foreach (($data['items'] ?? []) as $row) {
                $total += (int)($row['jumlah'] ?? 0);
            }
            $items[] = [
                'id' => basename($file, '.json'),
                'updated' => $disk->lastModified($file),
                'nomor' => $data['nomor'] ?? '',
                'tanggal' => $data['tanggal'] ?? '',
                'nota_nomor' => $data['nota']['nomor'] ?? '',
                'total' => $total,
                'raw_data' => $data,
            ];
        }
        usort($items, fn($a, $b) => $b['updated'] <=> $a['updated']);

        $page = $request->input('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($items, $offset, $perPage);
        $items = new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($items),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $notaDocs = $this->listNotaDocs();

        return view('pemeriksaan.index', compact('items', 'opd', 'notaDocs'));
    }

    public function show(string $id): View
    {
        $path = "users/".Auth::id()."/bap-pemeriksaan/{$id}.json";
        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }
        $json = Storage::disk('local')->get($path);
        $data = json_decode($json, true) ?: [];
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();
        $data['ppk'] = $data['ppk'] ?? [];
        
        // Apply fallbacks for viewing 
        $data['ppk']['nama'] = (trim($data['ppk']['nama'] ?? '') ?: (trim($master['ppk']['nama'] ?? '') ?: ($opd->kepala_nama ?? '')));
        $data['ppk']['nip'] = (trim($data['ppk']['nip'] ?? '') ?: (trim($master['ppk']['nip'] ?? '') ?: ($opd->kepala_nip ?? '')));
        $data['ppk']['alamat'] = (trim($data['ppk']['alamat'] ?? '') ?: trim($master['ppk']['alamat'] ?? ''));
        
        // Supplier Address Fallback
        if (empty($data['nota']['penyedia']['alamat']) && !empty($data['nota']['penyedia']['toko'])) {
            $data['nota']['penyedia']['alamat'] = (Supplier::where('name', $data['nota']['penyedia']['toko'])->first()->address ?? '');
        }

        session(['bap_current' => $data, 'bap_current_id' => $id]);

        $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', request()->header('User-Agent'));
        $view = $isMobile ? 'reports.mobile.pemeriksaan_report' : 'reports.pemeriksaan_report';

        return view($view, [
            'data' => $data,
            'opd' => $opd,
            'saved_id' => $id,
        ]);
    }

    public function edit(string $id): View
    {
        $path = "users/".Auth::id()."/bap-pemeriksaan/{$id}.json";
        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }
        $json = Storage::disk('local')->get($path);
        $data = json_decode($json, true) ?: [];
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();

        // Apply fallbacks for editing if fields are blank
        if (empty($data['ppk']['alamat'])) {
            $data['ppk']['alamat'] = trim($master['ppk']['alamat'] ?? '');
        }
        if (empty($data['ppk']['nama'])) {
            $data['ppk']['nama'] = (trim($master['ppk']['nama'] ?? '') ?: ($opd->kepala_nama ?? ''));
        }
        if (empty($data['ppk']['nip'])) {
            $data['ppk']['nip'] = (trim($master['ppk']['nip'] ?? '') ?: ($opd->kepala_nip ?? ''));
        }
        
        // Supplier Address Fallback
        if (empty($data['nota']['penyedia']['alamat']) && !empty($data['nota']['penyedia']['toko'])) {
            $sid = $data['nota']['supplier_id'] ?? null;
            if ($sid) {
                $data['nota']['penyedia']['alamat'] = (Supplier::find($sid)->address ?? '');
            } else {
                $data['nota']['penyedia']['alamat'] = (Supplier::where('name', $data['nota']['penyedia']['toko'])->first()->address ?? '');
            }
        }

        session([
            'bap_current' => $data,
            'bap_current_id' => $id,
        ]);
        $notaDocs = $this->listNotaDocs();
        return view('pemeriksaan.edit', compact('data', 'opd', 'notaDocs', 'id'));
    }

    public function delete(string $id): RedirectResponse
    {
        $disk = Storage::disk('local');
        $userId = Auth::id();
        $path = "users/{$userId}/bap-pemeriksaan/{$id}.json";
        if ($disk->exists($path)) {
            $json = $disk->get($path);
            $data = json_decode($json, true) ?: [];
            $nomor = $data['nomor'] ?? null;
            $disk->delete($path);
            
            if ($nomor) {
                // Find and delete related Penerimaan and Kwitansi
                $penerimaanNomors = [];
                $penerimaanDir = "users/{$userId}/bap-penerimaan";
                $penerimaanFiles = $disk->exists($penerimaanDir) ? $disk->files($penerimaanDir) : [];
                foreach ($penerimaanFiles as $file) {
                    if (! str_ends_with($file, '.json')) continue;
                    $doc = json_decode($disk->get($file), true) ?: [];
                    $docPemeriksaanNomor = $doc['pemeriksaan_nomor'] ?? null;
                    if ($docPemeriksaanNomor && trim($docPemeriksaanNomor) === trim($nomor)) {
                        if (!empty($doc['nomor'])) {
                            $penerimaanNomors[] = trim($doc['nomor']);
                        }
                        $disk->delete($file);
                    }
                }

                if (!empty($penerimaanNomors)) {
                    $kwitansiDir = "users/{$userId}/kwitansi";
                    $kwitansiFiles = $disk->exists($kwitansiDir) ? $disk->files($kwitansiDir) : [];
                    foreach ($kwitansiFiles as $file) {
                        if (! str_ends_with($file, '.json')) continue;
                        $doc = json_decode($disk->get($file), true) ?: [];
                        $docKwtPenerimaanNomor = $doc['penerimaan_nomor'] ?? null;
                        if ($docKwtPenerimaanNomor && in_array(trim($docKwtPenerimaanNomor), $penerimaanNomors)) {
                            // --- Stock Reversal START ---
                            $kwtNomor = $doc['nomor_kwt'] ?? null;
                            if ($kwtNomor) {
                                $transactions = \App\Models\StockTransaction::where('user_id', $userId)
                                    ->where('notes', 'Otomatis dari Kwitansi')
                                    ->where(function($q) use ($kwtNomor, $docKwtPenerimaanNomor) {
                                        $q->where('nosur', $kwtNomor);
                                        if ($docKwtPenerimaanNomor) {
                                            $q->orWhere('nosur', $docKwtPenerimaanNomor);
                                        }
                                    })
                                    ->get();
                                foreach ($transactions as $tx) {
                                    $product = \App\Models\Product::find($tx->product_id);
                                    if ($product) {
                                        $product->decrement('stock', $tx->quantity);
                                    }
                                    $tx->delete();
                                }
                            }
                            // --- Stock Reversal END ---
                            $disk->delete($file);
                        }
                    }
                }
            }
        }
        $bapNomor = $nomor ?? 'Berita Acara';
        return redirect()->route('reports.pemeriksaan.list')->with('success', 'Berita acara "' . $bapNomor . '" dan dokumen terkait berhasil dihapus');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $count = 0;
        $disk = Storage::disk('local');
        $userId = Auth::id();
        foreach ($ids as $id) {
            $path = "users/{$userId}/bap-pemeriksaan/{$id}.json";
            if ($disk->exists($path)) {
                $json = $disk->get($path);
                $data = json_decode($json, true) ?: [];
                $nomor = $data['nomor'] ?? null;
                $disk->delete($path);
                
                if ($nomor) {
                    // Find and delete related Penerimaan and Kwitansi
                    $penerimaanNomors = [];
                    $penerimaanDir = "users/{$userId}/bap-penerimaan";
                    $penerimaanFiles = $disk->exists($penerimaanDir) ? $disk->files($penerimaanDir) : [];
                    foreach ($penerimaanFiles as $file) {
                        if (! str_ends_with($file, '.json')) continue;
                        $doc = json_decode($disk->get($file), true) ?: [];
                        $docPemeriksaanNomor = $doc['pemeriksaan_nomor'] ?? null;
                        if ($docPemeriksaanNomor && trim($docPemeriksaanNomor) === trim($nomor)) {
                            if (!empty($doc['nomor'])) {
                                $penerimaanNomors[] = trim($doc['nomor']);
                            }
                            $disk->delete($file);
                        }
                    }

                    if (!empty($penerimaanNomors)) {
                        $kwitansiDir = "users/{$userId}/kwitansi";
                        $kwitansiFiles = $disk->exists($kwitansiDir) ? $disk->files($kwitansiDir) : [];
                        foreach ($kwitansiFiles as $file) {
                            if (! str_ends_with($file, '.json')) continue;
                            $doc = json_decode($disk->get($file), true) ?: [];
                            $docKwtPenerimaanNomor = $doc['penerimaan_nomor'] ?? null;
                            if ($docKwtPenerimaanNomor && in_array(trim($docKwtPenerimaanNomor), $penerimaanNomors)) {
                                // --- Stock Reversal START ---
                                $kwtNomor = $doc['nomor_kwt'] ?? null;
                                if ($kwtNomor) {
                                    $transactions = \App\Models\StockTransaction::where('user_id', $userId)
                                        ->where('notes', 'Otomatis dari Kwitansi')
                                        ->where(function($q) use ($kwtNomor, $docKwtPenerimaanNomor) {
                                            $q->where('nosur', $kwtNomor);
                                            if ($docKwtPenerimaanNomor) {
                                                $q->orWhere('nosur', $docKwtPenerimaanNomor);
                                            }
                                        })
                                        ->get();
                                    foreach ($transactions as $tx) {
                                        $product = \App\Models\Product::find($tx->product_id);
                                        if ($product) {
                                            $product->decrement('stock', $tx->quantity);
                                        }
                                        $tx->delete();
                                    }
                                }
                                // --- Stock Reversal END ---
                                $disk->delete($file);
                            }
                        }
                    }
                }
                $count++;
            }
        }
        return redirect()->route('reports.pemeriksaan.list')->with('success', "{$count} Berita acara pemeriksaan dan dokumen terkait berhasil dihapus");
    }
}
