<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->model  = config('gemini.model');
        $this->apiUrl = config('gemini.api_url');
    }
    public function chat(string $userMessage): string
    {
        try {
            $faqContext = $this->buildFaqContext();
            $prompt     = $this->buildPrompt($faqContext, $userMessage);

            $response = Http::timeout(30)->post(
                "{$this->apiUrl}{$this->model}:generateContent?key={$this->apiKey}",
                ['contents' => [['parts' => [['text' => $prompt]]]]]
            );


            if ($response->status() === 429) {
                return 'Bot sedang sibuk. Silakan coba beberapa saat lagi.';
            }

            if ($response->failed()) {
                Log::error('Gemini API Error', ['status' => $response->status()]);
                return 'Maaf, saya sedang mengalami gangguan. Coba lagi nanti.';
            }

            $reply = $response->json('candidates.0.content.parts.0.text');

            return !empty($reply) ? trim($reply)
                : 'Maaf, saya tidak bisa memproses pertanyaan itu. Coba ulangi dengan kalimat berbeda.';
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Gemini Connection Error: ' . $e->getMessage());
            return 'Koneksi ke server AI terputus. Periksa koneksi internet kamu.';
        } catch (\Exception $e) {
            Log::error('Gemini Error: ' . $e->getMessage());
            return 'Terjadi kesalahan yang tidak terduga.';
        }
    }

    private function buildFaqContext(): string
    {
        $faqs = Faq::all(['question', 'answer']);

        if ($faqs->isEmpty()) {
            return 'Belum ada FAQ yang tersedia.';
        }

        return $faqs->map(function ($faq, $i) {
            return "FAQ " . ($i + 1) . ":\nPertanyaan: {$faq->question}\nJawaban: {$faq->answer}";
        })->implode("\n\n");
    }

    private function buildPrompt(string $faqContext, string $userMessage): string
    {
        return "Kamu adalah asisten FAQ yang membantu pelanggan.\n"
            . "Jawab hanya berdasarkan informasi berikut. Gunakan bahasa Indonesia yang ramah.\n"
            . "Jika tidak ada di FAQ, katakan dengan sopan bahwa kamu tidak memiliki informasi tersebut.\n\n"
            . "=== DATA FAQ ===\n"
            . $faqContext . "\n"
            . "=== AKHIR FAQ ===\n\n"
            . "Pertanyaan pelanggan: " . $userMessage . "\n\nJawaban:";
    }
}
