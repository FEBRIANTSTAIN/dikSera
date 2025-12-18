<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    public function index()
    {
        $soals = BankSoal::latest()->paginate(10);
        return view('admin.bank_soal.index', compact('soals'));
    }

    public function create()
    {
        return view('admin.bank_soal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan'     => 'required|string',
            'kategori'       => 'required|string',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opsi.a'         => 'required|string',
            'opsi.b'         => 'required|string',
            'opsi.c'         => 'required|string',
            'opsi.d'         => 'required|string',
            'opsi.e'         => 'required|string',
            'kunci_jawaban'  => 'required|in:a,b,c,d,e',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('soal', 'public');
        }

        BankSoal::create([
            'pertanyaan'   => $request->pertanyaan,
            'gambar'       => $gambarPath,
            'kategori'     => $request->kategori,
            'opsi_jawaban' => $request->opsi,
            'kunci_jawaban' => $request->kunci_jawaban,
        ]);

        return redirect()->route('admin.bank-soal.index')->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $soal = BankSoal::findOrFail($id);
        return view('admin.bank_soal.edit', compact('soal'));
    }

    public function update(Request $request, $id)
    {
        $bankSoal = BankSoal::findOrFail($id);

        $request->validate([
            'pertanyaan'     => 'required|string',
            'kategori'       => 'required|string',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opsi.a'         => 'required|string',
            'opsi.b'         => 'required|string',
            'opsi.c'         => 'required|string',
            'opsi.d'         => 'required|string',
            'opsi.e'         => 'required|string',
            'kunci_jawaban'  => 'required|in:a,b,c,d,e',
        ]);

        $data = [
            'pertanyaan'   => $request->pertanyaan,
            'kategori'     => $request->kategori,
            'opsi_jawaban' => $request->opsi,
            'kunci_jawaban' => $request->kunci_jawaban,
        ];

        if ($request->hasFile('gambar')) {
            if ($bankSoal->gambar) {
                Storage::disk('public')->delete($bankSoal->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('soal', 'public');
        }

        $bankSoal->update($data);

        return redirect()->route('admin.bank-soal.index')->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $soal = BankSoal::findOrFail($id);
        if ($soal->gambar) {
            Storage::disk('public')->delete($soal->gambar);
        }
        $soal->delete();

        return back()->with('success', 'Soal berhasil dihapus');
    }

    public function importJson(Request $request)
    {
        $data = $request->json()->all();
        $count = 0;
        foreach ($data as $row) {
            if (empty($row['pertanyaan']) || empty($row['kunci_jawaban'])) continue;

            BankSoal::create([
                'pertanyaan' => $row['pertanyaan'],
                'kategori'   => $row['kategori'] ?? 'Umum',
                'opsi_jawaban' => [
                    'a' => $row['opsi_a'] ?? '-',
                    'b' => $row['opsi_b'] ?? '-',
                    'c' => $row['opsi_c'] ?? '-',
                    'd' => $row['opsi_d'] ?? '-',
                    'e' => $row['opsi_e'] ?? '-',
                ],
                'kunci_jawaban' => strtolower($row['kunci_jawaban']),
            ]);
            $count++;
        }
        return response()->json(['message' => "Berhasil mengimport $count soal.", 'count' => $count]);
    }
}
