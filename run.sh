#!/bin/bash

echo " Démarrage du projet Laravel avec Docker..."

# Vérifier si docker tourne
sudo systemctl start docker

# Lancer les containers
echo "📦 Lancement des containers..."
docker-compose up -d

# Vérifier si les containers sont actifs
echo "✅ Containers actifs :"
docker ps

echo ""
echo " Application lancée !"
echo " Tu peux travailler maintenant..."
echo " Logs en temps réel affichés..."

# Afficher les logs en direct
docker-compose logs --follow
