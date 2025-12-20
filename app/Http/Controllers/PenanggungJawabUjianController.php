<?php

namespace App\Http\Controllers;

use App\Models\PenanggungJawabUjian;
use App\Models\User; // Import Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB untuk Transaction
use Illuminate\Support\Facades\Hash; // Import Hash untuk Password

class PenanggungJawabUjianController extends Controller
{
    public function index()
    {
        // Load relasi user agar bisa menampilkan email jika perlu
        $data = PenanggungJawabUjian::with('user')->paginate(10);
        return view('admin.penanggung_jawab_ujian.index', compact('data'));
    }

    public function create()
    {
        return view('admin.penanggung_jawab_ujian.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Tambah validasi email & password)
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email', // Email wajib unik di tabel users
            'password' => 'required|string|min:6',           // Password untuk login
            'no_hp'    => 'required|string|max:20',
            'jabatan'  => 'required|string|max:255',
            'type'     => 'required|in:pewawancara,ujian',
        ]);

        // 2. Mulai Database Transaction
        // Gunanya: Jika pembuatan profil gagal, akun user tidak akan terbuat (mencegah data sampah)
        DB::beginTransaction();

        try {
            // A. Buat Akun User Baru
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pewawancara', // Set role otomatis
            ]);

            // B. Buat Data Profil Penanggung Jawab & Link ke User ID
            PenanggungJawabUjian::create([
                'user_id' => $user->id, // Sambungkan relasi
                'nama'    => $request->nama,
                'no_hp'   => $request->no_hp,
                'jabatan' => $request->jabatan,
                'type'    => $request->type,
            ]);

            // Jika keduanya sukses, simpan permanen
            DB::commit(); 

            return redirect()->route('admin.penanggung-jawab.index')
                ->with('success', 'Akun & Data Penanggung Jawab berhasil dibuat.');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = PenanggungJawabUjian::with('user')->findOrFail($id);
        return view('admin.penanggung_jawab_ujian.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = PenanggungJawabUjian::findOrFail($id);

        // 1. Validasi Update
        $request->validate([
            'nama'     => 'required|string|max:255',
            // Validasi email unik, tapi kecualikan email milik user ini sendiri
            'email'    => 'required|email|unique:users,email,' . ($item->user_id ?? 0), 
            'password' => 'nullable|string|min:6', // Password opsional saat edit
            'no_hp'    => 'required|string|max:20',
            'jabatan'  => 'required|string|max:255',
            'type'     => 'required|in:pewawancara,ujian',
        ]);

        DB::beginTransaction();

        try {
            // A. Update Data Profil
            $item->update([
                'nama'    => $request->nama,
                'no_hp'   => $request->no_hp,
                'jabatan' => $request->jabatan,
                'type'    => $request->type,
            ]);

            // B. Update Data Akun User (Jika ada relasinya)
            if ($item->user) {
                $dataUser = [
                    'name'  => $request->nama, // Sinkronkan nama
                    'email' => $request->email
                ];

                // Jika admin mengisi password baru, update passwordnya
                if ($request->filled('password')) {
                    $dataUser['password'] = Hash::make($request->password);
                }

                $item->user->update($dataUser);
            }

            DB::commit();

            return redirect()->route('admin.penanggung-jawab.index')
                ->with('success', 'Data & Akun Login berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = PenanggungJawabUjian::findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus User (Profil PenanggungJawab akan ikut terhapus jika migration set onDelete cascade)
            // Jika tidak diset cascade, hapus manual:
            
            if ($item->user) {
                $item->user->delete(); // Hapus akun loginnya
            }
            
            // Hapus item profil (jika belum terhapus otomatis via cascade)
            // $item->delete(); 
            // Note: Jika kamu sudah set onDelete('cascade') di migration, baris $item->user->delete() sudah cukup.
            // Tapi untuk keamanan, kita delete item-nya juga secara eksplisit jika user sudah null/hilang.
            $item->delete();

            DB::commit();
            return redirect()->route('admin.penanggung-jawab.index')
                ->with('success', 'Data & Akun Pewawancara berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}