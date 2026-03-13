<?php

namespace App\Http\Controllers;

use App\Models\OpdSetting;
use App\Models\NotaMaster;
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
        
        $notaMaster = NotaMaster::where('user_id', Auth::id())->first();
        if (!$notaMaster) {
            $notaMaster = NotaMaster::create(['user_id' => Auth::id()]);
        }
        
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', request()->header('User-Agent'));
        $view = $isMobile ? 'mobile.settings.combined' : 'settings.combined';
        
        return view($view, compact('setting', 'notaMaster'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            // OPD Settings
            'nama_opd' => 'nullable|string|max:255',
            'singkatan_opd' => 'nullable|string|max:50',
            'alamat_opd' => 'nullable|string|max:500',
            'kepala_nama' => 'nullable|string|max:255',
            'kepala_nip' => 'nullable|string|max:50',
            'kepala_pangkat' => 'nullable|string|max:255',
            'kepala_jabatan' => 'nullable|string|max:255',
            'pengurus_nama' => 'nullable|string|max:255',
            'pengurus_nip' => 'nullable|string|max:50',
            'pengguna_nama' => 'nullable|string|max:255',
            'pengguna_nip' => 'nullable|string|max:50',
            'tutup_buku_date' => 'nullable|date',
            
            // Nota Master (Penandatangan)
            'ppk_nama' => 'nullable|string|max:255',
            'ppk_nip' => 'nullable|string|max:50',
            'ppk_alamat' => 'nullable|string|max:500',
            'pejabat_nama' => 'nullable|string|max:255',
            'pejabat_nip' => 'nullable|string|max:50',
            'pptk_nama' => 'nullable|string|max:255',
            'pptk_nip' => 'nullable|string|max:50',
            'bendahara_nama' => 'nullable|string|max:255',
            'bendahara_nip' => 'nullable|string|max:50',
        ]);

        $setting = OpdSetting::where('user_id', Auth::id())->first();
        $oldSingkatan = $setting->singkatan_opd ?? 'DISKOMINFO';
        
        // Update OpdSetting
        $setting->update($request->only([
            'nama_opd', 'singkatan_opd', 'alamat_opd', 
            'kepala_nama', 'kepala_nip', 'kepala_pangkat', 'kepala_jabatan',
            'pengurus_nama', 'pengurus_nip', 'pengurus_pangkat', 'pengurus_jabatan',
            'pengguna_nama', 'pengguna_nip', 'pengguna_pangkat', 'pengguna_jabatan',
            'tutup_buku_date'
        ]));

        // Update NotaMaster
        $notaMaster = NotaMaster::where('user_id', Auth::id())->first();
        if (!$notaMaster) {
            $notaMaster = NotaMaster::create(['user_id' => Auth::id()]);
        }
        $notaMaster->update([
            'opd_nama' => $request->nama_opd,
            'opd_alamat' => $request->alamat_opd,
            'ppk_nama' => $request->ppk_nama,
            'ppk_nip' => $request->ppk_nip,
            'ppk_alamat' => $request->ppk_alamat,
            'pejabat_nama' => $request->pejabat_nama,
            'pejabat_nip' => $request->pejabat_nip,
            'pptk_nama' => $request->pptk_nama,
            'pptk_nip' => $request->pptk_nip,
            'bendahara_nama' => $request->bendahara_nama,
            'bendahara_nip' => $request->bendahara_nip,
            'pengurus_barang_nama' => $request->pengurus_nama,
            'pengurus_barang_nip' => $request->pengurus_nip,
            'pengurus_pengguna_nama' => $request->pengguna_nama,
            'pengurus_pengguna_nip' => $request->pengguna_nip,
        ]);

        $newSingkatan = $setting->singkatan_opd ?? 'DISKOMINFO';
        $olds = array_unique(['KOMINFO', $oldSingkatan]);
        if (($key = array_search($newSingkatan, $olds)) !== false) {
            unset($olds[$key]);
        }
        if (count($olds) > 0) {
            $this->syncNomorSurat(Auth::id(), $olds, $newSingkatan);
        }
        
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
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
