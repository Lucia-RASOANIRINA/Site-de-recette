<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ChatGroup;
use App\Models\Recette;
use App\Models\Ingredient;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\RecipeVote;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ============================================================
        //  ADMINISTRATRICE
        // ============================================================
        User::updateOrCreate(
            ['email' => 'luciarasoanirina8@gmail.com'],
            [
                'name' => 'Lucia Rasoanirina',
                'password' => Hash::make('admin1707'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'bio' => null,
                'specialty' => 'Gestion de la plateforme',
                'city' => 'Antananarivo',
            ]
        );

        // ============================================================
        //  UTILISATEURS (mot de passe : 123456, emails verifies)
        // ============================================================
        $usersData = [
            ['name' => 'Anniah',     'specialty' => 'Patisserie',        'city' => 'Antananarivo'],
            ['name' => 'Mbolatiana', 'specialty' => 'Cuisine malgache',  'city' => 'Fianarantsoa'],
            ['name' => 'Joba',       'specialty' => 'Grillades & Street Food', 'city' => 'Toamasina'],
            ['name' => 'Lorraine',   'specialty' => 'Cuisine du monde',  'city' => 'Mahajanga'],
            ['name' => 'Genitah',    'specialty' => 'Healthy & Vegan',   'city' => 'Antsirabe'],
        ];
        $users = [];
        foreach ($usersData as $u) {
            $email = Str::lower($u['name']) . '@ouratable.mg';
            $users[$u['name']] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('123456'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'specialty' => $u['specialty'],
                    'city' => $u['city'],
                    'bio' => "Passionne(e) de " . Str::lower($u['specialty']) . " sur OURATABLE.",
                ]
            );
        }

        // ============================================================
        //  GROUPES DE DISCUSSION (plus de 10)
        // ============================================================
        $groups = [
            ['Patisserie & Desserts', 'cake', 'Sucre', 'Partagez vos meilleures recettes sucrees.'],
            ['Cuisine Malgache', 'soup', 'Traditionnel', 'Les classiques de la cuisine de Madagascar.'],
            ['Cuisine du Monde', 'globe', 'International', 'Voyagez a travers les saveurs du monde.'],
            ['Healthy & Vegan', 'salad', 'Sante', 'Recettes saines, vegetariennes et veganes.'],
            ['Cuisine Italienne', 'pizza', 'Europe', 'Pasta, pizza et dolce vita.'],
            ['Cuisine Asiatique', 'soup', 'Asie', 'Wok, nouilles, sushis et epices d Asie.'],
            ['Cuisine Francaise', 'utensils-crossed', 'Europe', 'La gastronomie a la francaise.'],
            ['Grillades & BBQ', 'flame', 'Viandes', 'Barbecue, brochettes et grillades.'],
            ['Street Food', 'sandwich', 'Rapide', 'Burgers, tacos, sandwichs et plus.'],
            ['Cuisine Indienne', 'flame', 'Asie', 'Curry, tandoori et epices parfumees.'],
            ['Petit-dejeuner & Brunch', 'coffee', 'Matin', 'Pancakes, crepes et brunchs gourmands.'],
            ['Cuisine Mexicaine', 'flame', 'Amerique', 'Tacos, guacamole et saveurs piquantes.'],
        ];
        foreach ($groups as $g) {
            ChatGroup::updateOrCreate(
                ['name' => $g[0]],
                ['slug' => Str::slug($g[0]), 'icon' => $g[1], 'category' => $g[2], 'description' => $g[3]]
            );
        }

        // ============================================================
        //  RECETTES MONDIALES (vraies images dans storage/app/public/recettes)
        //  [auteur, titre, pays, image, description, [ingredients > 5]]
        // ============================================================
        $recettesData = [
            ['Anniah', 'Mousse au chocolat', 'France', 'mousse-au-chocolat.jpg', 'Dessert francais leger et aerien au chocolat noir.',
                [['Chocolat noir','200 g'],['Oeufs','6'],['Sucre','50 g'],['Beurre','30 g'],['Sucre vanille','1 sachet'],['Sel','1 pincee']]],
            ['Anniah', 'Tarte au citron meringuee', 'France', 'tarte-au-citron-meringuee.jpg', 'La tarte signature, acidulee et gourmande.',
                [['Citrons','4'],['Beurre','100 g'],['Sucre glace','80 g'],['Oeufs','4'],['Farine','250 g'],['Sucre','150 g'],['Maizena','30 g']]],
            ['Anniah', 'Crepes', 'France', 'crepes.jpg', 'Crepes fines et moelleuses, sucrees ou salees.',
                [['Farine','250 g'],['Lait','500 ml'],['Oeufs','3'],['Beurre fondu','50 g'],['Sucre','2 c.a.s'],['Sel','1 pincee']]],
            ['Anniah', 'Pancakes', 'Etats-Unis', 'pancakes.jpg', 'Pancakes americains moelleux pour le brunch.',
                [['Farine','200 g'],['Lait','250 ml'],['Oeufs','2'],['Levure','1 sachet'],['Sucre','40 g'],['Beurre','30 g']]],
            ['Mbolatiana', 'Romazava', 'Madagascar', 'romazava.jpg', 'Le plat national malgache, mijote et reconfortant.',
                [['Boeuf','500 g'],['Bredes mafana','1 botte'],['Gingembre','20 g'],['Tomates','3'],['Oignon','1'],['Ail','3 gousses'],['Sel','au gout']]],
            ['Mbolatiana', 'Brochettes de zebu', 'Madagascar', 'brochettes-de-zebu.jpg', 'Brochettes de zebu grillees marinees aux epices.',
                [['Zebu','600 g'],['Ail','4 gousses'],['Huile','3 c.a.s'],['Citron','1'],['Paprika','1 c.a.c'],['Poivron','1'],['Oignon','1']]],
            ['Mbolatiana', 'Poulet roti', 'France', 'poulet_roti.jpg', 'Poulet roti dore au four, herbes et beurre.',
                [['Poulet','1.5 kg'],['Beurre','60 g'],['Thym','3 branches'],['Ail','4 gousses'],['Citron','1'],['Sel','au gout'],['Poivre','au gout']]],
            ['Joba', 'Burger maison', 'Etats-Unis', 'burger.jpg', 'Burger juteux maison, sauce et cheddar fondant.',
                [['Boeuf hache','400 g'],['Pain burger','4'],['Cheddar','4 tranches'],['Salade','4 feuilles'],['Tomate','1'],['Oignon','1'],['Sauce','4 c.a.s']]],
            ['Joba', 'Tacos', 'Mexique', 'tacos.jpg', 'Tacos mexicains garnis et epices.',
                [['Tortillas','6'],['Boeuf hache','400 g'],['Haricots rouges','200 g'],['Cheddar','100 g'],['Salade','4 feuilles'],['Epices tacos','1 sachet'],['Creme','3 c.a.s']]],
            ['Joba', 'Spaghetti bolognese', 'Italie', 'spaghetti.jpg', 'Le classique italien a la sauce bolognaise.',
                [['Spaghetti','400 g'],['Boeuf hache','400 g'],['Tomates concassees','400 g'],['Oignon','1'],['Ail','2 gousses'],['Parmesan','50 g'],['Basilic','quelques feuilles']]],
            ['Joba', 'Sandwich club', 'Etats-Unis', 'sandwich.jpg', 'Sandwich club genereux a etages.',
                [['Pain de mie','6 tranches'],['Poulet','200 g'],['Bacon','4 tranches'],['Salade','4 feuilles'],['Tomate','1'],['Mayonnaise','3 c.a.s']]],
            ['Lorraine', 'Lasagnes', 'Italie', 'lasagnes.jpg', 'Lasagnes gratinees a la bolognaise et bechamel.',
                [['Pates lasagnes','12'],['Boeuf hache','500 g'],['Tomates','400 g'],['Bechamel','500 ml'],['Parmesan','100 g'],['Oignon','1'],['Ail','2 gousses']]],
            ['Lorraine', 'Pad Thai', 'Thailande', 'pad-thai-maison.jpg', 'Nouilles sautees thailandaises sucrees-salees.',
                [['Nouilles de riz','250 g'],['Crevettes','200 g'],['Cacahuetes','50 g'],['Oeufs','2'],['Sauce soja','3 c.a.s'],['Citron vert','1'],['Pousses de soja','100 g']]],
            ['Lorraine', 'Riz cantonais', 'Chine', 'riz_cantonnais.jpg', 'Riz saute cantonais aux petits legumes.',
                [['Riz','300 g'],['Oeufs','2'],['Petits pois','100 g'],['Jambon','100 g'],['Carotte','1'],['Sauce soja','3 c.a.s'],['Oignon','1']]],
            ['Lorraine', 'Poulet curry', 'Inde', 'poulet_curry.jpg', 'Poulet au curry cremeux et parfume.',
                [['Poulet','600 g'],['Curry','2 c.a.s'],['Lait de coco','400 ml'],['Oignon','1'],['Ail','3 gousses'],['Gingembre','20 g'],['Coriandre','quelques feuilles']]],
            ['Genitah', 'Buddha bowl vegan', 'Monde', 'buddha-bowl-vegan.jpg', 'Bol equilibre, colore et 100% vegetal.',
                [['Quinoa','150 g'],['Avocat','1'],['Pois chiches','200 g'],['Carotte','2'],['Concombre','1'],['Chou rouge','100 g'],['Graines de courge','30 g']]],
            ['Genitah', 'Salade composee', 'Monde', 'salade.jpg', 'Salade fraiche et colore, ideale en ete.',
                [['Salade','1'],['Tomates','2'],['Concombre','1'],['Mais','100 g'],['Thon','1 boite'],['Oeufs','2'],['Vinaigrette','3 c.a.s']]],
            ['Genitah', 'Quiche lorraine', 'France', 'quiche.jpg', 'Quiche lorraine cremeuse aux lardons.',
                [['Pate brisee','1'],['Lardons','200 g'],['Oeufs','3'],['Creme fraiche','200 ml'],['Lait','100 ml'],['Gruyere','100 g'],['Muscade','1 pincee']]],
        ];

        $recettes = [];
        foreach ($recettesData as $rd) {
            [$author, $titre, $pays, $image, $desc, $ings] = $rd;
            // Garantit la présence de l'image (génère un visuel de secours si absente).
            $this->ensureImage('recettes/' . $image, $titre);
            $recette = Recette::firstOrCreate(
                ['titre' => $titre],
                [
                    'user_id' => $users[$author]->id,
                    'description' => "[{$pays}] {$desc}",
                    'instructions' => "1. Reunir tous les ingredients.\n2. Preparer et cuire selon la tradition de « {$pays} ».\n3. Assaisonner a votre gout.\n4. Dresser avec soin.\n5. Bon appetit !",
                    'image_path' => 'recettes/' . $image,
                ]
            );
            foreach ($ings as $ing) {
                Ingredient::firstOrCreate(['recette_id' => $recette->id, 'nom' => $ing[0]], ['quantite' => $ing[1]]);
            }
            $recettes[$titre] = $recette;
        }

        // ============================================================
        //  PUBLICATIONS COMMUNAUTE (avec images dans storage/app/public/posts)
        // ============================================================
        if (Post::count() === 0) {
            $posts = [
                ['Anniah',     'recette', "Ma mousse au chocolat maison, un classique francais inratable !", 'posts/post-chocolat.jpg'],
                ['Mbolatiana', 'recette', "Romazava traditionnel : la fierte de la cuisine malgache.", 'posts/post-romazava.jpg'],
                ['Joba',       'astuce',  "Pour des grillades parfaites, laissez reposer la viande 5 min apres cuisson.", 'posts/post-grillade.jpg'],
                ['Lorraine',   'recette', "Pad Thai maison : un voyage en Thailande dans votre assiette.", 'posts/post-padthai.jpg'],
                ['Genitah',    'astuce',  "Astuce healthy : remplacez la creme par du lait de coco.", 'posts/post-healthy.jpg'],
            ];
            $created = [];
            foreach ($posts as $p) {
                $this->ensureImage($p[3], $p[2]);
                $created[] = Post::create(['user_id' => $users[$p[0]]->id, 'type' => $p[1], 'content' => $p[2], 'image_path' => $p[3]]);
            }
            Comment::create(['user_id' => $users['Lorraine']->id, 'post_id' => $created[0]->id, 'content' => 'Ca a l\'air delicieux, je teste ce week-end !']);
            Comment::create(['user_id' => $users['Genitah']->id,  'post_id' => $created[1]->id, 'content' => 'Le romazava, mon plat prefere depuis toujours.']);
            Comment::create(['user_id' => $users['Anniah']->id,   'post_id' => $created[3]->id, 'content' => 'Miam, j adore le Pad Thai !']);

            $likePairs = [['Lorraine',0],['Genitah',0],['Joba',0],['Anniah',1],['Joba',1],['Mbolatiana',3],['Anniah',4],['Lorraine',2]];
            foreach ($likePairs as $lp) {
                Like::firstOrCreate(['user_id' => $users[$lp[0]]->id, 'post_id' => $created[$lp[1]]->id]);
            }
        }

        // ============================================================
        //  COUPS DE COEUR SUR RECETTES
        // ============================================================
        $recetteLikes = [
            ['Anniah','Romazava'],['Anniah','Pad Thai'],['Anniah','Tacos'],
            ['Joba','Mousse au chocolat'],['Joba','Buddha bowl vegan'],['Joba','Lasagnes'],
            ['Lorraine','Mousse au chocolat'],['Lorraine','Romazava'],['Lorraine','Burger maison'],
            ['Genitah','Romazava'],['Genitah','Brochettes de zebu'],['Genitah','Quiche lorraine'],
            ['Mbolatiana','Mousse au chocolat'],['Mbolatiana','Tarte au citron meringuee'],['Mbolatiana','Poulet curry'],
        ];
        foreach ($recetteLikes as $rl) {
            if (isset($recettes[$rl[1]])) {
                Like::firstOrCreate(['user_id' => $users[$rl[0]]->id, 'recette_id' => $recettes[$rl[1]]->id]);
            }
        }

        // ============================================================
        //  VOTES PUBLICS (par email + nom de recette)
        // ============================================================
        $votes = [
            'Romazava' => 12, 'Mousse au chocolat' => 9, 'Pad Thai' => 7, 'Burger maison' => 6,
            'Tacos' => 5, 'Lasagnes' => 4, 'Poulet curry' => 3, 'Buddha bowl vegan' => 2, 'Crepes' => 1,
        ];
        foreach ($votes as $titre => $count) {
            if (!isset($recettes[$titre])) continue;
            for ($i = 1; $i <= $count; $i++) {
                RecipeVote::firstOrCreate(
                    ['recette_id' => $recettes[$titre]->id, 'email' => 'visiteur' . $i . '@exemple.com'],
                    ['recette_nom' => $titre]
                );
            }
        }
    }

    /**
     * Garantit l'existence d'un fichier image sur le disque public.
     *
     * Si l'image est absente (cas d'un déploiement où les médias ne sont pas
     * versionnés), un visuel de secours est généré via GD : dégradé orange de
     * la charte et libellé. L'opération est silencieuse si GD est indisponible.
     *
     * @param  string  $relativePath  Chemin relatif au disque public (ex. « recettes/burger.jpg »).
     * @param  string  $label         Texte affiché sur le visuel de secours.
     */
    private function ensureImage(string $relativePath, string $label): void
    {
        $fullPath = storage_path('app/public/' . $relativePath);

        if (file_exists($fullPath) || !function_exists('imagecreatetruecolor')) {
            return;
        }

        @mkdir(dirname($fullPath), 0775, true);

        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y++) {
            $r = (int) max(0, 249 - ($y / $height) * 40);
            $g = (int) max(0, 115 - ($y / $height) * 45);
            $color = imagecolorallocate($image, $r, $g, 22);
            imageline($image, 0, $y, $width, $y, $color);
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $text = mb_strtoupper(Str::limit($label, 28, ''));
        $font = 5;
        $x = max(20, (int) (($width - imagefontwidth($font) * strlen($text)) / 2));
        imagestring($image, $font, $x, 280, $text, $white);
        imagestring($image, 3, 30, 30, 'OURATABLE', $white);

        imagejpeg($image, $fullPath, 85);
        imagedestroy($image);
    }
}
