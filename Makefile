PROJECT=wshop-test
APP_SERVICE=web
DC=docker compose

.DEFAULT_GOAL := help

.PHONY: help build up down clean bash logs start stop-all rebuild install autoload

help:
	@echo "Commandes disponibles :"
	@echo "  make build       → Reconstruction des images Docker"
	@echo "  make up          → Démarre les conteneurs"
	@echo "  make down        → Stoppe les conteneurs"
	@echo "  make clean       → Supprime tout (volumes, images)"
	@echo "  make bash        → Ouvre un shell dans le conteneur web"
	@echo "  make logs        → Affiche les logs en continu"
	@echo "  make start       → Démarrage complet du projet (build + up -d)"
	@echo "  make stop-all    → Arrête et supprime tout (conteneurs, volumes, images)"
	@echo "  make rebuild     → Supprime tout puis relance le projet et recharge importSql.sql"
	@echo "  make install     → Installe les dépendances Composer dans le conteneur web"
	@echo "  make autoload    → Regénère l’autoload Composer dans le conteneur web"

build:
	$(DC) build

up:
	$(DC) up -d

down:
	$(DC) down

clean:
	$(DC) down -v --rmi all

bash:
	$(DC) exec $(APP_SERVICE) bash

logs:
	$(DC) logs -f

start:
	$(DC) up --build -d
	$(MAKE) install && $(MAKE) autoload

stop-all:
	$(DC) down --volumes --rmi all

rebuild:
	$(MAKE) stop-all
	$(MAKE) start

install:
	$(DC) exec $(APP_SERVICE) bash -c "composer install"

autoload:
	$(DC) exec $(APP_SERVICE) bash -c "composer dump-autoload"