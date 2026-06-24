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
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);
        $this->apiKey = env('DEEPSEEK_API_KEY');
        $this->apiUrl = env('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');
    }

    /**
     * Generer un defi culinaire UNIQUE a chaque fois
     */
    public function generateChallenge($previousTitles = [], $previousIngredients = [])
    {
        // Verifier si la clé API est configurée
        if (empty($this->apiKey) || $this->apiKey === 'votre_api_key_ici') {
            Log::error('DeepSeek API Key non configurée');
            return $this->getFallbackChallenge();
        }

        // Construire un prompt UNIQUE avec historique
        $prompt = $this->buildUniquePrompt($previousTitles, $previousIngredients);

        try {
            Log::info('Appel DeepSeek API - Generation defi');

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
                            'content' => 'Tu es un chef cuisinier expert en creation de defis culinaires ORIGINAUX et VARIES. Chaque defi doit etre UNIQUE et DIFFERENT des precedents.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.95,
                    'max_tokens' => 600,
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            
            if (isset($body['choices'][0]['message']['content'])) {
                $content = $body['choices'][0]['message']['content'];
                
                // Nettoyer et parser le JSON
                $content = preg_replace('/```json\s*|\s*```/', '', $content);
                $content = trim($content);
                
                // Extraire le JSON
                preg_match('/\{[^{}]*\}/', $content, $matches);
                
                if (!empty($matches)) {
                    $challenge = json_decode($matches[0], true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Valider la difficulte
                        $difficulty = $challenge['difficulty'] ?? 'moyen';
                        if (!in_array($difficulty, ['facile', 'moyen', 'difficile'])) {
                            $difficulty = 'moyen';
                        }
                        
                        return [
                            'title' => $challenge['title'] ?? 'Defi Original du Jour',
                            'description' => $challenge['description'] ?? 'Preparez un plat creatif.',
                            'ingredients' => $challenge['ingredients'] ?? ['Ingredients au choix'],
                            'difficulty' => $difficulty,
                            'duration' => $challenge['duration'] ?? 7
                        ];
                    }
                }
            }

            Log::warning('Reponse DeepSeek invalide, utilisation fallback');
            return $this->getFallbackChallenge();

        } catch (\Exception $e) {
            Log::error('DeepSeek API Error: ' . $e->getMessage());
            return $this->getFallbackChallenge();
        }
    }

    /**
     * Construire un prompt UNIQUE avec de la variete
     */
    private function buildUniquePrompt($previousTitles, $previousIngredients)
    {
        // Liste de themes pour varier
        $themes = [
            'cuisine du monde', 'healthy', 'gourmand', 'rapide', 'traditionnel', 
            'fusion', 'vegetarien', 'moleculaire', 'saisonnier', 'exotique',
            'mediterraneen', 'asiatique', 'africain', 'americain', 'europeen'
        ];
        
        // Liste de difficultes pour alterner
        $difficulties = ['facile', 'moyen', 'difficile'];
        
        // Choisir aleatoirement un theme et une difficulte
        $selectedTheme = $themes[array_rand($themes)];
        $selectedDifficulty = $difficulties[array_rand($difficulties)];
        
        // Ajouter un nombre aleatoire pour la variete
        $randomSeed = rand(1, 9999);
        
        $prompt = "Genere un defi culinaire CREATIF et ORIGINAL.

        THEME IMPOSE : {$selectedTheme}
        DIFFICULTE : {$selectedDifficulty}
        SEED UNIQUE : {$randomSeed} (utilise ce nombre pour varier ta reponse)

        IMPORTANT : Le defi doit etre DIFFERENT des suivants :";

        if (!empty($previousTitles)) {
            $prompt .= "\n\nDEFIS DEJA PROPOSES (a eviter) :";
            foreach ($previousTitles as $index => $title) {
                $prompt .= "\n" . ($index + 1) . ". " . $title;
            }
        }

        if (!empty($previousIngredients)) {
            $prompt .= "\n\nINGREDIENTS DEJA UTILISES (a eviter) :";
            $prompt .= "\n" . implode(', ', array_slice($previousIngredients, 0, 15));
        }

        $prompt .= "

        CONSIGNES :
        1. Titre : Accrocheur, original et UNIQUE
        2. Description : Detaille l'objectif, les conseils et les astuces (100-150 mots)
        3. Ingredients : 4 a 6 ingredients SURPRENANTS (evite ceux deja utilises)
        4. Difficulte : {$selectedDifficulty}
        5. Duree : 7 jours

        EXEMPLES DE STYLES POUR VARIER :
        - Fusion des cultures (ex: tacos japonais, pizza indienne)
        - Cuisine zero dechet
        - Defi des 5 couleurs
        - Cuisine moleculaire
        - Retour aux sources (recettes oubliees)
        - Defi epices du monde
        - Cuisine du feu (grillages, fumage)
        - Defi des 3 textures

        FORMAT JSON UNIQUEMENT :
        {
            \"title\": \"Titre unique\",
            \"description\": \"Description complete\",
            \"ingredients\": [\"Ingredient 1\", \"Ingredient 2\", \"Ingredient 3\", \"Ingredient 4\"],
            \"difficulty\": \"{$selectedDifficulty}\",
            \"duration\": 7
        }

        Sois ORIGINAL et SURPRENANT !";

        return $prompt;
    }

    /**
     * Defi de secours VARIE
     */
    private function getFallbackChallenge()
    {
        $challenges = [
            [
                'title' => 'Tour du Monde en 5 Plats',
                'description' => 'Creez un menu compose de 5 plats inspires de 5 pays differents. Chaque plat doit representer une culture culinaire unique.',
                'ingredients' => ['Epices du monde', 'Herbes fraiches', 'Legumes de saison', 'Cereales anciennes'],
                'difficulty' => 'difficile',
                'duration' => 7
            ],
            [
                'title' => 'Le Defi Zero Dechet Culinaire',
                'description' => 'Realisez un repas complet en utilisant UNIQUEMENT des ingredients que vous avez deja dans votre cuisine. Pas de nouveaux achats !',
                'ingredients' => ['Ce que vous avez dans le frigo', 'Ce que vous avez dans le placard', 'Fines herbes du jardin'],
                'difficulty' => 'facile',
                'duration' => 7
            ],
            [
                'title' => 'Cuisine Moleculaire Express',
                'description' => 'Preparez un plat en utilisant une technique de cuisine moleculaire : spherification, gelification, mousse, ou emulsion.',
                'ingredients' => ['Agar-agar', 'Lecithine de soja', 'Alginate de sodium', 'Jus de fruits naturels'],
                'difficulty' => 'difficile',
                'duration' => 7
            ],
            [
                'title' => 'Le Festin des 5 Couleurs',
                'description' => 'Creez un repas complet en utilisant des ingredients de 5 couleurs differentes.',
                'ingredients' => ['Tomate', 'Carotte', 'Poivron', 'Aubergine', 'Champignon'],
                'difficulty' => 'moyen',
                'duration' => 7
            ],
            [
                'title' => 'La Cuisine du Temps qui Passe',
                'description' => 'Preparez un plat qui necessite une cuisson lente ou une fermentation.',
                'ingredients' => ['Viande a braiser', 'Legumes racines', 'Epices douces', 'Vin rouge'],
                'difficulty' => 'difficile',
                'duration' => 7
            ],
            [
                'title' => 'Le Buffet Vegetal Gourmand',
                'description' => 'Creez un buffet 100% vegetal avec des recettes originales.',
                'ingredients' => ['Legumineuses', 'Cereales anciennes', 'Legumes de saison', 'Herbes aromatiques'],
                'difficulty' => 'moyen',
                'duration' => 7
            ],
            [
                'title' => 'Le Defi des Epices Oubliees',
                'description' => 'Creez un plat en utilisant au moins 5 epices differentes, dont au moins 2 epices peu courantes.',
                'ingredients' => ['Cumin', 'Coriandre', 'Curcuma', 'Gingembre', 'Cannelle', 'Cardamome'],
                'difficulty' => 'moyen',
                'duration' => 7
            ],
            [
                'title' => 'Fusion Orient-Express',
                'description' => 'Creez un plat qui marie les saveurs orientales et occidentales.',
                'ingredients' => ['Couscous', 'Safran', 'Curry', 'Creme fraiche', 'Citron confit'],
                'difficulty' => 'difficile',
                'duration' => 7
            ],
            [
                'title' => 'Le Petit Dejeuner du Monde',
                'description' => 'Preparez un petit dejeuner complet inspire d\'un pays different chaque jour de la semaine.',
                'ingredients' => ['Cereales variees', 'Fruits exotiques', 'Oeufs', 'Pains du monde'],
                'difficulty' => 'facile',
                'duration' => 7
            ],
            [
                'title' => 'Le Defi de la Mer',
                'description' => 'Creez un plateau de fruits de mer original avec des associations surprenantes.',
                'ingredients' => ['Poissons', 'Fruits de mer', 'Agrumes', 'Herbes marines'],
                'difficulty' => 'difficile',
                'duration' => 7
            ],
            [
                'title' => 'Le Potager Creatif',
                'description' => 'Creez un plat 100% vegetal en utilisant des legumes de saison.',
                'ingredients' => ['Courge', 'Champignons', 'Epinards', 'Noix', 'Huile d\'olive'],
                'difficulty' => 'moyen',
                'duration' => 7
            ],
            [
                'title' => 'Le Defi Chocolate',
                'description' => 'Creez un dessert ou un plat sale ou le chocolat est l\'ingredient principal.',
                'ingredients' => ['Chocolat noir', 'Chocolat au lait', 'Sel', 'Fruits secs'],
                'difficulty' => 'moyen',
                'duration' => 7
            ]
        ];

        // Choisir un defi aleatoire
        $selected = $challenges[array_rand($challenges)];
        
        // Ajouter un suffixe aleatoire pour eviter les doublons
        $suffixes = ['Variante', 'Edition', 'Version', 'Challenge'];
        $selected['title'] = $selected['title'] . ' - ' . $suffixes[array_rand($suffixes)] . ' ' . rand(1, 99);
        
        return $selected;
    }
}