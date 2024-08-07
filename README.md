# Conception du site DevClassRoom : [http://devclassroom.bizienadam.fr/](http://devclassroom.bizienadam.fr/)


### Étape 1 : <br>
  - Initialisation du projet avec les commandes : <br>
      - Si vous n'avez pas Symfony : <br>
      ```
        curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
        sudo apt install symfony-cli
        symfony
      ```
    
    - Pour créer le projet : <br>
    ```
      symfony check:requirements
      symfony new my_project_directory --version="7.0.*" --webapp
    ```

    - Pour installer l'ORM : <br>
    ```
      composer require orm
      composer require --dev symfony/maker-bundle
      composer require --dev orm-fixtures
    ```
      Créer le fichier .env.local avec le nom de la base de données souhaitée.<br>
    ```
      php bin/console doctrine:database:create
    ```
      
### Étape 2 [ici](https://github.com/Adambizien/DevClassRoom/commit/20a43793b4a3c2b818f5c88af2af8579f44bb72f) : <br>
  - Faire un subscribe pour styliser nos pages d'erreur : <br>
```
    php  bin/console make:subscriber
        -> ExceptionSubscriber
        ->  kernel.exception
```
  - Puis modifier le fichier ExceptionSubscriber.php.<br>
  - Enfin, ajouter le template d'erreur.
  - Si vous modifiez le template d'erreur, supprimez le cache pour que les modifications soient prises en compte (exécutez ``` php bin/console cache:clear ```).
