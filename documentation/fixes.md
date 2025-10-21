# Un fichier `fixes.md`** décrivant tous les correctifs apportés

---

## ⚙️ 1. Un environnement Docker complet et fonctionnel** pour le mini-framework `wshop-test` (PHP + MySQL + phpMyAdmin)

Création des fichiers à la racine du projet :

### 1/ docker-compose.yml

```yaml
services:
  web:
    image: php:8.2-apache
  db:
    image: mysql:8.0
  phpmyadmin:
    image: phpmyadmin:latest
```

### 2/ Dockerfile (PHP + Apache)

```dockerfile
# Dockerfile
FROM php:8.2-apache
```

---

## 🧩 3. Makefile

Crée un `Makefile` à la racine pour simplifier l’utilisation :

---

## 🗃️ 4. Correction SQL — `importSql.sql`

**Une correction du SQL** (renommer tables/champs et retirer les préfixes inutiles).

* Tables renommées `product` et `product_translation`
* Champs renommés en anglais et sans préfixes

---

## 🧾 5. Ajout d'un fichier .env

```env
# Database Configuration
DB_HOST=db
DB_PORT=3306
DB_NAME=wshop_test
DB_USER=wshop
DB_PASS=wshop
DB_CHARSET=utf8mb4
```

---

## 🐞 6. Corrections de code PHP (principales erreurs corrigées)

Correction plus modernes et robustes :
✅ les chemins relatifs fragiles,
✅ la gestion des namespaces,
✅ la compatibilité avec Apache sous Docker,
✅ la détection correcte des routes,
✅ la lisibilité globale
✅ typage strict, gestion d’exception claire, retour nullable cohérent (fetchRow)
✅ suppression de ProductAction.php et introduition de deux nouvelles classes (ProductDTO et ProductDAO)
✅ ajout de l’instruction ```php <?php declare(strict_types=1); ?>``` pour activer le typage strict
✅ création d'un Nouveau fichier `.env` et `.gitignore` à la racine de du projet

### ✅ `app/Router.php`

* Utilisation de `$_SERVER['REQUEST_URI']` corrigée pour fonctionner sous Apache
* Suppression d’exceptions trop strictes
* Gestion plus propre du chemin vers `controllers`

### ✅ `app/Loader.php`

* Suppression de la classe Loader.php
* gérer l’autoload PSR-4 automatiquement via Composer
* Meilleure gestion d’autoload

### ✅ `app/Database.php`

* La méthode executeSql n’existe pas en PDO.
* Erreur de syntaxe : un ; manquant (return false dans fetchAll())
* Propriétés inutilisées ou non typées (prepare non utilisée hors debug)
* Validation de la config : double vérification du champ database inutile
* Suppression de la logique du database.ini et remplacement par un chargement via $_ENV

### ✅ `models/ProductDTO.php`

* Représente les données produits (structure de ton entité)
* DTO prêt pour API ou sérialisation JSON

### ✅ `classes/ProductDAO.php`

* Gère la logique de persistance (accès à la BDD)
* Couche DAO facilement testable

### ✅ `controllers/AbstractController.php`

* Le chemin relatif depuis index.php da été corrigé pour pointer correctement vers config/constant.ini
* __DIR__ fait référence au dossier actuel (controllers/)
* realpath() résout le chemin absolu

### ✅ `controllers/ProductController.php`

* renommage du template en detail.tpl.php au lieu de detail_list.tpl.php
* caste de la variable `$limit` en entier avant de l’envoyer à la méthode findAll()

### ✅ `controllers/AjaxController.php`

* Ajout de l'argument dans la fonction delete($id);

### ✅ `templates/product/detail.tpl.php`

* Adapter le lien dans les templates pour utiliser la route sans .php

---

## 🧾 7. Modification du readme.md
