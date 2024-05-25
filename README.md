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