- Appliquer le style avec Tailwind [ici](https://github.com/Adambizien/DevClassRoom/commit/80410afad7799b574efe88ecfa1b3b1f27426ddb)

### Étape 3 [ici](https://github.com/Adambizien/DevClassRoom/commit/a5c091b3a84dbf9d783a36239723ecf57378c481) : <br>
  - Initialiser Tailwind :[ici](https://flowbite.com/docs/getting-started/symfony/)
  - Ne pas oublier : <br>
```
       composer require symfony/webpack-encore-bundle <br>
       npm run watch
```
  - Initialiser Flowbite CDN : [ici](https://flowbite.com/docs/getting-started/quickstart/#include-via-cdn)
    
### Étape 4 (branche : [UserInterface](https://github.com/Adambizien/DevClassRoom/commits/UserInterface) ) : <br>    

  - Implémenter l'utilisateur et le système de connexion :
    - Avec les commandes :
  ```
      composer require symfony/security-bundle
      
      php bin/console make:user
      -> 
      -> 
      -> email
      ->
      php bin/console make:security:form-login 
      -> 
      -> 
      
      php bin/console make:registration-form
      -> yes
      -> no
      -> no
  ```
  ```
    php bin/console doctrine:schema:update --force
  ```
  - Styliser les interfaces d'inscription et de connexion [ici](https://github.com/Adambizien/DevClassRoom/commit/48e4c3c9142b0e5b84c3ca19218c7ffb94bd17b4)
    
### Étape 5 (branche : [homeHeaderFooter](https://github.com/Adambizien/DevClassRoom/tree/homeHeaderFooter) ) : <br>    

  - Implémenter la page home, le header et le footer.
    
### Étape 6 (branche : [contactUs](https://github.com/Adambizien/DevClassRoom/tree/contactUs) ) : <br>    
  - Implémenter la page de contact pour envoyer un email aux administrateurs si l'utilisateur souhaite devenir formateur ou simplement nous contacter: <br>
    ```
      composer require symfony/mailer
    ```
      Allez sur mailtrap.io pour configurer le MAILER_DSN dans le .env, puis créez l'interface de messagerie. <br> 
      Et enfin, créer le formulaire. [ici](https://github.com/Adambizien/DevClassRoom/commit/d6f492c87923d5f151fe8c6b532322cc654084b3) <br>
      Avec les commandes : <br>
      ```
        php bin/console make:controller 
        php bin/console make:form
      ```    
### Étape 7 (branche : [profile](https://github.com/Adambizien/DevClassRoom/commits/profile/) ) : <br>    
  - Implémenter l'interface de profil avec l'édition des informations de l'utilisateur : [ici](https://github.com/Adambizien/DevClassRoom/commit/e2df45d3e1e0737d67d7b6d050f52f3fa45e014b) <br>
    ```
      php bin/console make:controller
      php bin/console make:form
    ```
    
### Étape 8 (branche : [adminMode](https://github.com/Adambizien/DevClassRoom/commits/adminMode) ) : <br>    
  - Implémenter l'interface administrateur avec l'édition des rôles des utilisateurs: [ici](https://github.com/Adambizien/DevClassRoom/commit/2d8d72acadf0cbd68d7de94d7a31ed12f2cf38e0) <br>
    ```
      php bin/console make:controller 
      php bin/console make:form
    ```

### Étape 9 (branche : [Entities](https://github.com/Adambizien/DevClassRoom/commits/Entities) ) : <br>    
  - Implémenter toutes les entités nécessaires pour ce site : [ici](https://github.com/Adambizien/DevClassRoom/commit/2a511142b5c1e88d463263c5e1fdf0245cea446f)
      ![Diagramme sans nom drawio](https://github.com/Adambizien/DevClassRoom/assets/127780676/db6f4616-a6c4-46d3-b38b-0846e53289eb) <br>
      (SCU -> Status - CreatedAt - UpdatedAt et CU -> CreatedAt - UpdatedAt ) <br>
    ```
      php bin/console make:entity
      php bin/console doctrine:schema:update --force
    ```
  - Faire un make CRUD sur toutes les entités: [ici](https://github.com/Adambizien/DevClassRoom/commit/b7b8550a1ef5a0dd8697a14722d17be540be00a1) <br>
    ```
    php bin/console make:crud
    ```


## Étape 10 (branche : [adminMode](https://github.com/Adambizien/DevClassRoom/commits/adminMode) ) : <br>    
  - Implémenter l'interface administrateur pour les catégories et les tutoriels : [ici](https://github.com/Adambizien/DevClassRoom/commit/cc933927839d75c273ceb9c52506b74fc6e7b034) <br>
      ```
      php bin/console make:controller
      php bin/console make:form
      ```
      Implémentation de VichUploaderBundle pour la gestion des images : [ici](https://github.com/dustin10/VichUploaderBundle)

### Étape 11 (branche : [formationInterface](https://github.com/Adambizien/DevClassRoom/commits/formationInterface) ) : <br>    
  - Implémenter l'interface de la vue des tutoriels : [ici](https://github.com/Adambizien/DevClassRoom/commit/48a98d55c7b7986d6ffa99d04749fd5bf451d5c8) <br>

### Étape 12 (branche : [historiqueInterface](https://github.com/Adambizien/DevClassRoom/tree/historiqueInterface) ) : <br>    
  - Implémenter l'interface d'historique : [ici](https://github.com/Adambizien/DevClassRoom/commit/d5abf3b26330efebe15873c6999fdbc6a1d037a7) <br>

### Étape 13 (branche : [legalInterface](https://github.com/Adambizien/DevClassRoom/commits/LegalInterface/) ) : <br>    
  - Implémenter l'interface légale : [ici](https://github.com/Adambizien/DevClassRoom/commit/980117a664b32c6424f77f29f9e3992adfc99d29) <br>

### Étape 14 (branche : [WhoWeAreInterface](https://github.com/Adambizien/DevClassRoom/commits/WhoWeAreInterface/) ) : <br>    
  - Implémenter l'interface 'Qui sommes-nous ?' : [ici](https://github.com/Adambizien/DevClassRoom/commit/e7c323be8e8740eac6faf9e966fc0d62250cc027) <br>

### Étape 15 (branche : [Main](https://github.com/Adambizien/DevClassRoom/commits/main/) ) : <br>    
  - Effectuer un merge dans la branche main.

## OVH :
- Créer un sous-domaine [http://devclassroom.bizienadam.fr/](http://devclassroom.bizienadam.fr/) dans la zone DNS d'OVH.<br>
- Si ce n'est pas le cas, installer Symfony : <br>
  ```
  curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
  sudo apt install symfony-cli
  symfony
  symfony check:requirements
  ```
-   ```git clone https://github.com/Adambizien/DevClassRoom.git``` Dans le dossier var/www/html pour récupérer le projet.
- Modifier le fichier .env (en mode production).
- Modifier le fichier .env avec les informations de la base de données.
- Effectuer un ```composer install```.

- ```php bin/console doctrine:database:create``` pour créer la base de données.
- ```php bin/console doctrine:schema:update --force``` pour la migration.
- ```sudo ufw status``` pour vérifier que le port 80 est ouvert dans le pare-feu.
- Aller dans etc/nginx/sites-available/, puis créer un fichier ```sudo nano site_DevClassRoom``` et y ajouter ce qui suit : <br>
```
server {
    listen 80;


    server_name Kanban.bizienadam.fr;

    root /var/www/html/NWSKanbanProject/public;
    index index.php index.html index.htm;

    location / {
         try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ { 
          include snippets/fastcgi-php.conf;
          fastcgi_pass unix:/run/php/php-fpm.sock;
     }

}
```

- Ensuite, déplacez-le dans sites-enabled avec cette commande : <br>
  ```
  sudo ln -s /etc/nginx/sites-available/site_kanban /etc/nginx/sites-enabled/
  ```
- Relancer Nginx avec cette commande : <br>
  ```
  sudo systemctl restart nginx
  ```

- Attention aux bugs de permission :<br>
  ```
  sudo mkdir -p /var/www/html/DevClassRoom/var/cache/dev
  sudo chown -R www-data:www-data /var/www/html/DevClassRoom/var
  sudo chmod -R 775 /var/www/html/DevClassRoom/var
  ```

- Attention de ne pas oublier de mettre en place la Webback Machine : <br>
  ```
  cd /var/www/html/DevClassRoom
  npm install
  npm run build ( pour la prod)
  ```
  
- Attention de ne pas oublier de mettre le MAILER_DSN dans le fichier .env. <br>
- Attention de ne pas oublier les permissions pour les images: <br>
  ```
  sudo chown -R www-data:www-data /var/www/html/DevClassRoom/public/images/content/
  sudo chmod -R 755 /var/www/html/DevClassRoom/public/images/content/
  sudo chown -R www-data:www-data /var/www/html/DevClassRoom/public/images/tutorials/
  sudo chmod -R 755 /var/www/html/DevClassRoom/public/images/tutorials/
  ```

### Débogage en production:
- Refaire un ```php bin/console doctrine:schema:update --force ``` en raison d'un bug.
- Vérifiez les permissions des répertoires de logs et de cache : <br>
  Assurez-vous que les répertoires var/log et var/cache existent et sont accessibles en écriture par le serveur web. <br>
  ```
  sudo mkdir -p var/log var/cache
  sudo chown -R www-data:www-data var/log var/cache
  sudo chmod -R 775 var/log var/cache
  ```
- Mettre ```APP_ENV=prod APP_DEBUG=0``` dans le fichier .env.

- Fixer quelques styles, créer un script pour ajouter un administrateur et supprimer certaines images. [ici](https://github.com/Adambizien/DevClassRoom/commit/a1aa1ce47ac4fd792f515a5c4facdcbe6672aaf8)
- Bug sur la recherche de formation [ici](https://github.com/Adambizien/DevClassRoom/commit/adaa655d08c0f19161ec1bbd9bff3bfdfd26e563)
- Bug sur la vidéo dans le contenu [ici](https://github.com/Adambizien/DevClassRoom/commit/ec892338fae249d747b0ebb68b9d101b5e0077ec)
- Effectuer un ```git pull``` dans le projet en production et vider le cache.

### Reload le cache prod: 
```
rm -rf /var/www/html/DevClassRoom/var/cache/prod
mkdir -p /var/www/html/DevClassRoom/var/cache/prod
sudo chown -R $(whoami):www-data /var/www/html/DevClassRoom/var/cache/prod
sudo chmod -R 775 /var/www/html/DevClassRoom/var/cache/prod
```

  



    

  
