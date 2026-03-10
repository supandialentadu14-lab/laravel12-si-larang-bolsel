<?php

namespace App\Http\Controllers;

use App\Models\OpdSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OpdController extends Controller
{
    public function index(): View
    {
        $items = OpdSetting::where('user_id', Auth::id())->orderByDesc('updated_at')->get();
        return view('settings.opd_list', compact('items'));
    }

    public function edit(): View
    {
        $setting = OpdSetting::where('user_id', Auth::id())->first();
        if (! $setting) {
            $setting = OpdSetting::create(['user_id' => Auth::id()]);
        }
        return view('settings.opd', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_opd' => 'nullable|string|max:255',
            'singkatan_opd' => 'nullable|string|max:50',
            'alamat_opd' => 'nullable|string|max:500',
            'kepala_nama' => 'nullable|string|max:255',
            'kepala_pangkat' => 'nullable|string|max:255',
            'kepala_jabatan' => 'nullable|string|max:255',
            'kepala_nip' => 'nullable|string|max:50',
            'pengurus_nama' => 'nullable|string|max:255',
            'pengurus_pangkat' => 'nullable|string|max:255',
            'pengurus_jabatan' => 'nullable|string|max:255',
            'pengurus_nip' => 'nullable|string|max:50',
            'pengguna_nama' => 'nullable|string|max:255',
            'pengguna_pangkat' => 'nullable|string|max:255',
            'pengguna_jabatan' => 'nullable|string|max:255',
            'pengguna_nip' => 'nullable|string|max:50',
            'tutup_buku_date' => 'nullable|date',
        ]);
        $setting = OpdSetting::where('user_id', Auth::id())->first();
        $oldSingkatan = $setting->singkatan_opd ?? 'DISKOMINFO';
        
        if (! $setting) {
            $setting = OpdSetting::create(array_merge($validated, ['user_id' => Auth::id()]));
        } else {
            $setting->update($validated);
        }
        
        $newSingkatan = $setting->singkatan_opd ?? 'DISKOMINFO';
        
        $olds = array_unique(['KOMINFO', $oldSingkatan]);
        if (($key = array_search($newSingkatan, $olds)) !== false) {
            unset($olds[$key]);
        }
        if (count($olds) > 0) {
            $this->syncNomorSurat(Auth::id(), $olds, $newSingkatan);
        }
        
        return redirect()->route('settings.opd.index')->with('success', 'Data OPD tersimpan.');
    }

    private function syncNomorSurat($userId, $olds, $newSingkatan)
    {
        // 1. Database records
        foreach ($olds as $old) {
            if (!$old) continue;
            $notas = \App\Models\NotaPesanan::where('user_id', $userId)->where('nomor', 'LIKE', "%/{$old}/%")->get();
            foreach ($notas as $nota) {
                $nota->nomor = str_replace("/{$old}/", "/{$newSingkatan}/", $nota->nomor);
                $nota->save();
            }
            $baps = \App\Models\BapPemeriksaan::where('user_id', $userId)->where('nomor', 'LIKE', "%/{$old}/%")->get();
            foreach ($baps as $bap) {
                $bap->nomor = str_replace("/{$old}/", "/{$newSingkatan}/", $bap->nomor);
                $bap->save();
            }
            $stocks = \App\Models\StockTransaction::where('user_id', $userId)->where('nosur', 'LIKE', "%/{$old}/%")->get();
            foreach ($stocks as $stock) {
                $stock->nosur = str_replace("/{$old}/", "/{$newSingkatan}/", $stock->nosur);
                $stock->save();
            }
        }
        
        // 2. JSON files
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $dirs = ['nota-pesanan', 'bap-penerimaan', 'bap-pemeriksaan', 'pinjam_pakai', 'kwitansi', 'opname'];
        foreach ($dirs as $dirName) {
            $dir = "users/{$userId}/{$dirName}";
            if (!$disk->exists($dir)) continue;
            foreach ($disk->files($dir) as $file) {
                if (!str_ends_with($file, '.json')) continue;
                $json = $disk->get($file);
                $data = json_decode($json, true);
                if (!$data) continue;
                
                $changed = false;
                $updater = function (&$val) use ($olds, $newSingkatan, &$changed) {
                    if (is_string($val)) {
                        foreach ($olds as $old) {
                            if ($old && str_contains($val, "/{$old}/")) {
                                $val = str_replace("/{$old}/", "/{$newSingkatan}/", $val);
                                $changed = true;
                            }
                        }
                    }
                };
                
                array_walk_recursive($data, $updater);
                
                if ($changed) {
                    $disk->put($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }
}
