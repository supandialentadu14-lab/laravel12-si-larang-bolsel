{{-- Komponen KOP Surat: gunakan @include('partials.kop', ['opd' => $opd]) --}}
{{-- Parameter: $opd (object/array) dengan nama_opd, opsional logo di public/images/bolsel.png --}}
<style>
  .kop { width: 100%; table-layout: fixed; border-collapse: collapse; }
  .kop td { vertical-align: middle; border: none !important; }
  .kop-logo { width: 80px; text-align: center; }
  .kop-logo img { width: 75px; height: auto; object-fit: contain; }
  .kop-text { text-align: center; padding-right: 40px; }
  .kop-text .line1 { font-weight: 800; font-size: 16px; letter-spacing: .4px; text-transform: uppercase; line-height: 1.2; margin: 0; }
  .kop-text .line2 { font-weight: 800; font-size: 22px; text-transform: uppercase; line-height: 1.2; margin: 2px 0; }
  .kop-text .line3 { 
    font-style: italic; 
    font-size: 12px; 
    line-height: 1.3; 
    margin: 0; 
    white-space: normal;
  }
  .kop-divider {
    border-bottom: 3px solid black;
    margin-top: 5px;
    position: relative;
  }
  .kop-divider::after {
    content: "";
    display: block;
    border-bottom: 1px solid black;
    margin-top: 2px;
  }
  @media print {
    .kop-logo img { width: 80px; }
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
      <div class="line2">{{ \Illuminate\Support\Str::upper($opd->nama_opd ?? 'Instansi Belum Diatur') }}</div>
      <div class="line3">{{ $opd->alamat_opd ?? 'Alamat Instansi Belum Diatur' }}</div>
    </td>
  </tr>
</table>
<div class="kop-divider"></div>
