# Système de Régime Alimentaire

Application pour sélectionner un régime alimentaire adapté selon les objectifs de l'utilisateur.

## Prérequis

- PHP 7.4 ou supérieur
- MySQL/MariaDB


##  Installation et Démarrage

### Étape 1 : Configuration de l'environnement

1. Accédez au dossier `src` du projet :
   ```bash
   cd src
   ```

2. Créez un fichier `.env` à partir du fichier `env` (qui est le fichier d'exemple) :
   ```bash
   cp env .env
   ```

3. Ouvrez le fichier `.env` et configurez les paramètres de la base de données MySQL :
   ```ini
   # Configurez les variables suivantes selon votre installation MySQL
   database.default.hostname = localhost    # Hôte du serveur MySQL
   database.default.database = nom_base     # Nom de votre base de données
   database.default.username = root         # Nom d'utilisateur MySQL
   database.default.password = password     # Mot de passe MySQL
   database.default.DBDriver = MySQLi       # Pilote de base de données
   database.default.port = 3306             # Port MySQL (3306 par défaut)
   app_baseURL = 'http://localhost:8080'    #Nécessaire pour pouvoir lancer les URL du projets
   ```

### Étape 2 : Installation des dépendances

Dans le dossier `src`, installez les dépendances PHP avec Composer :
```bash
composer install
```

### Étape 3 : Création de la base de données et des tables

Exécutez les migrations pour créer les tables de la base de données :
```bash
```

### Étape 4 : Lancement de l'application

Pour lancer le serveur de développement CodeIgniter :
```bash
php spark serve
```

Par défaut, l'application sera accessible à :
```
http://localhost:8080
```

Vous pouvez modifier le port en utilisant :
```bash
php spark serve --port 8000
```

## 📁 Structure du projet

```
src/
├── app/                 # Code de l'application
│   ├── Config/         # Fichiers de configuration
│   ├── Controllers/    # Contrôleurs
│   ├── Models/         # Modèles de données
│   ├── Views/          # Fichiers de vue
│   └── Database/       # Migrations et seeds
├── public/             # Dossier public (index.php)
├── system/             # Framework CodeIgniter
├── .env                # Fichier de configuration (À créer)
├── env                 # Exemple de fichier .env
└── spark               # Outil CLI CodeIgniter
```
