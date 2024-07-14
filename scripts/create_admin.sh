#!/bin/bash

# Chemin vers le fichier .env.local
ENV_FILE="../.env.local"

if [ -f "$ENV_FILE" ]; then
  echo "Chargement des variables d'environnement..."
  export $(grep -v '^#' "$ENV_FILE" | xargs)
else
  echo "Le fichier .env.local n'existe pas."
  exit 1
fi

# Vérification des variables d'environnement essentielles
if [ -z "$DB_USER" ] || [ -z "$DB_PASSWORD" ] || [ -z "$DB_NAME" ] || [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ]; then
  echo "Erreur: Des variables d'environnement essentielles sont manquantes."
  exit 1
fi

while true; do
  read -p "Entrez l'email de l'administrateur*: " admin_email
  if [[ -z "$admin_email" ]]; then
    echo "L'email est requis."
    continue
  fi

  email_exists=$(mysql -u "$DB_USER" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" "$DB_NAME" -se "SELECT COUNT(*) FROM user WHERE email='$admin_email';")
  
  if [ "$email_exists" -eq 0 ]; then
    break
  else
    echo "L'email est déjà utilisé. Veuillez entrer un autre email."
  fi
done

while true; do
  read -p "Entrez le nom de l'administrateur*: " admin_name
  if [[ -z "$admin_name" ]]; then
    echo "Le nom est requis."
    continue
  fi

  read -p "Entrez le prénom de l'administrateur*: " admin_firstname
  if [[ -z "$admin_firstname" ]]; then
    echo "Le prénom est requis."
    continue
  fi

  read -s -p "Entrez le mot de passe de l'administrateur* (minimum 6 caractères): " admin_password
  echo
  if [[ ${#admin_password} -lt 6 ]]; then
    echo "Le mot de passe doit contenir au moins 6 caractères."
    continue
  fi
  break
done

read -p "Entrez le numéro de téléphone de l'administrateur (laisser vide pour null): " admin_phone
read -p "Entrez la date de naissance de l'administrateur (AAAA-MM-JJ, laisser vide pour null): " admin_birthdate

# Convertir les champs vides en NULL pour MySQL
if [[ -z "$admin_phone" ]]; then
  admin_phone="NULL"
else
  admin_phone="'$admin_phone'"
fi

if [[ -z "$admin_birthdate" ]]; then
  admin_birthdate="NULL"
else
  admin_birthdate="'$admin_birthdate'"
fi

hashed_password=$(php -r "echo password_hash('$admin_password', PASSWORD_BCRYPT);")
createdAt=$(date -Iseconds)
updatedAt=$(date -Iseconds)

mysql -u "$DB_USER" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" "$DB_NAME" <<EOF
INSERT INTO user (email, roles, password, name, first_name, phone_number, created_at, updated_at, status, date_of_birth) 
VALUES ('$admin_email', '["ROLE_ADMIN"]', '$hashed_password', '$admin_name', '$admin_firstname', $admin_phone, '$createdAt', '$updatedAt', 'active', $admin_birthdate);
EOF

if [ $? -eq 0 ]; then
  echo "L'utilisateur administrateur a été créé avec succès !"
else
  echo "Échec de la création de l'utilisateur administrateur."
fi