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

Étape 3 [ici](https://github.com/Adambizien/DevClassRoom/commit/a5c091b3a84dbf9d783a36239723ecf57378c481) : <br>
  - Initialiser tailwind : [ici](https://flowbite.com/docs/getting-started/symfony/)
  - ne pas oublier : 
       composer require symfony/webpack-encore-bundle <br>
       npm run dev
  - Initialiser flowbite cdn: [ici](https://flowbite.com/docs/getting-started/quickstart/#include-via-cdn) 
