# Déploiement d'OURATABLE (hébergement gratuit)

## Point important sur l'architecture

OURATABLE est une application **Laravel monolithique** : le HTML est **rendu côté
serveur** par PHP (vues Blade). Il n'y a pas de « front » séparé (pas de SPA
React/Vue autonome).

**Conséquence :** **Netlify n'est pas adapté** ici. Netlify héberge des sites
statiques ou des fonctions JavaScript, mais **pas un backend PHP/Laravel**.
Déployer « le front sur Netlify » nécessiterait de réécrire toute l'interface en
application monopage (SPA) consommant une API — un chantier important et non
demandé.

➡️ **La bonne approche : héberger l'application complète (front + back) sur une
plateforme PHP gratuite.** Les fichiers `Dockerfile` et `render.yaml` fournis
visent **Render.com** (offre gratuite, Docker, idéale pour Laravel). Railway et
Fly.io fonctionnent de la même façon.

> Je ne peux pas créer de compte d'hébergement ni déployer à votre place : la
> création de compte exige **votre** email, une vérification et parfois un
> moyen de paiement. Voici la procédure complète à suivre — tout le nécessaire
> côté code est déjà prêt.

---

## Déploiement sur Render.com (gratuit)

### 1. Préparer le dépôt Git
```bash
cd D:\site-recette
git add .
git commit -m "Préparation déploiement OURATABLE"
git push        # vers GitHub / GitLab
```

### 2. Créer le compte et le service
1. Créez un compte sur **https://render.com** (connexion via GitHub recommandée).
2. **New + → Web Service**, puis sélectionnez votre dépôt.
3. Render détecte le `Dockerfile` et le `render.yaml`. Choisissez le plan
   **Free**.

### 3. Variables d'environnement (onglet *Environment*)
- `APP_KEY` : générez-la localement puis copiez la valeur :
  ```bash
  C:\php82\php.exe artisan key:generate --show
  ```
- `APP_URL` : l'URL fournie par Render (ex. `https://ouratable.onrender.com`).
- (Optionnel) `MAIL_USERNAME` / `MAIL_PASSWORD` pour activer l'envoi d'emails.

### 4. Déployer
Cliquez sur **Create Web Service**. Au démarrage, le conteneur exécute
automatiquement `migrate --force --seed` (base SQLite + données de démo, images
de secours générées), puis lance le serveur.

### 5. Connexion
- Administratrice : `luciarasoanirina8@gmail.com` / `admin1707`
- Membre de test : `anniah@ouratable.mg` / `123456`

---

## Notes

- **Base de données :** SQLite par défaut (simple, sans service). Sur l'offre
  gratuite, le disque est éphémère : les données sont **réinitialisées** à
  chaque redéploiement. Pour des données persistantes, attachez une base
  **PostgreSQL gratuite** Render et passez `DB_CONNECTION=pgsql` (+ variables
  `DB_*`).
- **Images :** les médias uploadés ne sont pas versionnés ; le seeder régénère
  un visuel de secours si une image est absente, donc le site reste cohérent
  après déploiement.
- **Mise en veille :** un service gratuit s'endort après inactivité ; le premier
  accès peut prendre ~30 s.

---

## Alternative : tout-en-un sur Railway
1. Compte sur **https://railway.app**.
2. *New Project → Deploy from GitHub repo*.
3. Railway utilise le `Dockerfile`. Ajoutez les mêmes variables d'environnement.
