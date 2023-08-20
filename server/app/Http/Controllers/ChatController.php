<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController
{
    function index(){
        return view("chat", ["value" => "", "preText" => "", "dialect" => 0]);
    }

    function post(Request $request){
        $validatedInput= $this->validatePostInput($request);
        $preText = $validatedInput["preText"];
        $dialect = (int)$validatedInput["dialect"];
        // 入力の処理

        Log::debug($dialect);

        if($dialect === 0){
            $dialectText = "関西弁";
        } else if ($dialect === 1) {
            $dialectText = "京都弁";
        }else if ($dialect === 2) {
            $dialectText = "東北弁";
        }

        $afterText = $this->accessApi($preText, $dialectText);
        // 具体的な処理

        return view("chat", ["value" => $afterText, "preText" => $preText, "dialect" => $dialect]);
        // 出力
    }

    private function validatePostInput($request){
        $preText = $request->input('pre-text');
        $dialect = $request->input('dialect');

        if ($preText === null || $preText === "") {
            return view("chat", ["value" =>"テキストを入力してください。", "preText" => $preText, "dialect" => $dialect]);
        }
        if ($dialect === null) {
            return view("chat", ["value" =>"値が不正です。", "preText" => $preText, "dialect" => $dialect]);
        }

        return [
            "preText" => $preText,
            "dialect" => $dialect
        ];
    }

    private function accessApi($preText, $dialectText){
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
                    "content" =>"以下の文を必ず" .$dialectText ."に翻訳してください。",
                ],
                [
                    "role" => "user",
                    "content" =>$preText
                ],
            ],
            "functions" => [
                    [
                    "name" => "convertDialects",
                    "description" => "標準語を特定の方言への変換を行う。例：(関西弁の場合)本当にありがとう->ほんまおおきに",
                    "parameters" => [
                        "type" => "object",
                        "properties" =>[
                            "converted_text" => [
                                "type" => "string",
                                "description" => "特定の方言で変換した後の文章。"
                            ]
                        ]
                    ]
                            ],
            ]
        );

        $response = Http::withHeaders($headers)->timeout(60)->post($url, $data);
        if ($response->json('error')) {
            info('エラーが発生');
        }

        Log::debug($response);

        $afterText = json_decode($response->json('choices')[0]['message']['function_call']['arguments'], true, 512, JSON_THROW_ON_ERROR)["converted_text"];

        return $afterText;
    }
}
