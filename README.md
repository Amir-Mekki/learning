# Learning Management System (LMS) - Documentation d'Installation

## 🚀 Prérequis
Avant d'installer et de lancer ce projet, assurez-vous d'avoir les outils suivants installés sur votre machine :

### 🔹 Outils nécessaires
- **XAMPP** (Apache, MySQL, PHP) : [https://www.apachefriends.org](https://www.apachefriends.org)
- **Composer** (Gestionnaire de dépendances PHP) : [https://getcomposer.org](https://getcomposer.org)
- **Redis** (Pour le cache et les WebSockets) :
  - Installation via Chocolatey : `choco install redis`
  - Ou télécharger Redis pour Windows : [https://github.com/tporadowski/redis/releases](https://github.com/tporadowski/redis/releases)
- **Keycloak** (SSO) : [https://www.keycloak.org/downloads](https://www.keycloak.org/downloads)
- **Git** (Pour la gestion de version) : [https://git-scm.com/downloads](https://git-scm.com/downloads)

## 📥 Installation du projet

### 1️⃣ Cloner le projet
```sh
cd C:\xampp\htdocs  # Aller dans le dossier de XAMPP

git clone https://github.com/TON-UTILISATEUR/TON-REPO.git learning
cd learning
```

### 2️⃣ Installer les dépendances PHP
```sh
composer install
```

### 3️⃣ Configurer l'environnement
1. Copier le fichier `.env.example` en `.env` :
   ```sh
   cp .env.example .env
   ```
2. Générer une clé d’application Laravel :
   ```sh
   php artisan key:generate
   ```

### 4️⃣ Configurer la base de données
1. **Créer une base de données** via phpMyAdmin :
   - Ouvrir [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Créer une nouvelle base de données nommée **learning**
2. **Modifier le fichier `.env`** :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=learning
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. **Exécuter les migrations** :
   ```sh
   php artisan migrate
   ```

### 5️⃣ Configurer Keycloak
1. **Démarrer Keycloak** :
   ```sh
   C:\keycloak\bin\kc.bat start-dev
   ```
2. **Créer un realm** nommé `learning`
3. **Créer un client** `learning-backend`
4. **Ajouter les rôles** (`admin`, `teacher`, `student`, `department_head`, `guest`)
5. **Récupérer le Client Secret** et l'ajouter à `.env` :
   ```env
   KEYCLOAK_REALM=learning
   KEYCLOAK_CLIENT_ID=learning-backend
   KEYCLOAK_CLIENT_SECRET=VOTRE_SECRET
   KEYCLOAK_SERVER_URL=http://localhost:8080
   ```

### 6️⃣ Installer les dépendances front-end (si nécessaire)
```sh
npm install
npm run dev
```

## 🚀 Lancer le projet

### 1️⃣ Démarrer le serveur Laravel
```sh
php artisan serve
```
📌 **Accéder à l’application** : [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 2️⃣ Démarrer Redis
```sh
redis-server
```

### 3️⃣ Vérifier que tout fonctionne
- Vérifier la connexion à Keycloak
- Vérifier que les migrations sont bien appliquées

## 🛠 Problèmes courants et solutions
### ❌ `php -v` ou `git --version` n'est pas reconnu ?
➡ **Ajoutez PHP et Git à votre PATH** via les variables d’environnement.

### ❌ `keycloak: JAVA_HOME is not set`
➡ Installer **Java 17+** et définir `JAVA_HOME` dans les variables d’environnement.

### ❌ `redis-server: commande introuvable`
➡ Vérifiez que Redis est bien installé et ajouté au `PATH`.

---
**🎉 Félicitations !** Votre LMS est maintenant installé et fonctionnel ! 🚀

