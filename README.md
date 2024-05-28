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
      
Étape 2 [ici](https://github.com/Adambizien/NWSKanbanProject/commit/40ebe0982e157ea03d4de0134622d7835bef90ae) : <br>
  - Faire un subscribe pour styliser nos pages d'erreur : <br>
    - php  bin/console make:subscriber <br>
        -> ExceptionSubscriber <br>
        ->  kernel.exception <br>
    - Puis modifier le ExceptionSubscriber.php <br>
    - Enfin ajouter le template error
    - Si vous modifiez le template error, supprimez le cache pour que les modifications soient prises en compte.(php bin/console cache:clear).
