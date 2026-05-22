# 📁 Laravel S3 Drive

Une application de gestion de fichiers (Cloud Drive) développée avec **Laravel** et propulsée par **Amazon S3**. Ce projet permet de stocker, lister et gérer des fichiers directement sur l'infrastructure cloud d'AWS.

## 🚀 Fonctionnalités

L'application répond aux exigences suivantes :
1. **Listing dynamique** : Récupération du contenu du Bucket S3 avec affichage des métadonnées (nom, type MIME, taille formatée, date de modification).
2. **Upload sécurisé** : Téléversement de fichiers vers un dossier spécifique sur le Bucket.
3. **Téléchargement privé** : Génération d'URLs temporaires signées (AWS Presigned URLs) pour permettre le téléchargement sécurisé des fichiers sans les rendre publics.
4. **Suppression** : Nettoyage des éléments du Bucket directement depuis l'interface.

## 🛠️ Stack Technique

- **Backend** : Laravel 11+ (PHP 8.5)
- **Stockage** : AWS S3 (via SDK AWS & Flysystem)
- **Frontend** : Blade & Tailwind CSS
- **Serveur** : AWS EC2 (Ubuntu 24.04, Nginx, PHP-FPM)

## 📦 Installation

### Prérequis
- PHP 8.5+
- Composer
- Un compte AWS avec un utilisateur IAM (accès S3) et un Bucket.

### Installation locale
1. **Cloner le projet** :
   ```bash
   git clone [https://github.com/Fox-Programs/AWS_Cloud_Stockage.git](https://github.com/Fox-Programs/AWS_Cloud_Stockage.git)
   cd AWS_Cloud_Stockage

2. **Installer les dépendances** :
    ```bash
    composer install
    ```

3. **Configurer l'environnement** :

    Copiez le fichier .env.example en .env et renseignez vos accès AWS :
    ```bash
    AWS_ACCESS_KEY_ID=votre_access_key
    AWS_SECRET_ACCESS_KEY=votre_secret_key
    AWS_DEFAULT_REGION=votre_region
    AWS_BUCKET=nom_de_votre_bucket
    FILESYSTEM_DISK=s3
    ```

4. **Lancer l'application** :
    ```bash
    php artisan key:generate
    php artisan serve
    ```

## 🌐 Déploiement sur AWS EC2

Le projet est configuré pour être déployé sur une instance EC2 avec Nginx.

**Points clés du déploiement :**
- Utilisation de PHP 8.5-FPM.
- Configuration des permissions sur `storage` et `bootstrap/cache`.
- Ajustement du `php.ini` pour autoriser l'upload de fichiers volumineux (`post_max_size` & `upload_max_filesize`).
- Mise en cache des configurations pour la performance (`php artisan config:cache`).

## 🔒 Sécurité
- Les fichiers sur S3 ne sont pas publics.
- L'accès aux fichiers se fait via des URLs temporaires (Presigned URLs) valides 5 minutes.
- Utilisation de politiques IAM restreintes au strict nécessaire (List, Get, Put, Delete).

---
Développé par [Fox-Programs](https://github.com/Fox-Programs)
