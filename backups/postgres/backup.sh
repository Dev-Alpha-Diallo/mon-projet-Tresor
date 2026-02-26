#!/bin/bash

# Nom du container PostgreSQL
CONTAINER_NAME="tresorerie-db"

# Nom de la base de données
DB_NAME="tresorerie"

# Utilisateur PostgreSQL
DB_USER="postgres"

# Dossier backup à l’intérieur du container
BACKUP_PATH="/var/backups/postgres"

# Date pour versionner les backups
DATE=$(date +%Y-%m-%d_%H-%M-%S)

# Nom du fichier backup
FILE_NAME="backup_$DATE.dump"

echo "Début du backup de la base $DB_NAME ..."

# Créer dossier dans le container s’il n’existe pas
docker exec $CONTAINER_NAME mkdir -p $BACKUP_PATH

# Faire le backup (format custom .dump)
docker exec $CONTAINER_NAME pg_dump -U $DB_USER -Fc $DB_NAME -f $BACKUP_PATH/$FILE_NAME

# Copier le backup vers ton serveur (machine host)
docker cp $CONTAINER_NAME:$BACKUP_PATH/$FILE_NAME backups/postgres/

echo "✅ Backup terminé : backups/postgres/$FILE_NAME"
