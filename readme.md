# WShop-Test

Ce projet est une application PHP/MySQL Dockerisée pour gérer une boutique de test.  
Il utilise Docker Compose pour orchestrer les services web, base de données et phpMyAdmin.

---

## Prérequis

- [Docker](https://www.docker.com/get-started) installé sur votre machine
- [Docker Compose](https://docs.docker.com/compose/install/) (version >= 2)
- Make (optionnel mais recommandé pour simplifier les commandes)

---

## Structure des services Docker

| Service       | Description                                      | Port local |
|---------------|-------------------------------------------------|------------|
| `web`         | Conteneur PHP + Apache avec le code de l'application | 8080       |
| `db`          | Conteneur MySQL 8.0 avec la base `wshop_test`  | 3306       |
| `phpmyadmin`  | Interface web pour MySQL                        | 8081       |

La base de données est initialisée automatiquement avec le fichier `importSql.sql`.

---

## Commandes Make disponibles

| Commande               | Description |
|------------------------|-------------|
| `make build`           | Reconstruit les images Docker |
| `make up`              | Démarre les conteneurs en arrière-plan |
| `make down`            | Stoppe les conteneurs |
| `make clean`           | Supprime tous les conteneurs, volumes et images |
| `make bash`            | Ouvre un shell dans le conteneur `web` |
| `make logs`            | Affiche les logs en continu |
| `make start`           | Démarrage complet du projet (build + up -d) |
| `make stop-all`        | Arrête et supprime tout (conteneurs, volumes, images) |
| `make rebuild`         | Supprime tout puis relance le projet et recharge `importSql.sql` |
| `make install`         | Installe les dépendances Composer dans `web` |
| `make autoload`        | Regénère l’autoload Composer dans le conteneur `web` |

---

## Démarrage du projet

1. **Cloner le projet**

   ```bash
   git clone <repo-url> wshop-test
   cd wshop-test
   ````

2. **Démarrer les services**

   ```bash
   make start
   ```

3. **Vérifier que les conteneurs tournent**

   ```bash
   docker ps
   ```

4. **Accéder à l’application**

| Application    | URL                                            | Identifiants                                  |
| -------------- | ---------------------------------------------- | --------------------------------------------- |
| **Frontend**   | [http://localhost:8080](http://localhost:8080) | —                                             |
| **PhpMyAdmin** | [http://localhost:8081](http://localhost:8081) | user : `wshop` / pass : `wshop` / host : `db` |

---

## Gestion de la base de données

- La base est initialisée avec `importSql.sql`.
- Pour réinitialiser la base :

  ```bash
  make rebuild
  ```

---

## Notes

- Les ports 8080 et 8081 doivent être libres sur votre machine.
- Les fichiers du projet sont montés dans le conteneur `web`, donc toute modification locale est immédiatement prise en compte.
- La base MySQL est persistée via le volume `db_data`.
