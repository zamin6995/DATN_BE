<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\ChatGemini;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ChatAIController extends Controller
{
    public function listQuestion()
    {
        $user = auth()->user();

        $qnaHistory = ChatGemini::where('user_id', $user->id)->orderBy('created_at')->get();

        return response()->json(['data' => $qnaHistory]);
    }

    public function sentQuestion(Request $request)
    {
        $question = $request->input('question');
        $apiKey = config('services.gemini.api_key');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";
        logger("TEST api url: ", [$apiUrl]);
        // Sử dụng Guzzle để gửi yêu cầu POST tới API
        $client = new Client();
        $response = $client->post($apiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $question]
                        ]
                    ]
                ]
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);
        $answer = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, không có cách trả lời câu hỏi này!';
        ChatGemini::create([
            'user_id' => auth()->user()->id,
            'question' => $question,
            'answer' => $answer,
        ]);
        return response()->json(['answer' => $answer]);
    }
}
