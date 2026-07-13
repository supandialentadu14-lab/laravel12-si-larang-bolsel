{{-- Komponen KOP Surat: gunakan @include('partials.kop', ['opd' => $opd]) --}}
{{-- Parameter: $opd (object/array) dengan nama_opd, opsional logo di public/images/bolsel.png --}}
<style>
  .kop { width: 100%; table-layout: fixed; border-collapse: collapse; margin-bottom: 0px; }
  .kop td { vertical-align: middle; border: none !important; padding: 0 !important; }
  .kop-logo { width: 65px; text-align: center; }
  .kop-logo img { width: 60px; height: auto; object-fit: contain; }
  .kop-text { text-align: center; padding-right: 40px; }
  .kop-text .line1 { font-weight: 800; font-size: 14px; letter-spacing: .4px; text-transform: uppercase; line-height: 1.2; margin: 0; }
  .kop-text .line2 { font-weight: 800; font-size: 18px; text-transform: uppercase; line-height: 1.2; margin: 2px 0; }
  .kop-text .line3 { 
    font-style: italic; 
    font-size: 11px; 
    line-height: 1.2; 
    margin: 0; 
    white-space: normal;
  }
  .kop-divider {
    border-bottom: 3px solid black;
    margin-top: 2px;
    position: relative;
  }
  .kop-divider::after {
    content: "";
    display: block;
    border-bottom: 1px solid black;
    margin-top: 2px;
  }
  @media print {
    .kop-logo img { width: 65px; }
    .kop-text .line2 { font-size: 22px; }
  }
</style>
<table class="kop">
  <tr>
    <td class="kop-logo">
      @if (file_exists(public_path('images/bolsel.png')))
        <img src="{{ asset('images/bolsel.png') }}" alt="Logo Bolsel">
      @else
        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-[10px]">LOGO</div>
      @endif
    </td>
    <td class="kop-text">
      <div class="line1">PEMERINTAH KABUPATEN BOLAANG MONGONDOW SELATAN</div>
      <div class="line2">
        @php
            $nama_opd = \Illuminate\Support\Str::upper($opd->nama_opd ?? 'Instansi Belum Diatur');
            $words = explode(' ', $nama_opd);
            $word_count = count($words);
            
            if (strlen($nama_opd) > 40 && $word_count > 1) {
                // Split by half word count (ceil for more words on top)
                $mid = ceil($word_count / 2);
                $line1 = implode(' ', array_slice($words, 0, $mid));
                $line2 = implode(' ', array_slice($words, $mid));
                echo $line1 . '<br>' . $line2;
            } else {
                echo $nama_opd;
            }
        @endphp
      </div>

      <div class="line3">{{ $opd->alamat_opd ?? 'Alamat Instansi Belum Diatur' }}</div>
    </td>
  </tr>
</table>
<div class="kop-divider"></div>
