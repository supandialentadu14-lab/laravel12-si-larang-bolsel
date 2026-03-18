<?php

// Menentukan namespace controller
namespace App\Http\Controllers;

// Mengimpor model User
use App\Models\User;

// Digunakan untuk tipe return redirect
use Illuminate\Http\RedirectResponse;

// Digunakan untuk menangkap request dari form
use Illuminate\Http\Request;

// Digunakan untuk mengenkripsi password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Digunakan untuk validasi unik saat update (ignore ID tertentu)
use Illuminate\Validation\Rule;

// Digunakan untuk tipe return View
use Illuminate\View\View;

// Controller untuk mengelola data user (CRUD + role)
class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::latest('created_at')
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        \Illuminate\Support\Facades\Log::debug('UserController@store: Request received', $request->except(['password','password_confirmation']));

        try {
            // Validasi input
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'nama_opd' => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'role'     => 'required|in:admin,staff',
                'permissions' => 'nullable|array',
            ]);

            // Generate password kompleks (minimal 8 karakter dengan huruf besar, kecil, angka, dan simbol)
            $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lower = 'abcdefghijklmnopqrstuvwxyz';
            $nums = '0123456789';
            $syms = '!@#$%^&*()_+-=[]{}|;:,.<>?';
            
            $passChars = [];
            $passChars[] = $upper[rand(0, strlen($upper) - 1)];
            $passChars[] = $lower[rand(0, strlen($lower) - 1)];
            $passChars[] = $nums[rand(0, strlen($nums) - 1)];
            $passChars[] = $syms[rand(0, strlen($syms) - 1)];
            
            $allChars = $upper . $lower . $nums . $syms;
            for ($i = 0; $i < 6; $i++) {
                $passChars[] = $allChars[rand(0, strlen($allChars) - 1)];
            }
            shuffle($passChars);
            $randomPassword = implode('', $passChars);

            \Illuminate\Support\Facades\Log::debug('UserController@store: Validation passed');

            // Membuat user baru
            $newUser = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'nama_opd' => $validated['nama_opd'],
                'password' => Hash::make($randomPassword),
                'role'     => $validated['role'],
                'chat_enabled' => $request->role === 'admin' ? true : $request->has('chat_enabled'),
                'permissions' => $request->role === 'admin' ? [] : $request->input('permissions', []),
            ]);

            // Kirim Email Credentials
            try {
                \Illuminate\Support\Facades\Mail::to($newUser->email)->send(new \App\Mail\UserCredentialsMail($newUser, $randomPassword));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email kredensial: ' . $e->getMessage());
            }

            \Illuminate\Support\Facades\Log::debug('UserController@store: User created with ID=' . $newUser->id);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna "' . $newUser->name . '" berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Illuminate\Support\Facades\Log::debug('UserController@store: Validation FAILED', $ve->errors());
            throw $ve; // Let Laravel handle it normally
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('UserController@store: EXCEPTION - ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form edit user
     */
    public function edit(User $user): View
    {
        // Route Model Binding otomatis mengambil user berdasarkan ID
        return view('users.edit', compact('user'));
    }

    /**
     * Menampilkan detail user (dialihkan ke tampilan edit)
     */
    public function show(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Mengupdate data user
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_opd' => 'required|string|max:255',

            // Email harus unik kecuali untuk user ini sendiri
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],

            'role' => 'required|in:admin,staff',
            'permissions' => 'nullable|array',

            // Password boleh kosong (nullable)
            'password' => [
                'nullable', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+-=\[\]{}|;:,.<>?]).{8,}$/'
            ],
            'avatar' => 'nullable|image|max:10240',
        ], [
            'password.regex' => 'Password harus mengandung kombinasi huruf besar, huruf kecil, angka, dan karakter khusus dengan panjang minimal 8 karakter.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        // Data dasar yang akan diupdate
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'nama_opd' => $validated['nama_opd'],
            'role' => $validated['role'],
            'chat_enabled' => $request->role === 'admin' ? true : $request->has('chat_enabled'),
            'permissions' => $request->role === 'admin' ? [] : $request->input('permissions', []),
        ];

        // Jika password diisi, maka update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Update user
        $user->update($data);

        // Redirect dengan pesan sukses
        $message = 'Data pengguna "' . $user->name . '" berhasil diperbarui.';
        if ($request->filled('password')) $message .= ' Password telah diubah.';
        if ($request->hasFile('avatar')) $message .= ' Foto profil telah diperbarui.';

        return redirect()->route('users.index')
            ->with('success', $message);
    }

    public function editSelf(): View
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * Mengupdate profil user yang sedang login
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_opd' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => [
                'nullable', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+-=\[\]{}|;:,.<>?]).{8,}$/'
            ],
            'avatar' => 'nullable|image|max:10240',
        ], [
            'password.regex' => 'Password harus mengandung kombinasi huruf besar, huruf kecil, angka, dan karakter khusus dengan panjang minimal 8 karakter.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'nama_opd' => $validated['nama_opd'],
        ];

        // Jika password diisi, maka update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
            $data['password_updated_at'] = now();
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
            $data['avatar_updated_at'] = now();
        }

        // Update user
        $user->update($data);
        $user->refresh();

        // Pesan sukses spesifik
        $info = [];
        if ($request->filled('password')) $info[] = 'Password';
        if ($request->hasFile('avatar')) $info[] = 'Foto Profil';
        
        $msg = 'Profil berhasil diperbarui.';
        if (!empty($info)) {
            $msg .= ' (' . implode(' & ', $info) . ' telah diupdate)';
        }

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', $msg);
    }

    /**
     * Toggle aktif/nonaktif user (admin only, tidak bisa untuk sesama admin atau diri sendiri)
     */
    public function toggleActive(User $user): RedirectResponse
    {
        // Hanya admin yang bisa
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Hanya admin yang dapat mengubah status pengguna.');
        }

        // Tidak bisa menonaktifkan diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        // Tidak bisa menonaktifkan admin lain
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun admin.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function toggleChat(User $user): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Hanya admin yang dapat mengubah akses chat.');
        }

        $user->update(['chat_enabled' => !$user->chat_enabled]);

        $status = $user->chat_enabled ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akses chat {$user->name} berhasil {$status}.");
    }

    /**
     * Menghapus user
     */
    public function destroy(User $user): RedirectResponse
    {
        $userName = $user->name;

        // Mencegah user menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Hapus user
        User::destroy($user->id);

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
            ->with('success', 'Pengguna "' . $userName . '" berhasil dihapus.');
    }

    public function dismissWelcome(): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->update(['first_login' => false]);
        return back();
    }
}
