@extends('layouts.admin') {{-- Menggunakan layout utama admin --}}

@section('header', 'Pengguna Baru') {{-- Judul halaman tambah user baru --}}

@section('content')

    {{-- Container utama dengan lebar maksimal dan posisi tengah --}}
    <div class="max-w-xl mx-auto">

        {{-- Card pembungkus form --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">

            {{-- Header card --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-slate-800">
                <h6 class="font-bold text-white">
                    Data Pengguna {{-- Judul form --}}
                </h6>
            </div>

            {{-- Form tambah user --}}
            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6">

                @csrf {{-- Token keamanan Laravel untuk mencegah CSRF --}}

                {{-- ⚠️ Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm font-bold text-red-700 mb-2">
                            <i class="fas fa-exclamation-circle mr-1"></i> Terdapat kesalahan:
                        </p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Input Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span> {{-- Tanda wajib --}}
                    </label>

                    {{-- Field input nama --}}
                    <input type="text" 
                        name="name" 
                        value="{{ old('name') }}" {{-- Menyimpan input lama jika validasi gagal --}}
                        placeholder="John Doe"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition"
                        required>
                </div>

                {{-- Input Email --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>

                    {{-- Field input email --}}
                    <input type="email" 
                        name="email" 
                        value="{{ old('email') }}" {{-- Mengisi ulang jika gagal validasi --}}
                        placeholder="john@example.com"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition"
                        required>
                </div>

                {{-- Dropdown Role --}}
                <div x-data="{ role: '{{ old('role', 'staff') }}' }">
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Hak Akses <span class="text-red-500">*</span>
                    </label>

                    {{-- Pilihan role user --}}
                    <select name="role" x-model="role"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition bg-white"
                        required>

                        {{-- Role Staff --}}
                        <option value="staff">Staff</option>

                        {{-- Role Administrator --}}
                        <option value="admin">
                            Administrator (Full Access)
                        </option>

                    </select>

                    {{-- Hak Akses Khusus Staff --}}
                    <div x-show="role === 'staff'" x-transition class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                        <label class="block text-sm font-bold text-gray-700 mb-2 border-b border-slate-200 pb-2">
                            <i class="fas fa-user-shield text-indigo-500 mr-1"></i> Izin Akses Halaman (Khusus Staff)
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                            @foreach(config('permissions', []) as $key => $label)
                                @php
                                    $userPermissions = old('permissions', array_keys(config('permissions'))); // Default all checked for new staff
                                @endphp
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                           {{ in_array($key, $userPermissions) ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 transition-all">
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-slate-500 mt-3"><i class="fas fa-info-circle"></i> Centang halaman yang boleh diakses oleh user ini. Jika tidak dicentang, user tidak bisa mengakses menu tersebut.</p>
                    </div>
                </div>

                {{-- Grid Password & Konfirmasi --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Input Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>

                        {{-- Field password --}}
                        <input type="password" 
                            name="password"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition"
                            required>
                    </div>

                    {{-- Input Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Confirm Password
                        </label>

                        {{-- Field konfirmasi password --}}
                        <input type="password" 
                            name="password_confirmation"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition"
                            required>
                    </div>

                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('users.index'),
                    'saveText' => 'Simpan',
                ])
            </form>
        </div>
    </div>

@endsection
