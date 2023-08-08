<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController
{
    function index(){
        return view("chat", ["value" => ""]);
    }

    function post(Request $request){
        $preText = $request->input('pre-text');
        $dialect = $request->input('dialect');
        if ($preText === null || $preText === "") {
            return view("chat", ["value" =>"テキストを入力してください。"]);
        }
        if ($dialect === null) {
            return view("chat", ["value" =>"値が不正です。"]);
        }

        $url = "https://api.openai.com/v1/chat/completions";

        $apiKey = env("OPEN_API_KEY");
        $headers = array(
            "Content-Type" => "application/json",
            "Authorization" => "Bearer $apiKey"
        );

        $data = array(
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" =>"以下の文を関西弁に翻訳してください。\n以下の文を関西弁に翻訳してください。\n以下の文を関西弁に翻訳してください。\n",
                ],
                [
                    "role" => "user",
                    "content" =>$preText
                ]
            ]
        );

        $response = Http::withHeaders($headers)->timeout(60)->post($url, $data);
        if ($response->json('error')) {
            info('エラーが発生');
        }

        return view("chat", ["value" =>$response->json('choices')[0]['message']['content']]);
    }
}
