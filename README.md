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
  
      php bin/console make:entity <br>
      

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
  
