Conception site d'apprentissage de développement avec des tutoriels.


Étape 1 : <br>
  - Initialisation du projet avec les commandes : <br>
      - Si vous n'avez pas Symfony : <br>
        curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash <br>
        sudo apt install symfony-cli <br>
        symfony <br>
    
    - Pour créer le projet : <br>
      symfony check:requirements <br>
      symfony new my_project_directory --version="7.0.*" --webapp <br>

    - Pour installer l'ORM : <br>
      composer require orm <br>
      composer require --dev symfony/maker-bundle <br>
      composer require --dev orm-fixtures <br>
      faire le .env.local avec le nom de database voulue <br>
      php bin/console doctrine:database:create <br>
      
Étape 2 [ici](https://github.com/Adambizien/DevClassRoom/commit/20a43793b4a3c2b818f5c88af2af8579f44bb72f) : <br>
  - Faire un subscribe pour styliser nos pages d'erreur : <br>
    - php  bin/console make:subscriber <br>
        -> ExceptionSubscriber <br>
        ->  kernel.exception <br>
    - Puis modifier le ExceptionSubscriber.php <br>
    - Enfin ajouter le template error
    - Si vous modifiez le template error, supprimez le cache pour que les modifications soient prises en compte.(php bin/console cache:clear).
- Faire le style avec tailwind [ici](https://github.com/Adambizien/DevClassRoom/commit/80410afad7799b574efe88ecfa1b3b1f27426ddb) 

Étape 3 [ici](https://github.com/Adambizien/DevClassRoom/commit/a5c091b3a84dbf9d783a36239723ecf57378c481) : <br>
  - Initialiser tailwind : [ici](https://flowbite.com/docs/getting-started/symfony/)
  - ne pas oublier : 
       composer require symfony/webpack-encore-bundle <br>
       npm run watch
  - Initialiser flowbite cdn: [ici](https://flowbite.com/docs/getting-started/quickstart/#include-via-cdn)
    
Étape 4 (branche : [UserInterface](https://github.com/Adambizien/DevClassRoom/commits/UserInterface) ) : <br>    

  - Implémenter le user et le système de connexion :
    - Avec les commandes :
      composer require symfony/security-bundle <br>
      
      php bin/console make:user <br>
      ->  <br>
      -> <br>
      -> email <br>
      -> <br>
      Ne pas oublier d'ajouter :<br>
      status, createdAt, updatedAt, name, firstname, phoneNumber et dateOfBirth avec php bin/console make:entity <br>
      
      php bin/console make:security:form-login <br>
      -> <br>
      -> <br>
      
      php bin/console make:registration-form <br>
      -> yes <br>
      -> no <br>
      -> no <br>

    php bin/console doctrine:schema:update --force
  - Faire le style des interfaces register et login [ici](https://github.com/Adambizien/DevClassRoom/commit/48e4c3c9142b0e5b84c3ca19218c7ffb94bd17b4)
    
Étape 5 (branche : [homeHeaderFooter](https://github.com/Adambizien/DevClassRoom/tree/homeHeaderFooter) ) : <br>    

  - Implémenter le home, le header et le footer
    
Étape 6 (branche : [contactUs](https://github.com/Adambizien/DevClassRoom/tree/contactUs) ) : <br>    
  - Implémenter la page mail pour envoyer un mail aux adminitrateur si le user veux devenir formateur ou tout simplement nous contacter: <br> 
      composer require symfony/mailer <br> 
      allez dans mailtrap.io puir comfigurer le MAILER_DSN dans le .env ensuite faire l'interface de mail. <br> 
      et enfin faire le formulaire [ici](https://github.com/Adambizien/DevClassRoom/commit/d6f492c87923d5f151fe8c6b532322cc654084b3) 
      avec les commande : <br> 
      php bin/console make:controller <br> 
      php bin/console make:form
    
Étape 7 (branche : [profile](https://github.com/Adambizien/DevClassRoom/commits/profile/) ) : <br>    
  - Implémenter l'interface profil avec les edit des infos du User: [ici](https://github.com/Adambizien/DevClassRoom/commit/e2df45d3e1e0737d67d7b6d050f52f3fa45e014b) <br> 
      php bin/console make:controller <br> 
      php bin/console make:form
    
Étape 8 (branche : [adminMode](https://github.com/Adambizien/DevClassRoom/commits/adminMode) ) : <br>    
  - Implémenter l'interface admin mode avec les edit des rôles des Users: [ici](https://github.com/Adambizien/DevClassRoom/commit/2d8d72acadf0cbd68d7de94d7a31ed12f2cf38e0) <br> 
      php bin/console make:controller <br> 
      php bin/console make:form

Étape 9 (branche : [Entities](https://github.com/Adambizien/DevClassRoom/commits/Entities) ) : <br>    
  - Implémenter de toutes les entiter que nous avons besoin pour ce site : [ici](https://github.com/Adambizien/DevClassRoom/commit/2a511142b5c1e88d463263c5e1fdf0245cea446f)
      ![Diagramme sans nom drawio](https://github.com/Adambizien/DevClassRoom/assets/127780676/db6f4616-a6c4-46d3-b38b-0846e53289eb) <br>
      (SCU -> Status - CreatedAt - UpdatedAt et CU -> CreatedAt - UpdatedAt ) <br>
      
      php bin/console make:entity <br>
      php bin/console doctrine:schema:update --force
  - Faire un make CRUD sur toutes les entiter : [ici](https://github.com/Adambizien/DevClassRoom/commit/b7b8550a1ef5a0dd8697a14722d17be540be00a1) <br>
    php bin/console make:crud



Étape 10 (branche : [adminMode](https://github.com/Adambizien/DevClassRoom/commits/adminMode) ) : <br>    
  - Implémenter l'interface admin mode des catégories et des tutoriels : [ici](https://github.com/Adambizien/DevClassRoom/commit/cc933927839d75c273ceb9c52506b74fc6e7b034) <br>
      php bin/console make:controller <br> 
      php bin/console make:form
      implementation de vichUploaderBundle pour la gestion des images : [ici](https://github.com/dustin10/VichUploaderBundle)

Étape 11 (branche : [formationInterface](https://github.com/Adambizien/DevClassRoom/commits/formationInterface) ) : <br>    
  - Implémenter l'interface de la vue tutoriels  : [ici](https://github.com/Adambizien/DevClassRoom/commit/48a98d55c7b7986d6ffa99d04749fd5bf451d5c8) <br>

Étape 12 (branche : [historiqueInterface](https://github.com/Adambizien/DevClassRoom/tree/historiqueInterface) ) : <br>    
  - Implémenter l'interface d'historique  : [ici](https://github.com/Adambizien/DevClassRoom/commit/d5abf3b26330efebe15873c6999fdbc6a1d037a7) <br>

Étape 13 (branche : [legalInterface](https://github.com/Adambizien/DevClassRoom/commits/LegalInterface/) ) : <br>    
  - Implémenter l'interface Légal  : [ici](https://github.com/Adambizien/DevClassRoom/commit/980117a664b32c6424f77f29f9e3992adfc99d29) <br>

Étape 14 (branche : [WhoWeAreInterface](https://github.com/Adambizien/DevClassRoom/commits/WhoWeAreInterface/) ) : <br>    
  - Implémenter l'interface Qui sommes-nous ? : [ici](https://github.com/Adambizien/DevClassRoom/commit/e7c323be8e8740eac6faf9e966fc0d62250cc027) <br>

Étape 15 (branche : [Main](https://github.com/Adambizien/DevClassRoom/commits/main/) ) : <br>    
  - Merge dans la branche Main.

OVH :
- On crée un sous-domaine DevClassRoom.bizienadam.fr dans la zone DNS de OVH.
- Si ce n'est pas le cas, installer Symfony : <br>
  curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash <br>
  sudo apt install symfony-cli <br>
  symfony <br>
  symfony check:requirements <br>
- git clone https://github.com/Adambizien/DevClassRoom.git dans le var/www/html pour récupérer le projet.
- Modifier le fichier .env (en mode prod).
- Change le .env avec les info de la database.
- Effectuer un composer install.

- php bin/console doctrine:database:create pour créer la base de données.
- php bin/console doctrine:schema:update --force  pour la migration.
- sudo ufw status pour vérifier que le port 80 est compris dans le pare-feu (si ce n'est pas déjà fait).
- Aller dans etc/nginx/sites-available/, puis créer un fichier sudo nano site_DevClassRoom et y ajouter ce qui suit : <br>
server {<br>
    listen 80; <br>


    server_name Kanban.bizienadam.fr; <br>

    root /var/www/html/NWSKanbanProject/public; <br>
    index index.php index.html index.htm; <br>

    location / { <br>
         try_files $uri $uri/ /index.php?$query_string; <br>
    } <br>

    location ~ \.php$ { <br>
          include snippets/fastcgi-php.conf; <br>
          fastcgi_pass unix:/run/php/php-fpm.sock; <br>
     } <br>

} <br>

- Ensuite, on le met dans les sites-enabled avec cette commande  :  <br>
  sudo ln -s /etc/nginx/sites-available/site_kanban /etc/nginx/sites-enabled/
- Relancer Nginx avec cette commande : <br>
  sudo systemctl restart nginx
- Normalement, cela fonctionne.

- mais il y a des buges de permision :<br>
  sudo mkdir -p /var/www/html/DevClassRoom/var/cache/dev <br>
sudo chown -R www-data:www-data /var/www/html/DevClassRoom/var <br>
sudo chmod -R 775 /var/www/html/DevClassRoom/var <br>

- mais j'ai aussi oublier de metter la webback machine : <br>
  cd /var/www/html/DevClassRoom <br>
  npm install <br>
  npm run build ( pour la prod) <br>
  
- et aussi j'ai oublier de mettre dans le .env le MAILER_DSN <br>
- et j'ai aussi oublier les permision pour les images

- et aussi ouvrire les permision pour les image avec :
  sudo chown -R www-data:www-data /var/www/html/DevClassRoom/public/images/content/ <br>
  sudo chmod -R 755 /var/www/html/DevClassRoom/public/images/content/<br>
  sudo chown -R www-data:www-data /var/www/html/DevClassRoom/public/images/tutorials/ <br>
  sudo chmod -R 755 /var/www/html/DevClassRoom/public/images/tutorials/ <br>


Débugage de prod : 
- refaire un  "php bin/console doctrine:schema:update --force" en raison d'un bug
- 



    

  
