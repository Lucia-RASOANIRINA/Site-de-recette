<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('DEEPSEEK_API_KEY');
        $this->apiUrl = env('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');
    }

    public function generateChallenge()
    {
        $prompt = "Genere un defi culinaire creatif et original. 
        Tu dois retourner UNIQUEMENT un objet JSON avec cette structure exacte:
        {
            \"title\": \"Titre accrocheur du defi\",
            \"description\": \"Description du defi\",
            \"ingredients\": [\"ingredient 1\", \"ingredient 2\", \"ingredient 3\"],
            \"difficulty\": \"facile|moyen|difficile\",
            \"duration\": nombre de jours (entre 3 et 7)
        }
        
        Le defi doit etre amusant, original et adapte a une communaute de passionnes de cuisine.
        Les ingredients doivent etre accessibles et creer une association surprenante mais delicieuse.
        Retourne UNIQUEMENT le JSON, sans aucun autre texte.";

        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un chef cuisinier expert en creation de defis culinaires originaux.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.8,
                    'max_tokens' => 500,
                ],
                'timeout' => 30,
            ]);

            $body = json_decode($response->getBody(), true);
            $content = $body['choices'][0]['message']['content'];
            
            $content = preg_replace('/```json\s*|\s*```/', '', $content);
            $challenge = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $challenge;
            }
            
            return $this->getFallbackChallenge();
            
        } catch (\Exception $e) {
            Log::error('DeepSeek API Error: ' . $e->getMessage());
            return $this->getFallbackChallenge();
        }
    }

    private function getFallbackChallenge()
    {
        return [
            'title' => 'Le Defi Fusion du Jour',
            'description' => 'Creez une recette unique en melangeant des saveurs inattendues !',
            'ingredients' => ['Miel', 'Cannelle', 'Citron', 'Gingembre'],
            'difficulty' => 'moyen',
            'duration' => 7
        ];
    }
}