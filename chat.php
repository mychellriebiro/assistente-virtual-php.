<?php
header('Content-Type: application/json');

// Coloque sua chave da OpenAI aqui:
$apiKey = 'SUA_CHAVE_API_DA_OPENAI';

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (!$userMessage) {
  echo json_encode(['reply' => 'Mensagem vazia.']);
  exit;
}

$payload = [
  "model" => "gpt-3.5-turbo",
  "messages" => [
    ["role" => "user", "content" => $userMessage]
  ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$reply = $data['choices'][0]['message']['content'] ?? 'Erro ao obter resposta.';

echo json_encode(['reply' => $reply]);
