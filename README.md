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

Étape 12 (branche : [historiqueInterface]() ) : <br>    
  - Implémenter l'interface d'historique  : [ici]() <br>
      

    

  
