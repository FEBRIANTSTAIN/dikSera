<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramService;
use Illuminate\Support\Str;

use Carbon\Carbon;

class TelegramController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function linkTelegram()
    {
        return view('perawat.telegram.link');
    }

    public function generateCode(Request $request)
    {
        $user = auth()->user();

        // Generate 6 digit code
        $code = strtoupper(Str::random(6));

        $user->telegram_verification_code = $code;
        $user->telegram_verification_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        return response()->json([
            'success' => true,
            'code' => $code,
            'expires_at' => $user->telegram_verification_expires_at->format('H:i')
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $user = auth()->user();

        if (!$user->telegram_verification_code) {
            return response()->json(['success' => false, 'message' => 'Kode belum digenerate'], 400);
        }

        if (Carbon::now()->gt($user->telegram_verification_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Kode sudah kadaluarsa'], 400);
        }

        if ($user->telegram_verification_code !== strtoupper($request->code)) {
            return response()->json(['success' => false, 'message' => 'Kode salah'], 400);
        }

        // Code valid - wait for webhook to set chat_id
        return response()->json(['success' => true, 'message' => 'Kode valid, menunggu konfirmasi dari bot...']);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['message']['text'])) {
            $chatId = $data['message']['chat']['id'];
            $text = trim($data['message']['text']);

            // Check if message is verification code
            $user = \App\Models\User::where('telegram_verification_code', strtoupper($text))
                ->where('telegram_verification_expires_at', '>', Carbon::now())
                ->first();

            if ($user) {
                $user->telegram_chat_id = $chatId;
                $user->telegram_verification_code = null;
                $user->telegram_verification_expires_at = null;
                $user->save();

                $this->telegramService->sendMessage("✅ Akun berhasil terhubung!\n\nHalo {$user->name}, akun Telegram Anda sudah terhubung dengan sistem. Anda akan menerima notifikasi untuk sertifikat yang akan kadaluarsa.");

                return response()->json(['ok' => true]);
            }
        }

        return response()->json(['ok' => true]);
    }

    public function unlinkTelegram()
    {
        $user = auth()->user();
        $user->telegram_chat_id = null;
        $user->save();

        return back()->with('success', 'Telegram berhasil diputus');
    }
}
